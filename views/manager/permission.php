<style>
	#worktable table,#worktable td{
		border:1px solid black;
	}
	table, tbody, thead{
		border-collapse: separate;
	}
	.permission{
		width:50px;
		height:auto;
		background-color: green;
	}
	.positive{
		background-color: red !important;
	}
	hr{
		border-color:#222;
	}
</style>
<script type="text/javascript">
$(function(){

	$("#worktable table").delegate("td","click", function(){


		if($(this).attr("id") != null && $(this).attr("id").indexOf("permission_") >= 0){
			if($(this).attr("class").indexOf("positive") >= 0){
				// current cell is positive
				$(this).attr("class", "permission");
			}else{
				// current cell is negative
				$(this).attr("class","permission positive");
			}
		}

	});

});
</script>
<div class="container wrapper">
    <?php include "nav.php"; ?>
	<div class="fifteen columns">
		<noscript>このページはJavascriptの有効が必要です。</noscript>
		<div id="worktable">
		<table >
			<thead>
				<tr>
					<td>id</td>
					<td>タイトル</td>
					<td>スレのホスト</td>
					<td>Guest</td>
					<td>野田</td>
					<td>葛飾</td>
					<td>削除</td>
				</tr>
			</thead>
			<tbody>

			 	<?php foreach(Thread::all() as $thread): ?>
				<tr>
					<td><?= $thread->getId() ?></td>
					<td> <?php echo $thread->getTitle(); ?> </td>
					<td> <?php echo $thread->getHost()->getNickname(); ?></td>
					<?php
						$permissions = json_decode($thread->getPermission(), TRUE);
						$guest_permission = in_array("guest",$permissions);
						$noda_permission = in_array("野田",$permissions);
						$kk_permission = in_array("葛飾",$permissions);
						$deleted_permission = in_array("削除",$permissions);

					?>
					<td id="permission_guest_<?= $thread->getId()?>" class="permission <?php if($guest_permission) echo "positive"; ?>"></td>
					<td id="permission_noda_<?= $thread->getId()?>" class="permission <?php if($noda_permission) echo "positive"; ?>"></td>
					<td id="permission_kk_<?= $thread->getId()?>" class="permission <?php if($kk_permission) echo "positive"; ?>"></td>
					<td id="permission_deleted_<?= $thread->getId()?>" class="permission <?php if($deleted_permission) echo "positive"; ?>"></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div　>
			<div style="width:1px;height:40px;"></div>
			<hr />

			<form action="<?= $_SERVER["REQUEST_URI"]?>" method="post">
				<input type="submit" value="更新">
			</form>
		</div>
	</div>
	</div>
</div>
