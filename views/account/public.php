<style>
    .wrapper{
        min-height: calc(100% - 120px);
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
    h3{
        margin-top:30px;
        margin-bottom:30px;
    }
    input[type="text"],input[type="password"]{
        padding:5px;
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
    .error{
        list-style-type: disc;
        color:red;
        margin-left: 30px;
    }
</style>
<div class="logo-nav">
    <div class="container" style="vertical-align:middle;line-height: 60px;">
        <div class="fifteen columns">
            <a class="logo" href="/thread/"><strong style="color:rgb(61,132,239);font-weight: bolder;">EIC&nbsp;</strong><span style="color:black;">掲示板</span></a>
            <hr/>
        </div>
    </div>
</div>
<div style="width:1px;height:60px;"></div>
<!-- body begin from here -->
<div class="wrapper">
	<div class="container">
        <?php include $content; ?>
    </div>
</div>
<!-- body end here -->
