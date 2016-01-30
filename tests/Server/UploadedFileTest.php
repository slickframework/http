<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Http\Server;

use PHPUnit_Framework_TestCase as TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Slick\Http\Server\UploadedFile;
use Slick\Http\Stream;

/**
 * UploadedFile Test case
 *
 * @package Slick\Tests\Http\Server
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class UploadedFileTest extends TestCase
{

    /**
     * Should be one of UPLOAD_ERR_* constants or raise an exception
     * @test
     * @expectedException \Slick\Http\Exception\InvalidArgumentException
     */
    public function statusError()
    {
        $file = new UploadedFile('php://memory', 0, UPLOAD_ERR_NO_FILE);
        $this->assertEquals(UPLOAD_ERR_NO_FILE, $file->getError());
        new UploadedFile('php://memory', 10, 6000);
    }

    /**
     * Should be a positive integer or raise an exception
     * @test
     * @expectedException \Slick\Http\Exception\InvalidArgumentException
     */
    public function size()
    {
        $file = new UploadedFile('php://memory', 1024, UPLOAD_ERR_OK);
        $this->assertEquals(1024, $file->getSize());
        new UploadedFile('php://memory', '3MB', UPLOAD_ERR_OK);
    }

    /**
     * Should be null or string or raise an exception
     * @test
     * @expectedException \Slick\Http\Exception\InvalidArgumentException
     */
    public function mediaType()
    {
        $file = new UploadedFile('php://memory', 1024, UPLOAD_ERR_OK, null, 'text/plain');
        $this->assertEquals('text/plain', $file->getClientMediaType());
        new UploadedFile('php://memory', 1024, UPLOAD_ERR_OK, null, 5);
    }

    /**
     * Should be null or string or raise an exception
     * @test
     * @expectedException \Slick\Http\Exception\InvalidArgumentException
     */
    public function fileName()
    {
        $file = new UploadedFile('php://memory', 1024, UPLOAD_ERR_OK, 'test.txt');
        $this->assertEquals('test.txt', $file->getClientFilename());
        new UploadedFile('php://memory', 1024, UPLOAD_ERR_OK, 5);
    }

    /**
     * Should set the stream and not the file
     * @test
     */
    public function setStream()
    {
        $stream = new Stream('php://memory', 'rw+');
        $stream->write('test');
        $file = new UploadedFile($stream, 4, UPLOAD_ERR_OK);
        $this->assertSame($stream, $file->getStream());
    }

    /**
     * Should raise an exception
     * @test
     * @expectedException \Slick\Http\Exception\InvalidArgumentException
     */
    public function setInvalidStream()
    {
        new UploadedFile(null, 0, UPLOAD_ERR_OK);
    }

    /**
     * Should create a stream when a resource identifying it is passed as
     * a file to constructor.
     * @test
     */
    public function lazyStreamCreation()
    {
        $file = new UploadedFile('php://memory', 0, UPLOAD_ERR_OK);
        $this->assertInstanceOf(Stream::class, $file->getStream());
    }

    /**
     * Should raine an exception
     * @test
     * @expectedException \Slick\Http\Exception\FileOperationException
     */
    public function moveFileWithError()
    {
        $file = new UploadedFile(null, 0, UPLOAD_ERR_CANT_WRITE);
        $file->moveTo('/tmp');
    }

    /**
     * Should read stream and write it to the destination file
     * Trying to move again will raise an exception
     * @test
     * @expectedException \Slick\Http\Exception\FileOperationException
     */
    public function moveFile()
    {
        $temp = tempnam (sys_get_temp_dir(), 'foo');
        $stream = new Stream('php://memory', 'rw+');
        $stream->write('test');
        $file = new UploadedFile($stream, 4, UPLOAD_ERR_OK);
        $file->moveTo($temp);
        $this->assertEquals('test', file_get_contents($temp));
        $file->moveTo($temp);
    }

    /**
     * Should read stream and write it to the destination file
     * Trying to move again will raise an exception
     * @test
     * @expectedException \Slick\Http\Exception\FileOperationException
     */
    public function writeProtectedFile()
    {
        $temp = __DIR__.'/test.txt';
        chmod($temp, 0444);
        $stream = new Stream('php://memory', 'rw+');
        $stream->write('test');
        $file = new UploadedFile($stream, 4, UPLOAD_ERR_OK);
        $file->moveTo($temp);
    }

    /**
     * Should raise an exception
     * @test
     * @expectedException \Slick\Http\Exception\InvalidArgumentException
     */
    public function moveToEmptyFile()
    {
        $file = new UploadedFile('php://memory', 4, UPLOAD_ERR_OK);
        $file->moveTo('');
    }

    /**
     * Should raise an exception
     * @test
     * @expectedException \Slick\Http\Exception\InvalidArgumentException
     */
    public function moveToInvalidFile()
    {
        $file = new UploadedFile('php://memory', 4, UPLOAD_ERR_OK);
        $file->moveTo(123);
    }

    /**
     * Should move original file to destination
     * @test
     * @expectedException \Slick\Http\Exception\FileOperationException
     */
    public function moveUploadedFile()
    {
        $temp = tempnam (sys_get_temp_dir(), 'foo');
        file_put_contents($temp, 'test');
        /** @var UploadedFile|MockObject $file */
        $file = $this->getMockBuilder(UploadedFile::class)
            ->setConstructorArgs([$temp, 4, UPLOAD_ERR_OK])
            ->setMethods(['isNonSAPIEnvironment'])
            ->getMock();
        $file->expects($this->once())
            ->method('isNonSAPIEnvironment')
            ->willReturn(false);
        $file->moveTo(sys_get_temp_dir());
    }
}
