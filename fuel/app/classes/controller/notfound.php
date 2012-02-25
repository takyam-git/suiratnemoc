<?php

class Controller_Notfound extends Controller_Template
{

	public function action_index()
	{
		$this->template->title = 'ページが見つかりません';
		$this->template->content = View::forge('notfound/index');
	}

}
