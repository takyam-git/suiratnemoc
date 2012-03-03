<?php

class Controller_Adminbase extends Controller_Template {

	public function before()
	{
		parent::before();
		
		if (!Auth::check() || (!Auth::member(100) && !Auth::member(99))){
			Response::redirect('auth/login');
		}
		
		// Assign current_user to the instance so controllers can use it
		$this->current_user = Auth::check() ? Model_User::find_by_username(Auth::get_screen_name()) : null;
		
		$this->is_admin_user = true;
		View::set_global('is_admin_user', $this->is_admin_user);
		
		$user_id = Auth::get_user_id();
		$this->current_user_id = $user_id[1];
		View::set_global('current_user_id', $this->current_user_id);
		
		// Set a global variable so views can use it
		View::set_global('current_user', $this->current_user);
	}

}