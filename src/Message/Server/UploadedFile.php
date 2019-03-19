<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Message\Server;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use Slick\Http\Message\Exception\InvalidArgumentException;
use Slick\Http\Message\Exception\RuntimeException;
use Slick\Http\Message\Stream\FileStream;

/**
 * UploadedFile
 *
 * @package Slick\Http\Message\Server
*/
final class UploadedFile implements UploadedFileInterface
{
    /**
     * @var StreamInterface
     */
    private $stream;

    /**
     * @var int
     */
    private $size;

    /**
     * @var int
     */
    private $error;

    /**
     * @var string
     */
    private $clientName;

    /**
     * @var string
     */
    private $mediaType;

    /**
     * @var string
     */
    private $tmpFile;

    /**
     * @var bool
     */
    private $moved = false;

    /**
     * Creates an UploadedFile
     *
     * @param StreamInterface $stream
     */
    private function __construct(StreamInterface $stream)
    {
        $this->stream = $stream;
    }

    /**
     * Creates an uploaded file from PHP's $_FILE upload data
     *
     * @param array $fileUploadData
     *
     * @return UploadedFile
     */
    public static function create(array $fileUploadData)
    {
        $uploadedFile = new UploadedFile(new FileStream($fileUploadData['tmp_name']));
        $uploadedFile->size = $fileUploadData['size'];
        $uploadedFile->error = $fileUploadData['error'];
        $uploadedFile->clientName = $fileUploadData['name'];
        $uploadedFile->mediaType = $fileUploadData['type'];
        $uploadedFile->tmpFile = $fileUploadData['tmp_name'];

        return $uploadedFile;
    }

    /**
     * Retrieve a stream representing the uploaded file.
     *
     * @return StreamInterface Stream representation of the uploaded file.
     *
     * @throws RuntimeException in cases when no stream is available
     */
    public function getStream()
    {
        if (!$this->stream) {
            throw new RuntimeException(
                "The uploaded file stream is no longer available."
            );
        }
        return $this->stream;
    }

    /**
     * Retrieve the file size.
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
     * If the file was uploaded successfully, this method will return
     * UPLOAD_ERR_OK.
     *
     * @see http://php.net/manual/en/features.file-upload.errors.php
     *
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
     * @return string|null The filename sent by the client or null if none
     *     was provided.
     */
    public function getClientFilename()
    {
        return $this->clientName;
    }

    /**
     * Retrieve the media type sent by the client.
     *
     * Do not trust the value returned by this method. A client could send
     * a malicious media type with the intention to corrupt or hack your
     * application.
     *
     * @return string|null The media type sent by the client or null if none
     *     was provided.
     */
    public function getClientMediaType()
    {
        return $this->mediaType;
    }

    /**
     * Move the uploaded file to a new location.
     *
     * $targetPath may be an absolute path, or a relative path. If it is a
     * relative path, resolution should be the same as used by PHP's rename()
     * function.
     *
     * The original file or stream will be removed on completion.
     *
     * If this method is called more than once, any subsequent calls will raise
     * an exception.
     *
     * @see http://php.net/is_uploaded_file
     * @see http://php.net/move_uploaded_file
     *
     * @param string $targetPath Path to which to move the uploaded file.
     *
     * @throws InvalidArgumentException if the $targetPath specified is invalid.
     * @throws RuntimeException on any error during the move operation, or on
     *     the second or subsequent call to the method.
     */
    public function moveTo($targetPath)
    {
        $this->checkMoved();
        $this->checkTargetDirExists($targetPath);
        $this->checkUpload($targetPath);

        $exception = null;
        set_error_handler(function ($number, $error) use (&$exception) {
            $exception = new RuntimeException(
                "Cannot move uploaded file: ($number) {$error}"
            );
        });

        move_uploaded_file($this->tmpFile, $targetPath);
        restore_error_handler();

        if ($exception instanceof RuntimeException) {
            throw $exception;
        }

        $this->stream = null;
        $this->moved = true;
    }

    /**
     * Check if current file is already moved
     */
    private function checkMoved()
    {
        if ($this->moved) {
            throw new RuntimeException(
                "Uploaded file has already been move."
            );
        }
    }

    /**
     * Check if target directory exists
     *
     * @param $targetPath
     */
    private function checkTargetDirExists($targetPath)
    {
        if (!is_dir(dirname($targetPath))) {
            throw new InvalidArgumentException(
                "Cannot move uploaded file: target directory dos not exists."
            );
        }
    }

    /**
     * Checks if upload was successful
     *
     * @param $targetPath
     */
    private function checkUpload($targetPath)
    {
        if (!is_uploaded_file($targetPath)) {
            throw new RuntimeException(
                "Cannot move uploaded file: the client file was not uploaded."
            );
        }
    }
}
