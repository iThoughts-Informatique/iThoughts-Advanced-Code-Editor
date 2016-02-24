window.ace_editors = [];

ithoughts_ace.setAceOpts = function(editor, language, opts){
	var keptData = {editor: editor, lang: language};
	if(typeof opts != "undefined")
		keptData["opts"] = opts;
	window.ace_editors.push(keptData);
	editor.setTheme("ace/theme/" + ithoughts_ace.theme);
	var s = editor.getSession();
	if(Object.keys(ithoughts_ace.langs).indexOf(language) != -1)
		s.setMode("ace/mode/" + ithoughts_ace.langs[language]);
	s.setUseWrapMode(true);
	s.setUseWorker(false);
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

$w.ready(function(){
	ace.config.set('basePath', ithoughts_ace.basepath);
	ace.require("ace/ext/beautify");

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
		ithoughts_ace.setAceOpts(editor, lang);
	});
});