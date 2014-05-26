<h3>スレッド新規</h3>
<div style="height:20px;width:1px;"></div>
<p>携帯の場合はシンプルテキストボックスになっているが、PCの場合はリーチテキストボックス。詳しくは<a href="/help/#help_textbox">こちら</a>。</p>
<form id="editor-form" action="<?= $_SERVER["REQUEST_URI"]?> " method="post">

	<?php if(!empty($error_message)): ?>
		<p style="color:red;"><?= $error_message?></p>
	<?php endif; ?>

	<input type="text" name="title" class="nine columns wmd-title" id="wmd-title" placeholder="タイトル" value="<?= $textarea_title ?>">

	<?php if(!$isMobile):
		// if it is pc, include rich-text-editor
		include "rich-text-editor.php"; ?>
	<?php else:?>
		<textarea name='content' class='nine columns' id='wmd-input'><?= $textarea_content; ?></textarea>
	<?php endif?>

	<div style="height: 20px;width:1px;"></div>

	<div class="nine columns">
		<h4>スレッドの公開権限設定</h4>

		<input type="radio" name="permission" value="<?=Thread::$PERMISSIONS[0]?>">
			 <?= $_SESSION[KEY_SESSION][Account::KEY_CAMPUS] ?>キャンパスだけに公開です。 <br/>
		<input type="radio" name="permission" value="<?=Thread::$PERMISSIONS[1]?>" checked>
			 野田にも葛飾にも公開しちゃいます、いわゆるパブリックスレッドです。<br/>
		<div style="height: 20px;width:1px;"></div>

	</div>


	<input class='wmd-submit' id='request_submit' type='submit' value='送信'>

</form>