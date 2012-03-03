<div class="row">
	<div class="span12">
		<?php if(isset($user_name) && (!empty($user_name) || is_numeric($user_name))){ ?>
			<h1 style="margin: 20px 0;"><?php echo $user_name; ?>のサマリ</h1>
		<?php } ?>
		<div class="well" style="margin-top: 20px;">
			<div id="search_error" class="alert alert-error">
			</div>
			<div>
				日付を指定：
				<input type="text" class="span3 datepicker" id="search_start" placeholder="開始日時" value="<?php echo $start_date; ?>">
				<a href="#" id="remove_start"><i class="icon-remove-circle"></i></a>
				～
				<input type="text" class="span3 datepicker" id="search_end" placeholder="終了日時" value="<?php echo ($start_date != $end_date) ? $end_date : ''; ?>">
				<a href="#" id="remove_end"><i class="icon-remove-circle"></i></a>
				<a href="<?php echo $link_path; ?>" id="go_summary" class="btn btn-primary">指定日に移動</a>
				<a href="<?php echo $link_path; ?>" class="btn btn-info">今日の日付に移動</a>
			</div>
		</div>
		<?php if(isset($prev_date) && isset($next_date)){ ?>
		<div style="overflow:hidden;margin-bottom:20px;">
			<a href="<?php echo $link_path; ?><?php echo $prev_date; ?>" class="btn pull-left">前の日に移動</a>
			<a href="<?php echo $link_path; ?><?php echo $next_date; ?>" class="btn pull-right">次の日に移動</a>
		</div>
		<?php } ?>
		<?php if(isset($sum)){ ?>
			<p class="label label-info" style="font-size:24px;line-height:38px;padding-left:10px;">合計：<?php echo number_format($sum, 2); ?>&nbsp;時間</p>
		<?php } ?>
		<?php if(isset($events) && is_array($events)){ ?>
		<div style="overflow:hidden;margin-bottom: 10px;">
			<h2 class="pull-left"><?php echo $title_date; ?>の全イベント</h2>
			<div class="pull-right">
				<a class="btn btn-primary" href="<?php echo $event_csv_link_path . $csv_url_suffix; ?>"><i class="icon-th icon-white"></i> CSV形式で出力</a>
				<!-- &nbsp;
				<a class="btn btn-success" href="#"><i class="icon-envelope icon-white"></i> メール形式を取得</a> -->
			</div>
		</div>
		<table class="table table-striped table-bordered table-condensed">
			<thead>
				<tr>
					<th style="width:120px;">開始時間</th>
					<th style="width:120px;">終了時間</th>
					<th>カテゴリ</th>
					<th>メモ</th>
					<th style="width:100px;">工数（時間）</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($events as $event){ ?>
				<tr>
					<td><?php echo date($summary_date_format, strtotime($event['start'])); ?></td>
					<td><?php echo date($summary_date_format	, strtotime($event['end'])); ?></td>
					<td><?php echo $event['name']; ?></td>
					<td><?php echo preg_replace('/\r\n|\r|\n/', '<br />', $event['title']); ?></td>
					<td style="text-align: right;"><?php echo number_format($event['sum'], 2) ?></td>
				</tr>	
			<?php } ?>
			</tbody>
		</table>
		<?php } ?>
		<div class="pagination pagination-centered">
			<ul>
				<?php echo Pagination::prev_link('前へ'); ?>
				<?php echo Pagination::page_links(); ?>
				<?php echo Pagination::next_link('次へ'); ?>
			</ul>
		</div>
		<?php if(isset($category_events) && is_array($category_events)){ ?>
		<div style="overflow:hidden;margin-bottom: 10px;">
			<h2 class="pull-left"><?php echo $title_date; ?>のカテゴリ別工数</h2>
			<div class="pull-right">
				<a class="btn btn-primary" href="<?php echo $category_csv_link_path . $csv_url_suffix; ?>"><i class="icon-th icon-white"></i> CSV形式で出力</a>
				<!-- &nbsp;
				<a class="btn btn-success" href="#"><i class="icon-envelope icon-white"></i> メール形式を取得</a> -->
			</div>
		</div>
		<table class="table table-striped table-bordered table-condensed">
			<thead>
				<tr>
					<th>カテゴリ</th>
					<th style="width:100px;">工数（時間）</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($category_events as $category_event){ ?>
				<tr>
					<td><?php echo $category_event['name'] ?></td>
					<td style="text-align: right;"><?php echo number_format($category_event['sum'], 2); ?></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
		<?php } ?>
		<?php if(isset($prev_date) && isset($next_date)){ ?>
		<div style="overflow:hidden;margin-bottom:20px;">
			<a href="<?php echo $link_path; ?><?php echo $prev_date; ?>" class="btn pull-left">前の日に移動</a>
			<a href="<?php echo $link_path; ?><?php echo $next_date; ?>" class="btn pull-right">次の日に移動</a>
		</div>
		<?php } ?>
	</div>
</div>