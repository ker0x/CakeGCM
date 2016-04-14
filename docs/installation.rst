Installation
============

Using `Composer <http://getcomposer.org/>`__
--------------------------------------------

To install this plugin, run ``composer require ker0x/cake_gcm`` or add this snippet in your project's ``composer.json``.

.. code:: json

    {
        "require": {
            "ker0x/cake_gcm": "~2.0"
        }
    }

Enable plugin
-------------

You need to enable the plugin in your ``config/bootstrap.php`` file:

.. code:: php

    Plugin::load('ker0x/CakeGcm');

If you are already using ``Plugin::loadAll();``, then this is not necessary.

Use the component
-----------------

In ``src/Controller/AppController.php``, add :

.. code:: php

    $this->loadComponent('ker0x/CakeGcm.Gcm', [
        'api' => [
            'key' => '*****'
        ]
    ]);

in your Controller's initialize() method. Replace ``****`` by your API Key.

To get an API key, go to `<https://console.cloud.google.com/start>`.

Then, in an action of one of your Controller, add the following code:

.. code:: php

    if ($this->Gcm->send($ids, $payload, $parameters)) {
        // do some stuff
    } else {
        // do other stuff
    }

where:

 - ``$ids`` is a string or an array of device ids. (required)
 - ``$payload`` is an array containing the notification and/or some datas that will be passed to a device. (optional)
 - ``$paramaters`` is an array of parameters for the notification. (optional)

You could have the response of the request by using the function ``response()``:

.. code:: php

    $response = $this->Gcm->response();

