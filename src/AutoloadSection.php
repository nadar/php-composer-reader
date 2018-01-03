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
    public $reader;
    
    protected $data;
    
    protected $type;
    
    const TYPE_PSR4 = 'psr-4';
    
    const TYPE_PSR0 = 'psr-0';
    
    const SECTION_KEY = 'autoload';
    
    public function __construct(ComposerReaderInterface $reader, $type = self::TYPE_PSR4)
    {
        $this->reader = $reader;
        $this->type = $type;
        $this->data = $this->getData();
    }
    
    protected function getData()
    {
        $types = $this->reader->contentSection(self::SECTION_KEY, []);
        
        return isset($types[$this->type]) ? $types[$this->type] : [];
    }
    
    public function rewind()
    {
        return reset($this->data);
    }
    
    public function current()
    {
        return new Autoload($this->reader, $this->key(), current($this->data), $this->type);
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
    
    public function add(Autoload $autoload)
    {
        $data = $this->reader->contentSection(self::SECTION_KEY, []);
        
        $data[$autoload->type][$autoload->namespace] = $autoload->source;
        
        $data = $this->ensureDoubleBackslash($data);
        
        $autoload->reader->updateSection(self::SECTION_KEY, $data);
        
        return $autoload->reader;
    }
    
    private function ensureDoubleBackslash($data)
    {
        $ensure = [];
        foreach ($data as $type => $items) {
            foreach ($items as $ns => $src) {
                
                $slashable = preg_replace('#\\+#','\\', $ns);
                
                $ensure[$type][$slashable] = $src;
            }
        }
        
        return $ensure;
    }
}