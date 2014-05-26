<style>	
	.side-menu a{
		color:#222;			
	}
	.side-menu .title{
		font-size:90%;
	}
	.side-menu .value{
		font-size:125%;
	}		
	input[type="text"],textarea,select,input[type="password"],input[type="submit"]{
		padding:5px;
	}
	input[type="submit"]{
		width:80px;
	}
</style>
<div class="side-menu">
    <div class="clearfix">
        <ul>
            <?php if($owner_profile): ?>
                    <li class="<?= $nav_show ?>"><a href="/account/"><i class="fa fa-user"><span class="box-text">プロフィール</span></i></a></li>
                    <li class="<?= $nav_profileedit ?>"><a href="/account/edit/"><i class="fa fa-wrench"><span class="box-text">プロフィール設定</span></i></a></li>
                    <li class="<?= $nav_passwordedit ?>"><a href="/account/passwordedit/"><i class="fa fa-unlock-alt"><span class="box-text">セキュリティー設定</span></i></a></li>
            <?php else: ?>
                    <li><i class="fa fa-user fa-lg"><span class="box-text">プロフィール</span></i></li>
            <?php endif; ?>
        </ul>			
    </div>
</div>	
<!--
<style>	
	.tab a{
		height:39px;
		line-height:39px;
		display:block;
		width:70px;
		float:left;
		text-align:center;
		text-decoration:none;
	}
	.tab .current-tab{
		border:1px solid #ddd;
		border-bottom: 1px solid #f5f5f0;
	}
</style>
<div class="clearfix">
	<div class="tab" style="width:auto;height:40px;border-bottom:1px solid #ddd;">
		<div style="float:right;width:auto;">
			<?php if($owner_profile): ?>
				<a class="current-tab" href="/account/"><i class="fa fa-user"><span class="box-text">プロフィール</span></i></a>
				<a href="/account/edit/"><i class="fa fa-wrench"><span class="box-text">プロフィール設定</span></i></a>
				<a href="/account/passwordedit/"><i class="fa fa-unlock-alt"><span class="box-text">セキュリティー設定</span></i></a>
			<?php else: ?>
				<a class="current-tab" href="#"><i class="fa fa-user"><span class="box-text">プロフィール</span></i></a>
			<?php endif; ?>
		</div>
	</div>
</div> -->