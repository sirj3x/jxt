# JXT Package

JSON Web Token Authentication for Laravel & Lumen
<br/>

## Requirement

* Laravel `>=5.4`
* PHP `>=7.4`

## Setup package

### 1- Download files
make directory: `<laravel-project-path>/packages/sirj3x`

clone repository: `git clone <your-repo-link>`

### 2- Add service provider
add line to: `config/app.php`
``` php
'providers' => [
    /*
     * Package Service Providers...
     */
    Sirj3x\Jxt\JxtServiceProvider::class, // add this
]
```

### 3- Add package source to composer
add line to: `composer.json`
``` php
"autoload-dev": {
    "psr-4": {
        "Tests\\": "tests/",
        "Sirj3x\\Jxt\\": "packages/sirj3x/jxt/src" // add this
    }
},
```
and run this command:
`composer dump-autoload`

## Publish the config
You can run vendor:publish command to have config file of package on this path: `config/jxt.php`
``` bash
php artisan vendor:publish --provider="Sirj3x\Jxt\JxtServiceProvider"
```
You should now have a `config/jxt.php` file that allows you to configure the basics of this package.

## Generate secret key
I have included a command to generate a key for you, which you need to run for the first time:
``` bash
php artisan jxt:secret
```
This will update your `.env` file with something like `JXT_SECRET=foobar`

It is the key that will be used to sign your tokens. How that happens exactly will depend on the algorithm that you choose to use.

## License
The [MIT license](http://opensource.org/licenses/MIT) (MIT). Please see [License File](https://raw.githubusercontent.com/sirj3x/jxt/main/LICENSE) for more information.
