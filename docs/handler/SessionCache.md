# vakata\session\handler\SessionCache
A session handler for storing sessions in cache (like memcached).

## Methods

| Name | Description |
|------|-------------|
|[__construct](#vakata\session\handler\sessioncache__construct)|Create an instance.|

---



### vakata\session\handler\SessionCache::__construct
Create an instance.  


```php
public function __construct (  
    \vakata\cache\CacheInterface $cache,  
    string $table  
)   
```

|  | Type | Description |
|-----|-----|-----|
| `$cache` | `\vakata\cache\CacheInterface` | the cache instance |
| `$table` | `string` | the cache namespace to use |

---

