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

    public function testSendNotification() {
        $ids = 'APA91bGi-XYHwGsmlVewZaamdKNUanc_AnZA-y3LnMpoiTyAGAEPnJ6F2IFhe6sBCn0e8y8FsuwzYoKoDpNuQAizgfrzT9jAb8eD88hWCBtsEpvKp_BrujkLJIljpBBkLtn86P5zT4Ke3QxqU8HENZy1zLC8qkVqzA';
        $data = array('message' => 'Hello World');
        $parameters = array(
            'time_to_live' => 3600
        )

        $result = $this->GcmComponent->send($ids, $data, $parameters);
        $this->assertTrue($result);
    }

    public function tearDown() {
        parent::tearDown();
        unset($this->GcmComponent);
    }
}
