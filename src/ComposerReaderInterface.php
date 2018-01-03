<?php

namespace Nadar\PhpComposerReader;

interface ComposerReaderInterface
{
    public function contentSection($section, $defaultValue);
}