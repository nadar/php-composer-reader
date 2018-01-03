<?php

namespace Nadar\PhpComposerReader\Tests;

use Nadar\PhpComposerReader\ComposerReader;
use Nadar\PhpComposerReader\AutoloadSection;

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
        }
    }
}