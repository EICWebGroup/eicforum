<h3>パスワードをリセット</h3>
<div class="password_generator_main_content">
    <p><i style="color:orangered;font-size:200%;" class="fa fa-warning"></i>&nbsp;学籍番号からユーザが見つかりませんでした。</p>
    <p>ログイン名を入力してください。</p>
    <div style="height:20px;"></div>
    <form action="<?= $_SERVER["REQUEST_URI"]?>" class="acc_auth_form" method="post">
        <input name="username" type="text" placeholder="ログイン名">
        <input name="username_studentid" type="text" style="display:none;" value="<?= $studentId?>">
        <input class="gray-button" type="submit" value="送信">
    </form>
    <div style="height:100px;width:20px"></div>
</div>
