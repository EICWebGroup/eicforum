<style>
	h3{ font-weight:bolder; }
	.author{
		width: 150px;
		float:right;
		background-color:#EEE;
		padding:5px;
	}
	.author p, .author a{
		margin:0;
		font-size:90%;
		text-decoration:none;
		color:#222;
	}
        .rss-link{
            padding:5px;
            background-color: #fcf8e3;
            border:1px solid #c09853;
        }
        #share-tip{
            -moz-box-shadow: 2px 2px 4px #999;
            -webkit-box-shadow: 2px 2px 4px #999;
            box-shadow: 2px 2px 4px #999;
            background-color: #eaeaea;
            margin-top: 5px;
            font-size: 12px;
            vertical-align: baseline;
            max-width: 500px;
            overflow: auto;
            padding: 10px;
            position: absolute;
            z-index: 100;
        }
        #share-tip p{
            margin:0px;
        }
        #share-tip a{
            color:blue;
        }
</style>
<script>
  
  !function($){
      
      //////////////////////////////////////////////////////
      // Dialog plugin begin
      //
      // Simple plugin about 
      var Dialog = function( element, options){
          this.__element = element;
          this.__options = options;

          this.init();
      };
      

      Dialog.prototype.init = function(){

        var element = this.__element;

        element.hide();

        $(document).click(function(e) {
              var elementId = element[0].id;
              
              if (!$(e.target).is('#'+elementId+', #'+elementId+' *')) {
                  element.hide();
              }
          });

      }

      Dialog.prototype.close = function(){
          this.__element.hide();
      }

      $.fn.Dialog = function(options){
        
        var obj = new Dialog(this, options);
        for(var key in obj){
          this[key] = obj[key];
        }

        return this;
      };
      // Dialog plugin end
      //////////////////////////////////////////////////////
      
  }(window.jQuery);


    // rss feed
    function parseRSS(url, callback){
        $.ajax({
           url: "/common/rss.php?q="+encodeURIComponent(url),
           dataType:"json",
           success:function(data){
               data["getTitle"] = function(title){
                   for(var i = 0; i < this.entry.length; i++){
                       if(this.entry[i].title == title)
                           return this.entry[i];
                   }
                   return null;
               };
               data["getLinkByTitle"] = function(title){
                   var e = this.getTitle(title);
                   console.log("a"+e);
                   if(e != null){
                       for(var i = 0; i < e.link.length; i++){
                           console.log(e.link[i]);
                           if(e.link[i]["@attributes"]["rel"] == "alternate" ){
                               return e.link[i]["@attributes"].href;
                           }
                       }
                   }else{
                       return null;
                   }
               }

               console.log(data);

               callback(data);
           }
        });
    }
</script>
<!-- ////////////////////////////////////////////////////////////////////  -->
<!-- title of thread -->
<?php if($thread->getSpecial() == "rss"): ?>
    <h3 id="rss-title"><i class="fa fa-rss" style="color:orange;"></i>&nbsp;<?= $thread->getTitle() ?></h3>
<?php else: ?>
    <h3><i class="fa fa-quote-left"></i><?= $thread->getTitle() ?><i class="fa fa-quote-right"></i></h3>
<?php endif; ?>
<p style="margin-bottom:0;text-align: right;"><a style="text-decoration: none;" href="#editor-form">投稿ボックスへ</a></p>
<!-- end of title of thread -->
<!-- ////////////////////////////////////////////////////////////////////  -->
<script>
    
    <?php if(!empty($_GET["request_post"])): ?>
        document.location.href="#<?= $_GET["request_post"]?>";
    <?php endif;?>
    
    
    $(function(){
        
        
        
        var dialog=$("#share-tip").Dialog();
        
        $(".share-button").click(function(event){

            event.preventDefault();
            dialog.appendTo($(this).parent());
            dialog.toggle();
            var ele = $("#"+dialog[0].id+" > input")[0];
            
            var url = "<?= "http://$_SERVER[HTTP_HOST]/thread/$thread_id/post/" ?>"+$(this).attr("href");
            
            // select 
            $("#share-url").val(url);
            
            event.stopPropagation();
            
            // select all the url
            ele.select();
            
            return false;
        });
        
        // close dialog by pressing close anchor inside dialog div
        $("#"+dialog[0].id+" > a").click(function(){
            dialog.close();
        });
        
    });

</script>
<ul>
	<?php foreach($posts as $post): ?>
	<li>
        <div class="clearfix post-container <?php if($post->isHost()) {echo"host";} ?>">
			<?php // this snippet is used to adjust line

				$author = $post->getAuthor();
			?>

			<?php if($post->isHost()): ?>
				<hr/>
				<div style="width:1px;height:20px;"></div>
			<?php endif; ?>

			<!-- post content  -->
			<div id="<?= $post->getPostId() ?>" class="post">
                <!-- to prevent noscript device to view the pictures, do something -->
                <!-- following script is working together with fixBug2 -->
                <?php
                    $filtered_content = preg_replace('/\<img\s/', '<data-image ',$post->getContent());
                    $filtered_content = preg_replace('/\<\/img\>/','</data-image>',$filtered_content);
                    echo $filtered_content;
				?>
			</div>


			<!--  if user have the permission to edit or delete, show it  -->
			<div class="post-box">
				<?php if($_SESSION[KEY_SESSION][Account::KEY_ID] == $author -> getId()): ?>
                    <?php if($post->isHost() && $thread->getSpecial() == "rss"): ?>
                        <a href="/thread/<?= $thread->getId() ."/editrss/". $post->getPostId()?>/">編集</a>
                    <?php else: ?>
                        <a href="/thread/<?= $thread->getId() ."/edit/". $post->getPostId()?>/">編集</a>
                    <?php endif; ?>
					<a href="/thread/<?= $thread->getId() ."/delete/". $post->getPostId()?>/">削除</a>
				<?php endif; ?>

                <a class="share-button" href="<?= $post->getPostId()?>">Share</a>
			</div>


			<!-- author and info of post  -->
			<div class="author">
				<a href="/account/<?= $author->getId()?>"><i class="fa fa-user"></i><?= $author->getNickname() ?></a>
				<p><?= $author->getDepartment() ?></p>
				<?php if($post->getModifiedTime() != $post->getCreatedTime()): ?>
					<p><?= Utils::getReadableDateTime($post->getModifiedTime()) ?>で最終編集</p>
				<?php endif; ?>
				<p><?= Utils::getReadableDateTime($post->getCreatedTime()) ?>に追加</p>
			</div>

		</div>

	</li>
	<?php endforeach; ?>



</ul>

<?php showPageBar($show,$page, count($thread->getAllPosts()) ,  $showPageCallback); ?>

<!-- bug => wrong path of image -->
<script type="text/javascript">
    // bug fixing
	var ele = document.getElementsByClassName("post");
	for(var i = 0; i < ele.length ; i++){


        ele[i].innerHTML = ele[i].innerHTML.replace(/\<data-image /g,"<img ");
        ele[i].innerHTML = ele[i].innerHTML.replace(/\<\\data-image\>/g,"</img>");

		ele[i].innerHTML = ele[i].innerHTML.replace(/<img alt(.*?) src=\"uploadManager/g,"<img src=\"/common/uploadManager");
        ele[i].innerHTML = ele[i].innerHTML.replace(/<img src=\"uploadManager/g,"<img src=\"/common/uploadManager");

		ele[i].innerHTML = ele[i].innerHTML.replace(/<img\s+src\s*=\s*(["'][^"']+["']|[^>]+)>/g,function(match, content){
			return "<a href="+content+" data-lightbox=\"a\"><img src="+content+"></a>";
		});
	}

</script>
<div class="textarea">
	<form id="editor-form" action="<?= $_SERVER["REQUEST_URI"]?>" method="post">

		<?php if(!empty($error_message)): ?>
			<p style="color:red;"><?= $error_message?></p>
			<script>
			$(function(){
				var p = $("#wmd-editor").position();
				console.log(p);
				$(window).scrollTop(p.top);
			});
			</script>
		<?php endif; ?>

		<?php if(!$isMobile):
			// if it is pc, include rich-text-editor
			include "rich-text-editor.php"; ?>
		<?php else:?>
			<textarea name='content' height="150px;" class='nine columns' id='wmd-input'><?= $textarea_content; ?></textarea>
		<?php endif?>

		<div style="height: 20px;width:1px;"></div>
		<input class='wmd-submit' id='request_submit' type='submit' value='送信'>
	</form>
</div>

<!-- share dialog box html -->
<!-- begin here -->
<div  id="share-tip">
    <p>スレッドをシェア</p>
    <input style="width:300px;margin-top:5px;margin-bottom: 5px;" type="text" id="share-url">
    <a href="javascript:undefined">キャンセル</a>
</div>
<!-- end here -->