<?php

namespace Nadar\PhpComposerReader\Tests;

use Nadar\PhpComposerReader\ComposerReader;
use Nadar\PhpComposerReader\AutoloadSection;
use Nadar\PhpComposerReader\Autoload;

class AutoloadSectionTest extends ComposerReaderTestCase
{
    public function testIterator()
    {
        $reader = new ComposerReader($this->getValidJson());
        $section = new AutoloadSection($reader);
        
        foreach ($section as $namespace => $autoload) {
            $this->assertSame('Nadar\\PhpComposerReader\\', $namespace);
            $this->assertSame('Nadar\\PhpComposerReader\\', $autoload->namespace);
            $this->assertSame('src/', $autoload->source);
            $this->assertSame(AutoloadSection::TYPE_PSR4, $autoload->type);
        }
    }
    
    public function testAdd()
    {
        $reader = new ComposerReader($this->getValidJson());
        $section = new AutoloadSection($reader);
        
        $new = new Autoload($reader, 'Foo\\Bar\\', 'src/foo/bar', AutoloadSection::TYPE_PSR4);
        
        $r = $section->add($new)->getContent();
        
        $this->assertSame([
            'psr-4' => ['Nadar\\PhpComposerReader\\' => 'src/', 'Foo\\Bar\\' => 'src/foo/bar']
        ], $r['autoload']);
    }
}