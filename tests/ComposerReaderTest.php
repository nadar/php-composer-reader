<?php

namespace Nadar\PhpComposerReader\Tests;

use Nadar\PhpComposerReader\ComposerReader;

class ComposerReaderTest extends ComposerReaderTestCase
{
    public function testCanRead()
    {
        $json = new ComposerReader($this->getInvalidJson());
        $this->assertFalse($json->canRead());
        
        $json = new ComposerReader($this->getValidJson());
        $this->assertTrue($json->canRead());
    }
    
    public function testCanWrite()
    {
        $json = new ComposerReader($this->getInvalidJson());
        $this->assertFalse($json->canWrite());
        $this->assertFalse($json->canReadAndWrite());
        
        $json = new ComposerReader($this->getValidJson());
        $this->assertTrue($json->canWrite());
        $this->assertTrue($json->canReadAndWrite());
    }
    
    public function testGetContent()
    {
        $json = new ComposerReader($this->getValidJson());
        $this->assertArrayHasKey('name', $json->getContent());
    }
    
    public function testMalformedJson()
    {
        $json = new ComposerReader($this->getMalformedJson());
        
        $this->expectException("Exception");
        $json->getContent();
    }
}