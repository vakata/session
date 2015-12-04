<?php

namespace vakata\session;

use \vakata\kvstore\StorageInterface;
use \vakata\kvstore\Storage;

class Session implements StorageInterface
{
    protected $storage;

    public function __construct(
        $start = true,
        \SessionHandlerInterface $handler = null,
        $name = 'PHPSESSID',
        $location = ''
    ) {
        ini_set('session.use_cookies', true);
        ini_set("session.entropy_file", "/dev/urandom");
        ini_set("session.entropy_length", "32");
        ini_set('session.session.hash_bits_per_character', 6);
        ini_set('session.use_only_cookies', true);
        ini_set('session.cookie_httponly', true);
        ini_set('session.use_trans_sid', false);
        ini_set('session.name', $name);

        if (!$handler && $location) {
            session_save_path($location);
        }
        if ($handler) {
            ini_set('session.save_handler', 'user');
            session_set_save_handler($handler);
            register_shutdown_function('session_write_close');
        }
        if ($start) {
            $this->start();
        }
        if (!$start) {
            if (isset($_SESSION)) {
                $this->storage = new Storage($_SESSION);
            }
            else {
                $this->storage = new Storage();
            }
        }
    }
    public function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
            $this->storage = new Storage($_SESSION);
        }
    }
    public function destroy()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_destroy();
        }
    }
    public function regenerate($keepOld)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_regenerate_id($keepOld);
        }
    }
    public function get($key, $default = null, $separator = '.')
    {
        return $this->storage->get($key, $default, $separator);
    }
    public function set($key, $value, $separator = '.')
    {
        return $this->storage->set($key, $value, $separator);
    }
    public function del($key, $separator = '.')
    {
        return $this->storage->del($key, $separator);
    }
}
