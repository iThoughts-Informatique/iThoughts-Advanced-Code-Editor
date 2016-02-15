$d.ready(function(){
	var langs = {php: "php", "css":"css","html":"html","js":"javascript"};
	if(typeof ithoughts_ace.autocompletion == "undefined" || ithoughts_ace.autocompletion == null)
		ithoughts_ace.autocompletion = {};

	function setAceOpts(editor, language){
		editor.setTheme("ace/theme/" + ithoughts_ace.theme);
		if(Object.keys(langs).indexOf(language) != -1)
			editor.getSession().setMode("ace/mode/" + langs[language]);
		editor.getSession().setUseWrapMode(true);
		editor.setShowPrintMargin(false);
		editor.setOptions({
			tooltipFollowsMouse: true,
			displayIndentGuides: true,
			fontSize: "16px",
			cursorStyle: "wide",
			highlightSelectedWord: true,
			highlightActiveLine: true,
			behavioursEnabled: true,
			showFoldWidgets: true,
			enableBasicAutocompletion: ithoughts_ace.autocompletion["autocompletion_ondemand"] === true,
			enableSnippets: true,
			enableLiveAutocompletion: ithoughts_ace.autocompletion["autocompletion_live"] === true
		});
	}

	var $newContent = $("#template #newcontent");
	if($newContent.length > 0){
		var clone = $newContent[0].cloneNode();
		var $clone = $(clone);
		$clone.css({display:"none"});
		var $parent = $newContent.parent();

		var language = qs('[name="file"]');
		if(language)
			language = language.value;
		if(language)
			language = language.replace(/^.+\.(\w+?)$/, '$1');
		/*
		(["g_custom","t_custom","c_custom"]).map(function(elem){
			var editor = ace.edit(elem);
			setAceOpts(editor);
			var textarea = $("[data-ace-id=\"" + elem + "\"]");
			editor.getSession().on('change', function () {
				textarea.val(editor.getSession().getValue());
			});
		})
		/**/var editor = ace.edit("newcontent");
		$parent.append(clone);
		editor.getSession().on('change', function () {
			$clone.val(editor.getSession().getValue());
		});
		setAceOpts(editor, language);/**/
		editorContainer = editor.container;
		$editorContainer = $(editorContainer);
		$container = $("#template");

		function updateEditorPostion(){
			if(window.matchMedia('(max-width:783px)').matches)
				$container.css({marginRight:0});
			else
				$container.css({marginRight:200});
			
			var windowPos = {
				top: $w.scrollTop() + 32,
				bottom: $w.scrollTop() + $w.height()
			}
			var baseOffsetContainer = ($container.offset().top - (parseInt($container.css("top")) || 0));
			var offsetTop = Math.max(0, (windowPos.top - baseOffsetContainer));
			$container.css({top: offsetTop});
			
			
			var availableHeight = windowPos.bottom - Math.max(baseOffsetContainer, windowPos.top);
			console.log($w.height(), availableHeight);
			var submitHeight = $container.height() - $editorContainer.height();
			console.log(submitHeight);
			var height = (availableHeight - submitHeight) - (parseFloat($('#template p.submit').css("margin-bottom")) || 0);
			$editorContainer.height(height);
			editor.resize();
			
		}
		$container.css({position:"relative"});
		$w.resize(updateEditorPostion).scroll(updateEditorPostion).scroll();
	}
})