<?php

namespace Nadar\PhpComposerReader;

use Composer\Semver\Comparator;
use Nadar\PhpComposerReader\Interfaces\SectionInstanceInterface;
use Nadar\PhpComposerReader\Interfaces\ComposerReaderInterface;

/**
 * Package Object.
 *
 * Package details which used for require and require-dev sections.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class Package implements SectionInstanceInterface
{
    public $reader;
    
    public $name;
    
    public $constraint;
    
    /**
     * Constructor
     *
     * @param ComposerReaderInterface $reader
     * @param string $name
     * @param string $constraint
     */
    public function __construct(ComposerReaderInterface $reader, $name, $constraint)
    {
        $this->reader = $reader;
        $this->name = $name;
        $this->constraint = $constraint;
    }

    /**
     * Whether constraint is greater then given constraint
     *
     * @param string $constraint
     * @return boolean
     * @since 1.1.0
     */
    public function greaterThan($constraint)
    {
        return Comparator::greaterThan($this->constraint, $constraint);
    }

    /**
     * Whether constraint is greater or equal then given constraint
     *
     * @param string $constraint
     * @return boolean
     * @since 1.1.0
     */
    public function greaterThanOrEqualTo($constraint)
    {
        return Comparator::greaterThanOrEqualTo($this->constraint, $constraint);
    }

    /**
     * Whether constraint is less than then given constraint
     *
     * @param string $constraint
     * @return boolean
     * @since 1.1.0
     */
    public function lessThan($constraint)
    {
        return Comparator::lessThan($this->constraint, $constraint);
    }

    /**
     * Whether constraint is less than or equal to then given constraint
     *
     * @param string $constraint
     * @return boolean
     * @since 1.1.0
     */
    public function lessThanOrEqualTo($constraint)
    {
        return Comparator::lessThanOrEqualTo($this->constraint, $constraint);
    }

    /**
     * Whether constraint is equal to then given constraint
     *
     * @param string $constraint
     * @return boolean
     * @since 1.1.0
     */
    public function equalTo($constraint)
    {
        return Comparator::equalTo($this->constraint, $constraint);
    }

    /**
     * Whether constraint is not equal to then given constraint
     *
     * @param string $constraint
     * @return boolean
     * @since 1.1.0
     */
    public function notEqualTo($constraint)
    {
        return Comparator::notEqualTo($this->constraint, $constraint);
    }
}
