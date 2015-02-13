<?php
App::uses('AppModel', 'Model');

class Gcm extends AppModel {

    public $useTable = false;

    protected $_schema = array(
        'tokens' => array('type' => 'string', 'null' => '', 'default' => '', 'length' => '255'),
        'message' => array('type' => 'string', 'null' => '', 'default' => '', 'length' => '255'),
    );
}
