# CakeGCM

CakeGCM is a plugin for CakePHP to send downstream message to an Android or iOS device through Google Cloud Messaging

[![Scrutinizer](https://img.shields.io/scrutinizer/g/ker0x/CakeGCM.svg?style=flat-square)](https://scrutinizer-ci.com/g/ker0x/CakeGCM/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/ker0x/cake_gcm.svg?style=flat-square)](https://packagist.org/packages/ker0x/cake_gcm)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://packagist.org/packages/ker0x/cake_gcm)

## Requirements

* CakePHP >= 2.4

## Installation
Run : `composer require ker0x/cake_gcm:dev-master`
Or add it in your `composer.json`:
``` php
"require": {
    "ker0x/cake_gcm": "dev-master"
},
```

### Enable plugin
In your `app/Config/bootstrap.php` file add :
```php
CakePlugin::load('Gcm');
```
or uncomment :
```php
CakePlugin::loadAll();
```
### Usage
In your `app/Controller/AppController.php` file add :
```php
public $components = array(
    'Gcm.Gcm' => array(
        'api' => array(
            'key' => '*****'
        )
    )
);
```

Replace `*****` by your API Key.

To get an API key, follow the instructions in this link: http://developer.android.com/google/gcm/gs.html#access-key

Then, in an action of your Controller, add the following code:
```php
if ($this->Gcm->send($ids, $payload, $parameters)) {
    // do some stuff
} else {
    // do other stuff
}
```
where:

 * `$ids` is a string or an array of device ids. (required)
 * `$payload` is an array containing the notification and/or some datas that will be passed to a device. (optional)
 * `$paramaters` is an array of parameters for the notification. (optional)

You could have the response of the request by using the function `response()`:
```php
$response = $this->Gcm->response();
```
## Examples

Send an empty notification to a device:
```php
$this->Gcm->send('1');
```

Send a notification to a device:
```php
$this->Gcm->send('1', array(
    'notification' => array(
        'title' => 'Hello World', 
        'body' => 'My awesome Hellow World!'
    )
));
```
or
```php
$this->Gcm->sendNotification('1', array(
    'title' => 'Hello World', 
    'body' => 'My awesome Hellow World!'
));
```

Send a notification to multiple devices:
```php
$this->Gcm->send(
    array('1', '2', '3', '4'),
    array(
        'notification' => array(
            'title' => 'Hello World', 
            'body' => 'My awesome Hellow World!'
        )
    )
);
```
or
```php
$this->Gcm->sendNotification(
    array('1', '2', '3', '4'),
    array(
        'title' => 'Hello World', 
        'body' => 'My awesome Hellow World!'
    )
);
```

Send datas to a device:
```php
$this->Gcm->send(
    array('1', '2', '3', '4'),
    array(
        'data' => array(
            'data-1' => 'Lorem ipsum',
            'data-2' => '1234',
            'data-3' => 'true'
        )
    )
);
```
or
```php
$this->Gcm->sendData(
    array('1', '2', '3', '4'),
    array(
        'data-1' => 'Lorem ipsum',
        'data-2' => 1234,
        'data-3' => true
    )
);
```

Send a notification and some datas to a device at the same time:
```php
$this->Gcm->send(
    array('1', '2', '3', '4'),
    array(
        'notification' => array(
            'title' => 'Hello World', 
            'body' => 'My awesome Hellow World!'
        ),
        'data' => array(
            'data-1' => 'Lorem ipsum',
            'data-2' => 1234,
            'data-3' => true
        )
    )
);
```

Send a notification with extra parameters:
```php
$this->Gcm->sendNotification(
    array('1', '2', '3', '4'),
    array(
        'notification' => array(
            'title' => 'Hello World',
            'body' => 'My awesome Hello World!'
        )
    ),
    array(
        'delay_while_idle' => true,
        'dry_run' => false,
        'time_to_live' => 86400,
        'collapse_key' => 'Gcm',
        'restricted_package_name' => 'my_awesome_package'
    )
);
```
## License

The MIT License (MIT)

Copyright (c) 2015 Romain Monteil

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
