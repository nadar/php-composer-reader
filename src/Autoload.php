<?php

namespace Nadar\PhpComposerReader;

use Nadar\PhpComposerReader\Interfaces\SectionInstanceInterface;
use Nadar\PhpComposerReader\Interfaces\ComposerReaderInterface;

/**
 * Autoload Object.
 *
 * A autoload or autoload-dev object.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class Autoload implements SectionInstanceInterface
{
    public $reader;
    
    public $namespace;
    
    public $source;
    
    public $type;
    
    public function __construct(ComposerReaderInterface $reader, $namespace, $source, $type)
    {
        $this->reader = $reader;
        $this->namespace = $namespace;
        $this->source = $source;
        $this->type = $type;
    }
}
