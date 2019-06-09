<?php

namespace Nadar\PhpComposerReader\Interfaces;

/**
 * Composer Reader Interface.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
interface ComposerReaderInterface
{
    /**
     * Get the content of a given section.
     *
     * @param string $section
     * @param mixed $defaultValue
     */
    public function contentSection($section, $defaultValue);
    
    /**
     * Update the content of a given section.
     *
     * This will replay the whole section with the new given $data. No merging of data!
     *
     * @param string $section
     * @param mixed $data
     */
    public function updateSection($section, $data);
    
    /**
     * Remove a given section.
     *
     * This will remove the whole section!
     *
     * @param string $section
     * @since 1.2.0
     */
    public function removeSection($section);
    
    /**
     * Save the current Data.
     *
     * Saves the current json data into the composer.json of the given reader.
     * 
     * @return boolean Whether saving was successfull or not.
     */
    public function save();
}
