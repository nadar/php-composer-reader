<?php

declare(strict_types=1);

namespace Nadar\PhpComposerReader\Tests;

use PHPUnit\Framework\TestCase;

error_reporting(E_ALL);

class ComposerReaderTestCase extends TestCase
{
    protected function getValidJson()
    {
        return getcwd() . '/tests/valid.json';
    }
    
    protected function getInvalidJson()
    {
        return getcwd() . '/tests/doesnotexists.json';
    }
    
    protected function getMalformedJson()
    {
        return getcwd() . '/tests/malformed.json';
    }
    
    protected function getWorkingComposerJson()
    {
        return getcwd() . '/tests/composer.json';
    }

    public function generateTemporaryJson(array $content)
    {
        $fileName = uniqid('c');
        $dir = getcwd();
        $path = $dir . DIRECTORY_SEPARATOR . $fileName;
        file_put_contents($path, json_encode($content));

        return $path;
    }

    public function removeTemporaryJson($filePath)
    {
        unlink($filePath);
    }
}
