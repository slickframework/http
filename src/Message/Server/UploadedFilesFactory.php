<?php

/**
 * This file is part of Http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Message\Server;

/**
 * UploadedFilesFactory
 *
 * @package Slick\Http\Message\Server
 */
class UploadedFilesFactory
{

    /**
     * Creates the uploaded file objects within a normalized files tree
     *
     * @return array|UploadedFile[]
     */
    public static function createFiles()
    {
        $fixer = new static();
        $files = $fixer->saneFilesArray($_FILES);
        $fixed = [];
        foreach ($files as $key => $data) {
            $fixed[$key] = $fixer->createUploadedFile($data);
        }
        return $fixed;
    }

    /**
     * Helper function to recursively create UploadedFile objects
     *
     * @param array $data
     *
     * @return array|UploadedFile
     */
    private function createUploadedFile($data)
    {
        if (array_key_exists('tmp_name', $data)) {
            return UploadedFile::create($data);
        }

        $result = [];
        foreach ($data as $key => $datum) {
            $result[$key] = $this->createUploadedFile($datum);
        }
        return $result;
    }

    /**
     * Fixes the $_FILES array structure
     *
     * For each subtree in the file tree that's more than one item deep:
     *      For each leaf of the subtree:
     *      $leaf[a][b][c] ... [y][z] -> $result[z][a][b][c]  ... [y]
     *
     *
     * @see: https://stackoverflow.com/a/24397828/1271488
     *
     * @param array $files
     * @return array
     */
    private function saneFilesArray(array $files)
    {
        $result = [];

        foreach($files as $field => $data) {
            foreach($data as $key => $val) {
                $result[$field] = [];
                if(!is_array($val)) {
                    $result[$field] = $data;
                    continue;
                }

                $res = [];
                $this->filesFlip($res, [], $data);
                $result[$field] += $res;
            }
        }

        return $result;
    }

    /**
     * Move the innermost key to the outer spot
     *
     * @param array  $result
     * @param array  $keys
     * @param mixed  $value
     */
    private function filesFlip(&$result, $keys, $value)
    {
        if(is_array($value)) {
            foreach($value as $k => $v) {
                $newKeys = $keys;
                array_push($newKeys, $k);
                $this->filesFlip($result, $newKeys, $v);
            }
            return;
        }

        $res = $value;
        // Move the innermost key to the outer spot
        $first = array_shift($keys);
        array_push($keys, $first);
        foreach(array_reverse($keys) as $kk) {
            // You might think we'd say $res[$kk] = $res, but $res starts
            // out not as an array
            $res = array($kk => $res);
        }

        $result = $this->arrayMergeRecursive($result, $res);
    }

    /**
     * Recursively merge provided arrays
     *
     * @param array|string $array1
     * @param array|string $array2
     *
     * @return array
     */
    private function arrayMergeRecursive($array1, $array2)
    {
        if (!is_array($array1) or !is_array($array2)) { return $array2; }

        foreach ($array2 AS $sKey2 => $sValue2) {
            $array1[$sKey2] = $this->arrayMergeRecursive(@$array1[$sKey2], $sValue2);
        }
        return $array1;
    }
}