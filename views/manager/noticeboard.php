<style>
	.notice_row{
		border-top: 1px solid #CCC;
		margin-bottom: 15px;
	}
	.notice_row td{
		vertical-align:middle;
	}
	.notice_row_left_column{
		width: 30%;
	}
	.notice_row_left_column p{
		width: 100%;
		text-align: center;
	}
	.notice_row_right_column{ width: 70%; }
	.topside textarea{ width:60%; height:auto; }

</style>
<script>
$(function(){
	$("#create_note_form_submit").click(function(event){
		// insert new note
		event.preventDefault();
		console.log("aaaa" + $("#create_note_form_textarea").val());

		if($("#create_note_form_textarea").val().length > 0){

			$.ajax({
				data:$("#create_note_form").serialize(),
				type:"post",
				success:function(response){
					document.location.reload();
				},
				error:function(XMLHttpRequest, textStatus, errorThrown){
					alert("失敗しました。");
				}
			});
		}else{
			alert("コンテンツは空白です。");
		}
		return false;
	});
	<?php foreach(NoticeBoard::all() as $notice): ?>
		$("#notice_checkbox_<?= $notice->getId()?>").change(function(){
			var check_value = "uncheck";
			if($(this).is(":checked")){
				check_value = "checked";
			}
			console.log("a "+check_value);
			$.ajax({
				data:{
					action: "noticeboard_update",
					id: <?= $notice->getId() ?>,
					checked: check_value
				},
				type:"post",
				success:function(response){

				},
				error:function(XMLHttpRequest, textStatus, errorThrown){
					alert("失敗しました。");
				}
			});
		});
	<?php endforeach; ?>
});
</script>
<div class="container wrapper">
    <?php include "nav.php"; ?>
	<div class="fifteen columns">
		<div class="topside">
			<p>お知らせボードの管理について</p>
			<ul>
				<li><span style='font-weight:bold;'>追加  </span>下のテキストボックスに内容をにゅうりょくして、書いたら「お知らせ追加」ボタンを押してください。追加したら表示チェックを付けてください。</li>
				<li><span style='font-weight:bold;'>表示  </span>リストのチェックがつけているやつだけが表示される。もしお知らせボードに表示しないやつがあれば、それのチェックを外せばよい。</li>
			</ul>

			<!--  -->
			<!--  -->
			<!-- noticeboard create -->
			<form id="create_note_form" action="<?= $_SERVER["REQUEST_URI"]?>" method="post">
				<input name="action" style="display:none" value="noticeboard_create">
				<textarea id="create_note_form_textarea" name="notice_content"></textarea>
				<input id="create_note_form_submit"type="submit" value="通知追加">
			</form>

		</div>
		<p>次のリストはチェックされている内容は表示され。なので、表示しない内容があったらチェックを外してください。</p>
		<table>
			<tbody>
				<!--  -->
				<!--  -->
				<!-- noticeboard update html code -->
			 	<?php foreach(NoticeBoard::all() as $notice): ?>
			 		<tr class="notice_row">
			 			<td >
			 				<?php if($notice->isChecked()) $checked = "checked"; else $checked = "";?>
			 				<input type="checkbox" name="<?=$notice->getId()?>" id="notice_checkbox_<?=$notice->getId()?>" class="notice_checkbox" <?=$checked?>>
			 			</td>
			 			<td class="notice_row_left_column">
			 				<?php $author = $notice->getAuthor(); ?>
			 				<p><strong><?= $author->getNickname()?></strong> <br /><?= $author->getGrade()." ".$author->getPosition() ?>
							<br /><br />更新時間 <br /><?= $notice->getUpdateDate()." ".$notice->getUpdateTime()  ?></p>
			 			</td>
			 			<td class="notice_row_right_column">
			 				<div>
			 					<?= Utils::filterMalicious($notice->getContent()); ?>
			 				</div>
			 			</td>
			 		</tr>
			 	<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
