<?php

namespace Nadar\PhpComposerReader;

use Nadar\PhpComposerReader\Interfaces\ComposerReaderInterface;
use Nadar\PhpComposerReader\Interfaces\ManipulationInterface;
use Nadar\PhpComposerReader\Interfaces\SectionInstanceInterface;

/**
 * Require Section Iterator.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class RequireSection extends DataIterator implements ManipulationInterface
{
    const SECTION_KEY = 'require';

    protected $reader;
    
    public function __construct(ComposerReaderInterface $reader)
    {
        $this->reader = $reader;
        $this->loadData();
    }
    
    /**
     * @inheritDoc
     */
    public function assignIteratorData()
    {
        return $this->reader->contentSection(static::SECTION_KEY, []);
    }

    /**
     * @inheritDoc
     */
    public function createIteratorItem()
    {
        return new Package($this->reader, $this->key(), current($this->data));
    }

    /**
     * {@inheritDoc}
     */
    public function add(SectionInstanceInterface $sectionInstance): ComposerReaderInterface
    {
        $data = $this->reader->contentSection(static::SECTION_KEY, []);
        /** @var Package $sectionInstance */
        $data[$sectionInstance->name] = $sectionInstance->constraint;
        
        $sectionInstance->reader->updateSection(static::SECTION_KEY, $data);
        
        return $sectionInstance->reader;
    }
}
