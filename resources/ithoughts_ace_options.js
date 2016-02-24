$d.ready(function(){
	var editor = ace.edit("test_ace_editor");
	editor.getSession().setMode("ace/mode/php");
	$("#autocompletion_autocompletion_ondemand, #autocompletion_autocompletion_live, #theme").change(function(){
		ithoughts_ace.setAceOpts(editor);
	}).change();
	
	
});