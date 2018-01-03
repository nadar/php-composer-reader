<?php

namespace Nadar\PhpComposerReader;

/**
 * Composer Reader Interface.
 * 
 * @author Basil Suter <basil@nadar.io>
 */
interface ComposerReaderInterface
{
    public function contentSection($section, $defaultValue);
    
    public function updateSection($section, $data);
    
    public function save();
}