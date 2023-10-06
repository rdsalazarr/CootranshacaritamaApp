<!DOCTYPE html>
<html>
<head>
  <script src="./tinymce/tinymce.min.js" referrerpolicy="origin"></script>
  <script type="text/javascript">

    // checklist | forecolor backcolor casechange permanentpen formatpainter removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media pageembed template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment

    //print preview powerpaste casechange importcss searchreplace autolink autosave save directionality advcode visualblocks visualchars fullscreen image link media mediaembed template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists checklist wordcount tinymcespellchecker a11ychecker imagetools textpattern noneditable help formatpainter permanentpen pageembed charmap tinycomments mentions quickbars linkchecker emoticons advtable export
    
  tinymce.init({
    language: 'es',
    height: 400,
    selector: '#mytextarea',
    plugins: 'print preview powerpaste casechange importcss searchreplace autolink autosave save directionality advcode visualblocks visualchars fullscreen image link media mediaembed template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists checklist wordcount tinymcespellchecker a11ychecker imagetools textpattern noneditable help formatpainter permanentpen pageembed charmap tinycomments mentions quickbars linkchecker emoticons advtable export',
    mobile: {
        plugins: 'print preview powerpaste casechange importcss searchreplace autolink autosave save directionality advcode visualblocks visualchars fullscreen image link media mediaembed template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists checklist wordcount tinymcespellchecker a11ychecker textpattern noneditable help formatpainter pageembed charmap mentions quickbars linkchecker emoticons advtable'
    },
    menubar: 'file edit view insert format tools table',
    toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | forecolor backcolor | link | table',

    menu:{
			file: {title: 'File', items: 'newdocument'},
		    edit: {title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall searchreplace'},
		    insert: {title: 'Insert', items: 'link  | hr | inserttable'},
		    view: {title: 'View', items: 'visualaid  | fullscreen'},
		    format: {title: 'Format', items: 'bold italic underline strikethrough superscript subscript'},
		    table: {title: 'Table', items: 'inserttable tableprops deletetable | cell row column'}		
		},

        table_responsive_width: true, 
        browser_spellcheck: true,
        spellchecker_language: 'es', 
        spellchecker_wordchar_pattern: /[^\s,\.]+/g ,

    });

  </script>
</head>
<body>
  <textarea id="mytextarea">Next, use our Get Started docs to setup Tiny!</textarea>


</body>
</html>