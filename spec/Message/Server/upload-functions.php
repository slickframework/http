<?php

namespace Slick\Http\Message\Server;

use spec\Slick\Http\Message\Server\UploadedFileSpec;

function is_uploaded_file($filename)
{
    return UploadedFileSpec::$isUploadFile;
}

function move_uploaded_file($filename, $destination)
{
    if (! UploadedFileSpec::$moveUploadFile) {
        trigger_error('Some error', E_USER_WARNING);
    }
    return UploadedFileSpec::$moveUploadFile;
}