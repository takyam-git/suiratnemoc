<?php
class Controller_Restbase extends Controller_Rest{
	public function before(){
		parent::before();
		if ( ! Auth::check() ){
			Response::redirect('auth/login');
		}
		
		$this->current_user = Auth::check() ? Model_User::find_by_username(Auth::get_screen_name()) : null;
		
		// Set a global variable so views can use it
		View::set_global('current_user', $this->current_user);
		
		$this->is_admin_user = Auth::member(100);
		View::set_global('is_admin_user', $this->is_admin_user);
		
		$user_id = Auth::get_user_id();
		$this->current_user_id = $user_id[1];
		View::set_global('current_user_id', $this->current_user_id);
	}
}