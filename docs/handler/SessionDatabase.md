# vakata\session\handler\SessionDatabase
A session handler for storing sessions in database.

## Methods

| Name | Description |
|------|-------------|
|[__construct](#vakata\session\handler\sessiondatabase__construct)|Create an instance.|

---



### vakata\session\handler\SessionDatabase::__construct
Create an instance.  


```php
public function __construct (  
    \vakata\database\DatabaseInterface $db,  
    string $tb  
)   
```

|  | Type | Description |
|-----|-----|-----|
| `$db` | `\vakata\database\DatabaseInterface` | the database instance to use |
| `$tb` | `string` | the sessions table (defaults to `'sessions'`) |

---

