<div id="is_admin_flag" data-isadmin="<?php echo intval($is_admin_user); ?>"></div>
<div class="row">
	<div class="span12" id="categoryTitle">
		<div class="row">
			<div class="span3">
				<div class="inner">
					<h1>カテゴリ管理</h1>
				</div>
			</div>
			<div class="span9">
				<div class="inner">
					<a href="#" class="btn btn-large pull-right" id="add-my-category-btn"><i class="icon-plus "></i>マイカテゴリを追加</a>
					<?php if($is_admin_user){ ?>
						<a href="#" class="btn btn-large pull-right" id="add-global-category-btn" style="margin-right:20px;"><i class="icon-plus "></i>グローバルカテゴリを追加</a>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row show-grid">
	<div class="span4" id="favoriteCategory">
		<div class="inner">
			<div class="categoryHeader">
				<p class="keyvisual"><img src="/assets/img/category/favorite.png"></p>
				<h2>お気に入り</h2>
				<p>よく利用するカテゴリをお気に入りとして登録しておくと、イベントの登録が楽になります。</p>
				<p>お気に入りに追加するには、ドラッグ＆ドロップしてください。削除するにはクリックします。</p>
			</div>
			<div class="form-search searchbox">
				<input type="text" class="input-xlarge search-query" placeholder="絞り込み検索">
			</div>
			<div class="categories">
				<ul class="categoryList" id="favoriteCategoryList">
					<li data-type="global" data-id="1" data-color="1"><i class="icon-remove icon-white pull-right category-config-icon"></i>global1</li>
					<li data-type="global" data-id="2" data-color="2"><i class="icon-remove icon-white pull-right category-config-icon"></i>global2</li>
					<li data-type="local" data-id="4" data-color="10"><i class="icon-remove icon-white pull-right category-config-icon"></i>local4</li>
					<li data-type="local" data-id="5" data-color="11"><i class="icon-remove icon-white pull-right category-config-icon"></i>local5</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="span4" id="globalCategory">
		<div class="inner">
			<div class="categoryHeader">
				<p class="keyvisual"><img src="/assets/img/category/shared_category.png"></p>
				<h2>グローバルカテゴリ</h2>
				<p>全ユーザで共有しているカテゴリで、管理者以外編集できません。</p>
			</div>
			<div class="form-search searchbox">
				<input type="text" class="input-xlarge search-query" placeholder="絞り込み検索">
			</div>
			<div class="categories">
				<ul class="categoryList baseCategoryList">
					<li id="globalCategory-1" data-type="global" data-id="1" data-color="1"><i class="icon-cog icon-white pull-right category-config-icon"></i>global1</li>
					<li id="globalCategory-2" data-type="global" data-id="2" data-color="2"><i class="icon-cog icon-white pull-right category-config-icon"></i>global2</li>
					<li id="globalCategory-3" data-type="global" data-id="3" data-color="3"><i class="icon-cog icon-white pull-right category-config-icon"></i>global3</li>
					<li id="globalCategory-4" data-type="global" data-id="4" data-color="4"><i class="icon-cog icon-white pull-right category-config-icon"></i>global4</li>
					<li id="globalCategory-5" data-type="global" data-id="5" data-color="5"><i class="icon-cog icon-white pull-right category-config-icon"></i>global5</li>
					<li id="globalCategory-6" data-type="global" data-id="6" data-color="6"><i class="icon-cog icon-white pull-right category-config-icon"></i>global6</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="span4" id="myCategory">
		<div class="inner">
			<div class="categoryHeader">
				<p class="keyvisual"><img src="/assets/img/category/personal_category.png"></p>
				<h2>マイカテゴリ</h2>
				<p>あなただけのカテゴリで、他の人が編集したり利用することはできません。</p>
			</div>
			<div class="form-search searchbox">
				<input type="text" class="input-xlarge search-query" placeholder="絞り込み検索">
			</div>
			<div class="categories">
				<ul class="categoryList baseCategoryList">
					<li id="localCategory-1" data-type="local" data-id="1" data-color="7"><i class="icon-cog icon-white pull-right category-config-icon"></i>local1</li>
					<li id="localCategory-2" data-type="local" data-id="2" data-color="8"><i class="icon-cog icon-white pull-right category-config-icon"></i>local2</li>
					<li id="localCategory-3" data-type="local" data-id="3" data-color="9"><i class="icon-cog icon-white pull-right category-config-icon"></i>local3</li>
					<li id="localCategory-4" data-type="local" data-id="4" data-color="10"><i class="icon-cog icon-white pull-right category-config-icon"></i>local4</li>
					<li id="localCategory-5" data-type="local" data-id="5" data-color="11"><i class="icon-cog icon-white pull-right category-config-icon"></i>local5</li>
					<li id="localCategory-6" data-type="local" data-id="6" data-color="12"><i class="icon-cog icon-white pull-right category-config-icon"></i>local6</li>
					<li id="localCategory-7" data-type="local" data-id="7" data-color="13"><i class="icon-cog icon-white pull-right category-config-icon"></i>local7</li>
					<li id="localCategory-8" data-type="local" data-id="8" data-color="14"><i class="icon-cog icon-white pull-right category-config-icon"></i>local8</li>
					<li id="localCategory-9" data-type="local" data-id="9" data-color="15"><i class="icon-cog icon-white pull-right category-config-icon"></i>local9</li>
					
				</ul>
			</div>
		</div>
	</div>
</div>
<div id="modifyCategoryDialog" class="modal">
	<div class="modal-header">
		<a class="close" data-dismiss="modal">×</a>
		<h3 id="modifyCategoryDialogTitle">カテゴリデータ</h3>
	</div>
	<div class="modal-body">
		<form class="form-horizontal">
			<fieldset>
				<div class="control-group" id="categoryNameGroup">
					<label class="control-label" for="categoryName">カテゴリ名</label>
					<div class="controls">
						<input type="text" class="input-xlarge" id="categoryName">
						<span class="help-inline" id="categoryNameError"></span>
						<span id="categoryNameScreen"></span>
					</div>
				</div>
				<div class="control-group" id="categoryColorGroup">
					<label class="control-label" for="selectedColorID">カラーセット</label>
					<div class="controls">
						<a href="#" class="btn" id="selectColorSet">色を選択する</a>
						<input type="hidden" id="selectedColorID" name="selectedColorID" value="" />
						<span class="help-inline" id="categoryColorError"></span>
						<span id="categoryColorScreen"></span>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-primary" id="categoryDialogSaveButton">保存する</a>
		<a class="btn" data-dismiss="modal">キャンセル</a>
	</div>
</div>
<div id="colorSetList">
	<ul class="colorSetListUl">
		<li>
			<a href="#">赤系</a>
			<ul>
				<li><a href="#" data-id="1" data-name="赤-A"><i class="category-color1"><i class="sub-color">00:00</i>イベント名</i></a></li>
				<li><a href="#" data-id="15" data-name="赤-B"><i class="category-color15"><i class="sub-color">00:00</i>イベント名</i></a></li>
				<li><a href="#" data-id="16" data-name="赤-C"><i class="category-color16"><i class="sub-color">00:00</i>イベント名</i></a></li>
				<li><a href="#" data-id="17" data-name="赤-D"><i class="category-color17"><i class="sub-color">00:00</i>イベント名</i></a></li>
				<li><a href="#" data-id="31" data-name="赤-E"><i class="category-color31"><i class="sub-color">00:00</i>イベント名</i></a></li>
				<li><a href="#" data-id="32" data-name="赤-F"><i class="category-color32"><i class="sub-color">00:00</i>イベント名</i></a></li>
			</ul>
		</li>
		<li>
			<a href="#">黄系</a>
			<ul>
				<li><a href="#" data-id="2" data-name="黄-A"><i class="category-color2"><i class="sub-color">00:00</i>イベント名</i></a></li>
				<li><a href="#" data-id="3" data-name="黄-B"><i class="category-color3"><i class="sub-color">00:00</i>イベント名</i></a></li>
				<li><a href="#" data-id="4" data-name="黄-C"><i class="category-color4"><i class="sub-color">00:00</i>イベント名</i></a></li>
				<li><a href="#" data-id="18" data-name="黄-D"><i class="category-color18"><i class="sub-color">00:00</i>イベント名</i></a></li>
				<li><a href="#" data-id="19" data-name="黄-E"><i class="category-color19"><i class="sub-color">00:00</i>イベント名</i></a></li>
				<li><a href="#" data-id="20" data-name="黄-F"><i class="category-color20"><i class="sub-color">00:00</i>イベント名</i></a></li>
			</ul>
		</li>
		<li>
			<a href="#">紫系</a>
			<ul>
				<li><a href="#" data-id="12" data-name="紫-A"><i class="category-color12"><i class="sub-color">00:00</i>イベント名</i></a></li>
				<li><a href="#" data-id="13" data-name="紫-B"><i class="category-color13"><i class="sub-color">00:00</i>イベント名</i></a></li>
				<li><a href="#" data-id="14" data-name="紫-C"><i class="category-color14"><i class="sub-color">00:00</i>イベント名</i></a></li>
				<li><a href="#" data-id="28" data-name="紫-D"><i class="category-color28"><i class="sub-color">00:00</i>イベント名</i></a></li>
				<li><a href="#" data-id="29" data-name="紫-E"><i class="category-color29"><i class="sub-color">00:00</i>イベント名</i></a></li>
				<li><a href="#" data-id="30" data-name="紫-F"><i class="category-color30"><i class="sub-color">00:00</i>イベント名</i></a></li>
				
			</ul>
		</li>
		<li>
			<a href="#">青系</a>
			<ul>
				<li><a href="#" data-id="9" data-name="青-A"><i class="category-color9"><i class="sub-color">00:00</i>イベント名</i></a></li>
				<li><a href="#" data-id="10" data-name="青-B"><i class="category-color10"><i class="sub-color">00:00</i>イベント名</i></a></li>
				<li><a href="#" data-id="11" data-name="青-C"><i class="category-color11"><i class="sub-color">00:00</i>イベント名</i></a></li>
				<li><a href="#" data-id="25" data-name="青-D"><i class="category-color25"><i class="sub-color">00:00</i>イベント名</i></a></li>
				<li><a href="#" data-id="26" data-name="青-E"><i class="category-color26"><i class="sub-color">00:00</i>イベント名</i></a></li>
				<li><a href="#" data-id="27" data-name="青-F"><i class="category-color27"><i class="sub-color">00:00</i>イベント名</i></a></li>
			</ul>
		</li>
		<li>
			<a href="#">緑系</a>
			<ul>
				<li><a href="#" data-id="5" data-name="緑-A"><i class="category-color5"><i class="sub-color">00:00</i>イベント名</i></a></li>
				<li><a href="#" data-id="6" data-name="緑-B"><i class="category-color6"><i class="sub-color">00:00</i>イベント名</i></a></li>
				<li><a href="#" data-id="7" data-name="緑-C"><i class="category-color7"><i class="sub-color">00:00</i>イベント名</i></a></li>
				<li><a href="#" data-id="8" data-name="緑-D"><i class="category-color8"><i class="sub-color">00:00</i>イベント名</i></a></li>
				<li><a href="#" data-id="21" data-name="緑-E"><i class="category-color21"><i class="sub-color">00:00</i>イベント名</i></a></li>
				<li><a href="#" data-id="22" data-name="緑-F"><i class="category-color22"><i class="sub-color">00:00</i>イベント名</i></a></li>
				<li><a href="#" data-id="23" data-name="緑-G"><i class="category-color23"><i class="sub-color">00:00</i>イベント名</i></a></li>
				<li><a href="#" data-id="24" data-name="緑-H"><i class="category-color24"><i class="sub-color">00:00</i>イベント名</i></a></li>
			</ul>
		</li>
		<li><a href="#" data-id="cancel">デフォルト</li>
	</ul>
</div>
