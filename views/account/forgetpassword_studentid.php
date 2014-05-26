<h3>パスワードをリセット</h3>
<div class="password_generator_main_content">
    <p>記入した学籍番号に基づいてTUSメールへパスワード再発行のメールを送ります。</p>
    <div style="height:20px;"></div>
    <?php if(!empty($error_message)): ?>
        <ul class="error"><?= $error_message ?> </ul>
    <?php endif; ?>
    <form action="<?= $_SERVER["REQUEST_URI"]?>" class="acc_auth_form" method="post">
        <input name="studentid" type="text" placeholder="学籍番号">
        <input class="gray-button" type="submit" value="送信">
    </form>
    <div style="height:100px;width:20px"></div>
</div>
