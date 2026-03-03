            <script>
                // Replace the <textarea id="editor1"> with a CKEditor 4
                // instance, using default configuration.
                

				editor = CKEDITOR.replace( 'editor1' , { 
                	      uiColor: '#C2D6FF',
					      basicEntities: false,
					      height  : 575,
					     });

                editor.addCommand("printCommand", {
                    exec: function() {
                        printMe();
                    }
                });
                editor.ui.addButton('print', {
                    label: "Print",
                    command: 'printCommand',
                    toolbar: 'clipboard,0',
                });

			function printMe(){
				styleCss = "<?php echo base_url('assets/css/printStyle.css'); ?>"

			    	var contents = CKEDITOR.instances['editor1'].getData()
			        var frame1 = $('<iframe />');
			        frame1[0].name = "frame1";
			        frame1.css({ "position": "absolute", "top": "-1000000px" });
			        $("body").append(frame1);
			        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
			        frameDoc.document.open();
			        //Create a new HTML document.
			        frameDoc.document.write('<html><head><title>Preview</title>');
			        frameDoc.document.write('</head><body>');
			        //Append the external CSS file.
			        frameDoc.document.write('<link href="'+styleCss+'" rel="stylesheet" type="text/css" />');
			        //Append the DIV contents.
			        frameDoc.document.write(contents);
			        frameDoc.document.write('</body></html>');
			        frameDoc.document.close();
			        setTimeout(function () {
			            window.frames["frame1"].focus();
			            window.frames["frame1"].print();
			            frame1.remove();
			        }, 500);
			 			
			}
			</script>