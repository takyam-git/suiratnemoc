<?php

class Controller_Base extends Controller_Template {

	public function before()
	{
		parent::before();
		
		// Assign current_user to the instance so controllers can use it
		$this->current_user = Auth::check() ? Model_User::find_by_username(Auth::get_screen_name()) : null;
		
		$this->is_admin_user = Auth::member(100);
		
		// Set a global variable so views can use it
		View::set_global('current_user', $this->current_user);
		View::set_global('is_admin_user', $this->is_admin_user);
	}

}