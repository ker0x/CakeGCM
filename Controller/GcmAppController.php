<?php
App::uses('AppController', 'Controller');

class GcmAppController extends AppController {

/**
 * Helpers to use in this Controller
 *
 * @var array
 */
    public $helpers =  array(
        'Html',
        'Form'
    );

/**
 * Components to use in this Controller
 *
 * @var array
 */
    public $components = array(
        'Session',
        'Gcm.Gcm' => array(
            'api' => array(
                'key' => '*****'
            )
        )
    );
}
