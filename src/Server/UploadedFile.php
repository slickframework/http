<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Server;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use Slick\Http\Exception\FileOperationException;
use Slick\Http\Exception\InvalidArgumentException;
use Slick\Http\Stream;

/**
 * Value object representing a file uploaded through an HTTP request.
 *
 * Instances of this interface are considered immutable; all methods that
 * might change state MUST be implemented such that they retain the internal
 * state of the current instance and return an instance that contains the
 * changed state.
 *
 * @package Slick\Http\Server
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class UploadedFile implements UploadedFileInterface
{

    /**
     * @var string
     */
    private $clientFilename;

    /**
     * @var string
     */
    private $clientMediaType;

    /**
     * @readwrite
     * @var int
     */
    private $error;

    /**
     * @readwrite
     * @var int
     */
    private $size;

    /**
     * @var null|StreamInterface
     */
    private $stream;

    /**
     * @var null|string
     */
    private $file;

    /**
     * @var bool Moved file flag
     */
    private $moved = false;

    /**
     * UploadedFile constructor.
     *
     * @param string|resource|StreamInterface $file
     * @param int $size
     * @param int $error
     * @param string $clientFilename
     * @param string $clientMediaType
     *
     * @throws InvalidArgumentException If provided file is not a
     *   resource, stream or valid file
     */
    public function __construct(
        $file, $size, $error, $clientFilename = null, $clientMediaType = null
    ) {
        $this->setError($error)
            ->setSize($size)
            ->setFile($file)
            ->setClientFilename($clientFilename)
            ->setClientMediaType($clientMediaType);
    }

    /**
     * Retrieve a stream representing the uploaded file.
     *
     * This method MUST return a StreamInterface instance, representing the
     * uploaded file. The purpose of this method is to allow utilizing native PHP
     * stream functionality to manipulate the file upload, such as
     * stream_copy_to_stream() (though the result will need to be decorated in a
     * native PHP stream wrapper to work with such functions).
     *
     * If the moveTo() method has been called previously, this method MUST raise
     * an exception.
     *
     * @return StreamInterface Stream representation of the uploaded file.
     * @throws \RuntimeException in cases when no stream is available or can be
     *     created.
     */
    public function getStream()
    {
        $this->checkBeforeMove();
        if ($this->stream instanceof StreamInterface) {
            return $this->stream;
        }
        $this->stream = new Stream($this->file);
        return $this->stream;
    }

    /**
     * Move the uploaded file to a new location.
     *
     * Use this method as an alternative to move_uploaded_file(). This method is
     * guaranteed to work in both SAPI and non-SAPI environments.
     * Implementations must determine which environment they are in, and use the
     * appropriate method (move_uploaded_file(), rename(), or a stream
     * operation) to perform the operation.
     *
     * $targetPath may be an absolute path, or a relative path. If it is a
     * relative path, resolution should be the same as used by PHP's rename()
     * function.
     *
     * The original file or stream MUST be removed on completion.
     *
     * If this method is called more than once, any subsequent calls MUST raise
     * an exception.
     *
     * When used in an SAPI environment where $_FILES is populated, when writing
     * files via moveTo(), is_uploaded_file() and move_uploaded_file() SHOULD be
     * used to ensure permissions and upload status are verified correctly.
     *
     * If you wish to move to a stream, use getStream(), as SAPI operations
     * cannot guarantee writing to stream destinations.
     *
     * @see http://php.net/is_uploaded_file
     * @see http://php.net/move_uploaded_file
     * @param string $targetPath Path to which to move the uploaded file.
     * @throws \InvalidArgumentException if the $path specified is invalid.
     * @throws \RuntimeException on any error during the move operation, or on
     *     the second or subsequent call to the method.
     *
     * @return bool
     */
    public function moveTo($targetPath)
    {
        $this->checkBeforeMove();

        if (! is_string($targetPath)) {
            throw new InvalidArgumentException(
                'Invalid path provided for move operation; must be a string'
            );
        }
        if (empty($targetPath)) {
            throw new InvalidArgumentException(
                'Invalid path provided for move operation; '.
                'must be a non-empty string'
            );
        }

        $this->moved = $this->isNonSAPIEnvironment()
            ? $this->write($targetPath)
            : $this->moveUploadedFile($targetPath);

        return $this->moved;
    }

    /**
     * Retrieve the file size.
     *
     * Implementations SHOULD return the value stored in the "size" key of
     * the file in the $_FILES array if available, as PHP calculates this based
     * on the actual size transmitted.
     *
     * @return int|null The file size in bytes or null if unknown.
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Retrieve the error associated with the uploaded file.
     *
     * The return value MUST be one of PHP's UPLOAD_ERR_XXX constants.
     *
     * If the file was uploaded successfully, this method MUST return
     * UPLOAD_ERR_OK.
     *
     * Implementations SHOULD return the value stored in the "error" key of
     * the file in the $_FILES array.
     *
     * @see http://php.net/manual/en/features.file-upload.errors.php
     * @return int One of PHP's UPLOAD_ERR_XXX constants.
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Retrieve the filename sent by the client.
     *
     * Do not trust the value returned by this method. A client could send
     * a malicious filename with the intention to corrupt or hack your
     * application.
     *
     * Implementations SHOULD return the value stored in the "name" key of
     * the file in the $_FILES array.
     *
     * @return string|null The filename sent by the client or null if none
     *     was provided.
     */
    public function getClientFilename()
    {
        return $this->clientFilename;
    }

    /**
     * Retrieve the media type sent by the client.
     *
     * Do not trust the value returned by this method. A client could send
     * a malicious media type with the intention to corrupt or hack your
     * application.
     *
     * Implementations SHOULD return the value stored in the "type" key of
     * the file in the $_FILES array.
     *
     * @return string|null The media type sent by the client or null if none
     *     was provided.
     */
    public function getClientMediaType()
    {
        return $this->clientMediaType;
    }

    /**
     * Sets the upload status error
     *
     * @param int $error
     *
     * @return $this|self|UploadedFileInterface
     *
     * @throws InvalidArgumentException If error value is not one of
     *      UPLOAD_ERR_* constants
     */
    protected function setError($error)
    {
        if (! is_int($error)
            || 0 > $error
            || 8 < $error
        ) {
            throw new InvalidArgumentException(
                'Invalid error status for UploadedFile; must be an '.
                'UPLOAD_ERR_* constant'
            );
        }
        $this->error = $error;
        return $this;
    }

    /**
     * Sets the size of this  file
     *
     * @param $size
     *
     * @return self|$this|UploadedFileInterface
     */
    protected function setSize($size)
    {
        if (! is_int($size)) {
            throw new InvalidArgumentException(
                'Invalid size provided for UploadedFile; must be an int'
            );
        }
        $this->size = $size;
        return $this;
    }

    /**
     * Sets the source file path
     *
     * If the file is not a string, it will try to create it as a stream
     * using UploadedFile::setStream() method.
     *
     * @param string|resource|StreamInterface $file
     *
     * @return $this|UploadedFileInterface|UploadedFile
     *
     * @throws InvalidArgumentException If provided file is not a
     *   resource, stream or valid file
     */
    protected function setFile($file)
    {
        if ($this->error !== UPLOAD_ERR_OK) {
            return $this;
        }

        if (! is_string($file)) {
            return $this->setStream($file);
        }

        $this->file = $file;
        return $this;
    }

    /**
     * Creates stream if provided file is a resource our a stream interface
     *
     * @param StreamInterface|resource $file
     *
     * @return $this|self|UploadedFileInterface
     *
     * @throws InvalidArgumentException If provided file is not a
     *   resource, stream or valid file
     */
    protected function setStream($file)
    {
        $isStream = $file instanceof StreamInterface;
        if (! is_resource($file) && ! $isStream) {
            throw new InvalidArgumentException(
                'Invalid stream or file provided for UploadedFile'
            );
        }

        $this->stream = ($isStream)
            ? $file
            : new Stream($file);
        return $this;
    }

    /**
     * Sets client original file name
     *
     * @param string|null $clientFilename
     *
     * @return $this|self|UploadedFileInterface
     */
    protected function setClientFilename($clientFilename)
    {
        if (null !== $clientFilename && ! is_string($clientFilename)) {
            throw new InvalidArgumentException(
                'Invalid client filename provided for UploadedFile; '.
                'must be null or a string'
            );
        }
        $this->clientFilename = $clientFilename;
        return $this;
    }

    /**
     * Sets original client file media type
     *
     * @param string|null $clientMediaType
     *
     * @return $this
     */
    protected function setClientMediaType($clientMediaType)
    {
        if (null !== $clientMediaType && ! is_string($clientMediaType)) {
            throw new InvalidArgumentException(
                'Invalid client media type provided for UploadedFile; '.
                'must be null or a string'
            );
        }
        $this->clientMediaType = $clientMediaType;
        return $this;
    }

    /**
     * Check settings before upload the file.
     */
    protected function checkBeforeMove()
    {
        if ($this->error !== UPLOAD_ERR_OK) {
            throw new FileOperationException(
                'Cannot retrieve stream due to upload error'
            );
        }
        if ($this->moved) {
            throw new FileOperationException(
                'Cannot retrieve stream after it has already been moved'
            );
        }
    }

    /**
     * Check if running in Non-SAPI environment, or no filename present
     *
     * @return bool
     */
    protected function isNonSAPIEnvironment()
    {
        $sapi = PHP_SAPI;
        return (empty($sapi) || 0 === strpos($sapi, 'cli') || ! $this->file);
    }

    /**
     * Move current uploaded file to provided target destination
     * @param $target
     *
     * @return bool
     */
    protected function moveUploadedFile($target)
    {
        if (false === move_uploaded_file($this->file, $target)) {
            throw new FileOperationException(
                'Error occurred while moving uploaded file'
            );
        }
        return true;
    }

    /**
     * Reads current stream and outputs it to provided path
     *
     * @param string $path
     * @return bool
     *
     * @throws FileOperationException If Unable to write to provided path
     */
    protected function write($path)
    {
        $handle = @fopen($path, 'wb+');
        if (false === $handle) {
            throw new FileOperationException(
                'Unable to write to designated path'
            );
        }
        $stream = $this->getStream();
        $stream->rewind();
        while (! $stream->eof()) {
            fwrite($handle, $stream->read(4096));
        }
        fclose($handle);
        return true;
    }
}