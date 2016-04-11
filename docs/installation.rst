Installation
------------

Using `Composer <http://getcomposer.org/>`__
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

To install this plugin, run ``composer require ker0x/cake_gcm`` or add this snippet in your project's ``composer.json``.

.. code:: json

    {
        "require": {
            "ker0x/cake_gcm": "~2.0"
        }
    }

Enable plugin
~~~~~~~~~~~~~

You need to enable the plugin in your ``config/bootstrap.php`` file:

.. code:: php

    <?php Plugin::load('ker0x/CakeGCM'); ?>

If you are already using ``Plugin::loadAll();``, then this is not necessary.

