
<?php

namespace vakata\session;

use SessionHandlerInterface;
use \vakata\kvstore\StorageInterface;
use \vakata\kvstore\Storage;

class Session implements SessionInterface
{
    protected bool $started = false;
    protected int $maxlifetime = 1440;
    protected string $id = '';
    protected array $data = [];
    protected StorageInterface $storage;
    protected SessionHandlerInterface $handler;

    /**
     * creates a session object
     * @param  \SessionHandlerInterface|null $handler  a session handler (if any)
     */
    public function __construct(\SessionHandlerInterface $handler, int $maxlifetime = 1440) {
        $this->handler = $handler;
        $this->storage = new Storage($this->data);
        $this->maxlifetime = $maxlifetime;
        register_shutdown_function([$this, 'close']);
    }
    /**
     * starts the session (if not done already)
     * @codeCoverageIgnore
     */
    public function id(): string
    {
        return $this->id;
    }
    public function start(string $id = ''): void
    {
        if (random_int(0,100) < 5) {
            $this->handler->gc($this->maxlifetime);
        }
        if ($this->isStarted()) {
            $this->close();
        }
        if ($id === '' || !$this->handler->read($id)) {
            do {
                $id = bin2hex(random_bytes(20));
            } while ($this->handler->read($id) !== '');
        }
        $this->id = $id;
        $data = $this->handler->read($id);
        $data = json_decode($data, true);
        $this->data = is_array($data) ? $data : [];
        $this->storage = new Storage($this->data);
        $this->started = true;
    }
    /**
     * checks if the session is started
     * @return bool is the session started
     * @codeCoverageIgnore
     */
    public function isStarted(): bool
    {
        return $this->started;
    }

    /**
     * closes the session
     * @codeCoverageIgnore
     */
    public function close(): void
    {
        if ($this->isStarted()) {
            $this->handler->write($this->id, json_encode($this->data));
        }
    }
    /**
     * destroys the session (if started)
     * @codeCoverageIgnore
     */
    public function destroy(): void
    {
        if ($this->isStarted()) {
            $this->handler->destroy($this->id);
            $this->data = [];
            $this->id = '';
            $this->started = false;
            $this->storage = new Storage($this->data);
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
            if ($deleteOld) {
                $this->handler->destroy($this->id);
            }
            do {
                $id = bin2hex(random_bytes(20));
            } while ($this->handler->read($id) !== '');
            $this->id = $id;
            $this->handler->write($this->id, json_encode($this->data));
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
