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
     * @param  string $location the directory to store files in
     */
    public function __construct($location, $prefix = '')
    {
        $this->location = $location;
        $this->prefix = $prefix;
    }
    public function close(): bool
    {
        return true;
    }
    /**
     * Destroy the session
     * @param  string  $sessionID the session ID
     * @return bool             was the session destroyed
     */
    public function destroy(string $sessionID): bool
    {
        $file = $this->location . DIRECTORY_SEPARATOR . $this->prefix . basename($sessionID);
        if (file_exists($file)) {
            @unlink($file);
        }
        return true;
    }
    /**
     * Clean sessions
     * @param  int  $maxlifetime the session maxlifetime
     * @return int               number of deleted sessions
     */
    public function gc(int $maxlifetime): int
    {
        $count = 0;
        foreach (scandir($this->location) as $name) {
            $file = $this->location . DIRECTORY_SEPARATOR . $name;
            if ((!$this->prefix || strpos($name , $this->prefix) === 0) &&
                is_file($file) &&
                filemtime($file) + $maxlifetime < time()
            ) {
                @unlink($file);
                $count ++;
            }
        }
        return $count;
    }
    /**
     * Open a session
     * @param  string $path path
     * @param  string $name session name
     * @return bool         was open OK
     */
    public function open(string $path, string $name): bool
    {
        if (!is_dir($this->location) && !mkdir($this->location, 0755, true)) {
            throw new \Exception('Could not open session storage dir');
        }
        return true;
    }
    /**
     * Read a session
     * @param  string $sessionID the session ID
     * @return string            the session data
     */
    public function read(string $sessionID): string
    {
        return (string)@file_get_contents($this->location . DIRECTORY_SEPARATOR . $this->prefix . basename($sessionID));
    }
    /**
     * Write session data
     * @param  string $sessionID   the session ID
     * @param  string $sessionData the sessino data
     * @return bool                was the write successful
     */
    public function write(string $sessionID, string $sessionData): bool
    {
        return @file_put_contents(
            $this->location . DIRECTORY_SEPARATOR . $this->prefix . basename($sessionID),
            $sessionData
        ) !== false;
    }
}
