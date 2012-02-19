<?php

class Model_Event extends \Orm\Model
{
	protected static $_belongs_to  = array(
		'user' => array(
			'key_from' => 'userid',
			'model_to' => 'Model_User',
			'key_to' => 'id',
			'cascade_save' => true,
			'cascade_delete' => false,
		),
		'category' => array(
			'key_from' => 'category',
	        'model_to' => 'Model_Category',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => false,
		),
	);
	
	protected static $_properties = array(
		'id',
		'userid',
		'title' => array(
			'default' => '',
		),
		'body' => array(
			'default' => '',
		),
		'start',
		'end',
		'category',
		'created_at',
		'updated_at'
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => false,
		),
	);
}
