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
<h3><i class="fa fa-rss" style="color:orange;"></i> RSS新規</h3>
<div style="height:20px;width:1px;"></div>
<p>RSSって知っていますか？ブログや記事などのコンテンツをリンク先まで行かずフォーラム内で表示できる技術のことですよ～</p>
<p>かなり複雑な技術で、わからない人は遠慮なく私に質問してください。私のGmailは<a href="mailto:edisonthk@gmail.com">edisonthk@gmail.com</a></p>
<form action="<?= $_SERVER["REQUEST_URI"]?>" method="post">
    <?php if(!empty($error_message)): ?>
    <ul class="error-message">
        <?= $error_message ?>
    </ul>
    <?php endif; ?>
    <div class="clearfix">
        <noscript>RSSのURL</noscript><input type="text" name="url" class="nine columns wmd-title" placeholder="RSSのURL" value="<?= $url?>">
        <noscript>記事やブログのタイトル</noscript><input type="text" name="title" class="nine columns wmd-title" placeholder="記事やブログのタイトル" value="<?= $title?>">
    </div>
    <div style="height:30px;width:1px"></div>


    <!-- スレッドの公開権限 -->
    <div class="nine columns">
        <h4>スレッドの公開権限設定</h4>

        <input type="radio" name="permission" value="<?=Thread::$PERMISSIONS[0]?>" >
             <?= $_SESSION[KEY_SESSION][Account::KEY_CAMPUS] ?>キャンパスだけに公開です。 <br/>
        <input type="radio" name="permission" value="<?=Thread::$PERMISSIONS[1]?>" checked>
             野田にも葛飾にも公開しちゃいます、いわゆるパブリックスレッドです。<br/>
        <div style="height: 20px;width:1px;"></div>

    </div>



    <input type="submit" value="新規" class="wmd-submit">
</form>