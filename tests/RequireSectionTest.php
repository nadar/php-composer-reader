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
            $this->assertTrue($package->greaterThan("^1.0@dev"));
            $this->assertTrue($package->greaterThanOrEqualTo("^1.0@dev"));
            $this->assertFalse($package->lessThan("^1.0@dev"));

            $this->assertFalse($package->greaterThan('^6.5'));
            $this->assertTrue($package->lessThanOrEqualTo('^6.5'));
            $this->assertTrue($package->equalTo('^6.5'));
            $this->assertTrue($package->notEqualTo('^6.4'));
        }
    }
}
