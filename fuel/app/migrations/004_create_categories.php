<?php

namespace Fuel\Migrations;

class Create_categories
{
	public function up()
	{
		\DBUtil::create_table('categories', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int', 'default' => 0),
			'name' => array('constraint' => 255, 'type' => 'varchar'),
			'description' => array('type' => 'text'),
			'color_set' => array('constraint' => 11, 'type' => 'int', 'default' => 0),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('categories');
	}
}