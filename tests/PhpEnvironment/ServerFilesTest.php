<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Http\PhpEnvironment;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Http\Exception\InvalidArgumentException;
use Slick\Http\PhpEnvironment\ServerFiles;
use Slick\Http\Server\UploadedFile;

/**
 * ServerFiles Test case
 *
 * @package Slick\Tests\Http\PhpEnvironment
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ServerFilesTest extends TestCase
{

    protected function tearDown()
    {
        parent::tearDown();
        $_FILES = [];
    }

    public function testSimpleUpload()
    {
        $fls = $_FILES;
        $_FILES = $this->simpleUpload;
        $this->assertEquals(
            $this->expectedSimple(),
            ServerFiles::get()
        );
        $_FILES = $fls;
    }

    public function testMultipleUpload()
    {
        $fls = $_FILES;
        $_FILES = $this->multipleUpload;
        $this->assertEquals(
            $this->expectedMultiple(),
            ServerFiles::get()
        );
        $_FILES = $fls;
    }

    public function testMixedUpload()
    {
        $fls = $_FILES;
        $_FILES = $this->mixed;
        $this->assertEquals(
            $this->expectedMixed(),
            ServerFiles::get()
        );
        $_FILES = $fls;
    }


    public function testInvalidArray()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $_FILES = ['foo' => 'bar'];
        ServerFiles::get();
    }

    protected $simpleUpload = [
        'fieldName' => [
            'name' => 'facepalm.jpg',
            'type' => 'image/jpeg',
            'tmp_name' => '/tmp/phpn3FmFr',
            'error' => UPLOAD_ERR_OK,
            'size' => 123123
        ]
    ];

    protected $multipleUpload = [
        'fieldName' => [
            'name' => [
                'cv' => 'cv.pdf',
                'im' => 'avatar.jpeg'
            ],
            'type' => [
                'cv' => 'application/x-pdf',
                'im' => 'image/jpeg'
            ],
            'tmp_name' => [
                'cv' => '/tmp/phpn3FmFr',
                'im' => '/tmp/phpn3FmFr'
            ],
            'error' => [
                'cv' => UPLOAD_ERR_OK,
                'im' => UPLOAD_ERR_OK
            ],
            'size' => [
                'cv' => 11211,
                'im' => 33233
            ],
        ]
    ];

    protected $mixed = [
        'fieldName1' => [
            'name' => 'facepalm.jpg',
            'type' => 'image/jpeg',
            'tmp_name' => '/tmp/phpn3FmFr',
            'error' => UPLOAD_ERR_OK,
            'size' => 123123
        ],
        'fieldName2' => [
            'fieldName1'=> [
                'name' => [
                    'cv' => 'cv.pdf',
                    'im' => 'avatar.jpeg'
                ],
                'type' => [
                    'cv' => 'application/x-pdf',
                    'im' => 'image/jpeg'
                ],
                'tmp_name' => [
                    'cv' => '/tmp/phpn3FmFr',
                    'im' => '/tmp/phpn3FmFr'
                ],
                'error' => [
                    'cv' => UPLOAD_ERR_OK,
                    'im' => UPLOAD_ERR_OK
                ],
                'size' => [
                    'cv' => 11211,
                    'im' => 33233
                ],
            ],
        ]
    ];

    protected function expectedSimple()
    {
        return [
            'fieldName' => new UploadedFile(
                '/tmp/phpn3FmFr',
                123123, UPLOAD_ERR_OK,
                'facepalm.jpg',
                'image/jpeg'
            )
        ];
    }

    protected function expectedMultiple()
    {
        return [
            'fieldName' => [
                'cv' => new UploadedFile(
                    '/tmp/phpn3FmFr',
                    11211, UPLOAD_ERR_OK,
                    'cv.pdf',
                    'application/x-pdf'
                ),
                'im' => new UploadedFile(
                    '/tmp/phpn3FmFr',
                    33233, UPLOAD_ERR_OK,
                    'avatar.jpeg',
                    'image/jpeg'
                )
            ]
        ];
    }

    protected function expectedMixed()
    {
        return [
            'fieldName1' => new UploadedFile(
                '/tmp/phpn3FmFr',
                123123, UPLOAD_ERR_OK,
                'facepalm.jpg',
                'image/jpeg'
            ),
            'fieldName2' => [
                'fieldName1' => [
                    'cv' => new UploadedFile(
                        '/tmp/phpn3FmFr',
                        11211, UPLOAD_ERR_OK,
                        'cv.pdf',
                        'application/x-pdf'
                    ),
                    'im' => new UploadedFile(
                        '/tmp/phpn3FmFr',
                        33233, UPLOAD_ERR_OK,
                        'avatar.jpeg',
                        'image/jpeg'
                    )
                ]
            ]
        ];
    }
}
