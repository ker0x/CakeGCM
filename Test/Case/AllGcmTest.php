<?php
App::uses('GcmController', 'Gcm.Controller');

class AllGcmTest extends CakeTestSuite {

/**
 * Adds all tests to the AllGcmTest case
 *
 * @return CakeTestSuite
 */
    public static function suite() {
        $suite = new CakeTestSuite('All tests');
        $path = dirname(__FILE__);
        $suite->addTestDirectory($path . DS . 'Controller' . DS . 'Component');
        return $suite;
    }
}
