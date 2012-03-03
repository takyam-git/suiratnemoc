<?php

class Controller_User extends Controller_Adminbase
{

	public function action_index()
	{
		//ページネーション用に全アイテムの件数を取得
		$count_query = DB::select(DB::expr('COUNT(*) AS `count`'))
			->from('users');
		$count_result = $count_query->execute()->as_array();
		$count = $count_result[0]['count'];
		
		//ページネーションの設定
		$pagination_config = array(
			'total_items' => $count,
			'pagination_url' => '/user/index',
			'per_page' => 20,
			'num_links' => 4,
			'uri_segment' => 3,
		);
		Pagination::set_config($pagination_config);
		
		$query = DB::select('id', 'username', 'group', 'last_login')
			->from('users')
			->order_by('id', 'asc')
			->limit(Pagination::$per_page)
			->offset(Pagination::$offset);
		
		$users = $query->execute()->as_array();
		
		$this->template->title = 'ユーザー';
		$this->template->content = View::forge('user/index', array(
			'users' => $users,
		));
	}
	
	public function action_permission($user_id){
		$user = Model_User::find_by_id($user_id);
		if($user){
			$group = intval($user->group);
			if($group < 100){
				if($group === 99){
					$user->group = 0;
				}else{
					$user->group = 99;
				}
				$user->save();
			}
		}
		
		Response::redirect('/user');
	}

}
