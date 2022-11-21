# JXT Package

JSON Web Token Authentication for Laravel & Lumen
<br/>

## Requirement

* Laravel `>=5`
* PHP `>=7.4`

## setup package
add line to: `config/app.php`
``` php
'providers' => [
    /*
     * Package Service Providers...
     */
    Sirj3x\Jxt\JxtServiceProvider::class, // add this
]
```
add line to: `composer.json`
``` php
"autoload-dev": {
    "psr-4": {
        "Tests\\": "tests/",
        "Sirj3x\\Jxt\\": "packages/sirj3x/jxt/src" // add this
    }
},
```

## vendor:publish
You can run vendor:publish command to have config file of package on this path: `config/jxt.php`
``` bash
php artisan vendor:publish --provider="Sirj3x\Jxt\JxtServiceProvider"
```


## License
The [MIT license](http://opensource.org/licenses/MIT) (MIT). Please see [License File](https://raw.githubusercontent.com/sirj3x/jxt/main/LICENSE) for more information.
<br>Package by *Mehdi Hashemi*
