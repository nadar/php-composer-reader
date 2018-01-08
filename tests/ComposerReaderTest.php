<?php

namespace Nadar\PhpComposerReader\Tests;

use Nadar\PhpComposerReader\ComposerReader;
use Nadar\PhpComposerReader\Autoload;
use Nadar\PhpComposerReader\AutoloadSection;

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
    
    public function testWriteSection()
    {
        $filename = getcwd() . '/tests/' . uniqid() . '.json';
        $file = file_put_contents($filename, "{}");
        $json = new ComposerReader($filename);
        $json->updateSection('foobar', ['hello' => 'world']);
        $json->getContent();
        $this->assertTrue($json->save());
        
        $newreader = new ComposerReader($filename);
        
        $this->assertSame([
            'foobar' => [
                'hello' => 'world',
            ]
        ], $newreader->getContent());
        unlink($filename);
    }
    
    public function testWriteSectionWithAutoloadData()
    {
        $filename = getcwd() . '/tests/' . uniqid('als') . '.json';
        $file = file_put_contents(
            $filename,
'{
    "autoload": {
        "psr-4": {
            "luya\\\": "core/"
        }
    }
}'
        );
        $json = new ComposerReader($filename);
        
        $al = new Autoload($json, '\\Foo\Post\\', 'src/goes/here', 'psr-4');
        
        $als = new AutoloadSection($json);
        
        
        $this->assertTrue($als->add($al)->save());
        
        $newreader = new ComposerReader($filename);
        
        $this->assertSame([
            'autoload' => [
                'psr-4' => [
                    'luya\\'  => 'core/',
                    '\Foo\Post\\' => 'src/goes/here',
                 ],
            ]
        ], $newreader->getContent());
        unlink($filename);
    }
    
    public function testRunCommand()
    {
        $reader = new ComposerReader($this->getWorkingComposerJson());

        $this->assertTrue($reader->canRead());
        
        $r = $reader->runCommand('dumpautoload');
        
        $this->assertTrue($r);
    }
}
