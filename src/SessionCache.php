<?php

namespace vakata\session;

class SessionCache implements \SessionHandlerInterface
{
    private $cache = null;
    private $table = null;
    private $expire = null;

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
        return $this->cache->del($sessionID, $this->table);
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
        $data = $this->cache->get($sessionID, $this->table);

        return $data ? $data : '';
    }
    public function write($sessionID, $sessionData)
    {
        return $this->cache->set($sessionID, $sessionData, $this->table, $this->expire);
    }
}
