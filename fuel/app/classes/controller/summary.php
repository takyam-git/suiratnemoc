<?php

class Controller_Summary extends Controller_Base
{
	public function before(){
		parent::before();

		if ( ! Auth::check() ){
			Response::redirect('auth/login');
		}
	}
	
	/**
	 * 共通処理
	 */
	private function init($date = null, $date2 = null, $user_mode = false){
		
		$user_mode = $this->is_admin_user && $user_mode;
		
		$uri_segment = 3;
		
		if($user_mode){
			$uri_segment++;
		}
		
		if(!is_null($date)){
			$uri_segment++;
		}
		
		$end_date = null;
		if(!isset($date) || is_null($date)){
			$start_date = date('Y-m-d');
		}else{
			$date = $date . ' 00:00:00';
			$int_start = strtotime($date);
			if(!$int_start){
				Response::redirect('/summary');
			}
			
			$start_date = date('Y-m-d', $int_start);
			
			if(isset($date2)){
				$date2 = $date2 . ' 00:00:00';
				$int_end = strtotime($date2);
				if($int_end && $int_start < $int_end){
					$end_date = date('Y-m-d', $int_end);
				}
			}
		}
		
		$is_multiple_days = false;
		$title_date = $start_date;
		
		if($user_mode){
			$base_url = '/summary/user/' . $this->current_user_id . '/';
		}else{
			$base_url = '/summary/index/';
		}
		$base_url = $base_url . $start_date;
		$csv_url_suffix = '/' . $start_date;
		$file_name = $start_date;
		if(is_null($end_date)){
			$end_date = $start_date;
		}else{
			$is_multiple_days = true;
			$title_date .= ' ～ ' . $end_date;
			$base_url .= '/' . $end_date;
			$csv_url_suffix .= '/' . $end_date;
			$uri_segment++;
			$file_name .= '_' . $end_date;
		}
		$file_name .= '.csv';
		
		$start = $start_date . ' 00:00:00';
		$end   = $end_date . ' 23:59:59';
		
		$prev_date = null;
		$next_date = null;
		$summary_date_format = 'H:i';
		if($is_multiple_days){
			$summary_date_format = 'Y-m-d H:i';
		}else{
			$int_start = strtotime($start_date);
			$year = intval(date('y', $int_start));
			$month = intval(date('m', $int_start));
			$day = intval(date('d', $int_start));
			$prev_date = date('Y-m-d', mktime(0,0,0, $month, $day - 1, $year));
			$next_date = date('Y-m-d', mktime(0,0,0, $month, $day + 1, $year));
		}
		
		$this->user_name = null;
		if($user_mode){
			$link_path = '/summary/user/' . $this->current_user_id . '/';
			$event_csv_link_path = '/summary/user_event_csv/' . $this->current_user_id;
			$category_csv_link_path = '/summary/user_category_csv/' . $this->current_user_id;
			$user = Model_User::find_by_id($this->current_user_id);
			if($user){
				$this->user_name = $user->username;
			}
		}else{
			$link_path = '/summary/index/';
			$event_csv_link_path = '/summary/event_csv';
			$category_csv_link_path = '/summary/category_csv';
		}
		
		$this->category_csv_link_path = $category_csv_link_path;
		$this->event_csv_link_path = $event_csv_link_path;
		$this->link_path = $link_path;
		$this->start_date = $start_date;
		$this->start = $start;
		$this->end_date = $end_date;
		$this->end = $end;
		$this->prev_date = $prev_date;
		$this->next_date = $next_date;
		$this->summary_date_format = $summary_date_format;
		$this->title_date = $title_date;
		$this->uri_segment = $uri_segment;
		$this->base_url = $base_url;
		$this->csv_url_suffix = $csv_url_suffix;
		$this->file_name = $file_name;
	}

	public function action_index($date = null, $date2 = null, $user_mode = false)
	{
		$this->init($date, $date2, $user_mode);
		
		$this->template->set_safe('optionScripts', Asset::js(array(
			'summary.js',
		)));
		
		
		
		//ページネーション用に全アイテムの件数を取得
		$count_query = DB::select(DB::expr('COUNT(*) AS `count`'))
			->from('events')
				->join('categories')
					->on('events.category', '=', 'categories.id')
			->where('events.userid', $this->current_user_id)
			->where('events.start', '>=', $this->start)
			->where('events.end', '<=', $this->end)
			->where('categories.in_summary', '=', 1);
		$count_result = $count_query->execute()->as_array();
		$count = $count_result[0]['count'];
		
		//ページネーションの設定
		$pagination_config = array(
			'total_items' => $count,
			'pagination_url' => $this->base_url,
			'per_page' => 20,
			'num_links' => 4,
			'uri_segment' => $this->uri_segment,
		);
		Pagination::set_config($pagination_config);
		//start, end, categoryname, eventtitle, manhour
		$query = DB::select(
				array('events.id', 'id'),
				array('events.start', 'start'),
				array('events.end', 'end'),
				array('events.title', 'title'),
				array('categories.name', 'name'),
				DB::expr('ABS(TIME_TO_SEC(TIMEDIFF(events.end,events.start)))/3600 AS sum')
			)
			->from('events')
				->join('categories')
					->on('events.category', '=', 'categories.id')
			->where('events.userid', $this->current_user_id)
			->where('events.start', '>=', $this->start)
			->where('events.end', '<=', $this->end)
			->where('categories.in_summary', '=', 1)
			->order_by('events.start', 'asc')
			->limit(Pagination::$per_page)
			->offset(Pagination::$offset);
		
		$events = $query->execute()->as_array();
		
		//期間の合計マンアワーを取得
		$sum = Model_Event::get_sum($this->current_user_id, $this->start, $this->end);
		
		//期間のカテゴリ別マンアワー合計を取得
		$category_events = Model_Event::get_categories_sum($this->current_user_id, $this->start, $this->end);
		
		$this->template->title = 'サマリー';
		$this->template->content = View::forge('summary/index', array(
			'start_date' => $this->start_date,
			'end_date' => $this->end_date,
			'prev_date' => $this->prev_date,
			'next_date' => $this->next_date,
			'sum' => $sum,
			'summary_date_format' => $this->summary_date_format,
			'title_date' => $this->title_date,
			'events' => $events,
			'category_events' => $category_events,
			'base_url' => $this->base_url,
			'csv_url_suffix' => $this->csv_url_suffix,
			'link_path' => $this->link_path,
			'user_name' => $this->user_name,
			'event_csv_link_path' => $this->event_csv_link_path,
			'category_csv_link_path' => $this->category_csv_link_path,
		));
	}
	
	public function action_user($user_id, $date = null, $date2 = null){
		if(!$this->is_admin_user){
			Response::redirect('auth/login');
		}
		$this->current_user_id = intval($user_id);
		$this->action_index($date, $date2, true);
	}
	
	public function action_user_event_csv($user_id, $date = null, $date2 = null){
		if(!$this->is_admin_user){
			Response::redirect('auth/login');
		}
		$this->current_user_id = intval($user_id);
		$this->action_event_csv($date, $date2, true);
	}

	public function action_user_category_csv($user_id, $date = null, $date2 = null){
		if(!$this->is_admin_user){
			Response::redirect('auth/login');
		}
		$this->current_user_id = intval($user_id);
		$this->action_category_csv($date, $date2, true);
	}

	public function action_event_csv($date = null, $date2 = null, $user_mode = false){
		$this->init($date, $date2, $user_mode);
		
		$query = DB::select(
				array('events.id', 'ID'),
				array('events.start', '開始日時'),
				array('events.end', '終了日時'),
				array('categories.name', 'カテゴリ名'),
				array('events.title', 'メモ'),
				DB::expr('ABS(TIME_TO_SEC(TIMEDIFF(events.end,events.start)))/3600 AS `工数`')
			)
			->from('events')
				->join('categories')
					->on('events.category', '=', 'categories.id')
			->where('events.userid', $this->current_user_id)
			->where('events.start', '>=', $this->start)
			->where('events.end', '<=', $this->end)
			->where('categories.in_summary', '=', 1)
			->order_by('events.start', 'asc');
		
		$events = $query->execute()->as_array();
		
		//テンプレートを無効化してCSVとして出力
		$this->template = null;
		$this->response->set_header('Content-Type', 'application/csv');
		$this->response->set_header('Content-Disposition', 'attachment; filename="events_' . $this->file_name . '"');
		echo Format::forge($events)->to_csv();
		return;
	}

	public function action_category_csv($date = null, $date2 = null){
		$this->init($date, $date2);
		$events = Model_Event::get_categories_sum($this->current_user_id, $this->start, $this->end, true);
		
		//テンプレートを無効化してCSVとして出力
		$this->template = null;
		$this->response->set_header('Content-Type', 'application/csv');
		$this->response->set_header('Content-Disposition', 'attachment; filename="categories_' . $this->file_name . '"');
		echo Format::forge($events)->to_csv();
		return;
	}

	private function time_to_manhour($diff){
		$manhour = 0;
		$diff = abs($diff);
		if($diff > 0){
			$manhour = floor($diff / 3600 * 100) / 100;
		}
		return $manhour;
	}

}
