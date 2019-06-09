<?php

namespace Nadar\PhpComposerReader;

use Nadar\PhpComposerReader\Interfaces\SectionInstanceInterface;
use Nadar\PhpComposerReader\Interfaces\ComposerReaderInterface;

/**
 * Autoload Object.
 *
 * A autoload or autoload-dev object.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class Autoload implements SectionInstanceInterface
{
    public $reader;
    
    public $namespace;
    
    public $source;
    
    public $type;
    
    /**
     * Autoload Section
     *
     * @param ComposerReaderInterface $reader
     * @param string $namespace The namespace like \\foo\\bar\\
     * @param string $source The path which is root for the namespace like path/to
     * @param string $type The type psr-4 or psr-0
     */
    public function __construct(ComposerReaderInterface $reader, $namespace, $source, $type)
    {
        $this->reader = $reader;
        $this->namespace = $namespace;
        $this->source = $source;
        $this->type = $type;
    }
}
