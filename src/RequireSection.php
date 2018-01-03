<?php

namespace Nadar\PhpComposerReader;

use Iterator;

/**
 * Require Section Iterator.
 * 
 * @author Basil Suter <basil@nadar.io>
 */
class RequireSection implements Iterator
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
        return $this->reader->contentSection('require', []);
    }
    
    public function rewind()
    {
        return reset($this->data);
    }
    
    public function current()
    {
        return new Package($this->reader, $this->key(), current($this->data));
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