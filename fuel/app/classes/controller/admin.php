<?php

class Controller_Admin extends Controller_Base {

	public $template = 'template';

	public function before()
	{
		parent::before();

		if ( ! Auth::member(100) and Request::active()->action != 'login')
		{
			Response::redirect('/');
		}
	}

	/**
	 * The index action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_index()
	{		
		$this->template->title = 'Dashboard';
		$this->template->content = View::factory('admin/index');
	}

}

/* End of file admin.php */