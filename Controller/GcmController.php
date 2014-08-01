<?php
App::uses('AppController', 'Controller');

class GcmController extends AppController {

	public $uses = array('Device', 'Notification');

	public function beforeFilter() {
		if (Configure::read('debug') < 1) {
			throw new MethodNotAllowedException(__('Debug setting does not allow access to this url.'));
		}
		parent::beforeFilter();
	}

	public function index() {
		// if ($this->request->is('post')) {
			$tokens = 'APA91bFkzcgZUCU_uCLDD8xP_BMCu-SjTeuLK_67PopaHV7FSAhlLJzQ8XCR15teS_vXC5RqGwVGLaPCIWPJ4CQdz6b3ZYead_4OI8id7icYBdLW_JXgzS7VlLE7CPX3IzL7EOmZjrFIhmCmFJhE2KUyaCp7cAPHpw';
			$this->Gcm->sendNotification($tokens, "Hello World", array(
				'type' => 'Test'
			));
		// }
	}
}