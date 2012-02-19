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
		<form class="form-horizontal" method="post" action="/auth/login">
			<fieldset>
				<legend>ログイン</legend>
					<div class="control-group">
						<label class="control-label" for="input01">ユーザー名</label>
						<div class="controls">
							<input type="text" class="input-xlarge" id="input01" name="username">
							<p class="help-block">ユーザー名を入力してください。メールアドレスはユーザー名ではありません。</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="input02">パスワード</label>
						<div class="controls">
							<input type="password" class="input-xlarge" id="input01" name="password">
						</div>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn btn-primary">ログイン</button>
					</div>
				</fieldset>
		 </form>
		 <p><a href="/auth/new">新規登録はこちら</a></p>
	</div>
</div>
