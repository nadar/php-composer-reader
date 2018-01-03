<?php

namespace Nadar\PhpComposerReader;

class Autoload
{
    protected $reader;
    
    public $namespace;
    
    public $source;
    
    public function __construct(ComposerReaderInterface $reader, $namespace, $source)
    {
        $this->reader = $reader;
        $this->namespace = $namespace;
        $this->source = $source;
    }
}