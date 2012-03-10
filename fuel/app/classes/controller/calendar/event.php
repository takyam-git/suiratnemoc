<?php
class Controller_Calendar_Event extends Controller_Restbase{
	public function post_events(){
		$data = array();
		if(empty($this->current_user_id)){
			$data['error'] = 'can\'t get user id';
		}else{
			
			$query = Model_Event::find()->where('userid', $this->current_user_id)->related('category');
			
			$start = strtotime(Input::post('start'));
			$end = strtotime(Input::post('end'));

			if($start){
				$query->where('start', '>=', date('Y-m-d H:i:s', $start));
			}
			if($end){
				$query->where('end', '<=', date('Y-m-d H:i:s', $end));
			}
			$data['events'] = $query->get();
			foreach($data['events'] as $key => $event){
				$data['events'][$key]->title = htmlspecialchars($event->title);
			}
		}
		$this->response($data);
	}

	public function post_update(){
		$data = array(
			'post' => Input::post(),
			'success' => false,
			'errors' => array(),
		);
		if(empty($this->current_user_id)){
			$data['error'] = 'can\'t get user id';
		}else{
			$val = Validation::forge();
			$val->add('start', '開始日時')->add_rule('required');
			$val->add('end', '終了日時')->add_rule('required');
			//$val->add('title', '件名')->add_rule('required')->add_rule('max_length', 200);;
			$val->add('title', '概要')->add_rule('max_length', 2000);
			//$val->add('body', '概要')->add_rule('max_length', 2000);;
			$val->add('category', 'カテゴリー')->add_rule('required');
			
			$cateogry_id = Input::post('category');
			if(is_numeric($cateogry_id)){
				$category = Model_Category::find_by_id($cateogry_id);
				if(!is_object($category)){
					$data['errors'][] = '存在しないカテゴリが選択されています';
				}
			}
			
			$start = strtotime(Input::post('start'));
			$end = strtotime(Input::post('end'));
			if(!$start){
				$data['errors'][] = '開始時間の書式が不正です';
			}
			if(!$end){
				$data['errors'][] = '終了時間の書式が不正です';
			}
			
			if($start && $end && $start > $end){
				$data['errors'][] = '開始時間が終了時間より遅い時間に設定されています';
			}
			
			if($start && $end){
				//重複する時刻のデータがないかバリデる
				$query = Model_Event::find()->where('userid', $this->current_user_id)->related('category');
				$query = DB::select(
					DB::expr('count(*) AS count')
				)
				->from('events')
				->where('userid', $this->current_user_id)
				->where('start', '<', date('Y-m-d H:i:s', $end))
				->where('end', '>', date('Y-m-d H:i:s', $start));
				
				if(Input::post('event_id')){
					$query->where('id', '<>', Input::post('event_id'));
				}
				
				$events = $query->execute()->as_array();
				
				if(is_array($events) && isset($events[0])
					&& isset($events[0]['count'])
					&& intval($events[0]['count']) > 0){
					$data['errors'][] = '期間が重複しています';
				}
			}
			
			if($val->run() && count($data['errors']) === 0){
				
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
				//$event->body = Input::post('body');
				$event->category = intval(Input::post('category'));
				
				if($event->save()){
					$colorset = 0;
					$category_name = '';
					$category = Model_Category::find_by_id($event->category);
					if(is_object($category)){
						$colorset = intval($category->color_set);
						$category_name = $category->name;
					}
					
					$data['success'] = true;
					$data['event'] = array(
						'id' => $event->id,
						'start' => $event->start,
						'end' => $event->end,
						'title' => htmlspecialchars($event->title),
						//'body' => $event->body,
						'category' => $event->category,
						'category_name' => $category_name,
						'colorset' => $colorset,
					);
				}
			}else{
				if($errors = $val->error()){
					foreach($errors as $error){
						$data['errors'][] = $error->get_message();
					}
				}
			}
		}
		$this->response($data);
	}

	/**
	 * イベントの削除
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
			$val->add('id', 'イベントID')->add_rule('required');
			
			if($val->run() && !$hasError){
				
				$id = intval(Input::post('id'));
				if($id > 0){
					$event = Model_Event::find_by_id($id);
					if(is_object($event)){
						$user_id = intval($event->userid);
						if($user_id !== intval($this->current_user_id)){
							$data['errors']['_common'][] = 'あなたは、あなたのイベント以外削除できません';
						}else{
							if($event->delete()){
								$data['success'] = true;
							}else{
								$data['errors']['_common'][] = 'イベントの削除に失敗しました';
							}
						}
					}else{
						$data['errors']['_common'][] = '存在しないイベントが指定されています';
					}
				}else{
					$data['errors']['_common'][] = '不正なイベントが指定されています';
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
}