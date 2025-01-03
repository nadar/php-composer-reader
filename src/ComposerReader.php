<?php

namespace Nadar\PhpComposerReader;

use Exception;
use Nadar\PhpComposerReader\Interfaces\ComposerReaderInterface;

/**
 * Composer Reader Object.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class ComposerReader implements ComposerReaderInterface
{
    /**
     * Create new ComposerReader instance by providing the path to the composer.json file.
     *
     * @param string $file The path to the composer.json file.
     */
    public function __construct(public string|array $file)
    {
    }

    protected function isProvidedAsJsonArray(): bool
    {
        return is_array($this->file);
    }

    /**
     * Whether current composer.json file is readable or not.
     *
     * @return boolean Whether current composer.json file is readable or not.
     */
    public function canRead(): bool
    {
        if ($this->isProvidedAsJsonArray()) {
            return true;
        }

        return is_file($this->file) && is_readable($this->file);
    }

    /**
     * Whether current composer.json file can be written or not.
     *
     * @return boolean Whether current composer.json file can be written or not.
     */
    public function canWrite(): bool
    {
        if ($this->isProvidedAsJsonArray()) {
            return false;
        }

        return is_file($this->file) && is_writable($this->file);
    }

    /**
     * Whether file can be written and read.
     *
     * @return boolean Whether file can be written and read.
     */
    public function canReadAndWrite()
    {
        return $this->canRead() && $this->canWrite();
    }

    private ?array $_content = null;

    /**
     * The content of the json file as array.
     *
     * @throws Exception
     * @return array The composer.json file as array.
     */
    public function getContent(): array
    {
        if ($this->isProvidedAsJsonArray()) {
            return $this->file;
        }

        if ($this->_content === null) {
            if (!$this->canRead()) {
                throw new Exception(sprintf("Unable to read config file '%s'.", $this->file));
            }

            $buffer = $this->getFileContent($this->file);
            $this->_content = $this->jsonDecode($buffer);
        }

        return $this->_content;
    }

    /**
     * Write the content into the composer.json.
     *
     * @param array $content The content to write.
     * @throws Exception
     * @return boolean Whether writing was successfull or not.
     */
    public function writeContent(array $content)
    {
        if ($this->isProvidedAsJsonArray()) {
            throw new Exception("Unable to write content as file is provided as array.");
        }

        if (!$this->canWrite()) {
            throw new Exception(sprintf("Unable to write config file '%s'.", $this->file));
        }

        $json = $this->jsonEncode($content);

        return $this->writeFileContent($this->file, $json);
    }

    /**
     * {@inheritDoc}
     */
    public function save()
    {
        return $this->writeContent($this->_content);
    }

    /**
     * {@inheritDoc}
     */
    public function contentSection($section, $defaultValue)
    {
        $content = $this->getContent();

        return $content[$section] ?? $defaultValue;
    }

    /**
     * {@inheritDoc}
     */
    public function updateSection($section, $data)
    {
        $content = $this->getContent();

        $content[$section] = $data;

        $this->_content = $content;
    }

    /**
     * {@inheritDoc}
     */
    public function removeSection($section)
    {
        $content = $this->getContent();

        if (isset($content[$section])) {
            unset($content[$section]);
        }

        $this->_content = $content;
    }

    private ?string $_commandOutput = null;

    private ?int $_commandStatus = null;

    public function createCommand(string $command, ?string $folder = null): self
    {
        $this->_commandOutput = null;
        $this->_commandStatus = null;

        $olddir = null;
        if ($folder === null) {
            $folder = dirname($this->file);
            $olddir = getcwd();
        }

        chdir($folder);

        // Execute the command and capture its full output
        $outputLines = [];
        $status = null;
        exec('composer ' . $command . ' 2>&1', $outputLines, $status);

        // Store full output in case of failure
        if ($status !== 0) {
            // Failure: Keep all lines
            $this->_commandOutput = implode("\n", $outputLines);
        } else {
            // Success: Keep only the last non-empty line
            $filteredLines = array_filter($outputLines); // Remove empty lines
            $this->_commandOutput = trim(end($filteredLines)); // Get last non-empty line
        }

        $this->_commandStatus = $status;

        if ($olddir !== null) {
            chdir($olddir);
        }

        return $this;
    }

    public function commandIsSuccessful(): bool
    {
        return $this->getCommandStatus() === 0;
    }

    public function getCommandOutput(): ?string
    {
        return trim($this->_commandOutput);
    }

    public function getCommandStatus(): ?int
    {
        return $this->_commandStatus;
    }

    /**
     * Run a composer command in the given composer.json.
     *
     * Example usage
     *
     * ```php
     * $reader = new ComposerReader('path/to/composer.json');
     * $reader->runCommand('dump-autoload'); // equals to `composer dump-autoload`
     * ```
     *
     * @param string $command
     * @return boolean
     */
    public function runCommand($command)
    {
        return $this->createCommand($command)->commandIsSuccessful();
    }

    /**
     * Get the file content.
     *
     * @param string $file
     * @return string
     */
    protected function getFileContent($file)
    {
        return file_get_contents($file);
    }

    /**
     * Write the file content.
     *
     * @param string $file
     * @param string $data
     * @return boolean
     */
    protected function writeFileContent($file, $data)
    {
        $handler = file_put_contents($file, $data);

        return $handler !== false;
    }

    /**
     * Decodes a json string into php structure.
     *
     * @param string $json
     * @return array
     */
    protected function jsonDecode($json)
    {
        $content = json_decode((string) $json, true);
        $this->handleJsonError(json_last_error());

        return $content;
    }

    /**
     * Encodes a php array structure into a json string.
     *
     * @return string
     */
    protected function jsonEncode(array $data)
    {
        set_error_handler(function () {
            $this->handleJsonError(JSON_ERROR_SYNTAX);
        }, E_WARNING);

        $json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        restore_error_handler();
        $this->handleJsonError(json_last_error());

        return $json;
    }

    /**
     * Handle json parsing errors.
     *
     * @param integer $error
     * @throws Exception
     */
    protected function handleJsonError($error)
    {
        switch ($error) {
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
