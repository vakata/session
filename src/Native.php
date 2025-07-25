<?php

namespace vakata\session;

use \vakata\kvstore\StorageInterface;
use \vakata\kvstore\Storage;

class Native implements SessionInterface
{
    protected StorageInterface $storage;

    /**
     * creates a session object
     * @param  \SessionHandlerInterface|null $handler  a session handler (if any)
     */
    public function __construct(?\SessionHandlerInterface $handler = null, ?int $maxlifetime = null) {
        if (!$this->isStarted() && $handler) {
            session_set_save_handler($handler);
            register_shutdown_function('session_write_close');
            if ($maxlifetime) {
                ini_set('session.gc_maxlifetime', $maxlifetime);
            }
        }
        $this->storage = isset($_SESSION) ? new Storage($_SESSION) : new Storage();
    }
    public function id(?string $id = null): string
    {
        return $this->isStarted() ? session_id() : '';
    }
    /**
     * starts the session (if not done already)
     * @codeCoverageIgnore
     */
    public function start(?string $id = null): void
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
    public function isStarted(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * closes the session
     * @codeCoverageIgnore
     */
    public function close(): void
    {
        if ($this->isStarted()) {
            session_write_close();
        }
    }
    /**
     * destroys the session (if started)
     * @codeCoverageIgnore
     */
    public function destroy(): void
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
    public function regenerate(bool $deleteOld = true): void
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
    public function get(string $key, mixed $default = null, string $separator = '.'): mixed
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
    public function set(string $key, mixed $value, string $separator = '.'): mixed
    {
        return $this->storage->set($key, $value, $separator);
    }
    /**
     * delete an element from the storage
     * @param  string $key       the element to delete (can be a deeply nested element of the data array)
     * @param  string $separator the string used to separate levels of the array, defaults to "."
     * @return mixed|null        the deleted value (or null)
     */
    public function del(string $key, string $separator = '.'): mixed
    {
        return $this->storage->del($key, $separator);
    }
}
