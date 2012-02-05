<?php

class Controller_Calendar extends Controller_Base
{
	public function before(){
		parent::before();

		if ( ! Auth::check() ){
			Response::redirect('auth/login');
		}
		
	}

	public function action_index()
	{
		$this->template->set_safe('optionStyles', Asset::css(array(
			'smoothness/jquery-ui-1.8.17.custom.css',
			'jquery-week-calendar/jquery.weekcalendar.css',
			'jquery-week-calendar/skins/default.css',
			'jquery-week-calendar/calendar.css',
		)));
		
		$this->template->set_safe('optionScripts', Asset::js(array(
			'jquery-ui-1.8.17.custom.min.js',
			'jquery-week-calendar/date.js',
			'jquery-week-calendar/jquery.weekcalendar.js',
			'calendar.js',
		)));

		$this->template->title = 'Calendar &raquo; Index';
		$this->template->content = View::forge('calendar/index');
	}

}
