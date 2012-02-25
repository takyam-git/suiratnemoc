<?php

namespace Fuel\Migrations;

class Add_insummary_to_categories
{
	public function up()
	{
    \DBUtil::add_fields('categories', array(
						'in_summary' => array('constraint' => 1, 'type' => 'int', 'default' => 1),

    ));	
	}

	public function down()
	{
    \DBUtil::drop_fields('categories', array(
			'in_summary'    
    ));
	}
}