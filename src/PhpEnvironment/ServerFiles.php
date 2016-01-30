<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\PhpEnvironment;
use Psr\Http\Message\UploadedFileInterface;
use Slick\Http\Exception\InvalidArgumentException;
use Slick\Http\Server\UploadedFile;

/**
 * ServerFiles Utility; fixes PHP $_FILES structure
 *
 * @package Slick\Http\PhpEnvironment
 */
class ServerFiles
{

    public function getFiles()
    {
        return $this->normalizeFiles($_FILES);
    }



    public static function get()
    {
        $serverFiles = new static;
        return $serverFiles->getFiles();
    }

    /**
     * Normalize uploaded files
     *
     * Transforms each value into an UploadedFileInterface instance, and ensures
     * that nested arrays are normalized.
     *
     * @param array $files
     * @return array
     * @throws InvalidArgumentException for unrecognized values
     */
    protected function normalizeFiles(array $files)
    {
        $normalized = [];
        foreach ($files as $key => $value) {

            if (is_array($value) && $this->isUploadData($value)) {
                $normalized[$key] = $this->createUploadedFileFromSpec($value);
                continue;
            }
            if (is_array($value)) {
                $normalized[$key] = $this->normalizeFiles($value);
                continue;
            }
            throw new InvalidArgumentException('Invalid value in files specification');
        }
        return $normalized;
    }

    /**
     * Check if the provided array is an upload data array
     *
     * The error key will be present no matter what appended
     *
     * @param array $array
     *
     * @return bool
     */
    private function isUploadData(array $array)
    {
        return array_key_exists('error', $array);
    }

    /**
     * Create and return an UploadedFile instance from a $_FILES specification.
     *
     * If the specification represents an array of values, this method will
     * delegate to normalizeNestedFileSpec() and return that return value.
     *
     * @param array $value $_FILES struct
     * @return array|UploadedFileInterface
     */
    private function createUploadedFileFromSpec(array $value)
    {
        if (is_array($value['tmp_name'])) {
            return $this->normalizeNestedFileSpec($value);
        }
        return new UploadedFile(
            $value['tmp_name'],
            $value['size'],
            $value['error'],
            $value['name'],
            $value['type']
        );
    }

    /**
     * Normalize an array of file specifications.
     *
     * Loops through all nested files and returns a normalized array of
     * UploadedFileInterface instances.
     *
     * @param array $files
     * @return UploadedFileInterface[]
     */
    private function normalizeNestedFileSpec(array $files = [])
    {
        $normalizedFiles = [];
        foreach (array_keys($files['tmp_name']) as $key) {
            $spec = [
                'tmp_name' => $files['tmp_name'][$key],
                'size'     => $files['size'][$key],
                'error'    => $files['error'][$key],
                'name'     => $files['name'][$key],
                'type'     => $files['type'][$key],
            ];
            $normalizedFiles[$key] = self::createUploadedFileFromSpec($spec);
        }
        return $normalizedFiles;
    }
}