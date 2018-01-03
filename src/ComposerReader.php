<?php

namespace Nadar\PhpComposerReader;

use Exception;

/**
 * Composer Reder Object.
 * 
 * @author Basil Suter <basil@nadar.io>
 */
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
    
    public function writeContent(array $content)
    {
        if (!$this->canWrite()) {
            throw new Exception("Unable to write file {this->file}.");
        }
        
        $json = $this->jsonEncode($content);
        
        return $this->writeFileContent($this->file, $json);
    }
    
    public function save()
    {
        return $this->writeContent($this->_content);
    }
    
    public function contentSection($section, $defaultValue)
    {
        $content = $this->getContent();
        
        return isset($content[$section]) ? $content[$section] : $defaultValue;
    }
    
    public function updateSection($section, $data)
    {
        $content = $this->getContent();
        
        $content[$section] = $data;
        
        $this->_content = $content;
    }
    
    protected function getFileContent($file)
    {
        return file_get_contents($file);
    }
    
    protected function writeFileContent($file, $data)
    {
        $handler = file_put_contents($file, $data);
        
        return $handler === false ? false : true;
    }
    
    protected function jsonDecode($json)
    {
        $content = json_decode((string) $json, true);
        $this->handleJsonError(json_last_error());
        
        return $content;
    }
    
    /**
     * 
     * @param array $data
    */
    protected function jsonEncode(array $data)
    {
        set_error_handler(function () {
            $this->handleJsonError(JSON_ERROR_SYNTAX);
        }, E_WARNING);
        
        $json = json_encode($data, JSON_PRETTY_PRINT);
        restore_error_handler();
        $this->handleJsonError(json_last_error());
        
        return $json;
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