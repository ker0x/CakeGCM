<?php $this->assign('title', $title); ?>
<h2><?php echo __('Google Cloud Messaging Plugin'); ?></h2>
<hr>
<?php echo $this->Form->create('Gcm') ?>
    <fieldset>
        <legend>Send a notification to one device</legend>
        <?php echo $this->Session->flash(); ?>
        <?php if (isset($response)): ?>
            <div>
                <?php debug($response) ?>
            </div>
        <?php endif ?>
        <?php echo $this->Form->input('ids', array(
            'label' => 'Token (required) :'
        )); ?>
        <?php echo $this->Form->input('Gcm.payload.notification.title', array(
            'type' => 'text',
            'label' => 'Title (required) :',
            'required' => true
        )) ?>
        <?php echo $this->Form->input('Gcm.payload.notification.body', array(
            'type' => 'textarea',
            'label' => 'Message :'
        )) ?>
        <?php echo $this->Form->input('Gcm.parameters.time_to_live', array(
            'type' => 'select',
            'label' => 'Duration of  the notification (in seconds) :',
            'options' => array(
                60 => '1 minutes',
                300 => '5 minutes',
                900 => '15 minutes',
                1800 => '30 minutes',
                3600 => '1 hour',
                86400 => '1 day'
            )
        )) ?>
        <?php echo $this->Form->input('Gcm.parameters.delay_while_idle', array(
            'type' => 'select',
            'label' => 'The message will not be sent immediately if the device is idle :',
            'options' => array(
                false => 'false',
                true => 'true',
            )
        )) ?>
        <?php echo $this->Form->input('Gcm.parameters.dry_run', array(
            'type' => 'select',
            'label' => 'Test the request without sending message :',
            'options' => array(
                false => 'false',
                true => 'true',
            )
        )) ?>
        <?php echo $this->Form->submit('Send') ?>
    </fieldset>
<?php echo $this->Form->end(); ?>
