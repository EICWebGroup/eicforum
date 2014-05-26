<script type="text/javascript">
$(function(){
	$('a[href*=#]').click(function() {
		var $target = $(this.hash);
		$target = $target.length && $target || $('[name=' + this.hash.slice(1) +']');
		if ($target.length) {
			var targetOffset = $target.offset().top;
			$('html,body').animate({scrollTop: targetOffset}, 1500);
			return false;
		}
	});
});

</script>
<style>
	#help_container a{
		text-decoration:none;
	}
	#help_container img{
		max-width:75%;
	}
	.bracket{
		width: 300px;
		padding: 10px;
		margin: 5px;
		border: 1px solid #c0c0c0;
	}
</style>
<div class="container wrapper">	
	<div class="fifteen columns">
		<div id="help_container">
			<div style="margin-top:50px;">

				<h2 id="help_title">掲示板手引き</h2>
				<ul>
					<li><a id="li_intro" href="#help_intro">掲示板の紹介</a></li>
					<li><a id="li_quest" href="#help_ques">質問したいです！</a></li>
                    <li><a id="li_textbox" href="#help_textbox">テキストボックス！</a></li>
					<li><a id="li_ans" href="#help_answer">解答したいです！</a></li>
					<!--<li><a id="li_ inform" href="#help_inform">お知らせをアップロード</a></li>-->
					<li><a href="#help_profile">プロフィールに関して</a></li>
					<li><a href="#help_security">セキュリティに関して</a></li>
					<li><a href="#https">HTTPSに関して</a></li>
					<li><a href="#help_developer">このサイトへ提案したいです！</a></li>
				</ul>
			</div>


			<div style="margin-top:50px">
				<a id="help_intro"  style="padding-bottom:5px;"><h3>掲示板の紹介</h3></a>
				<br>
				<p> このサイトはEIC内部向けの掲示板です。みなさんが工作の時に難関にぶつかったことがあるでしょう。この時に、誰かに聞けたらいいな！って思うことはあると思います。なので、みなさんが困った時に質問のできる場所をEICの先輩たちが頑張って作りました。つまり、ここはみなさんが質問しやすい環境が整えた掲示板です。</p>
				<br>
				<p> 自分で頑張って頭脳を絞り出しても解決方法が見つからない時はどうぞ、気楽に質問してください。できる限り質問に応答いたします。</p>
				<br>
				<p> また、この掲示板に載せていることはEICのみなさんに見てもらう権力を与えているので、技術力や知識などの共有しているということになります。なので、先輩たちにもこの掲示板からなにかメリットが得られるといいなと私は思っています。</p>
				<br>
				<p> 最後に、この掲示板はみんなの掲示板ですから、もしなにかこのサイトをよくするの提案があれば、<a href="#help_developer">このリング</a>を覗いてみてください。</p>
				<br><br><br><br>
				<a id="help_ques" href="#help_title"><h3>質問したいです</h3></a>
				<br>まず、左上の電気工学研究会掲示板のロゴを押して、ホームページに戻ります。

                <br />
                <br />
                <img src="/images/help/help1.png" />
                <br />
                <br />
                ホームページに戻ったら図に書いてある通り、「質問したい！！」というボタンはあるので、それを押してください。
                <br />
                <br />
                そして、次のような画面が表示します。
                <br /><br />
                <img src="/images/help/help2.png" />
                <br /><br />
                画面中のテキストボックスに質問したいことを書けばよいです。最後に、ツールバーにあるツールをぜひぜひ使ってください。よりわかりやすく、きれいな質問を作ると見てる側も気持ちよく問題を理解できるし、より正確な解答をしてくれますよ。
                <br /><br /><br /><br />

                <a id="help_textbox" href="#help_title"><h3>テキストボックス</h3></a>
				<br>
				<p>テキストボックスは符号を使って文字を飾っていますが、もしツールバー以外の機能を利用したい場合はHTMLを使って書くことも可能です。</p>
                <br />
                まず、符号について説明します。「 **」「** 」を入力し、その間に文字を入力してみます。図のようにテキストボックスには変化はありませんが、その次の枠を見てみると「太文」は太文化されていますよね。
                <br /><br />
                <img src="/images/help/help3.png" />
                <br />
                また、斜体にしたい文字を選択し、ツールボックスのボタン「斜体」アイコンを押してみると、自動に符号に変換してくれます。
                <br /><br />
                <img src="/images/help/help4.png" />
                <br /><br />
                次の一覧でツールの符号を紹介します。
                <ul>
                    <li> **太文** </li>
                    <li> --太文-- </li>
                    <li> [ URL リング := 表示名 ]　、例）[ http://www.example.com := example ]</li>
                    <li> 行の最初を4つ空白しておくとその行はソースコードとして表示になります。</li>
                    <li> $$画像URLリング$$ 、例） $$http://www.tus.ac.jp/img/header/logo.gif$$ </li>
                    <li> 行の最初に空白2つを置き、数字0～9までを入力、最後に[. ]点と空白すると、段落番号になります。　例）  1. 一番目</li>
                    <li> 行の最初に空白2つを置き、符号-を入力、最後に1つ空白を置くと、箇条書きになります。　例）  - 一番目</li>
                    <li> ~~見出し~~ 　　HTMLがわかる人へ、見出しは1種しかありません。</li>
                    <li> --------- 　横線を引きます </li>

                </ul>
                <br />
                ツールバーの符号は以上となります。キーボードから符号などを入力してみますが、「変換はしないなあ～」と思った際に文字を選択しツールバーのアイコンを押してみてください。もしこれでも文字は変換しない際に<a href="#help_developer">私</a>にメールしてください。
                <br /><br />
                注）ソースコードの枠の中に基本的に文字のスタイルを変換することができません。つまり、文字を太くしたり、斜めしたりすることができません。
                <br /><br /><br />
                次の符号はHTML式へ変換です。
                <br />
                <img src="/images/help/help5.png" style="width:auto" />
                <br />
                HTMLの書き式はまずツールバーの機能が使えません。そして、使えるタグは&lt;strong&gt;,&lt;img&gt;,&lt;em&gt;,&lt;a&gt;,&lt;pre&gt;,&lt;scan&gt; 以上となります。それ以外のタグを使うとフィルターによって消されたり、文字化けしたりする可能性があります。

                <br><br><br><br>

                <!-- 解答したいです〜　-->
                <!-- ＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝ -->
				<a id="help_answer" href="#help_title"><h3>解答したいです</h3></a>
                <br />
                まず、左上の「掲示板ロゴ」を押して、ホームページに戻ります。
                <br /><br />
                <img src="/images/help/help10.png" />
                <br /><br />
                気になるリングにアクセスします。
                <br /><br />
                アクセスした質問の一番したにスクロールすると、次のような画面が見えます。
                <br /><br />
                <img src="/images/help/help11.png" />
                <br /><br />
                テキストボックスに入力し、解答します。テキストボックスに関する説明はここを<a href="#help_textbox">クリック</a>してください。

				<br><br><br><br>

				<!-- プロフィールに関して　-->
                <!-- ＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝ -->
				<a id="help_profile" href="#help_title"><h3>プロフィールに関して</h3></a>
                <br />
                まず、左上のプロフィールを押してください。そして、次のような画面がでます。
                <br />
                <img src="/images/help/help6.png" />
                <br /><br />
                プロフィールの情報を変更したい場合は図に示されている「プロフィール変更」ボタンを押してください。そして、次のような画面が出ます。
                <br />
                <img src="/images/help/help7.png" />
                <br /><br />
                図に書いてある通り、記入しなければいけない項目はユーザー名のみです。理由としては自分は「誰ですか？」とみんながわかるようなコミュニティを作りたいからです。ほかの項目したくない場合は項目を空白のままにしてください。
                <br /><br />
                変更が終わりましたら下にある決定をおしてください。
                <br /><br />
                以上プロフィールの変更となります。
				<br />

				<br>
				<p>このサイトはとても固いセキュリティが整備されてい	ないため、なるべく漏らされても構わないような情報を提供してください。もしも、情報を漏らした場合は私、一切責任取りませんのでご了承ください。</p>
				<br><br><br><br>


				<!-- セキュリティーに関して　-->
                <!-- ＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝ -->
				<a id="help_security"  href="#help_title"><h3>セキュリティーに関して</h3></a>
				<br>
                <p>セキュリティーの変更についてはまず一番上のところの「プロフィール」を押してください。そして、つぎのような画面が表示します。</p>
                <img src="/images/help/help8.png" />
                <br /><br />
                図に示している「セキュリティー設定」のボタンを押すと、次のような画面が表示します。
                <br /><br />
                <img src="/images/help/help9.png" />
                <br /><br />
                ここでログインの時のパスワードやログイン名などを変更できます。

                <br /><br />

                <p><a href="#help_profile" href="#help_title">プロフィールのとき</a>にも話しましたが、
				このサイトはとても固いセキュリティが整備されていないため、なるべく漏らされても構わないような情報を提供してください。もしも、情報を漏らした場合は私、一切責任取りませんのでご了承ください。</p>
				<br>
				<p>また、このサイトはEIC外部に公開しないため、アカウントを作成するところがありません。すべてのアカウントは私より発行させていただきます。</p>
				<br>
				<p>万が一、パスワードを忘れた場合は私のところへ連絡してください。
				<a href="#help_developer">gmailメール</a>でも構いません。</p>
				<br><br><br><br>

				
                <!-- HTTPSに関して　-->
                <!-- ＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝ -->
                <a id="https" href="#help_title">
                	<h3>HTTPSに関して</h3>
                </a>
                <p>5月９日でHTTPは自動的にHTTPSにリダイレクトするようにしました。</p>
                <p>5月１７日でガラケーのバグ問題が発見しました。掲示板全体の文字コードはUTF-8になっていますが、<strong>ガラケーはHTTPSを使う場合の文字コードがshift-jisでなければ行けません。</strong></p>
                <p>対策法としてHTTPとHTTPSはマニュアル切り替えにしました。	</p>
                <p>HTTPSを利用したい人はURLの所にhttpをhttpsに書き換えれば良いです。</p>
                <img src="/images/help/help12.png">
                <br><br><br><br>


				<a id="help_developer"  href="#help_title"><h3> このサイトへ提案したい人</h3></a>
				<br>
				<p> まず、今のところはこのサイトを開発した人は私、3年電電ヘン・リーウィー,一人です。でも、もし今後、ウェブサイトをやりたい人がいたらぜひ気軽に私のところまで連絡してください。
				また、もし掲示板に○○の機能があったらいいなって思っている人がいたら同じく私のところへ連絡してください。もし時間などありましたら提案を採用しいくつの機能を追加するかもしれません。</p>
				<br>
				<p class="bracket"> ヘン　リーウィー　<br> edisonthk@gmail.com　<br><br> 日本語で一応コミュニケーションが取れるので、英語が話せなくても構いません。
				<br>むしろ日本語で話しかけてください。</p>
				<br>
				<p> もし質問などがあればまず気軽にメールをよろしくお願いします。ただし、スパムしたい方は上記のメールをやめてください。私は怒りますから。</p>
				<br><br><br><br>
			</div>
		</div>
	</div>
</div>