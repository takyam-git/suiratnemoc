<?php
class Controller_Calendar_Event extends Controller_Rest{
	public function before(){
		parent::before();
		if ( ! Auth::check() ){
			Response::redirect('auth/login');
		}
		
		$this->current_user = Auth::check() ? Model_User::find_by_username(Auth::get_screen_name()) : null;
		
		// Set a global variable so views can use it
		View::set_global('current_user', $this->current_user);
		
		$user_id = Auth::get_user_id();
		$this->current_user_id = $user_id[1];
		View::set_global('current_user_id', $this->current_user_id);
	}
	
	public function post_events(){
		$data = array();
		if(empty($this->current_user_id)){
			$data['error'] = 'can\'t get user id';
		}else{
			$where = array(
				array('userid', $this->current_user_id),
			);
			$start = strtotime(Input::post('start'));
			$end = strtotime(Input::post('end'));
			if($start){
				$where[] = array('start', '>=', date('Y-m-d H:i:s', $start));
			}
			if($end){
				$where[] = array('end', '<=', date('Y-m-d H:i:s', $end));
			}
			$data['events'] = Model_Event::find('all', array('where' => $where, 'limit' => 500));
		}
		$this->response($data);
	}

	public function post_update(){
		$data = array(
			'post' => Input::post(),
			'success' => false,
		);
		if(empty($this->current_user_id)){
			$data['error'] = 'can\'t get user id';
		}else{
			$val = Validation::forge();
			$val->add('start', '開始日時')->add_rule('required');
			$val->add('end', '終了日時')->add_rule('required');
			$val->add('title', '件名')->add_rule('required')->add_rule('max_length', 200);;
			$val->add('body', '概要')->add_rule('max_length', 2000);;
			$val->add('category', 'カテゴリー');
			
			if($val->run() && strtotime(Input::post('start')) && strtotime(Input::post('end'))){
				
				if(Input::post('event_id')){
					$event = Model_Event::find_by_id(intval(Input::post('event_id')));
				}
				if(!isset($event) || !is_object($event) || $event->id <= 0){
					$event = new Model_Event();
				}
				$event->userid = $this->current_user_id;
				$event->start = date('Y-m-d H:i:s', strtotime(Input::post('start')));
				$event->end = date('Y-m-d H:i:s', strtotime(Input::post('end')));
				$event->title = Input::post('title');
				$event->body = Input::post('body');
				$event->category = intval(Input::post('category'));
				
				if($event->save()){
					$data['success'] = true;
					$data['event'] = array(
						'id' => $event->id,
						'start' => $event->start,
						'end' => $event->end,
						'title' => $event->title,
						'body' => $event->body,
						'category' => $event->category,
					);
				}
			}else{
				if($errors = $val->errors()){
					$data['errors'] = array();
					foreach($errors as $error){
						$data['errors'][] = $error->get_message();
					}
				}
			}
		}
		$this->response($data);
	}
}