<?php
class Format extends Fuel\Core\Format {
	/**
	 * CSV出力をSJIS-WINで返す
	 * @access public
	 * @param mixed $data
	 * @return string csv(sjis-win)
	 */
	public function to_csv($data = null){
		$csv = parent::to_csv($data);
		$csv = mb_convert_encoding($csv, 'SJIS-win', 'UTF-8');
		return $csv;
	}
}

