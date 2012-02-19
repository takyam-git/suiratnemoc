<div id="calendar"></div>
<!-- <div id="event_edit_container">
	<div class="dialog_errors" style="color: #f00">
		
	</div>
	<form>
		<input type="hidden" />
		<ul>
			<li>
				<span>日にち: </span><span class="date_holder"></span>
			</li>
			<li>
				<label for="start">開始時間: </label>
				<select name="start" id="start">
					<option value="">開始時間を選んでください</option>
				</select>
			</li>
			<li>
				<label for="end">終了時間: </label>
				<select name="end" id="end">
					<option value="">終了時間を選んでください</option>
				</select>
			</li>
			<li>
				<label for="category">カテゴリ: </label>
				<select name="category" id="category">
					<option value="">デフォルト</option>
					<option value="1">赤</option>
				</select>
			</li>
			<li>
				<label for="title">件名: </label>
				<input id="title" type="text" name="title" />
			</li>
			<li>
				<label for="body">概要: </label>
				<textarea id="body" name="body" rows="5"></textarea>
			</li>
		</ul>
	</form>
</div> -->
<div id="event_edit_container" class="modal">
	<div class="modal-header">
		<a class="close" data-dismiss="modal">×</a>
		<h3 id="eventTitle" class="date_holder">カテゴリデータ</h3>
	</div>
	<div class="modal-body">
		<div class="alert alert-error dialog_errors" id="categoryDialogError">
		</div>
		<form class="form-horizontal">
			<fieldset>
				<!-- <div class="control-group">
					<label class="control-label">日にち</label>
					<div class="controls">
						<span class="date_holder"></span>
					</div>
				</div> -->
				<div class="control-group" id="startGroup" style="display: none">
					<label class="control-label" for="start">時間</label>
					<div class="controls">
						<select name="start" id="start" class="span2">
							<option value="">開始時間</option>
						</select>
						～
						<select name="end" id="end" class="span2">
							<option value="">終了時間</option>
						</select>
						<span class="help-inline" id="startError"></span>
						<span id="startScreen"></span>
					</div>
				</div>
				<!-- <div class="control-group" id="endGroup">
					<label class="control-label" for="end">終了時間</label>
					<div class="controls">
						<select name="end" id="end">
							<option value="">終了時間を選んでください</option>
						</select>
						<span class="help-inline" id="endError"></span>
						<span id="endScreen"></span>
					</div>
				</div> -->
				<div class="control-group" id="categoryGroup">
					<label class="control-label" for="category">カテゴリ</label>
					<div class="controls">
						<a href="#" class="btn" id="categorySet">カテゴリを選択してください</a>
						<input type="hidden" id="category" name="category" value="" />
						<span class="help-inline" id="categoryError"></span>
						<span id="categoryScreen"></span>
					</div>
				</div>
				<!-- <div class="control-group" id="titleGroup">
					<label class="control-label" for="title">件名</label>
					<div class="controls">
						<input id="title" type="text" name="title" class="span3" />
						<span class="help-inline" id="titleError"></span>
						<span id="titleScreen"></span>
					</div>
				</div> -->
				<div class="control-group" id="titleGroup">
					<label class="control-label" for="title">メモ</label>
					<div class="controls">
						<textarea id="title" name="title" rows="5" class="span3"></textarea>
						<span class="help-inline" id="titleError"></span>
						<span id="titleScreen"></span>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-danger" style="float:left;" id="removeButton"><i class="icon-trash icon-white"></i> 削除する</a>
		<a href="#" class="btn btn-primary" id="saveButton"><i class="icon-ok icon-white"></i> 保存する</a>
		<a class="btn" data-dismiss="modal">キャンセル</a>
	</div>
</div>
<div id="categoryList">
	<ul class="categoryListUl">
		<li>
			<a href="#">お気に入り</a>
			<ul>
				<?php if(isset($favorite_categories) && is_array($favorite_categories)){ ?>
					<?php foreach($favorite_categories as $favorite_category){ ?>
						<li><a href="#" data-id="<?php echo $favorite_category->id; ?>"><?php echo $favorite_category->name; ?></a></li>
					<?php } ?>
				<?php } ?>
			</ul>
		</li>
		<li>
			<a href="#">グローバルカテゴリ</a>
			<ul>
				<?php if(isset($global_categories) && is_array($global_categories)){ ?>
					<?php foreach($global_categories as $global_category){ ?>
						<li><a href="#" data-id="<?php echo $global_category->id; ?>"><?php echo $global_category->name; ?></a></li>
					<?php } ?>
				<?php } ?>
			</ul>
		</li>
		<li>
			<a href="#">マイカテゴリ</a>
			<ul>
				<?php if(isset($local_categories) && is_array($local_categories)){ ?>
					<?php foreach($local_categories as $local_category){ ?>
						<li><a href="#" data-id="<?php echo $local_category->id; ?>"><?php echo $local_category->name; ?></a></li>
					<?php } ?>
				<?php } ?>
			</ul>
		</li>
	</ul>
</div>
<div id="removeCategoryDialog" class="modal">
	<div class="modal-header">
		<a class="close" data-dismiss="modal">×</a>
		<h3 id="removeCategoryDialogTitle">イベントの削除</h3>
	</div>
	<div class="modal-body">
		<div class="alert alert-error" id="removeCategoryDialogError">
		</div>
		<p>このイベントを本当に削除してもよろしいですか？</p>
		<small>※削除すると、復活させる事ができません</small>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-danger" id="categoryDialogRemoveDoButton"><i class="icon-trash icon-white"></i> 削除する</a>
		<a class="btn" data-dismiss="modal">キャンセル</a>
	</div>
</div>