<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<h1>検索機能</h1>
<!-- postフォーム -->
<style>
    .hit{
        background-color:rgb(230,230,230);
        /* font-size: 30px; */
    }
</style>
    
<?php
//ini_set("display_errors",1);
//include_once ('simple_html_dom.php');



if(!empty($_GET["search"])){

	// 次に追加してほしい機能はHTMLから内容を抜き出すプログラミング。
	// 今の場合は<a href="http://example.com?a=%30d">myText</a>を検索していると、example.com?aなどの
	// いらない文字まで検索されてしまう。検索対象となるのはmyTextのみなので、myTextを抜き出すプログラミングを組んでみて
	//
	// ヒント：　"php simple_html_dom"について調べて 
	//


	$words = $_GET["search"];
	//メタ文字のescape化
	$words2 = preg_replace_callback("/[\*\?\+\[\]\(\)\{\}\^\$\|\\\\.\/]/",
									function($matches){return "\\".$matches[0];},
									$words);
	//echo $words2;
	
	$threads=Thread::all();
 	$result_list=array();
	//全スレッド
	for($j=0; $j<count($threads); $j++){
		$Post=$threads[$j]->getAllPosts();
		//全ポスト
		for($i=0; $i<count($Post); $i++){
 			//本文の抜出し
			$html=str_get_html($Post[$i]->getContent());
			//本文match?
			if(preg_match("/$words2/i",$html->plaintext)){
			
				$obj = array(
						"title" => $threads[$j]->getTitle(),
						"body"	=> $html->plaintext
					);
					
				array_push($result_list, $obj);
			
			}
		
		}
		
		
	}
	
	echo "<h1>検索結果".count($result_list)."</h1>";
	
	echo "<ul>";
	foreach($result_list as $item){
	
		if($title!=$item["title"]){
			$title=$item["title"];
			echo "<h1>{$title} </h1>";
		}
		echo "<li class='hit'><pre>{$item["body"]}</pre></li>";
			
	}
	echo "</ul>";
	
	
	//preg_matchに引っかからなかったPostがNotice errorとして表示されるのを非表示にする
	
	}