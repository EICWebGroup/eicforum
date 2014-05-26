/* NOTICE
   if replace all use //g instead of ''
   exp) replace(/[character]\s/g,'word replaced')

   On expReg   -> \s === space
               -> \* === '*'

   On getting highlighted text, getSelectionText cannot be used alone, must be combined with select event listener function

   get element html code   ->    $['#element'].html();
   write into textArea     ->    $['#textArea'].text('content');
   get textArea content    ->    $['#textArea'].val();

*/
$(function(){

   var errorDialog = $('#message-error-dialog');
   var titleTextBox = $('#wmd-title');
   var textInput = $('#wmd-input');
   var htmlInput = $('#html-input');
   var confirmDialog = $('.image-upload');
   
   var textOutput = $('#wmd-output');
   
   confirmDialog.hide();

   var inputType = "";
   var listId = [];
   var listNum = 0;

   var uploadFileName = "";
   var url =  "/common/uploadManager/server/php/";

   var imageFileUploadButton = $('#image-file-upload');
   var undoMemory = new UndoMemory();
   var preText = "";
   htmlInput.hide();
   errorDialog.hide();
   $('#html_hint_message').hide();
   $("#image-upload-alert").hide();
   $('#hyperlink-dialog-modal').hide();


	/**
	*  replace the string begin from the index start given and end with index end given
	*/
	String.prototype.replaceString = function(start,end,str){
		var openStr = this.substr(0,start);
		var closeStr = this.substr(end);
		return openStr + str + closeStr;
	}

   /*
   If you need to get selected text, never tried to implement getSelectionText function as it will return null

   Use var textHightlighted, it returns the text that selected.
   */
   var textHighlighted = "";


   textInput.select(function () {

       textHighlighted = getSelectionText();
		
   });
   
   function getInputSelection(el) {
    var start = 0, end = 0, normalizedValue, range,
        textInputRange, len, endRange;

		if (typeof el.selectionStart == "number" && typeof el.selectionEnd == "number") {
			start = el.selectionStart;
			end = el.selectionEnd;
		} else {
			range = document.selection.createRange();

			if (range && range.parentElement() == el) {
				len = el.value.length;
				normalizedValue = el.value.replace(/\r\n/g, "\n");

				// Create a working TextRange that lives only in the input
				textInputRange = el.createTextRange();
				textInputRange.moveToBookmark(range.getBookmark());

				// Check if the start and end of the selection are at the very end
				// of the input, since moveStart/moveEnd doesn't return what we want
				// in those cases
				endRange = el.createTextRange();
				endRange.collapse(false);

				if (textInputRange.compareEndPoints("StartToEnd", endRange) > -1) {
					start = end = len;
				} else {
					start = -textInputRange.moveStart("character", -len);
					start += normalizedValue.slice(0, start).split("\n").length - 1;

					if (textInputRange.compareEndPoints("EndToEnd", endRange) > -1) {
						end = len;
					} else {
						end = -textInputRange.moveEnd("character", -len);
						end += normalizedValue.slice(0, end).split("\n").length - 1;
					}
				}
			}
		}

		return {
			start: start,
			end: end
		};
	}



   /*
   If textarea is empty unload confirm window
   */


   function getSelectionText() {
   
		var mText = "";
		if (window.getSelection) {
			mText = window.getSelection().toString();
		} else if (document.selection && document.selection.type != "Control") {
			mText = document.selection.createRange().text;
		}
	   return mText; 
	   /*
		var textarea = textInput;
		var start = textarea.selectionStart;
		var end = textarea.selectionEnd;
		var sel = textarea.value.substring(start, end);
		return sel;*/
   }
   

   function replaceIt(txtarea, newtxt) {
       $(txtarea).val(
	   
             $(txtarea).val().substring(0, txtarea.selectionStart) +
             newtxt +
             $(txtarea).val().substring(txtarea.selectionEnd)
			 
        );
   }


   // Bold-button clicked and replace the word highlighted to ( ** + [textHightlighted] + ** )
   $('#wmd-bold-button').click(function () {

       // clean current highlighted text before format
       textHighlighted = textHighlighted.replace(/\*\*\s/g, '');
       textHighlighted = textHighlighted.replace(/\s\*\*/g, '');

       // format begin
       replaceIt(textInput[0], ' **' + textHighlighted + '** ');
       undoMemory.store(textInput.val());
       formatTextArea(textInput);

       // clear textHighlight text
       textHighlighted = "";
   });

   // italic-button clicked and replace the word highlighted to ( * + [textHightlighted] + * )
   $('#wmd-italic-button').click(function () {

       // clean current highlighted text before format
       textHighlighted = textHighlighted.replace(/\s--/g, '');
       textHighlighted = textHighlighted.replace(/--\s/g, '');

       // format begin
       replaceIt(textInput[0], ' --' + textHighlighted + '-- ');
       undoMemory.store(textInput.val());
       formatTextArea(textInput);

       // clear textHighlight text
       textHighlighted = "";
   } );


   // hyperlink
   $('#wmd-link-button').click(function ()  {

       // clean current highlighted text
       textHighlighted = textHighlighted.replace(/\s\[\s/g, '');
       textHighlighted = textHighlighted.replace(/\s\]\s/g, '');
       textHighlighted = textHighlighted.replace(/\s\:=\s/g, '');

       //begin format

       $( "#hyperlink-dialog-modal" ).dialog({
      height: 250,
      modal: true,
      buttons: {
        "決定": function() {
           if ($('#thread_hyperlink_url').val().length < 1) {
           $(this).dialog('close');
       } else {
           replaceIt(textInput[0], textHighlighted+' [ '+$('#thread_hyperlink_url').val()+' := '
           +$('#thread_hyperlink_name').val()+' ] ');
               // system format
           undoMemory.store(textInput.val());
           formatTextArea(textInput);

           // clear textHighlight text
           textHighlighted = "";
           $(this).dialog('close');
           }
        }
      }
    });


   } );

   //source code
   $('#wmd-code-button').click(function () {
       // clean all list type
       //textHighlighted = textHighlighted.replace(/\s\s\s\s/g, '');
       //textHighlighted = textHighlighted.replace(/\s\s[0-9]\.\s/g, '');
       //textHighlighted = textHighlighted.replace(/\s\s-/g, '');

       // setup begin
	   textHighlighted = textHighlighted.replace(/\n/g,'￥n');
       textHighlighted = '\n' + textHighlighted;
       textHighlighted = textHighlighted.replace(/\n/g, '\n    ');
       replaceIt(textInput[0], textHighlighted);

       // system format
       undoMemory.store(textInput.val());
       formatTextArea(textInput);

       // clear textHighlight text
       textHighlighted = "";
   });


	

   //image
   $('#wmd-image-button').click(function () {
        var uploadConfirm = false;
		var fuploading = false;
		var femptyfile = true;

        $('#image-file-upload-input').fileupload({
            url: url,
            dataType: 'json',
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
            maxFileSize: 2000000, // 2 MB
            done: function (e, data) {
                $.each(data.result.files, function (index, file) {
                    uploadFileName = file.name;
                    $('#upload_result').text(uploadFileName + ' アップロード完了。Add Imageボタンを押してください。');
                    uploadConfirm = true;
					fuploading = false;
					femptyfile = false;
                });
            },
            progressall: function (e, data) {
                $('#upload_result').text('アップロード中. 画面を閉じないで待ってください。');
				uploadConfirm = false;
				fuploading = true;
				femptyfile = false;
            },
            processalways: function(e, data) {
                var index = data.index,
                    file = data.files[index];

                if (file.error) {
                    $('#upload_result').html('<span style="color:red">'+uploadFileName+' '+file.error+'</span>');
                    uploadConfirm = false;
					fuploading = false;
					femptyfile = true;
                }
            }
        });


       confirmDialog.dialog({
           resizable: false,
           width: 370,
           height: 300,
           modal: true,
           buttons: {
               'Add Image': function () {


                   if ($('#web-img').is(':checked')) {
                       // get image from URL
                       var iurl = ($('#image-url').val());

                       iurl = iurl.replace(/\s/g,'');

                       textHighlighted += ' $$' + iurl + '$$ ';

                       replaceIt(textInput[0], textHighlighted);
                       undoMemory.store(textInput.val());
                       formatTextArea(textInput);

                       $(this).dialog("close");

                   } else {
						// get image from file uploaded						
                        if(uploadConfirm){
                            textHighlighted += '\n $$uploadManager/server/php/files/' + uploadFileName + '$$ ';

                           replaceIt(textInput[0], textHighlighted);
                           undoMemory.store(textInput.val());
                           formatTextArea(textInput);

                           // clear textHighlight text
                           textHighlighted = "";
						   $('#upload_result').text('');
						   $(this).dialog("close");
                        }else if(fuploading){
							$('#upload_result').text('アップロード中、ちょっと待ってください。');
						}else if(femptyfile){
							$('#upload_result').html('<span style="color:red">ファイルを選択してください。</span>');
						}
                        
                   }

               },
               'Cancel': function () {
					$('#upload_result').text('');
					$(this).dialog("close");
               }
           }

       });
        // clear textHighlight text prevent
       textHighlighted = "";

   });

   //number list
   $('#wmd-olist-button').click(function () {
       // clean all list type
       textHighlighted = textHighlighted.replace(/\s\s-\s/g, '');
       textHighlighted = textHighlighted.replace(/\s\s[0-9]\.\s/g, '');
       var olTokenized = [];

       // setup begin
       textHighlighted = '\n' + textHighlighted;

       olTokenized = textHighlighted.split('\n');
       textHighlighted = "";
       for (var i = 1; i < olTokenized.length; i++) {
           var j = i + 1;
           textHighlighted += '  '+i + '. ' + olTokenized[i] +'\n';
       }
       replaceIt(textInput[0], textHighlighted);
       undoMemory.store(textInput.val());
       formatTextArea(textInput);

       // clear textHighlight text
       textHighlighted = "";
   });

   //point list
   $('#wmd-ulist-button').click(function () {
       // clean all list type
       textHighlighted = textHighlighted.replace(/\s\s-\s/g, '');
       textHighlighted = textHighlighted.replace(/\s\s[0-9]\.\s/g, '');

       // setup begin
       textHighlighted = '\n' + textHighlighted;
       textHighlighted = textHighlighted.replace(/\n/g, '\n  - ');
       replaceIt(textInput[0], textHighlighted);
       undoMemory.store(textInput.val());
       formatTextArea(textInput);

       // clear textHighlight text
       textHighlighted = "";
   });

   //headline
   $('#wmd-heading-button').click(function () {
       textHighlighted = textHighlighted.replace(/\s\~\~/g, '');
       textHighlighted = textHighlighted.replace(/\~\~\s/g, '');

       textHighlighted = ' ~~' + textHighlighted + '~~ ';
       replaceIt(textInput[0], textHighlighted);
       undoMemory.store(textInput.val());
       formatTextArea(textInput);

       // clear textHighlight text to prevent the event that is not relate to it occur
       textHighlighted = "";

   });

   // line
   $('#wmd-hr-button').click(function () {
       textHighlighted = '';

       replaceIt(textInput[0], textHighlighted + '\n---------\n');
       undoMemory.store(textInput.val());
       formatTextArea(textInput);

       // clear textHighlight text
       textHighlighted = "";
   });


   // switch normal text to html script
   $('#wmd-html-button').click(function ()  {
   /*
       if (textInput.is(':visible')) {

           textInput.hide();
           htmlInput.show();
           $('#hint_message').hide(500);
           $('#html_hint_message').show(500);
       } else {
           textInput.show();
           htmlInput.hide();
           $('#hint_message').show(500);
           $('#html_hint_message').hide(500);
       }*/
	   
	   alert("工事中");
   });


   $('#wmd-undo-button').click(function () {
       textInput.val(undoMemory.undo());
       formatTextArea(textInput);
   });

   $('#wmd-redo-button').click(function () {
       textInput.val(undoMemory.redo());
       formatTextArea(textInput);
   });


   titleTextBox.blur(function () {
       var count = $(this).val().length;
       if (count < 5) {
           errorDialog.show(300);
       } else {
           errorDialog.hide(300);
       }
   });

   errorDialog.click(function () {
       errorDialog.hide(300);
   });

   

    formatTextArea(textInput);

   // textInput element input action event
   textInput.keyup(function (e) {

       $(window).bind('beforeunload', function(){
		    return 'データは保存されないので';
	    });

		var evt = e || window.event;
       var key = evt.keyCode;
       if (e.ctrlKey && e.keyCode == 90) {
           // Ctrl + Z -> undo

           textInput.val(undoMemory.undo());
       } else if (e.ctrlKey && e.keyCode == 89) {
           // Ctrl + Y -> redo

           textInput.val(undoMemory.redo());
       }else {


           if (preText == "") {
               preText = textInput.val();
           } else {
               if (preText == textInput.val()) {

               } else {

                   undoMemory.store(textInput.val());
                   preText = textInput.val();
               }

           }
       }

       formatTextArea(textInput);
   });

   // htmlInput element input action event
   htmlInput.keyup(function (e) {
       textOutput.html(htmlInput.val());
   });


   // format the text input inside textarea to html form
   function formatTextArea(textArea)
   {

       function RegisterWordFormat(str) {
           var words = [];
           str = str.replace(/{/g, ' { ');
           str = str.replace(/}/g, ' } ');
           str = str.replace(/\)/g, ' ) ');
           str = str.replace(/\(/g, ' ( ');
           str = str.replace(/&lt;/g, ' < ');
           str = str.replace(/>/g, ' > ');

           // int, char, if, else , for , while, return

           str = str.replace(/\sif\s/g,' <span style="color:blue">if</span> ');
           str = str.replace(/\selse\s/g, ' <span style="color:blue">else</span> ');
           str = str.replace(/\sint\s/g, ' <span style="color:blue">int</span> ');
           str = str.replace(/\schar\s/g, ' <span style="color:blue">char</span> ');
           str = str.replace(/\sfor\s/g, ' <span style="color:blue">for</span> ');
           str = str.replace(/\swhile\s/g, ' <span style="color:blue">while</span> ');
           str = str.replace(/\sreturn\s/g, ' <span style="color:blue">return</span> ');
		   str = str.replace(/\sstruct\s/g, ' <span style="color:blue">return</span> ');
		   str = str.replace(/\svoid\s/g, ' <span style="color:blue">return</span> ');
		   str = str.replace(/\stypedef\s/g, ' <span style="color:blue">return</span> ');

           //str = str.replace(/\s\s/g, ' ');
           return str;
       }

		
	
        //var formatTokenized = textArea.val().split('\n');
        var formatTokenized = textArea.val().split('\n');
       var rst = "";       


       for (var i = 0; i < formatTokenized.length; i++) {

           formatTokenized[i] = formatTokenized[i].replace(/</g, "&lt;");
           formatTokenized[i] = formatTokenized[i].replace(/>/g, "&gt;");
		   

           if (formatTokenized[i].match(/\s\s\s\s/g)) {

               /*

               -------- source code algorithm ------------

               if i == 0
                   create <pre><code> source code <br /></code></pre>

               if i >= 1
                   clear </code></pre> on [i-1] line and put it on the end of [i] line


               */

               formatTokenized[i] = formatTokenized[i].replace(/\s\s\s\s/, '');
               if (i >= 1) {
                   if (formatTokenized[i - 1].match('</code></pre>')) {

                       formatTokenized[i] = RegisterWordFormat(formatTokenized[i]);

                       formatTokenized[i - 1] = formatTokenized[i - 1].replace('</code></pre>', '');
                       formatTokenized[i] = formatTokenized[i] + '<br /></code></pre>';

                   } else {
                       formatTokenized[i] = RegisterWordFormat(formatTokenized[i]);

                       formatTokenized[i] = formatTokenized[i].replace(/\s\s\s\s/, '');
                       formatTokenized[i] = '<pre><code>' + formatTokenized[i] + '<br /></code></pre>';
                   }
               } else {
                   formatTokenized[i] = RegisterWordFormat(formatTokenized[i]);

                   formatTokenized[i] = formatTokenized[i].replace(/\s\s\s\s/, '');
                   formatTokenized[i] = '<pre><code>' + formatTokenized[i] + '<br /></code></pre>';

               }

           } else {


               //$('.result_display').html(this.html().replace(/\n/g, "<br />"));
               formatTokenized[i] = formatTokenized[i].replace(/</g, "&lt;");
               formatTokenized[i] = formatTokenized[i].replace(/>/g, "&gt;");

               // bold format
               formatTokenized[i] = formatTokenized[i].replace(/\s\*\*/g, "<strong>");
               formatTokenized[i] = formatTokenized[i].replace(/\*\*\s/g, "</strong>");

               // img
               formatTokenized[i] = formatTokenized[i].replace(/\s\$\$/g, "<img src=\"");
               formatTokenized[i] = formatTokenized[i].replace(/\$\$\s/g, "\"></img>");

               // hr
               formatTokenized[i] = formatTokenized[i].replace(/---------/g, "<hr />");

               // italic format
               // \s -> space,  \* -> '*'
               formatTokenized[i] = formatTokenized[i].replace(/\s--/g, "<em>");
               formatTokenized[i] = formatTokenized[i].replace(/--\s/g, "</em>");

               // link format
               // \s -> space,  \* -> '*'
               formatTokenized[i] = formatTokenized[i].replace(/\s\[\s/g, "<a href='");
               formatTokenized[i] = formatTokenized[i].replace(/\s\:=\s/g, "'>");
               formatTokenized[i] = formatTokenized[i].replace(/\s\]\s/g, "</a>");
			   
				// replace if there is any match <a href="http://.."></a>
			   while(formatTokenized[i].match(/'><\/a>/g)){
					var beginIndex = formatTokenized[i].indexOf("<a href='");
					var endIndex = formatTokenized[i].indexOf("'><\/a>");
					var lengthOfLink = endIndex - (beginIndex + 9);
					var link = formatTokenized[i].substring(beginIndex + 9, endIndex);
					console.log("link:"+link);
					console.log("begin:"+beginIndex);
					console.log("end:"+endIndex);
					formatTokenized[i] = formatTokenized[i].replace(/'><\/a>/,"'>"+link+"</a>");
			   }
				
               // headline

               formatTokenized[i] = formatTokenized[i].replace(/\s\~\~/g, "<h1>");
               formatTokenized[i] = formatTokenized[i].replace(/\~\~\s/g, "</h1>");


               if (formatTokenized[i].match(/\<hr\s\>?/g)) {

               } else if (formatTokenized[i].match(/\s\s[0-9]\.\s/g)) {

                   // ol format
                   formatTokenized[i] = formatTokenized[i].replace(/\s\s[0-9]\.\s/, '');
                   if (i >= 1) {
                       if (formatTokenized[i - 1].match('</ol>')) {
                           formatTokenized[i - 1] = formatTokenized[i - 1].replace('</ol>', '');
                           formatTokenized[i] = '<li>' + formatTokenized[i] + '</li></ol>';
                       } else {
                           formatTokenized[i] = formatTokenized[i].replace(/\s\s/, '');
                           formatTokenized[i] = '<ol><li>' + formatTokenized[i] + '</li></ol>';
                       }

                   } else {
                       formatTokenized[i] = formatTokenized[i].replace(/\s\s/, '');
                       formatTokenized[i] = '<ol><li>' + formatTokenized[i] + '</li></ol>';
                   }


               } else if (formatTokenized[i].match(/\s\s-\s/g)) {

                   // ul format
                   formatTokenized[i] = formatTokenized[i].replace(/-\s/g, '');
                   if (i >= 1) {
                       if (formatTokenized[i - 1].match('</ul>')) {
                           formatTokenized[i - 1] = formatTokenized[i - 1].replace('</ul>', '');
                           formatTokenized[i] = '<li>' + formatTokenized[i] + '</li></ul>';
                       } else {
                           formatTokenized[i] = formatTokenized[i].replace(/\s\s/, '');
                           formatTokenized[i] = '<ul><li>' + formatTokenized[i] + '</li></ul>';
                       }
                   } else {
                       formatTokenized[i] = formatTokenized[i].replace(/\s\s/, '');
                       formatTokenized[i] = '<ul><li>' + formatTokenized[i] + '</li></ul>';
                   }


               } else {
               		if(i < formatTokenized.length - 1)               	
                   		formatTokenized[i] += '<br />';

               }
           }


       }

	   
		var temp = "";
		for (var i = 0; i < formatTokenized.length; i++) {			
			formatTokenized[i] = formatTokenized[i].replace(/\\/g,'<span>&#92;</span>');
			formatTokenized[i] = formatTokenized[i].replace(/<img src=\"/g,"<img src=\"/common/");
			rst += formatTokenized[i];
		}
        //source code
        //msg = formatList(msg);
		
		textOutput.html(rst);
		
		
   }
     
});

