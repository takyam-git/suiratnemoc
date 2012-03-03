<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<title><?php echo $title; ?> :: すもっく！</title>
		<meta name="description" content="">
		<meta name="author" content="">

		<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Le styles -->
		<?php echo Asset::css(array(
			'bootstrap.min.css',
			'bootstrap-responsive.min.css',
			'smoothness/jquery-ui-1.8.17.custom.css',
		)); ?>
		<style>
			body {
				padding-top: 40px; /* 60px to make the container go all the way to the bottom of the topbar */
			}
		</style>
		<?php
			if(isset($optionStyles)){
				echo $optionStyles;
			}
		?>
		
		<?php echo Asset::js(array(
			'jquery-1.7.1.min.js',
			'bootstrap.min.js',
			'jquery-ui-1.8.17.custom.min.js',
			'main.js',
		)); ?>
		
		<?php
			if(isset($optionScripts)){
				echo $optionScripts;
			}
		?>
	</head>

	<body>

		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<a class="brand" href="/">すもっく！</a>
					<div class="nav-collapse">
						<?php if(isset($current_user) && $current_user){ ?>
						<ul class="nav">
							<li class="<?php echo strpos(Uri::string(), 'calendar') === 0 ? 'active' : '' ?>">
								<?php echo Html::anchor('calendar', 'カレンダー');  ?>
							</li>
							<li class="<?php echo strpos(Uri::string(), 'category') === 0 ? 'active' : '' ?>">
								<?php echo Html::anchor('category', 'カテゴリー');  ?>
							</li>
							<li class="<?php echo strpos(Uri::string(), 'summary') === 0 ? 'active' : '' ?>">
								<?php echo Html::anchor('summary', 'サマリー');  ?>
							</li>
							<?php if(isset($is_admin_user) && $is_admin_user === true){ ?>
							<li class="<?php echo strpos(Uri::string(), 'user') === 0 ? 'active' : '' ?>">
								<?php echo Html::anchor('user', 'ユーザー');  ?>
							</li>
							<?php } ?>
						</ul>
						<?php } ?>
					</div><!--/.nav-collapse -->
					<ul class="nav pull-right">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<?php
								if(isset($current_user) && $current_user){
									echo $current_user->username;
								}else{
									echo 'ゲスト';
								}
							?>
								<b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
								<?php
									if(isset($current_user) && $current_user){
								?>
									<!-- <li><a href="/auth/profile">プロフィールの変更</a></li> -->
									<li><a href="/auth/logout">ログアウト</a></li>
								<?php }else{ ?>
									<li><a href="/auth/login">ログイン</a></li>
									<li><a href="/auth/new">新規登録</a></li>
								<?php } ?>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<div class="container">
<?php echo $content; ?>
		</div> <!-- /container -->

		<!-- Le javascript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<?php echo Asset::js(array(
			'bootstrap-transition.js',
			'bootstrap-alert.js',
			'bootstrap-modal.js',
			'bootstrap-dropdown.js',
			'bootstrap-scrollspy.js',
			'bootstrap-tab.js',
			'bootstrap-tooltip.js',
			'bootstrap-popover.js',
			// 'bootstrap-button.js',
			'bootstrap-collapse.js',
			'bootstrap-carousel.js',
			'bootstrap-typeahead.js',
		)); ?>
	</body>
</html>