<style>
	.confirm-form a:hover, .confirm-form input:hover{
		background-color: #ff8400;
	}
	.confirm-form a, .confirm-form input{
		background-color: #303030;
		margin: 2px;
		padding: 10px 0px;
		color: #f0f0f0;
		border: 0px;
		text-decoration: none;
		font-size: 100%;		
		text-align: center;
		width:100px;
		display:inline-block;
		line-height:80%;
	}	
	.hide{
		
		display:none !important;
	}
</style>
<div class="confirm-form">
	<h3>削除の確認</h3>
	<p>このスレを削除してしまうと元に戻せないので、削除しますか？</p>
	<a href="/thread/<?= $thread_id."/delete/".$post_id ?>/confirm/">削除</a>	
	<a href="/thread/<?=$thread_id ?>">取り消す</a>
</div>
	
	
