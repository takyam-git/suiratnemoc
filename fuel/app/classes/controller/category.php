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
			'smoothness/jquery-ui-1.8.17.custom.css',
			'jquery-menu/fg.menu.css',
			'colors.css',
			'category/category.css',
		)));
		
		$this->template->set_safe('optionScripts', Asset::js(array(
			'jquery-ui-1.8.17.custom.min.js',
			'jquery-menu/fg.menu.js',
			'category.js',
		)));
		
		
		
		$this->template->title = 'Category &raquo; Index';
		$this->template->content = View::forge('category/index');
	}

}
