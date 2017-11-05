<?php

$content = 'Hello world, form a test!';
$file = tempnam("/tmp", "FOO");
file_put_contents($file, $content);

return [
    'file1' => [
        'name' => 'test.txt',
        'type' => 'plain/text',
        'size' => strlen($content),
        'tmp_name' => $file,
        'error' => UPLOAD_ERR_OK
    ],
    'file2' => [
        'name' => [
            'test.txt',
            'test.txt',
            'test.txt',
        ],
        'type' => [
            'plain/text',
            'plain/text',
            'plain/text',
        ],
        'tmp_name' => [
            $file,
            $file,
            $file,
        ],
        'size' => [
            strlen($content),
            strlen($content),
            strlen($content),
        ],
        'error' => [
            UPLOAD_ERR_OK,
            UPLOAD_ERR_OK,
            UPLOAD_ERR_OK,
        ]
    ],
    'file3' => [
        'name' => [
            'foo' => 'test.txt',
            'bar' => 'test.txt',
        ],
        'type' => [
            'foo' => 'plain/text',
            'bar' => 'plain/text',
        ],
        'tmp_name' => [
            'foo' => $file,
            'bar' => $file,
        ],
        'size' => [
            'foo' => strlen($content),
            'bar' => strlen($content),
        ],
        'error' => [
            'foo' => UPLOAD_ERR_OK,
            'bar' => UPLOAD_ERR_OK,
        ]
    ]
];