<div class="row">
	<div class="span12">
		<h1 style="margin: 20px 0;">ユーザ一覧</h1>
		<table class="table table-striped table-bordered table-condensed">
			<thead>
				<tr>
					<th>ID</th>
					<th>名前</th>
					<th>権限</th>
					<th>最終ログイン日</th>
					<th>コンソール</th>
				</tr>
			</thead>
			<tbody>
			<?php if(isset($users) && is_array($users)){ ?>
				<?php foreach($users as $user){ ?>
				<tr>
					<td><?php echo $user['id']; ?></td>
					<td><?php echo $user['username'] ?></td>
					<?php $group = intval($user['group']); ?>
					<td><?php echo $group === 100 ? 'スーパーユーザー' : ( $group === 99 ? '管理者' : '一般'); ?></td>
					<td><?php echo date('Y/m/d H:i:s', $user['last_login']); ?></td>
					<td>
						<a class="btn btn-mini" href="/summary/user/<?php echo $user['id']; ?>">サマリーを見る</a>
						<?php if($group < 100){ ?>
						<a class="btn btn-mini" href="/user/permission/<?php echo $user['id']; ?>">
							<?php echo $group === 99 ? '一般ユーザーに戻す' : '管理者にする'; ?>
						</a>
						<?php } ?>
					</td>
				</tr>
				<?php } ?>
			<?php } ?>
			</tbody>
		</table>
		<div class="pagination pagination-centered">
			<ul>
				<?php echo Pagination::prev_link('前へ'); ?>
				<?php echo Pagination::page_links(); ?>
				<?php echo Pagination::next_link('次へ'); ?>
			</ul>
		</div>
	</div>
</div>