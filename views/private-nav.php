<style type="text/css">
	.top-nav{
		position:absolute;
		top:60px;
		left:0;
		background: #e3e3e3;
		width:100%;
		height:40px;
	}
	.top-nav-content{
		height:40px;
	}
	.vertical-offset{
		width:1px;
		height:30px;
	}
	.top-nav-offset{
		display:block;
		position:relative;
		width:100%;
		height:100px;
		z-index:-1;
	}
	.left{
		float:left;
	}
	.right{
		float:right;
	}
	.nav-logo{
		display:block;
		height:40px;
	}
	.nav-item{
		color:black;
		height:40px;
		text-align:center;
		vertical-align:middle;
		line-height:40px;
		padding-right:10px;
		padding-left:10px;
	}
	.nav-item:hover{
		background-color:#d0d0d0;
	}

	.nav-item i{
		color:black;
		font-size:130%;
		padding-right:2px;
	}
    .logo-nav{
        position:absolute;
	top:0;
	left:0;
        width:100%;
        height:60px;
    }
    .logo{
        text-decoration: none;
        vertical-align: middle;
        font-size:30px;;
    }
</style>
<div class="logo-nav">
    <div class="container" style="vertical-align:middle;line-height: 60px;">
        <div class="fifteen columns">
            <a class="logo" href="/thread/"><strong style="color:rgb(61,132,239);font-weight: bolder;">EIC&nbsp;</strong><span style="color:black;">掲示板</span></a>
        </div>
    </div>
</div>
<div class="top-nav">
	<div class="container top-nav-content">
		<div class="fifteen columns">

			<div class="clearfix" style="height:100%;">

				<a class="anchor nav-item right" href="/account/signout"><i class="fa fa-sign-out"></i><span class="box-text">サインアウト</span></a>
				<a class="anchor nav-item right" href="/account/"><i class="fa fa-user"></i><span class="box-text">アカウント</span></a>
				<a class="anchor nav-item right" href="/help/"><i class="fa fa-question-circle"></i><span class="box-text">手引き</span></a>
   				<!-- <a class="anchor nav-item right nav-item-create-thread" href="/thread/create/"><i class="fa fa-comments-o nav-item-create-thread"></i><span class="box-text"></span></a> -->
                <?php if($_SESSION[KEY_SESSION][Account::KEY_ADMIN] == "1"): ?>
                    <a class="anchor nav-item right" href="/manager/googleanalytics"><i class="fa fa-gears"></i><span class="box-text">管理者</span></a>
                <?php endif; ?>

				<a class="anchor nav-item right garake-item" href="/thread/create"><i class="fa fa-comments-o"></i><span class="box-text">質問したい！</span></a>
                <a class="anchor nav-item right garake-item" href="/thread/createrss"><i class="fa fa-rss"></i><span class="box-text">RSS</span></a>

			</div>

		</div>
	</div>
</div>
<div class="top-nav-offset">&nbsp;</div>
