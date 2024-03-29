<?php

namespace Nadar\PhpComposerReader;

/**
 * Require Section Iterator.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.3.0
 */
class AutoloadDevSection extends AutoloadSection
{
    /**
     * @var string
     */
    public const SECTION_KEY = 'autoload-dev';
}
