<?php
	$grade_list = array(
		'学部1年生',
		'学部2年生',
		'学部3年生',
		'学部4年生',
		'修士1年生',
		'修士2年生'
	);
	
	$department_list = array(
		'電気電子情報工学科',
		'物理学科',
		'情報科学科',
		'土木工学科',
		'建築学科',
		'数学科',
		'工業化学科',
		'機械工学科',
		'経営工学科',
		'応用物理学科',
		'電気工学科',
		'その他'
	);
?>
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
			$nav_passwordedit = "";
			$nav_profileedit = "current-tab";
			$nav_show = "";
			include "nav.php"; 
		?>
	</div>
	<div class="two-thirds column">
		<div>
			<form action="<?= $_SERVER["REQUEST_URI"] ?>" method="post">
			
				<!-- show updated status message -->
				<?php if(!empty($updated_message)): ?>						
					<p class="<?php if(!$success) echo "err"; else echo "positive"; ?>"><?= $updated_message ?></p>
				<?php endif; ?>
				
				
				<input type="text" name="action" value="edit_account" style="display:none">
				<div>			
					<!-- 名前（ニックネーム）　-->	
					<p>
						<?php if(!empty($nickname_error)): ?>
							<span class="err"><?= $nickname_error ?></span><br/>
						<?php endif; ?>
						<span class="title">名前　（ログイン名と別に、フォーラムの表示名になります）</span><br/>
						<input type="text" name="nickname" value="<?= $nickname ?>" >
					</p>
					
					<!-- 役職　-->
					<?php if($account->isAdmin()): ?>
						 <p><span class="title">役職 (管理者のみ編集可能、空白可)</span><br/>
						 	<input name="position" type="text" name="position" value="<?= $position ?>" >	
						 </p>
					<?php endif; ?>				
					<p>
						<!-- 学年　-->
						<span class="title">学年</span><br/>
						<select name="grade">
							<?php foreach($grade_list as $grade_item): ?>
								<?php if($grade_item == $grade): ?>
									<option selected><?= $grade_item?></option>
								<?php else: ?>
									<option><?= $grade_item?></option>
								<?php endif; ?>									
							<?php endforeach; ?>
						</select>							
					</p>
					
					<!-- 校舎　-->
					<p>
						<span class="title">校舎  （編集不可）</span><br/>
						<span><?php echo $account->getCampus(); ?>キャンパス</span>
					</p>
					
					<!-- 学科　-->
					<p>
						<span class="title">学科</span><br/>
						<select name="department">
							<?php foreach($department_list as $department_item): ?>
								<?php if($department_item == $department): ?>
									<option selected><?= $department_item?></option>
								<?php else: ?>
									<option><?= $department_item?></option>
								<?php endif; ?>									
							<?php endforeach; ?>
						</select>
					</p>
					
					<!-- 専門　-->
					<p>
						<span class="title">専門（自慢の分野）</span><br/>
						<input type="text" name="major" value="<?= $major ?>">
					</p>		
					<hr style="height:5px;color:#ddd;background-color:#ddd;position:absolute;width:100%;"/>		
					<br/><br/>	
					
					<!-- メールアドレス　-->	
					<p>
						<span class="title">メールアドレス</span><br/>
						<input type="text" name="mail" value="<?= $mail ?>">
					</p>
					
					<!-- ブログ　-->
					<p>
						<span class="title">ブログ</span><br/>
						<input type="text" name="blog" value="<?= $blog ?>">
					</p>
					
					<!-- その他のリンク　-->
					<p>
						<span class="title">その他のリンク</span><br/>
						<input type="text" name="others_link" value="<?= $other_link ?>" >
					</p>
					
					<!-- 自己紹介　-->
					<p>
						<span class="title">自己紹介</span>												
						<textarea name="comment" style="width:300px;"><?= $comment ?></textarea>								
					</p>	
				</div>
				
				<!-- submit button -->
				<input type="submit" value="更新">
			</form>
		</div>
	</div>
</div>