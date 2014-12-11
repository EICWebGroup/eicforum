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
    <a href="https://login.microsoftonline.com/login.srf?wa=wsignin1.0&rpsnv=3&ct=1412850313&rver=6.1.6206.0&wp=MBI_SSL&wreply=https:%2F%2Foutlook.office365.com%2Fowa%2F%3Fexsvurl%3D1%26ll-cc%3D1041%26modurl%3D0%26realm%3Ded.tus.ac.jp&id=260563&whr=ed.tus.ac.jp&CBCXT=out">https://login.microsoftonline.com/login.srf?wa=wsignin1.0&amp;rpsnv=3&amp;ct=1412850313&amp;rver=6.1.6206.0&amp;wp=MBI_SSL&amp;wreply=https:%2F%2Foutlook.office365.com%2Fowa%2F%3Fexsvurl%3D1%26ll-cc%3D1041%26modurl%3D0%26realm%3Ded.tus.ac.jp&amp;id=260563&amp;whr=ed.tus.ac.jp&amp;CBCXT=out</a>
</div>
