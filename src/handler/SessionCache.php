<?php

namespace vakata\session\handler;

use \vakata\cache\CacheInterface;

/**
 * A session handler for storing sessions in cache (like memcached).
 */
class SessionCache implements \SessionHandlerInterface
{
    private $cache = null;
    private $table = null;
    private $expire = 1440;
    /**
     * Create an instance.
     * @param  \vakata\cache\CacheInterface $cache the cache instance
     * @param  string                       $table the cache namespace to use
     */
    public function __construct(CacheInterface $cache, $table = 'sessions')
    {
        $this->cache = $cache;
        $this->table = $table;
        $this->expire = (int)ini_get('session.gc_maxlifetime');
    }
    public function close()
    {
        return true;
    }
    /**
     * Destroy the session
     * @param  string  $sessionID the session ID
     * @return bool             was the session destroyed
     */
    public function destroy($sessionID)
    {
        $this->cache->delete($sessionID, $this->table);
        return true;
    }
    /**
     * Clean sessions
     * @param  string  $maxlifetime the session maxlifetime
     * @return bool                 was gc executed OK
     */
    public function gc($maxlifetime)
    {
        return true;
    }
    /**
     * Open a session
     * @param  string $path path
     * @param  string $name session name
     * @return bool         was open OK
     */
    public function open($path, $name)
    {
        return true;
    }
    /**
     * Read a session
     * @param  string $sessionID the session ID
     * @return string            the session data
     */
    public function read($sessionID)
    {
        return $this->cache->get($sessionID, '', $this->table);
    }
    /**
     * Write session data
     * @param  string $sessionID   the session ID
     * @param  string $sessionData the sessino data
     * @return bool                was the write successful
     */
    public function write($sessionID, $sessionData)
    {
        $this->cache->set($sessionID, $sessionData, $this->table, $this->expire);
        return true;
    }
}
