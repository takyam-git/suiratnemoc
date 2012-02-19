<?php

class Controller_Summary extends Controller_Base
{

	public function action_index($date = null, $date2 = null)
	{
		// $this->template->set_safe('optionStyles', Asset::css(array(
			// 'jquery-menu/fg.menu.css',
			// 'colors.css',
			// 'category/category.css',
		// )));
		
		$this->template->set_safe('optionScripts', Asset::js(array(
			'summary.js',
		)));
		
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
		if(is_null($end_date)){
			$end_date = $start_date;
		}else{
			$is_multiple_days = true;
			$title_date .= ' ～ ' . $end_date;
		}
		
		$start = $start_date . ' 00:00:00';
		$end   = $end_date . ' 23:59:59';
		
		$query = Model_Event::find()
					->where('userid', $this->current_user_id)
					->where('start', '>=', $start)
					->where('end', '<=', $end)
					->order_by('start', 'asc')
					->related('category');
					
		$event_objects = $query->get();
		
		$events = array();
		$category_events = array();
		$sum = 0;
		if(is_array($event_objects)){
			foreach($event_objects as $key => $event){
				$event_array = $event->to_array();
				$diff = strtotime($event->end) - strtotime($event->start);
				$event_array['manhour'] = $this->time_to_manhour($diff);
				$events[] = $event_array;
				
				$category_id = $event_array['category']['id'];
				if(!isset($category_events[$category_id])){
					$category_events[$category_id] = array(
						'diff' => 0,
						'category_name' => $event_array['category']['name'],
					);
				}
				$category_events[$category_id]['diff'] += $diff;
				$sum += $diff;
			}
		}
		
		foreach($category_events as $key => $category_event){
			$category_events[$key]['manhour'] = $this->time_to_manhour($category_event['diff']);
		}
		
		$sum = $this->time_to_manhour($sum);
		
		$prev_date = null;
		$next_date = null;
		$summary_date_format = 'H:i:s';
		if($is_multiple_days){
			$summary_date_format = 'Y-m-d H:i:s';
		}else{
			$int_start = strtotime($start_date);
			$year = intval(date('y', $int_start));
			$month = intval(date('m', $int_start));
			$day = intval(date('d', $int_start));
			$prev_date = date('Y-m-d', mktime(0,0,0, $month, $day - 1, $year));
			$next_date = date('Y-m-d', mktime(0,0,0, $month, $day + 1, $year));
		}
		
		$this->template->title = 'Summary &raquo; Index';
		$this->template->content = View::forge('summary/index', array(
			'start_date' => $start_date,
			'end_date' => $end_date,
			'prev_date' => $prev_date,
			'next_date' => $next_date,
			'sum' => $sum,
			'summary_date_format' => $summary_date_format,
			'title_date' => $title_date,
			'events' => $events,
			'category_events' => $category_events,
		));
	}

	private function time_to_manhour($diff){
		$manhour = 0;
		if($diff > 0 || $diff < 0){
			if($diff < 0){
				$diff = $diff * -1;
			}
			$fifteen = floor($diff / 60 / 15);
			if($fifteen > 0){
				$manhour = floor(($fifteen * 0.25) * 100) / 100;
			}
		}
		return $manhour;
	}

}
