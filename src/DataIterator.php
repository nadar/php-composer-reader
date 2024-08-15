<?php

namespace Nadar\PhpComposerReader;

use Countable;
use Iterator;

/**
 * Data Iterator.
 *
 * This class mainly helps to generate the itrator for a given section. The `loadData()` method must be run in
 * order to retrieve data.
 *
 * ```php
 * public function createIteratorItem()
 * {
 *    return new Package($this->reader, $this->key(), current($this->data));
 * }
 * ```
 *
 * ```php
 * public function assignIteratorData()
 * {
 *      return $this->reader->contentSection('require', []);
 * }
 * ```
 *
 * run `loadData()` in constructor.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.1.0
 */
abstract class DataIterator implements Iterator, Countable
{
    protected $data = [];

    /**
     * Assign the data.
     *
     * Returns an array with data which should be assigned to the iterator.
     *
     * @return array
     */
    abstract public function assignIteratorData();

    /**
     * Create the item for the current item.
     *
     * @return mixed
     */
    abstract public function createIteratorItem();

    /**
     * Load the iterator from assignIteratorData to the array.
     *
     * @return void
     */
    public function loadData()
    {
        $this->data = $this->assignIteratorData();
    }

    /**
     * @inheritDoc
     */
    #[\ReturnTypeWillChange]
    public function current(): mixed
    {
        return $this->createIteratorItem();
    }

    /**
     * {@inheritDoc}
     */
    #[\ReturnTypeWillChange]
    public function rewind(): void
    {
        reset($this->data);
    }

    /**
     * @inheritDoc
     */
    #[\ReturnTypeWillChange]
    public function key(): mixed
    {
        return key($this->data);
    }

    /**
     * @inheritDoc
     */
    #[\ReturnTypeWillChange]
    public function next(): void
    {
        next($this->data);
    }

    /**
     * @inheritDoc
     */
    #[\ReturnTypeWillChange]
    public function valid(): bool
    {
        return key($this->data) !== null;
    }

    /**
     * @inheritDoc
     */
    #[\ReturnTypeWillChange]
    public function count(): int
    {
        return count($this->data);
    }
}
