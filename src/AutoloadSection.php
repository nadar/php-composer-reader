<?php

namespace Nadar\PhpComposerReader;


/**
 * Require Section Iterator.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class AutoloadSection extends DataIterator
{
    protected $reader;
    
    protected $type;
    
    const TYPE_PSR4 = 'psr-4';
    
    const TYPE_PSR0 = 'psr-0';
    
    const SECTION_KEY = 'autoload';
    
    public function __construct(ComposerReaderInterface $reader, $type = self::TYPE_PSR4)
    {
        $this->reader = $reader;
        $this->type = $type;
        $this->loadData();
    }
    
    /**
     * @inheritDoc
     */
    public function createIteratorItem()
    {
        return new Autoload($this->reader, $this->key(), current($this->data), $this->type);
    }

    /**
     * @inheritDoc
     */
    public function assignIteratorData()
    {
        $types = $this->reader->contentSection(static::SECTION_KEY, []);
        
        return isset($types[$this->type]) ? $types[$this->type] : [];
    }
    
    /**
     * Add a new Autoload object int othe section.
     *
     * @param Autoload $autoload
     * @return ComposerReaderInterface
     */
    public function add(Autoload $autoload)
    {
        $data = $this->reader->contentSection(static::SECTION_KEY, []);
        
        $data[$autoload->type][$autoload->namespace] = $autoload->source;
        
        $data = $this->ensureDoubleBackslash($data);
        
        $autoload->reader->updateSection(static::SECTION_KEY, $data);
        
        return $autoload->reader;
    }
    
    /**
     * Ensure double black slashes of input data.
     *
     * @param [type] $data
     * @return void
     */
    private function ensureDoubleBackslash($data)
    {
        $ensure = [];
        foreach ($data as $type => $items) {
            foreach ($items as $ns => $src) {
                $slashable = preg_replace('#\\+#', '\\', $ns);
                
                $ensure[$type][$slashable] = $src;
            }
        }
        
        return $ensure;
    }
}
