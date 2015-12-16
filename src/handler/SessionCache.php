<?php

namespace vakata\session\handler;

/**
 * A session handler for storing sessions in cache (like memcached).
 */
class SessionCache implements \SessionHandlerInterface
{
    private $cache = null;
    private $table = null;
    private $expire = null;
    /**
     * Create an instance.
     * @method __construct
     * @param  \vakata\cache\CacheInterface $cache the cache instance
     * @param  string                       $table the cache namespace to use
     */
    public function __construct(\vakata\cache\CacheInterface $cache, $table = 'sessions')
    {
        $this->cache = $cache;
        $this->table = $table;
        $this->expire = ini_get('session.gc_maxlifetime');
    }
    public function close()
    {
        return true;
    }
    public function destroy($sessionID)
    {
        $this->cache->delete($sessionID, $this->table);
        return true;
    }
    public function gc($maxlifetime)
    {
        return true;
    }
    public function open($path, $name)
    {
        return true;
    }
    public function read($sessionID)
    {
        try {
            $data = $this->cache->get($sessionID, $this->table);
        } catch (\vakata\cache\CacheException $e) {
            return '';
        }

        return $data ? $data : '';
    }
    public function write($sessionID, $sessionData)
    {
        $this->cache->set($sessionID, $sessionData, $this->table, $this->expire);
        return true;
    }
}
