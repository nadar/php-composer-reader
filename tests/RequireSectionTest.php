<?php

namespace Nadar\PhpComposerReader\Tests;

use Nadar\PhpComposerReader\ComposerReader;
use Nadar\PhpComposerReader\RequireSection;

class RequireSectionTest extends ComposerReaderTestCase
{
    public function testIterator()
    {
        $reader = new ComposerReader($this->getValidJson());
        
        $section = new RequireSection($reader);
        
        foreach ($section as $name => $package) {
            $this->assertSame('phpunit/phpunit', $name);

            $this->assertSame("^6.5", $package->constraint);
            $this->assertSame("phpunit/phpunit", $package->name);
        }
    }
}