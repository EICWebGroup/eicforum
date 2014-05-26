<div class="container wrapper">
    <?php include "nav.php"; ?>
	<div class="fifteen columns">
		<p>リクエスト記録は次の３の条件に基づいて記録しています。</p>
		<ul>
			<li><span style='font-weight:bold;'>request </span>ログインページがリクエストされた時</li>
			<li><span style='font-weight:bold;'>login </span>ログインページからメインページにロokグインした時</li>
			<li><span style='font-weight:bold;'>coie login </span>ログインページからメインページにクッキーでログインした時（自動ログインのこと）</li>
		</ul>
		<p>ただし、nullは該当のＩＰアドレスの場所データが存在しないという意味です。</p>
		<br/>

			<?php
				$file = fopen(Utils::getLoginLog(), "r") or exit("Unable to open file!");
				//Output a line of the file until the end is reached

				$file_rows = array();
				while(!feof($file)){
				   array_push($file_rows,fgets($file));
				}
				fclose($file);

				$num_row = 0;
				for($i = count($file_rows)-1; $i >= 0 ;$i--){
					if($num_row > 150) break;
					echo $file_rows[$i]."<br/>";
					$num_row ++;
				}
			?>

	</div>
</div>
