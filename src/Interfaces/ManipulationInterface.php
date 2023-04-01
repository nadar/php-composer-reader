<?php

namespace Nadar\PhpComposerReader\Interfaces;

/**
 * Manipulation Interface.
 *
 * Defines whether the current section can be manipulated.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.4.0
 */
interface ManipulationInterface
{
    /**
     * Add a new Autoload object int othe section.
     *
     * @param SectionInstanceInterface $sectionInstance
     * @return ComposerReaderInterface
     */
    public function add(SectionInstanceInterface $sectionInstance): ComposerReaderInterface;

    /**
     * Removes a given section entry based on the identifier.
     *
     * @param string $sectionIdentifier The section identifer which is the namespace or the package name
     * @return ComposerReaderInterface
     */
    public function remove($sectionIdentifier): ComposerReaderInterface;
}
