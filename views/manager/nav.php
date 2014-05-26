<style>
	.manager-nav{
		width:100%;
		height:40px;
	}
	.manager-nav-height{
		height:40px;
		line-height:40px;
	}
	.manager-nav-item{
		color:#f0f0f0;
		display:inline-block;
		text-align:center;
		vertical-align:middle;
		padding-right:10px;
		padding-left:10px;
	}
	.manager-nav-item:hover{
		background-color:#303030;
	}

	.manager-nav-item i{
		color:#f0f0f0;
		font-size:130%;
		padding-right:2px;
	}
	.selected{
		background-color:#e0e000;
	}
</style>
<div class="manager-nav">
	<div class="manager-nav-height">
		<div class="fifteen columns manager-nav-height">
                    <a class="manager-nav-menu <?php if($googleAnalytics) echo "selected"; ?>" href="/manager/googleanalytics">Google Analytics</a>
                    <!-- <a class="manager-nav-menu <?php if($loginrecord)echo "selected"; ?>" href="/manager/loginrecord"><span>ログイン記録</span></a> -->
                    <a class="manager-nav-menu <?php if($noticeboard)echo "selected"; ?>" href="/manager/noticeboard"><span>お知らせボード</span></a>
                    <a class="manager-nav-menu <?php if($permission)echo "selected"; ?>" href="/manager/permission"><span>スレの権限</span></a>
		</div>
	</div>
</div>
<div style="width:1px;height:25px;"></div>
