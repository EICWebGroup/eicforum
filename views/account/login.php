<style>
	.wrapper{
		min-height:100%;
		min-width:100%;
		position:relative;
		background-color:#000;
		/* background-image:url("/images/b1.jpg"); */
		background-repeat:no-repeat;
		background-size:cover;
		display:block;
	}
	.login-box{
		background-color:rgba(85,0,0,0.85);
		padding:20px;
	}
	.login-text{
		padding:10px;
		font-size:100%;
	}
	.gray-button{
		color: white;
		padding-top: 3px;
		font-family: 'ヒラギノ角ゴ Pro W3','Hiragino Kaku Gothic Pro','メイリオ',Meiryo,sans-serif;
		font-size: 110%;
		text-align: center;
		width: 160px;
		background-color: #505050;
		border: 0px;
		cursor: pointer;
	}
	.login-box a{
		padding:5px;
	}
	.h1-index-page{ }
	.hide{ display:none !important;}

</style>
<div class="wrapper">
	<div class="container">
		<div class="sixteen columns">
			<h1 class="h1-index-page remove-bottom" style="margin-top: 40px">東京理科大学<span style="color:rgb(61,132,239);font-weight:bold">EIC</span>掲示板</h1>
			<h5 class="h5-index-page">Forum of Electrical Industrial Community</h5>
			<hr />
		</div>
		<div class="sixteen columns" style="height:55px;"></div>
		<div class="clearfix">
			<div class="login-box-position">
				<div class="login-box">
                    <form action="/account/login/?request_page=<?= $_GET["request_page"] ?>" method="post">
						<?php
							$usernameShow = "";
							if(!empty($_POST) || !empty($_COOKIE["k"])){
								if(!$login){
									if(!empty($_COOKIE["k"])){
										echo "<p style='color:red;'>自動ログインにエラーが発生しました。再ログインしてください。</p>";
									}else{
										echo "<p style='color:red;'>パスワードもしくはIDが間違えました。</p>";
									}
									$usernameShow = $_POST["username"];
								}
							}
						?>
						<noscript><span style="color:white;">ユーザ名</span></noscript><input type="text" name="username" id="username" class="login-text" value="<?php echo $usernameShow; ?>" placeholder="ログイン名"></input>
						<noscript><span style="color:white;">パスワード</span></noscript><input type="password" name="password" id="password" class="login-text" placeholder="パスワード"></input>
						<input type="checkbox" name="rememberme" style="width:auto"><span style="color:#d0d0d0;"class="login-text">自動ログイン</span><br/>
                        <a style="color:#d0d0d0;" href="/account/forgetpassword">パスワード忘れました</a><br/>
                        <a style="color:#d0d0d0;" href="/account/create">アカウント新規</a>
						<div style="height:10px;"></div>
						<input type="submit" value="ログイン" class="gray-button" id="front_page_submit_button" style="text-decoration:none;margin-left:0px" ></input><br/><br/>
						<!-- <a class="gray-button" style="text-decoration:none;margin-left:0px" href="/account/create">アカウント新規</a> -->
					</form>
				</div>

			</div>
		</div>
		<div class="sixteen columns" style="height:75px;"></div>
	</div>
</div>
<script>
	var bgImg = new Image();
	bgImg.onload = function(){
		console.log("hello");
		$(".wrapper").fadeIn("slow");
	   document.getElementsByClassName("wrapper")[0].style.backgroundImage = 'url(' + bgImg.src + ')';
	};
	bgImg.src = "/images/b1.jpg";
	
</script>