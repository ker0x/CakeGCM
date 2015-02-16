# CakeGCM

CakeGCM is a plugin for CakePHP to send notification to an Android device through Google Cloud Messaging

## Requirements

* CakePHP >= 2.6

## Installation
Run : `composer require ker0x/cake_gcm:*`
Or add it in your `composer.json`:
``` php
"require": {
    "ker0x/cake_gcm": "dev-master"
},
```

### Enable plugin
In your `app/Config/bootstrap.php` file add :
```php
CakePlugin::load('CakeGCM');
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
if ($this->Gcm->send($ids, $data, $parameters)) {
    // do some stuff
} else {
    // do other stuff
}
```

You could have the response of the request by using the function `response()`:
```php
$response = $this->Gcm->response();
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
