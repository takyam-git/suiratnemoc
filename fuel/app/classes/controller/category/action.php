<?php
class Controller_Category_Action extends Controller_Restbase{

	/**
	 * カテゴリの追加と更新
	 */
	public function post_save(){
		$data = array(
			'post' => Input::post(),
			'success' => false,
			'errors' => array(
				'_common' => array(),
			),
		);
		$hasError = false;
		if(empty($this->current_user_id)){
			$data['errors']['_common'][] = 'can\'t get user id';
		}else{
			$val = Validation::forge();
			$val->add('name', 'カテゴリ名')->add_rule('required')->add_rule('max_length', 200);
			$val->add('colorset', 'カラーセット')->add_rule('required')
				->add_rule('valid_string', array('numeric'))
				->add_rule('numeric_min', 0)->add_rule('numeric_max', 32);
			$val->add('description', '概要')->add_rule('max_length', 2000);
			$val->add('id', 'id')->add_rule('required')
				->add_rule('valid_string', array('numeric'));
			$val->add('type', 'type')->add_rule('required')
				->add_rule('match_pattern', '/^(global|local)$/');
			$val->add('include', 'include')->add_rule('valid_string', array('numeric'));
			
			//globalカテゴリはadminユーザーのみ許可
			$is_global = false; 
			$type = Input::post('type');
			if(isset($type) && !empty($type) && strtolower($type) === 'global'){
				$is_global = true;
				if(!$this->is_admin_user){
					$data['errors']['_common'][] = 'グローバルカテゴリは管理者以外編集できません';
					$hasError = true;
				}
			}
			
			$name = Input::post('name');
			if(!empty($name) || is_numeric($name)){
				//$name_matched_categories = Model_Category::find_by_name($name);
				$where_user_id = $is_global ? 0 : $this->current_user_id;
				$where = array(
					array('name', '=', $name),
					array('deleted', '=', 0),
					array('user_id', '=', $where_user_id),
				);
				$id = intval(Input::post('id'));
				if($id > 0){
					$where[] = array('id', '<>', $id);
				}
				$name_matched_categories = Model_Category::find('all', array(
					'where' => $where
				));
				if(!is_null($name_matched_categories) && (is_object($name_matched_categories)
					|| (is_array($name_matched_categories) && count($name_matched_categories) > 0)
				)){
					if(!isset($data['errors']['name']) || !is_array($data['errors']['name'])){
						$data['errors']['name'] = array();
					}
					$data['errors']['name'][] = '既に同じ名前のカテゴリが登録されています';
					$hasError = true;
				}
			}
			
			
			
			if($val->run() && !$hasError){
				$category_id = intval(Input::post('id'));
				if($category_id > 0){
					$category = Model_Category::find_by_id($category_id);
				}else{
					$category = new Model_Category();
				}

				if(is_object($category)){
					$category->name = Input::post('name');
					$category->color_set = Input::post('colorset');
					$category->description = '';

					if(intval(Input::post('include')) > 0){
						$category->in_summary = 1;
					}else{
						$category->in_summary = 0;
					}
					
					switch(Input::post('type')){
						case 'global':
							$category->user_id = 0;
							break;
						case 'local':
							$category->user_id = $this->current_user_id;
							break;
					}
					if($category->save()){
						$data['success'] = true;
						$data['category'] = array(
							'id' => $category->id,
							'name' => $category->name,
							'color_set' => $category->color_set,
							'in_summary' => $category->in_summary,
						);
					}else{
						$data['_common'][] = 'カテゴリの保存に失敗しました';
					}
				}else{
					if(!isset($data['errors']['id']) || !is_array($data['errors']['id'])){
						$data['errors']['id'] = array();
					}
					$data['errors']['id'] = '無効なカテゴリIDが指定されています';
				}
			}else{
				if($errors = $val->error()){
					$data['errors'] = array();
					foreach($errors as $field => $error){
						if(!isset($data['errors'][$field]) || !is_array($data['errors'][$field])){
							$data['errors'][$field] = array();
						}
						$data['errors'][$field][] = $error->get_message();
					}
				}
			}
		}
		$this->response($data);
	}
	
	/**
	 * カテゴリの削除
	 */
	public function post_remove(){
		$data = array(
			'success' => false,
			'errors' => array(
				'_common' => array(),
			),
		);
		$hasError = false;
		if(empty($this->current_user_id)){
			$data['errors']['_common'][] = 'can\'t get user id';
		}else{
			$val = Validation::forge();
			$val->add('id', 'カテゴリ名')->add_rule('required');
			
			if($val->run() && !$hasError){
				
				$id = intval(Input::post('id'));
				if($id > 0){
					$category = Model_Category::find_by_id($id);
					if(is_object($category)){
						$user_id = intval($category->user_id);
						if($user_id === 0 && !$this->is_admin_user){
							$data['errors']['_common'][] = 'グローバルカテゴリは管理者以外削除できません';
						}else if($user_id !== intval($this->current_user_id)){
							$data['errors']['_common'][] = 'このマイカテゴリはあなたのカテゴリではありません';
						}else{
							$category->deleted = 1;
							if($category->save()){
								$data['success'] = true;
							}else{
								$data['errors']['_common'][] = 'カテゴリの削除に失敗しました';
							}
						}
					}else{
						$data['errors']['_common'][] = '存在しないカテゴリが指定されています';
					}
				}else{
					$data['errors']['_common'][] = '不正なカテゴリが指定されています';
				}
			}else{
				if($errors = $val->error()){
					$data['errors'] = array();
					foreach($errors as $field => $error){
						if(!isset($data['errors'][$field]) || !is_array($data['errors'][$field])){
							$data['errors'][$field] = array();
						}
						$data['errors'][$field][] = $error->get_message();
					}
				}
			}
		}
		$this->response($data);
	}

	/**
	 * お気に入りの更新
	 */
	public function post_favorite(){
		$data = array(
			'success' => false,
			'errors' => array(
				'_common' => array(),
			),
		);
		$hasError = false;
		if(empty($this->current_user_id)){
			$data['errors']['_common'][] = 'can\'t get user id';
		}else{
			$val = Validation::forge();
			$val->add('categories', 'お気に入りカテゴリ')->add_rule('required');
			
			if($val->run()){
				$categories = implode(':', Input::post('categories'));
				$user_id = $this->current_user_id;
				$user_favorite = Model_Category_Favorite::find_by_user_id($user_id);
				if(!is_object($user_favorite)){
					$user_favorite = Model_Category_Favorite::forge();
					$user_favorite->user_id = $user_id;
				}
				$user_favorite->category_ids = $categories;
				if($user_favorite->save()){
					$data['success'] = true;
				}else{
					$data['errors']['_common'][] = 'お気に入りの保存に失敗しました';
				}
			}else{
				if($errors = $val->error()){
					$data['errors'] = array();
					foreach($errors as $field => $error){
						if(!isset($data['errors'][$field]) || !is_array($data['errors'][$field])){
							$data['errors'][$field] = array();
						}
						$data['errors'][$field][] = $error->get_message();
					}
				}
			}
		}
		$data['time'] = date('Y-m-d H:i:s');
		$this->response($data);
	}
	
}