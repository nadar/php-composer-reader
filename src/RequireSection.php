<?php

namespace Nadar\PhpComposerReader;

use Exception;
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
    /**
     * @var string
     */
    public const SECTION_KEY = 'require';

    protected $reader;

    /**
     * Constructor
     */
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

        if (!array_key_exists($sectionIdentifier, $data)) {
            throw new Exception(sprintf('Unable to find the given section key \'%s\' to remove.', $sectionIdentifier));
        }

        unset($data[$sectionIdentifier]);
        $this->reader->updateSection(static::SECTION_KEY, $data);

        return $this->reader;
    }
}
