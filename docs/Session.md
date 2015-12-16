# vakata\session\Session


## Methods

| Name | Description |
|------|-------------|
|[__construct](#vakata\session\session__construct)|creates a session object|
|[start](#vakata\session\sessionstart)|starts the session (if not done already)|
|[destroy](#vakata\session\sessiondestroy)|destroys the session (if started)|
|[regenerate](#vakata\session\sessionregenerate)|regenerates the session ID|
|[get](#vakata\session\sessionget)|get a key from the storage by using a string locator|
|[set](#vakata\session\sessionset)|set an element in the storage to a specified value|
|[del](#vakata\session\sessiondel)|delete an element from the storage|

---



### vakata\session\Session::__construct
creates a session object  


```php
public function __construct (  
    boolean $start,  
    \SessionHandlerInterface|null $handler,  
    string $name,  
    string $location  
)   
```

|  | Type | Description |
|-----|-----|-----|
| `$start` | `boolean` | should the session be started immediately, defaults to true |
| `$handler` | `\SessionHandlerInterface`, `null` | a session handler (if any) |
| `$name` | `string` | name of the session cookie, defaults to "PHPSESSID" |
| `$location` | `string` | location of session files on disk (only if no handler is used) |

---


### vakata\session\Session::start
starts the session (if not done already)  


```php
public function start ()   
```


---


### vakata\session\Session::destroy
destroys the session (if started)  


```php
public function destroy ()   
```


---


### vakata\session\Session::regenerate
regenerates the session ID  


```php
public function regenerate (  
    boolean $keepOld  
)   
```

|  | Type | Description |
|-----|-----|-----|
| `$keepOld` | `boolean` | should the old session data be kept |

---


### vakata\session\Session::get
get a key from the storage by using a string locator  


```php
public function get (  
    string $key,  
    mixed $default,  
    string $separator  
) : mixed    
```

|  | Type | Description |
|-----|-----|-----|
| `$key` | `string` | the element to get (can be a deeply nested element of the data array) |
| `$default` | `mixed` | the default value to return if the key is not found in the data |
| `$separator` | `string` | the string used to separate levels of the array, defaults to "." |
|  |  |  |
| `return` | `mixed` | the value of that element in the data array (or the default value) |

---


### vakata\session\Session::set
set an element in the storage to a specified value  


```php
public function set (  
    string $key,  
    mixed $value,  
    string $separator  
) : mixed    
```

|  | Type | Description |
|-----|-----|-----|
| `$key` | `string` | the element to set (can be a deeply nested element of the data array) |
| `$value` | `mixed` | the value to assign the selected element to |
| `$separator` | `string` | the string used to separate levels of the array, defaults to "." |
|  |  |  |
| `return` | `mixed` | the stored value |

---


### vakata\session\Session::del
delete an element from the storage  


```php
public function del (  
    string $key,  
    string $separator  
) : boolean    
```

|  | Type | Description |
|-----|-----|-----|
| `$key` | `string` | the element to delete (can be a deeply nested element of the data array) |
| `$separator` | `string` | the string used to separate levels of the array, defaults to "." |
|  |  |  |
| `return` | `boolean` | the status of the del operation - true if successful, false otherwise |

---

