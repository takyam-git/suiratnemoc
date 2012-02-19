<?php

class Model_Category extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'user_id',
		'name',
		'description'  => array(
            'label' => '概要',
            'default' => '',
        ),
		'color_set',
		'deleted' => array(
            'default' => 0,
        ),
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
