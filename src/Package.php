<?php

namespace Nadar\PhpComposerReader;

/**
 * Package Object.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class Package
{
    public $reader;
    
    public $name;
    
    public $constraint;
    
    public function __construct(ComposerReaderInterface $reader, $name, $constraint)
    {
        $this->reader = $reader;
        $this->name = $name;
        $this->constraint = $constraint;
    }
}
