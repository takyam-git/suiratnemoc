<div class="row">
	<div class="span12">
		<ul>
		<?php
			if($error_msg){
		?>
		<div class="alert">
			<a class="close">×</a>
  			<h4 class="alert-heading">Warning!</h4>
  			<?php echo $error_msg; ?>
		</div>
		<?php
			}
		?>
		</ul>
		<form class="form-horizontal" method="post" action="/auth/new">
			<fieldset>
				<legend>ログイン</legend>
					<div class="control-group">
						<label class="control-label" for="input01">ユーザー名</label>
						<div class="controls">
							<input type="text" class="input-xlarge" id="input01" name="username" value="<?php echo $values['username']; ?>">
							<p class="help-block">3～200文字以内で入力してください</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="input02">パスワード</label>
						<div class="controls">
							<input type="password" class="input-xlarge" id="input01" name="password" value="<?php echo $values['password']; ?>">
							<p class="help-block">最低8文字は必要です</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="input03">パスワード（確認）</label>
						<div class="controls">
							<input type="password" class="input-xlarge" id="input03" name="password_confirm" value="<?php echo $values['password_confirm']; ?>">
							<p class="help-block">確認のため、もう一度パスワードを入力してください</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="input04">メールアドレス</label>
						<div class="controls">
							<input type="text" class="input-xlarge" id="input04" name="mail" value="<?php echo $values['mail']; ?>">
						</div>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn btn-primary">新規登録</button>
					</div>
				</fieldset>
		 </form>
	</div>
</div>
