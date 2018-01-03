<?php

namespace Nadar\PhpComposerReader\Tests;

use PHPUnit\Framework\TestCase;

class ComposerReaderTestCase extends TestCase
{
    protected function getValidJson()
    {
        return getcwd() . '/tests/valid.json';
    }
    
    protected function getInvalidJson()
    {
        return getcwd() . '/tests/doesnotexists.json';
    }
    
    protected function getMalformedJson()
    {
        return getcwd() . '/tests/malformed.json';
    }
}