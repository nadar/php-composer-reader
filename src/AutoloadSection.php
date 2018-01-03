<?php

namespace Nadar\PhpComposerReader;

use Iterator;

/**
 * Require Section Iterator.
 *
 * @author Basil Suter <basil@nadar.io>
 */
class AutoloadSection implements Iterator
{
    protected $reader;
    
    protected $data;
    
    public function __construct(ComposerReaderInterface $reader)
    {
        $this->reader = $reader;
        $this->data = $this->getData();
    }
    
    protected function getData()
    {
        $data = [];
        $types = $this->reader->contentSection('autoload', []);
        foreach ($types as $name => $content) {
            $data = array_merge($data, $content);
        }
        
        return $data;
    }
    
    public function rewind()
    {
        return reset($this->data);
    }
    
    public function current()
    {
        return new Autoload($this->reader, $this->key(), current($this->data));
    }
    
    public function key()
    {
        return key($this->data);
    }
    
    public function next()
    {
        return next($this->data);
    }
    
    public function valid()
    {
        return key($this->data) !== null;
    }
}