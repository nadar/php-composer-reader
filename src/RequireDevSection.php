<?php

namespace Nadar\PhpComposerReader;

use Nadar\PhpComposerReader\Interfaces\ComposerReaderInterface;

/**
 * Require Dev Section Iterator.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.1.0
 */
class RequireDevSection extends RequireSection
{
    const SECTION_KEY = 'require-dev';
}
