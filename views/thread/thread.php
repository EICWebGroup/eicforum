<style>
	.anchor-block{
		font-size:135%;
		background-color:#e3e3e3;
		height:1.5em;
		line-height:1.5em;
		width:calc(100% - 20px);
		padding:10px;
		display:block;
		text-align:center;
		vertical-align:middle;
	}
	.create-thread{
		font-weight:bolder;
		color:rgb(173,41,98);
	}
	.anchor-block i{
		font-size:180%;
	}

	.notice-block{
		background-color:#e3e3e3;
		padding:10px;
	}
	.notice-block h5{
		color:rgb(136,68,0);
		font-weight:bold;
		font-size:135%;
	}
	.notice-block .font-tiny{
		font-size:75%;
	}

	.offset-block{
		width:100%;
		height:25px;
	}
	.onedrive-block{
		padding:10px;
	}
	.onedrive-block img{
		width:calc(100% - 20px);
	}
	.item-title{
		font-size:120%;
	}
	.item-descriptor{
		float:right;
		font-size:90%;
	}
</style>
<div class="container wrapper">
	<div class="fifteen columns">

		<div class="vertical-offset"></div>
		<div class="ten columns">
			<!-- include content here. For example, include create.php , show.php or list.php here. -->
			<?php include $content; ?>
		</div>

		<?php if(!Utils::is_mobile()): ?>
		<div class="four columns side-column">

			<a href="/account/" class="anchor-block anchor"><i class="fa fa-user"></i> <?= $_SESSION[KEY_SESSION][Account::KEY_NICKNAME] ?></a>

			<div class="offset-block"></div>

			<a href="/thread/create" class="create-thread anchor-block anchor"><i class="fa fa-comments-o"></i>質問したい！</a>

			<div class="offset-block"></div>

            <a href="/thread/createrss" class="create-thread anchor-block anchor"><i class="fa fa-rss" style="color:orange;"></i><span style="color:orange;">RSS</span></a>

			<div class="offset-block"></div>

			<a href="https://skydrive.live.com/" class="onedrive-block anchor-block anchor"><img src="/images/Logo_OneDrive.png"></img></a>

			<div class="offset-block"></div>
			<div class="notice-block">
				<h5><i class="fa fa-bookmark-o"></i>お知らせ</h5>
				<br/>
				<?php foreach(NoticeBoard::AllChecked() as $notice): ?>
					<p><span class="font-tiny"><?php echo $notice->getUpdateDate(); ?> </span><br/> <?= $notice->getContent() ?></p>
				<?php endforeach; ?>
			</div>
			<div class="offset-block"></div>
		</div>
		<?php endif; ?>
	</div>
</div><!-- container -->
