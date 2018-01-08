<?php

namespace Nadar\PhpComposerReader;

/**
 * Autoload Object.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class Autoload
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
