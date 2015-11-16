<?php
App::uses('AppController', 'Controller');

class GcmController extends GcmAppController {

	public function beforeFilter() {
		if (Configure::read('debug') < 1) {
			throw new MethodNotAllowedException(__('Debug setting does not allow access to this url.'));
		}

		parent::beforeFilter();
	}

	public function index() {
		if ($this->request->is('post')) {

			$ids = $this->request->data['Gcm']['ids'];
			$payload = $this->request->data['Gcm']['payload'];
			$parameters = $this->request->data['Gcm']['parameters'];

			if ($this->Gcm->send($ids, $payload, $parameters)) {
				$this->Session->setFlash(__('Notification sucessfully send.'), 'default', array('class' => 'success'));
			} else {
				$this->Session->setFlash(__('Unable to send notification.'));
			}

			$this->set('response', $this->Gcm->response());
		}

		$this->set('title', __('Google Cloud Messaging Plugin'));
	}
}
