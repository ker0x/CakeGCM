<?php
class CreateShell extends AppShell {

	public function main() {
		if (!config('database')) {
			$this->out(__d('cake_console', 'Your database configuration was not found. Take a moment to create one.'));
			$this->args = null;
			return $this->DbConfig->execute();
		}

		$this->out(__d('gcm_console', 'Gcm Shell'));
		$this->hr();
		$this->out(__d('gcm_console', 'Creation of tables Devices and Notifications'));
		$this->hr();
		$this->dispatchShell('schema create Gcm --plugin Gcm');
		
		return true;
	}
}
