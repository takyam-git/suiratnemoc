<?php

class Model_Event extends \Orm\Model
{
	protected static $_belongs_to  = array(
		'user' => array(
			'key_from' => 'userid',
			'model_to' => 'Model_User',
			'key_to' => 'id',
			'cascade_save' => true,
			'cascade_delete' => false,
		),
		'category' => array(
			'key_from' => 'category',
	        'model_to' => 'Model_Category',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => false,
		),
	);
	
	protected static $_properties = array(
		'id',
		'userid',
		'title' => array(
			'default' => '',
		),
		'body' => array(
			'default' => '',
		),
		'start',
		'end',
		'category',
		'created_at',
		'updated_at'
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => false,
		),
	);
	
	/**
	 * 特定ユーザーの特定期間のマンアワーの合計を返す
	 * @access static
	 * @param int $user_id ユーザーID
	 * @param int $start 開始日時
	 * @param int $end 終了日時
	 * @return int $sum 合計時間
	 */
	public static function get_sum($user_id, $start, $end){
		$query = DB::select(DB::expr('SUM(ABS(TIME_TO_SEC(timediff(events.end,events.start))))/3600 AS sum'))
			->from('events')
			->join('categories')
			->on('events.category', '=', 'categories.id')
			->where('events.userid', $user_id)
			->where('events.start', '>=', $start)
			->where('events.end', '<=', $end)
			->where('categories.in_summary', '=', 1)
			->order_by('events.start', 'asc')
			->limit(1);
		$result = $query->execute();
		$sum = 0;
		if(is_object($result) && $result->count() > 0){
			$result = $result->as_array();
			$sum = $result[0]['sum'];
		}
		return $sum;
	}
	
	/**
	 * 特定ユーザーの特定期間のカテゴリ別のマンアワー合計を返す
	 * @access static
	 * @param int $user_id ユーザーID
	 * @param int $start 開始日時
	 * @param int $end 終了日時
	 * @return array カテゴリ別マンアワー合計
	 */
	public static function get_categories_sum($user_id, $start, $end, $csv_mode = false){
		if(!$csv_mode){
			$id = 'id';
			$name = 'name';
			$sum = 'sum';
		}else{
			$id = 'ID';
			$name = 'カテゴリ名';
			$sum = '工数';
		}
		$query = DB::select(
				array('categories.id', $id),
				array('categories.name', $name),
				DB::expr('SUM(ABS(TIME_TO_SEC(timediff(events.end,events.start))))/3600 AS ' . $sum)
			)
			->from('events')
			->join('categories')
			->on('events.category', '=', 'categories.id')
			->where('events.userid', $user_id)
			->where('events.start', '>=', $start)
			->where('events.end', '<=', $end)
			->where('categories.in_summary', '=', 1)
			->group_by('events.category')
			->order_by('categories.id', 'asc');
		$result = $query->execute();
		return $result->as_array();
	}
}
