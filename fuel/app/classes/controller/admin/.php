<?php
class Controller_Admin_ extends Controller_Admin 
{

	public function action_index()
	{
		$data['s'] = Model_::find('all');
		$this->template->title = "S";
		$this->template->content = View::forge('admin//index', $data);

	}

	public function action_view($id = null)
	{
		$data[''] = Model_::find($id);

		$this->template->title = "";
		$this->template->content = View::forge('admin//view', $data);

	}

	public function action_create($id = null)
	{
		if (Input::method() == 'POST')
		{
			$val = Model_::validate('create');
			
			if ($val->run())
			{
				$ = Model_::forge(array(
				));

				if ($ and $->save())
				{
					Session::set_flash('success', 'Added  #'.$->id.'.');

					Response::redirect('admin/');
				}

				else
				{
					Session::set_flash('error', 'Could not save .');
				}
			}
			else
			{
				Session::set_flash('error', $val->show_errors());
			}
		}

		$this->template->title = "S";
		$this->template->content = View::forge('admin//create');

	}

	public function action_edit($id = null)
	{
		$ = Model_::find($id);
		$val = Model_::validate('edit');

		if ($val->run())
		{

			if ($->save())
			{
				Session::set_flash('success', 'Updated  #' . $id);

				Response::redirect('admin/');
			}

			else
			{
				Session::set_flash('error', 'Could not update  #' . $id);
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{

				Session::set_flash('error', $val->show_errors());
			}
			
			$this->template->set_global('', $, false);
		}

		$this->template->title = "S";
		$this->template->content = View::forge('admin//edit');

	}

	public function action_delete($id = null)
	{
		if ($ = Model_::find($id))
		{
			$->delete();

			Session::set_flash('success', 'Deleted  #'.$id);
		}

		else
		{
			Session::set_flash('error', 'Could not delete  #'.$id);
		}

		Response::redirect('admin/');

	}


}