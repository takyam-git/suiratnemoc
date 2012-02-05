<div id="calendar"></div>
<div id="event_edit_container">
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
			<!-- <li>
				<label for="category">カテゴリ: </label>
				<select name="category" id="category">
					<option value="">デフォルト</option>
					<option value="1">赤</option>
				</select>
			</li> -->
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
</div>