<?php

namespace Nadar\PhpComposerReader;

use Exception;

class ComposerReader implements ComposerReaderInterface
{
    public $file;
    
    public function __construct($file)
    {
        $this->file = $file;    
    }
    
    public function canRead()
    {
        if (is_file($this->file) && is_readable($this->file)) {
            return true;
        }
        
        return false;
    }
    
    public function canWrite()
    {
        if (is_file($this->file) && is_writable($this->file)) {
            return true;
        }
        
        return false;
    }
    
    public function canReadAndWrite()
    {
        return $this->canRead() && $this->canWrite();
    }
    
    private $_content;
    
    public function getContent()
    {
        if ($this->_content === null) {
            if (!$this->canRead()) {
                throw new Exception("Unable to read config file {$this->file}.");
            }
            
            $buffer = $this->getFileContent($this->file);
            $this->_content = $this->jsonDecode($buffer);
        }
        
        return $this->_content;
    }
    
    public function contentSection($section, $defaultValue)
    {
        $content = $this->getContent();
        
        return isset($content[$section]) ? $content[$section] : $defaultValue;
    }
    
    protected function getFileContent($file)
    {
        return file_get_contents($file);
    }
    
    protected function jsonDecode($json)
    {
        $content = json_decode((string) $json, true);
        $this->handleJsonError(json_last_error());
        
        return $content;
    }
    
    protected function jsonEncode(array $data)
    {
        
    }
    
    protected function handleJsonError($error)
    {
        switch($error) {
            case JSON_ERROR_NONE: break; // handle nothing
            case JSON_ERROR_DEPTH: throw new Exception("Maximum stack depth exceeded");
            case JSON_ERROR_STATE_MISMATCH: throw new Exception("Underflow or the modes mismatch");
            case JSON_ERROR_CTRL_CHAR: throw new Exception("Unexpected control character found");
            case JSON_ERROR_SYNTAX: throw new Exception("Syntax error, malformed JSON");
            case JSON_ERROR_UTF8: throw new Exception("Malformed UTF-8 characters, possibly incorrectly encoded");
            default: throw new Exception("Unknown error");
        }
    }
}