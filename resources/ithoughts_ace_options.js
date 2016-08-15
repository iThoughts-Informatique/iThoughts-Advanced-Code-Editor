/**
 * @file Client Javascript that handles "options" page specific functions & logic
 *
 * @author Gerkin
 * @copyright 2016 iThoughts Informatique
 * @license https://raw.githubusercontent.com/iThoughts-Informatique/iThoughts-Advanced-Code-Editor/master/LICENSE GPL3.0
 * @package ithoughts_advanced_code_editor
 *
 * @version 1.2.10
 */

(function(ithoughts){
	'use strict';

	var i_a = ithoughts_ace;

	ithoughts.$d.ready(function(){
		var editor = ace.edit("test_ace_editor");
		i_a.setAceOpts(editor, "php",{});
		ithoughts.$("#autocompletion_autocompletion_ondemand, #autocompletion_autocompletion_live, #theme").change(updatepreview).change();
		ithoughts.$('#theme option').mouseover(updatepreview);

		function updatepreview(){
			if(this.id == "theme")
				i_a.update_editor_style(editor, this.value);
			else if(this.id == "autocompletion_autocompletion_ondemand"){
				i_a.autocompletion.autocompletion_ondemand = this.checked;
				i_a.setAceOpts(editor, "php");
			}
			else if(this.id == "autocompletion_autocompletion_live"){
				i_a.autocompletion.autocompletion_live = this.checked;
				i_a.setAceOpts(editor, "php");
			}
		}
	});
})(Ithoughts.v3);