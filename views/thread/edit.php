<?php

	if($isMobile){

//		if(Utils::foundUnavailableTag($textarea_content)){
//			include ROOT . "common/no-available-to-edit.php";
//			die();
//		}
		$textarea_content = preg_replace("/<br \/>|<br\/>|<br>/",'&#13;&#10;',$textarea_content);
	}


?>
<h3><i class="fa fa-quote-left"></i><?= $thread->getTitle()?><i class="fa fa-quote-right"></i>の編集</h3>
<div style="height:20px;width:1px;"></div>
<p>携帯の場合はシンプルテキストボックスになっているが、PCの場合はリーチテキストボックス。詳しくは<a href="/help/#help_textbox">こちら</a>。</p>
<?php if(!empty($error_message)): ?>
	<p style="color:red;"><?= $error_message?></p>
<?php endif; ?>
<form id="editor-form" action="<?= $_SERVER["REQUEST_URI"]?>" method="post">



	<input type="text" class="nine columns wmd-title" id="wmd-title" name="title" placeholder="タイトル" value="<?= $textarea_title ?>" <?php if($post_id != 1){ echo "readonly"; }?>>

	<?php if(!$isMobile):
		// if it is pc, include rich-text-editor
		include "rich-text-editor.php"; ?>
	<?php else:?>
		<textarea name='content' class='nine columns' id='wmd-input'><?= $textarea_content; ?></textarea>
	<?php endif?>
	<div style="height: 20px;width:1px;"></div>

	<!-- スレッドの公開権限 -->
	<?php if($post->isHost()): ?>
		<div class="nine columns">
			<h4>スレッドの公開権限設定</h4>

			<input type="radio" name="permission" value="<?=Thread::$PERMISSIONS[0]?>" <?php if(Thread::$PERMISSIONS[0] == $thread->getPermission()) echo"checked"; ?> >
				 <?= $_SESSION[KEY_SESSION][Account::KEY_CAMPUS] ?>キャンパスだけに公開です。 <br/>
			<input type="radio" name="permission" value="<?=Thread::$PERMISSIONS[1]?>" <?php if(Thread::$PERMISSIONS[1] == $thread->getPermission()) echo"checked"; ?> >
				 野田にも葛飾にも公開しちゃいます、いわゆるパブリックスレッドです。<br/>
			<div style="height: 20px;width:1px;"></div>

		</div>
	<?php endif; ?>

	<input class='wmd-submit' id='request_submit' type='submit' value='送信'>

</form>
