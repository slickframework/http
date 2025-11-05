<?php

declare(strict_types=1);

namespace Slick\Http\Message\Server;

use Psr\Http\Message\UploadedFileInterface as PSRFile;

/**
 * UploadedFilesFactory
 *
 * @package Slick\Http\Message\Server
 */
final class UploadedFilesFactory
{
    /**
     * Creates the uploaded file objects within a normalized files tree.
     *
     * @return array<string, array<int|string, array<int|string, PSRFile>|PSRFile>|UploadedFile>
     * @SuppressWarnings(PHPMD)
     */
    public static function createFiles(): array
    {
        $factory = new self();
        return $factory->normalize($_FILES);
    }

    /**
     * Converts $_FILES to UploadedFile instances.
     *
     * If the $_FILES array is multidimensional, it will return an array of UploadedFile instances.
     * It will preserve the keys of the original array.
     *
     * @param array<string, array{
     *     tmp_name: string|array<string|int, string|array<string|int, string>>,
     *     size: int|array<string|int, int|array<string|int, int>>,
     *     error: int|array<string|int, int|array<string|int, int>>,
     *     name: string|array<string|int, string|array<string|int, string>>,
     *     type: string|array<string|int, string|array<string|int, string>>
     * }> $files
     * @return array<string, array<int|string, array<int|string, PSRFile>|PSRFile>|UploadedFile>
     * @SuppressWarnings(PHPMD)
     */
    private function normalize(array $files): array
    {
        $normalized = [];

        foreach ($files as $field => $fileData) {
            if (\is_string($fileData['tmp_name'])) {
                /** @var array{tmp_name: string, size: int, error: int, name: string|null, type: string|null} $fileData */
                $normalized[$field] = UploadedFile::create([
                    'tmp_name' => (string) $fileData['tmp_name'],
                    'size'     => (int) $fileData['size'],
                    'error'    => (int) $fileData['error'],
                    'name'     => (string) $fileData['name'],
                    'type'     => (string) $fileData['type'],
                ]);
                continue;
            }

            $normalized[$field] = $this->normalizeNestedFiles(
                (array) $fileData['tmp_name'],
                (array) $fileData['size'],
                (array) $fileData['error'],
                (array) $fileData['name'],
                (array) $fileData['type']
            );
        }

        return $normalized;
    }

    /**
     * Normalizes a nested set of uploaded files (recursively)
     *
     * @param array<string|int, string|array<string|int, string>> $tmpNames
     * @param array<string|int, int|array<string|int, int>> $sizes
     * @param array<string|int, int|array<string|int, int>> $errors
     * @param array<string|int, string|array<string|int, string>> $names
     * @param array<string|int, string|array<string|int, string>> $types
     *
     * @return array<string, array<int|string, array<int|string, PSRFile>|PSRFile>|UploadedFile>
     */
    private function normalizeNestedFiles(
        array $tmpNames,
        array $sizes,
        array $errors,
        array $names,
        array $types
    ): array {
        $normalized = [];

        foreach ($tmpNames as $key => $tmpName) {
            if (\is_array($tmpName)) {
                $normalized[$key] = $this->normalizeNestedFiles(
                    $tmpName,
                    (array) ($sizes[$key] ?? []),
                    (array) ($errors[$key] ?? []),
                    (array) ($names[$key] ?? []),
                    (array) ($types[$key] ?? [])
                );
                continue;
            }

            /** @var array{tmp_name: string, size: int, error: int, name: string|null, type: string|null} $fileUploadData */
            $fileUploadData = [
                'tmp_name' => $tmpName,
                'size' => (int) ($sizes[$key] ?? 0),
                'error' => (int) ($errors[$key] ?? 0),
                'name' => (string) (!\is_array($names[$key]) ? $names[$key] : ''),
                'type' => (string) (!\is_array($types[$key]) ? $types[$key] : ''),
            ];
            $normalized[$key] = UploadedFile::create($fileUploadData);
        }

        return $normalized;
    }
}
