<?php
App::uses('ComponentCollection', 'Controller');
App::uses('GcmComponent', 'Gcm.Controller/Component');

class GcmComponentTest extends CakeTestCase {

    public $GcmComponent = null;

    public function setUp() {
        parent::setUp();
        $Collection = new ComponentCollection();
        $this->GcmComponent = new GcmComponent($Collection);
    }

    public function testCheckEmptyParameters() {
        $parameters = array();
        $expected = array();

        $result   = $this->GcmComponent->_checkParamaters($parameters);
        $this->assertEquals($expected, $result);
    }

    public function tearDown() {
        parent::tearDown();
        unset($this->GcmComponent);
    }
}
