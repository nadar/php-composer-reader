<?php

namespace Nadar\PhpComposerReader;

use Iterator;

/**
 * Require Dev Section Iterator.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.1.0
 */
class RequireDevSection extends DataIterator implements Iterator
{
    const SECTION_KEY = 'require-dev';

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
        return $this->reader->contentSection(self::SECTION_KEY, []);
    }

    /**
     * @inheritDoc
     */
    public function createIteratorItem()
    {
        return new Package($this->reader, $this->key(), current($this->data));
    }
}
