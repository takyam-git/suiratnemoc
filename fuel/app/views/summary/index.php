<div class="row">
	<div class="span12">
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
				<a href="#" id="go_summary" class="btn btn-primary">指定日に移動</a>
				<a href="/summary" class="btn btn-info">今日の日付に移動</a>
			</div>
		</div>
		<?php if(isset($prev_date) && isset($next_date)){ ?>
		<div style="overflow:hidden;margin-bottom:20px;">
			<a href="/summary/index/<?php echo $prev_date; ?>" class="btn pull-left">前の日に移動</a>
			<a href="/summary/index/<?php echo $next_date; ?>" class="btn pull-right">次の日に移動</a>
		</div>
		<?php } ?>
		<?php if(isset($events) && is_array($events)){ ?>
		<h2><?php echo $title_date; ?>の全イベント</h2>
		<table class="table table-striped table-bordered table-condensed">
			<thead>
				<tr>
					<th>開始時間</th>
					<th>終了時間</th>
					<th>カテゴリ</th>
					<th>メモ</th>
					<th>工数（時間）</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($events as $event){ ?>
				<tr>
					<td><?php echo date($summary_date_format, strtotime($event['start'])); ?></td>
					<td><?php echo date($summary_date_format	, strtotime($event['end'])); ?></td>
					<td><?php echo $event['category']['name']; ?></td>
					<td><?php echo $event['title']; ?></td>
					<td><?php echo $event['manhour'] ?></td>
				</tr>	
			<?php } ?>
				<tr>
					<td colspan="4">合計</td>
					<td><?php echo $sum; ?></td>
				</tr>
			</tbody>
		</table>
		<?php } ?>
		<?php if(isset($category_events) && is_array($category_events)){ ?>
		<h2><?php echo $title_date; ?>のカテゴリ別工数</h2>
		<table class="table table-striped table-bordered table-condensed">
			<thead>
				<tr>
					<th>カテゴリ</th>
					<th>工数（時間）</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($category_events as $category_event){ ?>
				<tr>
					<td><?php echo $category_event['category_name'] ?></td>
					<td><?php echo $category_event['manhour']; ?></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
		<?php } ?>
		<?php if(isset($prev_date) && isset($next_date)){ ?>
		<div style="overflow:hidden;margin-bottom:20px;">
			<a href="/summary/index/<?php echo $prev_date; ?>" class="btn pull-left">前の日に移動</a>
			<a href="/summary/index/<?php echo $next_date; ?>" class="btn pull-right">次の日に移動</a>
		</div>
		<?php } ?>
	</div>
</div>