# session

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Code Climate][ico-cc]][link-cc]
[![Tests Coverage][ico-cc-coverage]][link-cc]

A simple key-value storage class. used for configurations and extended in vakata/session.

## Install

Via Composer

``` bash
$ composer require vakata/session
```

## Usage

Using the `$_SESSION` superglobal is perfectly fine and works well with this class.

``` php
$session = new \vakata\session\Session(); // autostarts session and applies useful defaults
$session->get('value'); // same as $_SESSION['value'];
$session->set('val.ue', 2); // same as $_SESSION['val'] = [ 'ue' => 1 ];
$session->del('value'); // same as unset($_SESSION['value']);
// optionally sessions can be stored in a database
$sessionDB = new \vakata\session\Session(
    true, // autostart
    new \vakata\sessions\SessionDatabase(
        new \vakata\database\DB('mysqli://user:pass@host/database'),
        'table'
    )
);
// optionally sessions can be stored in memcached
$sessionDB = new \vakata\session\Session(
    true, // autostart
    new \vakata\sessions\SessionCache(
        new \vakata\cache\Memcache(),
        'table'
    )
);
```

For more on setting, getting and deleting values read here:
https://github.com/vakata/kvstore

For more on the database class read here:
https://github.com/vakata/database

For more on the memcached class read here:
https://github.com/vakata/cache

## Testing

``` bash
$ composer test
```


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email github@vakata.com instead of using the issue tracker.

## Credits

- [vakata][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/vakata/session.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/vakata/session/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/vakata/session.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/vakata/session.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/vakata/session.svg?style=flat-square
[ico-cc]: https://img.shields.io/codeclimate/github/vakata/session.svg?style=flat-square
[ico-cc-coverage]: https://img.shields.io/codeclimate/coverage/github/vakata/session.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/vakata/session
[link-travis]: https://travis-ci.org/vakata/session
[link-scrutinizer]: https://scrutinizer-ci.com/g/vakata/session/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/vakata/session
[link-downloads]: https://packagist.org/packages/vakata/session
[link-author]: https://github.com/vakata
[link-contributors]: ../../contributors
[link-cc]: https://codeclimate.com/github/vakata/session

