<?php

namespace vakata\session\handler;

use vakata\cache\CacheException;
use \vakata\cache\CacheInterface;

/**
 * A session handler for storing sessions in cache (like memcached).
 */
class SessionCache implements \SessionHandlerInterface
{
    private $cache = null;
    private $prefix = '';
    private $expire = 1440;
    /**
     * Create an instance.
     * @param  \vakata\cache\CacheInterface $cache the cache instance
     * @param  string                       $table the cache namespace to use
     */
    public function __construct(CacheInterface $cache, $prefix = 'sessions')
    {
        $this->cache = $cache;
        $this->prefix = $prefix;
        $this->expire = (int)ini_get('session.gc_maxlifetime');
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
        try {
            $this->cache->delete($this->prefix . $sessionID);
        } catch (CacheException $ignore) {}
        return true;
    }
    /**
     * Clean sessions
     * @param  string  $maxlifetime the session maxlifetime
     * @return bool                 was gc executed OK
     */
    public function gc(int $maxlifetime): int
    {
        return 0;
    }
    /**
     * Open a session
     * @param  string $path path
     * @param  string $name session name
     * @return bool         was open OK
     */
    public function open(string $path, string $name): bool
    {
        return true;
    }
    /**
     * Read a session
     * @param  string $sessionID the session ID
     * @return string            the session data
     */
    public function read(string $sessionID): string
    {
        return $this->cache->get($this->prefix . $sessionID, '');
    }
    /**
     * Write session data
     * @param  string $sessionID   the session ID
     * @param  string $sessionData the sessino data
     * @return bool                was the write successful
     */
    public function write(string $sessionID, string $sessionData): bool
    {
        $this->cache->set($this->prefix . $sessionID, $sessionData, $this->expire);
        return true;
    }
}
