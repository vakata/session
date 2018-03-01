<?php

namespace vakata\session;

use \vakata\kvstore\StorageInterface;
use \vakata\kvstore\Storage;

class Session implements StorageInterface
{
    protected $storage;

    /**
     * creates a session object
     * @param  boolean                       $start    should the session be started immediately, defaults to true
     * @param  \SessionHandlerInterface|null $handler  a session handler (if any)
     * @param  array                         $cookie   an array of cookie options (name, path, httponly, lifetime)
     */
    public function __construct(
        $start = true,
        \SessionHandlerInterface $handler = null,
        array $cookie = []
    ) {
        ini_set('session.use_cookies', true);
        ini_set("session.entropy_file", "/dev/urandom");
        ini_set("session.entropy_length", "32");
        ini_set('session.session.hash_bits_per_character', 6);
        ini_set('session.use_only_cookies', true);
        ini_set('session.use_trans_sid', false);
        ini_set('session.session.use_strict_mode', true);
        if (!(int)ini_get('session.gc_probability') || !(int)ini_get('session.gc_divisor')) {
            ini_set('session.gc_probability', '1');
            ini_set('session.gc_divisor', '100');
        }

        $cookie = array_merge([
            'name' => 'PHPSESSID',
            'path' => '/',
            'lifetime' => 0,
            'httponly' => true
        ], $cookie);
        ini_set('session.name', $cookie['name']);
        foreach ($cookie as $setting => $value) {
            ini_set('session.cookie_' . $setting, $value);
        }
        if ($handler) {
            //ini_set('session.save_handler', 'user');
            session_set_save_handler($handler);
            register_shutdown_function('session_write_close');
        }
        if ($start) {
            $this->start();
        } else {
            $this->storage = isset($_SESSION) ? new Storage($_SESSION) : new Storage();
        }
    }
    /**
     * starts the session (if not done already)
     * @codeCoverageIgnore
     */
    public function start()
    {
        if (!$this->isStarted()) {
            session_start();
            $this->storage = new Storage($_SESSION);
        }
    }
    /**
     * checks if the session is started
     * @return bool is the session started
     * @codeCoverageIgnore
     */
    public function isStarted()
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }
    
    /**
     * closes the session
     * @codeCoverageIgnore
     */
    public function close()
    {
        if ($this->isStarted()) {
            session_write_close();
        }
    }
    /**
     * destroys the session (if started)
     * @codeCoverageIgnore
     */
    public function destroy()
    {
        if ($this->isStarted()) {
            session_destroy();
        }
    }
    /**
     * regenerates the session ID
     * @param  boolean     $deleteOld should the old session data be removed, defaults to `true`
     * @codeCoverageIgnore
     */
    public function regenerate($deleteOld = true)
    {
        if ($this->isStarted()) {
            session_regenerate_id($deleteOld);
        }
    }
    /**
     * get a key from the storage by using a string locator
     * @param  string $key       the element to get (can be a deeply nested element of the data array)
     * @param  mixed  $default   the default value to return if the key is not found in the data
     * @param  string $separator the string used to separate levels of the array, defaults to "."
     * @return mixed             the value of that element in the data array (or the default value)
     */
    public function get($key, $default = null, $separator = '.')
    {
        return $this->storage->get($key, $default, $separator);
    }
    /**
     * set an element in the storage to a specified value
     * @param  string $key       the element to set (can be a deeply nested element of the data array)
     * @param  mixed  $value     the value to assign the selected element to
     * @param  string $separator the string used to separate levels of the array, defaults to "."
     * @return mixed             the stored value
     */
    public function set($key, $value, $separator = '.')
    {
        return $this->storage->set($key, $value, $separator);
    }
    /**
     * delete an element from the storage
     * @param  string $key       the element to delete (can be a deeply nested element of the data array)
     * @param  string $separator the string used to separate levels of the array, defaults to "."
     * @return mixed|null        the deleted value (or null)
     */
    public function del($key, $separator = '.')
    {
        return $this->storage->del($key, $separator);
    }
}
