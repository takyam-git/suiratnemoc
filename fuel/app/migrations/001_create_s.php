<?php

namespace Fuel\Migrations;

class Create_s
{
	public function up()
	{
		\DBUtil::create_table('s', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('s');
	}
}