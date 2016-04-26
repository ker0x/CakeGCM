Examples
========

Send notifications
------------------

Send an empty notification to a device:

.. code:: php

    $this->Gcm->send('1');

Send a notification to a device:

.. code:: php

    $this->Gcm->send('1', [
        'notification' => [
            'title' => 'Hello World',
            'body' => 'My awesome Hellow World!'
        ]
    ]);

or with the shortcut

.. code:: php

    $this->Gcm->sendNotification('1', [
        'title' => 'Hello World',
        'body' => 'My awesome Hellow World!'
    ]);

Send a notification to multiples devices:

.. code:: php

    $this->Gcm->send(
        ['1', '2', '3', '4'],
        [
            'notification' => [
                'title' => 'Hello World',
                'body' => 'My awesome Hellow World!'
            ]
        ]
    );

or

.. code:: php

    $this->Gcm->sendNotification(
        ['1', '2', '3', '4'],
        [
            'title' => 'Hello World',
            'body' => 'My awesome Hellow World!'
        ]
    );

Send datas
----------

Send datas to a device

.. code:: php

    $this->Gcm->send('1', [
        'data' => [
            'data-1' => 'Lorem ipsum',
            'data-2' => 1234,
            'data-3' => true
        ]
    ]);

or with the shortcut

.. code:: php

    $this->Gcm->sendData('1', [
        'data-1' => 'Lorem ipsum',
        'data-2' => 1234,
        'data-3' => true
    ]);

Send notifications and datas
----------------------------

Send a notification and some datas to multiple devices at the same time:

.. code:: php

    $this->Gcm->send(
        ['1', '2', '3', '4'],
        [
            'notification' => [
                'title' => 'Hello World',
                'body' => 'My awesome Hellow World!'
            ],
            'data' => [
                'data-1' => 'Lorem ipsum',
                'data-2' => 1234,
                'data-3' => true
            ]
        ]
    );

Extra parameters
----------------

Send a notification with extra parameters:

.. code:: php

    $this->Gcm->send(
        ['1', '2', '3', '4'],
        [
            'notification' => [
                'title' => 'Hello World',
                'body' => 'My awesome Hello World!'
            ]
        ],
        [
            'delay_while_idle' => true,
            'dry_run' => false,
            'time_to_live' => 86400,
            'collapse_key' => 'Gcm',
            'restricted_package_name' => 'my_awesome_package'
        ]
    );