<?php

namespace Nadar\PhpComposerReader;

use Exception;
use Nadar\PhpComposerReader\Interfaces\ManipulationInterface;
use Nadar\PhpComposerReader\Interfaces\ComposerReaderInterface;
use Nadar\PhpComposerReader\Interfaces\SectionInstanceInterface;

/**
 * Require Section Iterator.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class AutoloadSection extends DataIterator implements ManipulationInterface
{
    protected $reader;
    
    protected $type;
    
    const TYPE_PSR4 = 'psr-4';
    
    const TYPE_PSR0 = 'psr-0';
    
    const SECTION_KEY = 'autoload';
    
    /**
     * Constuctor
     *
     * @param ComposerReaderInterface $reader
     * @param string $type
     */
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
     * {@inheritDoc}
     */
    public function add(SectionInstanceInterface $sectionInstance): ComposerReaderInterface
    {
        /** @var array $data */
        $data = $this->reader->contentSection(static::SECTION_KEY, []);
        
        /** @var Autoload $sectionInstance */
        $data[$sectionInstance->type][$sectionInstance->namespace] = $sectionInstance->source;
        
        $data = $this->ensureDoubleBackslash($data);
        
        $this->reader->updateSection(static::SECTION_KEY, $data);
        
        return $this->reader;
    }
    
    /**
     * {@inheritDoc}
     */
    public function remove($sectionIdentifier): ComposerReaderInterface
    {
        /** @var array $data */
        $data = $this->reader->contentSection(static::SECTION_KEY, []);

        foreach ($data as $type => $values) {
            if (array_key_exists($sectionIdentifier, $values)) {
                unset($data[$type][$sectionIdentifier]);
            }
        }
        
        $this->reader->updateSection(static::SECTION_KEY, $data);

        return $this->reader;
    }

    /**
     * Ensure double black slashes of input data.
     *
     * @param array $data An array with autoload entries
     * @return array
     */
    private function ensureDoubleBackslash(array $data)
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
