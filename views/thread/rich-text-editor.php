
<link rel="stylesheet" type="text/css" href="/stylesheets/jquery-ui.css">
<style>
    .hide{ display:none !important; }
</style>

<div style="height:60px;width:1px;"></div>
<div class="clearfix">
    <textarea name="content" id='wmd-editor' class='wmd-panel nine columns'><?= $textarea_content ?></textarea>
</div>


<script type="text/javascript">
$(function(){

    var KeyEvent = {
                    ARROW_UP : 38,
                    ARROW_DOWN : 40,
                    ARROW_LEFT :37,
                    ARROW_RIGHT : 39,
                    SPACE : 32,
                    KEY_A : 65,
                    KEY_B : 66,
                    KEY_I : 73,
                    KEY_K : 75,
                    KEY_Z : 90,
                    KEY_ENTER : 13,
                    KEY_BACKSPACE : 8,
                    KEY_DELETE : 46,
                    KEY_TAB : 9,
                    KEY_0 : 48,
                    KEY_9 : 57,
                    KEY_ESC : 27
                }

     var suppressedKeyRepeat = false;
     var undoMemory = new UndoMemory();

           $("#wmd-editor").markdown({
               autofocus:false,
               savable:false,
               onKeyUp:function(instance , e){

                   // reduce occurance of keyup and keydown
                   if(suppressedKeyRepeat){
                       suppressedKeyRepeat = false;
                   }else{
                       suppressedKeyRepeat = true;
                       return;
                   }

                   var keyPressed = ('which' in e) ? e.which : e.keyCode;

                   // example of used
                   if( (e.ctrlKey||e.metaKey) && keyPressed == KeyEvent.KEY_B){
                       e.preventDefault();
                       $('li[data-handler="bootstrap-markdown-cmdBold"]').click();
                   }else if((e.ctrlKey||e.metaKey) && keyPressed == KeyEvent.KEY_I){
                       e.preventDefault();
                       $('li[data-handler="bootstrap-markdown-cmdItalic"]').click();
                   }else if((e.ctrlKey||e.metaKey) && keyPressed == KeyEvent.KEY_K){
                       e.preventDefault();
                       $('li[data-handler="bootstrap-markdown-cmdCode"]').click();
                   }

                   undoMemory.store(instance.getContent());
                   instance.showPreview();

                   return false;
               },
               additionalButtons:[
                   [{
                       name: "groupCustom",
                       data: [{
                           name: "cmdUndo",
                           title: "Undo",
                           callback: function(e){
                               document.execCommand("undo");
                           }
                       },{
                           name:"cmdRedo",
                           title:"Redo",
                           callback: function(e){
                               document.execCommand("redo");
                           }
                       }]
                   }]
               ]
           });


	$("form").submit(function(e){
        console.log("heee");
		$("#wmd-editor").val( $("#wmd-output").html().replace(/<img alt="(.*?)" src=\"\/common\//g,"<img src=\""));
		$(window).unbind('beforeunload');

		return true;
	});

});
</script>

<!-- ************************************** -->
<!-- All kinds of dialog html -->

<div style='display:none'>
	<!--  image upload dialog -->
	<div class='image-upload' title='画像アップロード' >
	    <p><span></span>アップロードできる画像はサイズ2MB以下とpng、gif、jpg フォーマットのみ。</p>
	    <div style='margin-top:10px'>
	        <input type='radio' name='img-type' id='web-img' value='1'>ウェブサイトからのリング<br>
	        <input type='radio' name='img-type' id='upload-img' value='2'>画像ファイルをアップロード<br />
	        <div id='web-img-form'>
	            <label style='margin-top:12px;margin-right:5px;float:left;' for='name'>リング</label>
	            <input type='text' class='text ui-widget-content ui-corner-all' id='image-url' style='float:left' placeholder='写真のリング'/>
	            <span style='clear:both'></span>

	        </div>
	    </div>
	<div id='image-file-upload'>
	<input type='file' name='files' id='image-file-upload-input' >
	<div id='upload_result'></div>
	</div>

	</div>

	<script type='text/javascript'>
	    $(function () {

	        //default
	        $('#web-img').attr('checked', 'checked');
	        $('#image-file-upload').hide();

	        // checked changed event
	        $('input:radio[name=\'img-type\']').change(function () {
	            if ($('#web-img').is(':checked')) {
	                $('#web-img-form').show();
	                $('#image-file-upload').hide();
	            } else {
	                $('#web-img-form').hide();
	                $('#image-file-upload').show();
	            }
	        });

	    });
	</script>

	 <!-- hyperlink dialog -->
	<div id='hyperlink-dialog-modal' title='Basic modal dialog'>
	    <p style="margin-bottom: 0;">ハイパーリンくを挿入。<br />*http://をつけることを忘れずに</p>
	    <b> 名前</b>
	  <input style="margin-bottom: 0;" id='thread_hyperlink_name' type='text' name='name'>
	    <b> URL</b>
	    <input style="margin-bottom: 0;" id='thread_hyperlink_url'type='text' name='url'>

	</div>

	<!--  image upload failed dialog -->
	<div id='image-upload-alert' title='Download complete'>
	    <p>
	    <span class='ui-icon ui-icon-alert' style='float: left; margin: 0 7px 50px 0;'></span>
	   	 二つの選択を同時に選ぶことができないので、必ず一つを空白してください。
	    </p>
	</div>
</div>

<script type="text/javascript" src="/javascript/bootstrap-markdown.js"></script>
<script type='text/javascript' src='/javascript/undoMemory.js'></script>
<script type='text/javascript' src='/javascript/jquery-ui.js'></script>
<script type='text/javascript' src='/common/uploadManager/js/jquery.iframe-transport.js'></script>
<script type='text/javascript' src='/common/uploadManager/js/jquery.fileupload.js'></script>