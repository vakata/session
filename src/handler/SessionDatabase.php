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
     * @param  \vakata\database\DBInterface       $db the database instance to use
     * @param  string                             $tb the sessions table (defaults to `'sessions'`)
     */
    public function __construct(\vakata\database\DBInterface $db, $tb = 'sessions')
    {
        $this->db = $db;
        $this->tb = $tb;
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
        $this->db->query("DELETE FROM {$this->tb} WHERE id = ?", [$sessionID]);
        return true;
    }
    /**
     * Clean sessions
     * @param  string  $maxlifetime the session maxlifetime
     * @return bool                 was gc executed OK
     */
    public function gc($maxlifetime)
    {
        $this->db->query(
            "DELETE FROM {$this->tb} WHERE updated < ?",
            [ date('Y-m-d H:i:s', (time() - (int)$maxlifetime)) ]
        );
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
        $data = $this->db->one("SELECT data FROM {$this->tb} WHERE id = ?", [$sessionID]);
        return $data ? $data : '';
    }
    /**
     * Write session data
     * @param  string $sessionID   the session ID
     * @param  string $sessionData the sessino data
     * @return bool                was the write successful
     */
    public function write($sessionID, $sessionData)
    {
        $this->db->query(
            "INSERT INTO {$this->tb} (id, data, created, updated) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE data = ?, updated = ?",
            [ $sessionID, $sessionData, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), $sessionData, date('Y-m-d H:i:s') ]
        );
        return true;
    }
}
