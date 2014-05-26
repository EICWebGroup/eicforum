<h3>パスワードをリセット</h3>
<h4><i style="color:green;" class="fa fa-check-circle"></i>TUSメールの認証が成功しました。</h4>
<p>次の項目を記入してパスワードをリセットしてください。</p>
<br/>
<form action="<?= $_SERVER["REQUEST_URI"]?>" method="post">
    <?php if(!empty($error_message)): ?>
        <ul class="error"><?= $error_message?></ul>
    <?php endif; ?>
    <input type="password" name="password" placeholder="パスワード" value="<?= $password?>">
    <input type="password" name="password-confirm" placeholder="再確認用パスワード">
    <br/>
    <input class="gray-button" type="submit" value="パスワードをリセット" class="">
</form>