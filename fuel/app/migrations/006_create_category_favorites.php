<?php

namespace Fuel\Migrations;

class Create_category_favorites
{
	public function up()
	{
		\DBUtil::create_table('category_favorites', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'category_ids' => array('type' => 'text', 'default' => ''),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('category_favorites');
	}
}