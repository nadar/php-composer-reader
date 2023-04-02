<?php

namespace Nadar\PhpComposerReader\Tests;

use Exception;
use Nadar\PhpComposerReader\ComposerReader;
use Nadar\PhpComposerReader\RequireSection;
use Nadar\PhpComposerReader\Package;
use Nadar\PhpComposerReader\RequireDevSection;

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

        $this->assertSame(1, count($section));
    }

    public function testRequireDevSection()
    {
        $reader = new ComposerReader($this->getValidJson());
        
        $section = new RequireDevSection($reader);

        $this->assertSame(0, count($section));
    }

    public function testAddFunctionOfRequireSection()
    {
        $json = $this->generateTemporaryJson(['name' => 'barfoo']);
        $reader = new ComposerReader($json);
        
        $new = new Package($reader, 'xyz', '1.0.0');
        $section = new RequireSection($reader);
        $r = $section->add($new)->save();

        $this->assertTrue($r);

        $this->removeTemporaryJson($json);
    }

    public function testAddAndRemove()
    {
        $reader = new ComposerReader($this->getValidJson());
        $section = new RequireSection($reader);

        $new = new Package($reader, 'xyz', '1.0.0');

        $this->assertTrue($section->add($new)->save());
        $this->assertTrue($section->remove($new->name)->save());
    }

    public function testNotExistingRemove()
    {

        $reader = new ComposerReader($this->getValidJson());
        $section = new RequireSection($reader);

        $this->expectException(Exception::class);
        $section->remove('doesnotexists');
    }
}
