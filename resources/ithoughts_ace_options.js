$d.ready(function(){
	var editor = ace.edit("test_ace_editor");
	editor.getSession().setMode("ace/mode/php");
	setAceOpts(editor);
	
	function setAceOpts(editor){
		editor.setTheme("ace/theme/" + $("#theme").val());
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
			enableBasicAutocompletion: $("#autocompletion_autocompletion_ondemand")[0].checked,
			enableSnippets: true,
			enableLiveAutocompletion: $("#autocompletion_autocompletion_live")[0].checked
		});
	}
	
	$("#autocompletion_autocompletion_ondemand, #autocompletion_autocompletion_live, #theme").change(function(){
		setAceOpts(editor);
	});
});