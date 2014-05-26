<style>
    .box{
        border:1px solid #CCC;
        width:350px;
        padding:15px;
    }
    .box p{font-weight:bold;}
</style>
<h3><i style="color:orangered;font-size:200%;" class="fa fa-warning"></i>&nbsp;TUSメールにて認証を行ってください！</h3>
<p>アカウントの作成はまだ完了していません。最後にTUSメールの認証を行えば完了になります。</p>
<?php if(!empty($noticemessage)) echo $noticemessage; ?>
<p>認証メールは５分以内に届くので、もし届いていなかったら<a href="/account/verifyplease?request=resend&accountid=<?= $accountId ?>">再送信ボタン</a>を押してください。</p>
<br/>
<div class="box">
    <p><img src="/images/deepsoft_favicon.png">&nbsp;TUSメールのリンク</p>
    <a href="https://mail.ed.tus.ac.jp/cgi-bin/index.cgi">https://mail.ed.tus.ac.jp/cgi-bin/index.cgi</a>
</div>
