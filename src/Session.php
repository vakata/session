<?php

namespace vakata\session;

use \vakata\kvstore\StorageInterface;
use \vakata\kvstore\Storage;

class Session implements StorageInterface
{
    protected $storage;

    /**
     * creates a session object
     * @method __construct
     * @param  boolean                       $start    should the session be started immediately, defaults to true
     * @param  \SessionHandlerInterface|null $handler  a session handler (if any)
     * @param  string                        $name     name of the session cookie, defaults to "PHPSESSID"
     * @param  string                        $location location of session files on disk (only if no handler is used)
     */
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
        if (!(int)ini_get('session.gc_probability') || !(int)ini_get('session.gc_divisor')) {
            ini_set('session.gc_probability', '1');
            ini_set('session.gc_divisor', '100');
        }

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
            } else {
                $this->storage = new Storage();
            }
        }
    }
    /**
     * starts the session (if not done already)
     * @method start
     * @codeCoverageIgnore
     */
    public function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
            $this->storage = new Storage($_SESSION);
        }
    }
    /**
     * destroys the session (if started)
     * @method destroy
     * @codeCoverageIgnore
     */
    public function destroy()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_destroy();
        }
    }
    /**
     * regenerates the session ID
     * @method regenerate
     * @param  boolean     $keepOld should the old session data be kept
     * @codeCoverageIgnore
     */
    public function regenerate($keepOld)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_regenerate_id($keepOld);
        }
    }
    /**
     * get a key from the storage by using a string locator
     * @method get
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
     * @method set
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
     * @method set
     * @param  string $key       the element to delete (can be a deeply nested element of the data array)
     * @param  string $separator the string used to separate levels of the array, defaults to "."
     * @return boolean           the status of the del operation - true if successful, false otherwise
     */
    public function del($key, $separator = '.')
    {
        return $this->storage->del($key, $separator);
    }
}
