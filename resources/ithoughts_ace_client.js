$w.ready(function(){
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

	var $editors = $(".ace-editor");
	$editors.each(function(index, elem){
		var id = elem.getAttribute("id");
		var lang = elem.getAttribute("data-lang");
		var clone = elem.cloneNode();
		var $clone = $(clone);
		$clone.css({display:"none"});
		var $parent = $(elem).parent();
		$parent.append(clone);
		var editor = ace.edit(id);
		editor.getSession().on('change', function () {
			$clone.val(editor.getSession().getValue());
		});
		setAceOpts(editor, lang);
	})
});