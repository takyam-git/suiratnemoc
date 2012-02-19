<?php

namespace Fuel\Migrations;

class Create_events
{
	public function up()
	{
		\DBUtil::create_table('events', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'userid' => array('constraint' => 11, 'type' => 'int'),
			//'title' => array('constraint' => 255, 'type' => 'varchar'),
			'title' => array('type' => 'text'),
			'body' => array('type' => 'text'),
			'start' => array('type' => 'datetime'),
			'end' => array('type' => 'datetime'),
			'category' => array('constraint' => 11, 'type' => 'int'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('events');
	}
}