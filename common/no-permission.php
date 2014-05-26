<div class="container wrapper">
	<div style="width:1px;height:80px;"></div>
	<h3>このページへアクセスする権限がありません。</h3>
    <?php if($_SESSION[KEY_SESSION][Account::KEY_USERNAME] == "guest"): ?>
        <p>ゲストアカウントはこのページへアクセスする権限はありませんのでご了承ください。</p>
    <?php else: ?>
        <p>ヘンなことをするとリーウィーは怒るよ。</p>
    <?php endif; ?>
	<a href="/thread/">もとに戻る</a>
</div>