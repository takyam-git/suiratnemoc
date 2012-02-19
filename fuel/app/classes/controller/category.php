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
			'jquery-menu/fg.menu.css',
			'colors.css',
			'category/category.css',
		)));
		
		$this->template->set_safe('optionScripts', Asset::js(array(
			'jquery-menu/fg.menu.js',
			'category.js',
		)));
		
		$select = array('id', 'user_id', 'name', 'description', 'color_set');
		$order_by = array('updated_at' => 'desc');
		$favorite_categories = null;
		$user_favorites = Model_Category_Favorite::find_by_user_id($this->current_user_id);
		if(is_object($user_favorites)){
			$user_favorite_categories = explode(':', $user_favorites->category_ids);
			$favorite_categories_ary = Model_Category::find('all', array(
				'select' => $select,
				'where' => array(
					array('user_id', 'in', array(0, $this->current_user_id)),
					array('id', 'in', $user_favorite_categories),
					array('deleted', '=', 0),
				)
			));
			//sort
			$favorite_categories = array();
			foreach($user_favorite_categories as $fav_id){
				if(isset($favorite_categories_ary[$fav_id])
					&& is_object($favorite_categories_ary[$fav_id])){
					$favorite_categories[] = $favorite_categories_ary[$fav_id];
				}
			}
		}
		
		$global_categories = Model_Category::find('all', array(
			'select' => $select,
			'where' => array(
				array('user_id', '=', 0),
				array('deleted', '=', 0),
			),
			'order_by' => $order_by,
		));
		$local_categories = Model_Category::find('all', array(
			'select' => $select,
			'where' => array(
				array('user_id', '=', $this->current_user_id),
				array('deleted', '=', 0),
			),
			'order_by' => $order_by,
		));
		
		$this->template->title = 'Category &raquo; Index';
		$this->template->content = View::forge('category/index', array(
			'favorite_categories' => $favorite_categories,
			'global_categories' => $global_categories,
			'local_categories' => $local_categories,
		));
	}

}
