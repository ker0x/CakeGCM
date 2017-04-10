Installation
============

Using `Composer <http://getcomposer.org/>`__
----

To install this plugin, run ``composer require ker0x/cake_gcm`` or add this snippet in your project's ``composer.json``.

.. code:: json

    {
        "require": {
            "ker0x/cake-gcm": "~2.0"
        }
    }

Enable plugin
----

You need to enable the plugin in your ``config/bootstrap.php`` file:

.. code:: php

    Plugin::load('ker0x/CakeGcm');

If you are already using ``Plugin::loadAll();``, then this is not necessary.

Get an API key
----

Go to https://console.developers.google.com/apis/library and enable Google Clous Messaging API

Use the Gcm class
----

First, you have to include it :

.. code:: php

    use ker0x\CakeGcm\Webservice\Gcm;

Then, in your code:

.. code:: php

    $Gcm = new Gcm(['api' => ['key' => '*****']]);
    $Gcm->send($ids, $payload, $parameters);

where:

    - ``*****`` is your API key (required)
    - ``$ids`` is a string or an array of device ids. (required)
    - ``$payload`` is an array containing the notification and/or some datas that will be passed to a device. (optional)
    - ``$paramaters`` is an array of parameters for the notification. (optional)

You could have the response of the request by using the function ``response()``:

.. code:: php

    $response = $Gcm->response();

Use the component
----

In ``src/Controller/AppController.php``, add :

.. code:: php

    $this->loadComponent('ker0x/CakeGcm.Gcm', [
        'api' => [
            'key' => '*****'
        ]
    ]);

in your Controller's initialize() method.

Then, in an action of one of your Controller, add the following code:

.. code:: php

    if ($this->Gcm->send($ids, $payload, $parameters)) {
        // do some stuff
    } else {
        // do other stuff
    }

    $response = $this->Gcm->response();
