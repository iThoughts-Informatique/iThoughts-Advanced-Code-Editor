/**
 * @file Client JS file that will be required by every functions of plugin
 *
 * @author Gerkin
 * @copyright 2016 iThoughts Informatique
 * @license https://raw.githubusercontent.com/iThoughts-Informatique/iThoughts-Advanced-Code-Editor/master/LICENSE GPL3.0
 * @package ithoughts_advanced_code_editor
 *
 * @version 1.2.8
 */

window.ace_editors = [];

ithoughts_ace.setAceOpts = function(editor, language, opts){
	var keptData = {editor: editor, lang: language};
	if(typeof opts != "undefined"){
		keptData["opts"] = opts;
		window.ace_editors.push(keptData);
	}
	ithoughts_ace.update_editor_style(editor, ithoughts_ace.theme);
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
		tabSize: 4,
		useSoftTabs: false,
		enableBasicAutocompletion: ithoughts_ace.autocompletion["autocompletion_ondemand"] === true,
		enableSnippets: true,
		enableLiveAutocompletion: ithoughts_ace.autocompletion["autocompletion_live"] === true
	});
}
ithoughts_ace.update_editor_style = function(editor, theme){
	editor.setTheme("ace/theme/" + theme);
}

$d.ready(function(){
	ace.config.set('basePath', 	ithoughts_ace.basepath);
	ace.require("ace/ext/beautify");

	var $editors = $(".ace-editor");
	$editors.each(function(index, elem){
		var id = elem.getAttribute("id");
		if(id == null){
			id = (new Date()).getTime() + "_" + $(elem).offset().top + $(elem).offset().left + "_" + $(elem).width() + $(elem).height();
			elem.setAttribute("id", id);
		}
		var lang = elem.getAttribute("data-lang");
		var clone = elem.cloneNode();
		clone.removeAttribute("id");
		var $clone = $(clone);
		$clone.css({opacity:0,height:0,width:0,minHeight:0,minWidth:0,position:"absolute",top:"50%",left:"50%",padding:0,margin:0});
		var $parent = $(elem).parent();
		var editor = ace.edit(id);
		editor.getSession().on('change', function () {
			$clone.val(editor.getSession().getValue());
		});
		$(editor.container).prepend($clone);
		clone.setAttribute("id", id);
		$clone.focusin(function(){
			editor.focus();
		})
		ithoughts_ace.setAceOpts(editor, lang);
	});
});