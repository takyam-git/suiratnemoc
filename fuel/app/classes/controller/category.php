<?php

class Controller_Category extends Controller_Base
{
	public function before(){
		parent::before();

		if ( ! Auth::check() ){
			Response::redirect('auth/login');
		}
		
		$user_id = Auth::get_user_id();
		$this->current_user_id = $user_id[1];
	}

	public function action_index(){
		$this->template->set_safe('optionStyles', Asset::css(array(
			'jquery-week-calendar/colors.css',
		)));
		
		$this->template->set_safe('optionScripts', Asset::js(array(
			'category.js',
		)));
		
		
		
		$this->template->title = 'Category &raquo; Index';
		$this->template->content = View::forge('category/index');
	}

}
