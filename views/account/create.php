<h3>アカウント新規</h3>
<p>以下の項目をすべて記入してください。</p>
<?php if(!empty($error_message)): ?>
<ul class="error">
    <?= $error_message; ?>
</ul>
<?php endif; ?>
<form action="<?= $_SERVER["REQUEST_URI"]?>" class="acc_auth_form" method="post">
    <input id="createacc_studentid" type="text" name="studentid" placeholder="学籍番号" value="<?= $studentId ?>">
    <input id="createacc_username" type="text" name="username" placeholder="ログイン名" value="<?= $username ?>">
    <input id="createacc_password" type="password" name="password" placeholder="パスワード" value="<?= $password ?>">
    <input id="createacc_repeat_password" type="password" name="repeat_password" placeholder="パスワード（再確認）" value="">
    <input id="createacc_nickname" type="text" name="nickname" placeholder="名前 (表示名)" value="<?= $nickname?>">
    <input class="gray-button" type="submit" value="アカウント作成">
</form>