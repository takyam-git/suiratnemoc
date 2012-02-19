<?php

class Controller_Auth extends Controller_Base
{
	public function before(){
		parent::before();
		
		$free_actions = array('login', 'new');
		if ( ! Auth::check() and !in_array(Request::active()->action, $free_actions)){
			Response::redirect('auth/login');
		}
	}
	
	public function action_index(){
		$this->template->title = 'Auth &raquo; Index';
		$this->template->content = View::forge('auth/index');
	}

	public function action_login(){
		Auth::check() and Response::redirect('auth');
		
		$val = Validation::forge();
		
		$error_msg = '';
		
		if (Input::method() == 'POST'){
			
			$val->add('username', 'ユーザー名')->add_rule('required');
			$val->add('password', 'パスワード')->add_rule('required');

			if ($val->run()){
				
				$auth = Auth::instance();
				
				// check the credentials. This assumes that you have the previous table created
				if (Auth::check() or $auth->login(Input::post('username'), Input::post('password'))){
					Response::redirect('calendar');
				}else{
					$error_msg .= 'ログインに失敗しました<br>';
				}
			}
		}
		
		if($errors = $val->error()){
			foreach($errors as $error){
				$error_msg .= $error->get_message(false, '', '<br>');
			}
		}
		
		$this->template->title = 'Auth &raquo; Index';
		$this->template->content = View::forge('auth/login', array('error_msg' => $error_msg));
	}
	
	public function action_logout(){
		Auth::logout();
		Response::redirect('auth');
	}
	
	public function action_new(){
		Auth::check() and Response::redirect('auth');
		
		$val = Validation::forge();
		
		$error_msg = '';
		$values = array(
			'username' => '',
			'password' => '',
			'password_confirm' => '',
			'mail' => '',
		);
		
		if(Input::method() == 'POST'){
			$val->add('username', 'ユーザー名')->add_rule('required')->add_rule('min_length', 3)->add_rule('max_length', 200);
			$val->add('password', 'パスワード')->add_rule('required')->add_rule('min_length', 8)->add_rule('max_length', 128);
			$val->add('password_confirm', 'パスワード（確認）')->add_rule('required')->add_rule('match_field', 'password');
			$val->add('mail', 'メールアドレス')->add_rule('required')->add_rule('valid_email');
			
			foreach($values as $key => $value){
				
				$values[$key] = Input::post($key);
			}
			
			if($val->run()){
				try{
					$auth = Auth::instance();
					$auth->create_user(Input::post('username'), Input::post('password'), Input::post('mail'), 1);
					if($auth->login(Input::post('username'), Input::post('password'))){
						Response::redirect('calendar');
					}
				}catch(SimpleUserUpdateException $e){
					$error_msg .= $e->getMessage() . '<br>';
				}
			}
		}
		
		if($errors = $val->error()){
			foreach($errors as $error){
				$error_msg .= $error->get_message(false, '', '<br>');
			}
		}

		$this->template->title = '新規登録';
		$this->template->content = View::forge('auth/new', array('values' => $values));
		$this->template->content->set_safe('error_msg', $error_msg);
	}

	public function action_logged(){
		$this->template->title = 'ログインしました';
		$this->template->content = View::forge('auth/logged');
	}

}
