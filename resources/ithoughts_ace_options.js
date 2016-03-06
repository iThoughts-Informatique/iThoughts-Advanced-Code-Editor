$d.ready(function(){
	var editor = ace.edit("test_ace_editor");
	ithoughts_ace.setAceOpts(editor, "php",{});
	$("#autocompletion_autocompletion_ondemand, #autocompletion_autocompletion_live, #theme").change(updatepreview).change();
	$('#theme option').mouseover(updatepreview);

	function updatepreview(){
		console.log(this.value);
		if(this.id == "theme")
			ithoughts_ace.update_editor_style(editor, this.value);
		else if(this.id == "autocompletion_autocompletion_ondemand"){
			ithoughts_ace.autocompletion.autocompletion_ondemand = this.checked;
			ithoughts_ace.setAceOpts(editor, "php");
		}
		else if(this.id == "autocompletion_autocompletion_live"){
			ithoughts_ace.autocompletion.autocompletion_live = this.checked;
			ithoughts_ace.setAceOpts(editor, "php");
		}
	}
});