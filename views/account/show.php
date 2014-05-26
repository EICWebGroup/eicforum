<style>	
	.container.wrapper hr{
		border-width:2px;
	}
	.container.wrapper .title{
		font-size:90%;
	}
	.container.wrapper .value{
		font-size:125%;
	}
</style>
<div class="container wrapper">
	<div style="width:100%;height:15px;"></div>	
	<div class="side-menu one-third column">
		<?php			
			$nav_passwordedit = "";
			$nav_profileedit = "";
			$nav_show = "current-tab";
			include "nav.php"; 
		?>
	</div>
	<div class="two-thirds column">
		<div>				
			<p>
				<span class="title">名前</span><br/>
				<span class="value"><?php echo $account->getNickname(); ?></span>
			</p>
			<?php if($account->getPosition() != "" && $account->getPosition() != NULL): ?>
				 <p><span class="title">役職</span><br/><span class="value"><?= $account->getPosition()?></span>	</p>
			<?php endif; ?>				
			<p>
				<span class="title">学年</span><br/>
				<span class="value"><?php echo $account->getGrade(); ?></span>
			</p>
			<p>
				<span class="title">校舎</span><br/>
				<span class="value"><?php echo $account->getCampus(); ?>キャンパス</span>
			</p>
			<p>
				<span class="title">学科</span><br/>
				<span class="value"><?php echo $account->getDepartment(); ?></span>
			</p>
			<p>
				<span class="title">専門（自慢の分野）</span><br/>
				<span class="value"><?php echo $account->getMajor(); ?></span>
			</p>		
			<hr />			
			<p>
				<span class="title">メールアドレス</span><br/>
				<?php if($account->getMail() == "" ): ?>
					<span class="value">（空白）</span>
				<?php else: ?>
					<span class="value"><a class="anchor" href="mailto:<?= $account->getMail() ?>"><?= $account->getMail() ?></a></span>									
				<?php endif; ?>
			</p>
			<p>
				<span class="title">ブログ</span><br/>
				<?php if($account->getBlog() == "" ): ?>
					<span class="value">（空白）</span>
				<?php else: ?>
					<span class="value"><a class="anchor" href="<?= $account->getBlog() ?>"><?= $account->getBlog() ?></a></span>									
				<?php endif; ?>
			</p>
			<p>
				<span class="title">その他のリンク</span><br/>
				<?php if($account->getOtherLink() == "" ): ?>
					<span class="value">（空白）</span>
				<?php else: ?>
					<span class="value"><a class="anchor" href="<?= $account->getOtherLink() ?>"><?= $account->getOtherLink() ?></a></span>									
				<?php endif; ?>
				
			</p>
			<p>
				<span class="title">自己紹介</span><br />
				<span class="value"><?= $account->getComment() ?></span>
			</p>
		</div>
	</div>

</div>
