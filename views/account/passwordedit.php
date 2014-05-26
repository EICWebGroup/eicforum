<style>
	.err{
		color:red;
	}
	.positive{
		color:green;
	}
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
			$nav_passwordedit = "current-tab";
			$nav_profileedit = "";
			$nav_show = "";
			include "nav.php";
		?>
	</div>
	<div class="two-thirds column">
		<div>
			<p style="margin-top:10px; margin-bottom:30px">* このサイトは重装備したセキュリティーシステムが整えていないので、なるべく他のアカウントと共通のパスワードを使わないようにお願いします。</p>

		   	<div style="width:1px;height:40px;"></div>

		   	<!-- show updated status message -->
		   	<?php if(!empty($updated_message) && $success): ?>
				<p class="positive"><?= $updated_message ?></p>
			<?php endif; ?>

			<form action="<?= $_SERVER["REQUEST_URI"] ?>" method="post">

				<input type="text" name="action" value="edit_password" style="display:none">

				<!-- old password -->
				<p>
					<?php if(!empty($updated_message1) && !$old_password_correct): ?>
						<span class="err"><?= $updated_message1 ?></span><br/>
					<?php endif; ?>
					<span class="title">旧パスワード</span><br/>
					<input type="password" name="old_password" value="<?= $old_password ?>" >
				</p>

				<!-- new password -->
				<p>
					<?php if(!empty($updated_message2) && !$new_password_correct): ?>
						<span class="err"><?= $updated_message2 ?></span><br/>
					<?php endif; ?>
					<span class="title">新しいパスワード</span><br/>
					<input type="password" name="new_password" value=<?= $new_password ?> >
				</p>

				<!-- new password reentry -->
				<p>
					<?php if(!empty($updated_message3) && !$new_password_reentry_correct): ?>
						<span class="err"><?= $updated_message3 ?></span><br/>
					<?php endif; ?>
					<span class="title">パスワード再入力</span><br/>
					<input type="password" name="new_password_reentry" value="<?= $new_password_reentry ?>" >
				</p>
				<input type="submit" value="更新">
			</form>
		</div>
	</div>
</div>
