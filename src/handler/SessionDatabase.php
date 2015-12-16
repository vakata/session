<?php

namespace vakata\session\handler;

/**
 * A session handler for storing sessions in database.
 */
class SessionDatabase implements \SessionHandlerInterface
{
    private $db = null;
    private $tb = null;
    /**
     * Create an instance.
     * @method __construct
     * @param  \vakata\database\DatabaseInterface $db the database instance to use
     * @param  string                             $tb the sessions table (defaults to `'sessions'`)
     */
    public function __construct(\vakata\database\DatabaseInterface $db, $tb = 'sessions')
    {
        $this->db = $db;
        $this->tb = $tb;
    }

    public function close()
    {
        return true;
    }
    public function destroy($sessionID)
    {
        $this->db->query("DELETE FROM {$this->tb} WHERE id = ?", [$sessionID]);
        return true;
    }
    public function gc($maxlifetime)
    {
        $this->db->query(
            "DELETE FROM {$this->tb} WHERE updated < ?",
            [ date('Y-m-d H:i:s', (time() - (int)$maxlifetime)) ]
        );
        return true;
    }
    public function open($path, $name)
    {
        return true;
    }
    public function read($sessionID)
    {
        $data = $this->db->one("SELECT data FROM {$this->tb} WHERE id = ?", [$sessionID]);
        return $data ? $data : '';
    }
    public function write($sessionID, $sessionData)
    {
        $this->db->query(
            "INSERT INTO {$this->tb} (id, data, created, updated) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE data = ?, updated = ?",
            [ $sessionID, $sessionData, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), $sessionData, date('Y-m-d H:i:s') ]
        );
        return true;
    }
}
