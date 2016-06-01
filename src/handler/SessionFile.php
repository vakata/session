<?php

namespace vakata\session\handler;

/**
 * A session handler for storing sessions in a directory (similar to the default PHP handling).
 */
class SessionFile implements \SessionHandlerInterface
{
    private $location;
    private $prefix;
    /**
     * Create an instance.
     * @method __construct
     * @param  string $location the directory to store files in
     */
    public function __construct($location, $prefix = '')
    {
        $this->location = $location;
        $this->prefix = $prefix;
    }
    public function close()
    {
        return true;
    }
    public function destroy($sessionID)
    {
        $file = $this->location . DIRECTORY_SEPARATOR . $this->prefix . $sessionID;
        if (file_exists($file)) {
            @unlink($file);
        }
        return true;
    }
    public function gc($maxlifetime)
    {
        foreach (scandir($this->location) as $name) {
            $file = $this->location . DIRECTORY_SEPARATOR . $name;
            if ((!$this->prefix || strpos($name , $this->prefix) === 0) &&
                is_file($file) &&
                filemtime($file) + $maxlifetime < time()
            ) {
                @unlink($file);
            }
        }
        return true;
    }
    public function open($path, $name)
    {
        if (!is_dir($this->location) && !mkdir($this->location, 0755, true)) {
            throw new \Exception('Could not open session storage dir');
        }
        return true;
    }
    public function read($sessionID)
    {
        return (string)@file_get_contents($this->location . DIRECTORY_SEPARATOR . $this->prefix . $sessionID);
    }
    public function write($sessionID, $sessionData)
    {
        return @file_put_contents(
            $this->location . DIRECTORY_SEPARATOR . $this->prefix . $sessionID,
            $sessionData
        ) !== false;
    }
}
