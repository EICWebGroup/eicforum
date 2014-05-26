<style>
    .nine.columns.wmd-title{
        margin-left:0;
    }
    .error-message{
        list-style-type:disc;
        margin-left:20px;
        color:red;
    }
</style>
<h3><i class="fa fa-rss" style="color:orange;"></i> RSSの編集</h3>
<div style="height:20px;width:1px;"></div>
<p>RSSって知っていますか？ブログや記事などのコンテンツをリンク先まで行かずフォーラム内で表示できる技術のことですよ～</p>
<form action="<?= $_SERVER["REQUEST_URI"]?>" method="post">
    <?php if(!empty($error_message)): ?>
    <ul class="error-message">
        <?= $error_message ?>
    </ul>
    <?php endif; ?>
    <div class="clearfix">
        <noscript>URL</noscript><input type="text" name="url" class="nine columns wmd-title" placeholder="URL" value="<?= $url?>">
        <noscript>タイトル</noscript><input type="text" name="title" class="nine columns wmd-title" placeholder="タイトル" value="<?= $title?>">
    </div>
    <div style="height:30px;width:1px"></div>


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


    <input type="submit" value="編集" class="wmd-submit">
</form>