<!-- postフォーム -->
<style>
    li{
    	margin-bottom: 0;
    }
    
    .notice-block {
        	background-color:#e3e3e3;
                padding:10px;
    }
    .search{
        display:block;
    }
    .search input{
        display:inline-block;
    }
    .hit-post{
    	padding:0 5px;
    	margin-top:50px;
    	border:2px solid #CCC;
    }
    .hit-item{
    	
    	padding:0 5px;
    	border: 2px solid rgba(0,0,0,0);
    }
    .hit-item:hover, .hit-post:hover{
    	border: 2px solid green;
    }

    .hit-item a{
    	display:block;
    	text-decoration: none;
    	color:#303030;
    	border:1px solid #CCC;
    	padding:10px;
    	margin:10px 0;
    }
    .hit-title h4{
    	top:-15px;
    	left:20px;
    	width:auto;
    	position:relative;
    	display: inline-block;
    	background-color: #FFF;
    }
    .fa-top-small{
    	font-size:75%;
    	vertical-align: top;
    }
    .wmd-title{
    	height:22px;
    	padding:5px;
    	font-size:100%;
    }

</style>
<div class="container wrapper">
	<div class="fifteen columns">

		<div class="vertical-offset"></div>
		<div class="ten columns">
                    
                    
        <h3>検索機能</h3>
                        
			<!-- include content here. For example, include create.php , show.php or list.php here. -->
			
                        <form class="search" action="https://eicforum.mydns.jp/search" method="get">
				<input class="wmd-title" type="text" name="search" value="<?= $_GET["search"] ?>">
				<button class="wmd-submit" type="submit" ><i class='fa fa-search fa-fw'></i></button>
			</form>
                        
                        
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
						"thread_id"	=> $threads[$j]->getId(),
						"post_id" => $Post[$i]->getPostId(),
						"title" => $threads[$j]->getTitle(),
						"body"	=> $html->plaintext
					);
					
				array_push($result_list, $obj);
			
			}
		
		}
		
		
	}
	
	echo "<h3><i class='fa fa-quote-left fa-fw fa-top-small'></i>".$words."<i class='fa-top-small fa-fw fa fa-quote-right'></i>についての検索結果は".count($result_list)."件。</h3>";
	
	$threadBegin = true;

	echo "<ul class='hit-post'>";
	foreach($result_list as $item){

		if($title!=$item["title"]){
			$title=$item["title"];

			if($threadBegin){
				echo "<li class='hit-title'><h4>{$title} </h4></li>";
				$threadBegin = false;
			}else{
				echo "</ul>";
				echo "<ul class='hit-post'>";
				echo "<li class='hit-title'><h4 >{$title} </h4></li>";
			}

		}
		
		$thread_id = $item["thread_id"];
		$post_id = $item["post_id"];
		
		echo "<li class='hit-item'>";
		
		echo "<a href='/thread/{$thread_id}/post/{$post_id}'><span>";
	////////////////////////////////////////////	
	// START : 文字の短縮
	
	mb_internal_encoding("UTF-8");

	$i=8;
	$str=$item["body"];
	$sw=mb_strlen($words);
	$ss=mb_strlen($str);
	
	$k=mb_strpos($str,$words,0);
	
	for(; $k !== false ;){
		

		$start=$k-$i;
		$length=$sw+2*$i;
		
		if($start<=0 && $ss>=$length){
			$start=0;
			$length=2*$length;
			$str2=mb_substr($str,$start,$length);
			echo "{$str2}...";
		}else if($ss-$k<$i+2 && $ss>=$length){
			$str2=mb_substr($str,$start,$length);
			echo "...{$str2}";
		}else if($start>0 && $ss>=$length){
			$str2=mb_substr($str,$start,$length);
			echo "...{$str2}...";
		}
	
		$offset = $k + 1;
		$k=mb_strpos($str,$words,$offset);
	}
	
	// END : 文字の短縮
	////////////////////////////////////////////
	
		echo "</span></a>";
		
		//echo "<a href='#'><span>{$item["body"]}</span></a>";
		
		echo "</li>";
	}
	echo "</ul>";
	
	
	//preg_matchに引っかからなかったPostがNotice errorとして表示されるのを非表示にする
	
	}
        
?>
                        
		</div>

		<?php if(!Utils::is_mobile()): ?>
		<div class="four columns side-column">

			<div class="notice-block">
				<h5><i class="fa fa-bookmark-o"></i>検索機能について</h5>
				<br/>
               <p>まだ制作段階です。もうすぐ完了です。もうちょっと待っててね。夜露死苦。</p>
			</div>
			<div class="offset-block"></div>
		</div>
		<?php endif; ?>
	</div>
</div><!-- container -->