<?php

namespace Nadar\PhpComposerReader;

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