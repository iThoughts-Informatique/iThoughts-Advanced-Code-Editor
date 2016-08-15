/**
 * @file Admin-specific functions for iThoughts Advanced Code Editor
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

	var i_a = ithoughts_ace,
		$ = ithoughts.$,
		$w = ithoughts.$w;

	ithoughts.$d.ready(function(){
		var replacedEditorsLoc = {
			wordpressEditors:{
				target:"#template #newcontent",
				container:"#template",
				preInit: function(opts){
					$("#scrollto").attr("id","_scrollto");
					opts.clone = opts.$elem[0].cloneNode();
					opts.$textarea = $(opts.clone);
					opts.$textarea.css({display:"none"});

					var fileInput = ithoughts.qs('[name="file"]');
					if(fileInput)
						opts.language = fileInput.value;
					if(opts.language)
						opts.language = opts.language.replace(/^.+\.(\w+?)$/, '$1');

					// Check if we can lock the POST for sandbox
					if(opts.language == "php"){
						$("#template .submit").append('<p class="codecheck codecheck-php codecheck-on">CodeCheck</p>');
						console.info("Sandboxing");
						opts.submit = ithoughts.gei("submit");
						if(opts.submit){
							$("#template").submit(function(e){
								if(opts.submit.getAttribute("data-waschecked") != "unchecked"){
								} else {
									return ithoughts_ace.sandboxPhp(opts, fileInput.value, e, submit);
								}
							});
						}
					}
					return opts;
				},
				postInit: function(editor, opts){
					if(opts.language == "php"){
						editor.on("change",function(){
							if(opts.submit){
								opts.submit.setAttribute("data-waschecked", "unchecked");
							}
						});
					}
					if(opts.$container){
						opts.$container.css({position:"relative"});
					}
					opts.$parent.append(opts.$textarea);
					return function(){
						if(window.matchMedia('(max-width:783px)').matches)
							opts.$container.css({marginRight:0});
						else
							opts.$container.css({marginRight:200});

						var windowPos = {
							top: $w.scrollTop() + 32,
							bottom: $w.scrollTop() + $w.height()
						}
						var baseOffsetContainer = (opts.$container.offset().top - (parseInt(opts.$container.css("top")) || 0));
						var offsetTop = Math.max(0, (windowPos.top - baseOffsetContainer));
						opts.$container.css({top: offsetTop});


						var availableHeight = windowPos.bottom - Math.max(baseOffsetContainer, windowPos.top);
						var submitHeight = opts.$container.height() - opts.$editorContainer.height();
						var height = (availableHeight - submitHeight) - (parseFloat($('#template p.submit').css("margin-bottom")) || 0);
						opts.$editorContainer.height(height);
						editor.resize();
					}
				}
			},
			ithoughtsHTMLSnippets:{
				target:"body.post-type-html_snippet #wp-content-editor-container",
				container:"#wp-content-editor-container",
				preInit: function(opts){
					opts.language = "php";

					opts.$textarea = $('#content').clone(); 
					opts.$textarea.css({display:"none"});
					opts.$parent
						.append(opts.$textarea)
						.prepend($.parseHTML('<div id="ed_toolbar"></div>')	);
					$("#wp-content-editor-tools").remove();
					return opts;
				},
				postInit: function(editor, opts){
					opts.editorContainer.classList.add("ithoughts_ace-postcontent");
					opts.editorContainer.classList.add('mce-edit-area');
					opts.editorContainer.style.height = "388px";

					opts.$container.css({marginTop:20});
					editor.resize();
				}
			},
			WpAllImport:{
				target:"#wp_all_import_code",
				container:"#wp_all_import_code",
				preInit: function(opts){
					opts.$textarea = $(".CodeMirror.cm-s-default");
					opts.$textarea.hide();
					var codemirror = $('.CodeMirror.cm-s-default')[0].CodeMirror;
					opts.$textarea.val = function(text){
						codemirror.doc.setValue(text);
					}
					opts.language = "php";
					return opts;
				}
			},
		};

		// Ignore specified types
		if(ithoughts_ace.ignoreReplacement && ithoughts_ace.ignoreReplacement.constructor.name == "Array" && ithoughts_ace.ignoreReplacement.length > 0){
			for(var i = 0, j = ithoughts_ace.ignoreReplacement.length; i < j; i++){
				if(replacedEditorsLoc[ithoughts_ace.ignoreReplacement[i]]){
					delete replacedEditorsLoc[ithoughts_ace.ignoreReplacement[i]];
				}
			}
		}

		// Merge
		if(window.replacedEditors && window.replacedEditors.constructor.name == "Object"){
			for(var type in replacedEditorsLoc){
				if(typeof window.replacedEditors[type] == "undefined"){
					window.replacedEditors[type] = replacedEditorsLoc[type];
				}
			}
		} else {
			window.replacedEditors = replacedEditorsLoc;
		}




		for(var type in window.replacedEditors){
			(function(){
				console.log("Init type", type);
				var typeopts = window.replacedEditors[type];
				var opts = {};
				opts.$elem = $(typeopts["target"]);
				if(opts.$elem.length == 1){
					opts.$parent = opts.$elem.parent();

					opts.$container = $(typeopts["container"]);
					if(opts.$container.length > 0)
						opts.$container = $(opts.$container[0]);
					else
						opts.$container = {};

					if(typeof typeopts["preInit"] != "undefined" && typeopts["preInit"] != null && typeopts["preInit"].constructor.name == "Function"){
						opts = typeopts["preInit"](opts);
					}

					var editor = ace.edit(opts.$elem[0].id);
					ithoughts_ace.setAceOpts(editor, opts.language, opts);
					opts.editorContainer = editor.container; 
					opts.$editorContainer = $(opts.editorContainer);
					opts.editor = editor;
					var params = decodeParameters(location.search.substring(1));
					if(typeof params["scrollto"] != "undefined" && params["scrollto"] != null){
						editor.scrollToLine(params["scrollto"]);
					}

					var updateEditorPosition = null;
					if(typeof typeopts["postInit"] != "undefined" && typeopts["postInit"] != null && typeopts["postInit"].constructor.name == "Function"){
						updateEditorPosition = typeopts["postInit"](editor, opts);
					}
					var scrollto = $('[name="scrollto"]');
					if(opts.$textarea && opts.$textarea.length > 0){
						editor.getSession().on('change', function () {
							scrollto.val(editor.getSelectionRange().start.row);
							opts.$textarea.val(editor.getSession().getValue());
						});
					}
					if(typeof updateEditorPosition != "undefined" && updateEditorPosition != null && updateEditorPosition.constructor.name == "Function"){
						$w.resize(updateEditorPosition).scroll(updateEditorPosition).scroll();
					}
				}
			})();
		}
	});

	if(typeof ithoughts_ace == "undefined")
		ithoughts_ace = {};

	ithoughts_ace.sandboxPhp = function(editorObject, file, event, submitButton, form){
		console.log(form,editorObject);
		function toggleLoadingMarker(displayed){
			$("#ithoughts_ace-loader").remove();
			if(displayed === true){
				$(".submit").append($.parseHTML('<div id="ithoughts_ace-loader"></div>'));
			}
		}

		function setResultDisplay(data){
			toggleLoadingMarker(false);
			try{
				if(data && data.constructor.name == "String")
					data = JSON.parse(data);
				$("#checkresult").remove();
				var classes = ["notice"];
				if(data.success && data.data && data.data.text)
					classes.push("notice-success");
				else
					classes.push("notice-error");
				$("#submit").parent().css({display: "inline-block",width: "auto"}).parent().append($.parseHTML('<div id="checkresult" class="' + classes.join(" ") + '">' + (data.data && data.data.text ? data.data.text : "<p>Could not parse server response</p>") + "</div>"));
				if(data.success && data.data && data.data.text){
					submitButton.setAttribute("data-waschecked","checked-valid");
					$(submitButton).click();
				} else {
					submitButton.setAttribute("data-waschecked","checked-invalid");
				}
			} catch(e){
				$("#submit").parent().css({display: "inline-block",width: "auto"}).parent().append($.parseHTML('<div id="checkresult" class="notice notice-error"><p>Could not parse server response</p></div>'));
			}
		}

		toggleLoadingMarker(true);


		$.post(ithoughts_ace.ajax + "?" + $.param(params), {
			"code": editorObject.$textarea.val(),
			"file": file,
			"action": "ithoughts_ace_check_syntax"
		}).success(function(data){
			setResultDisplay(data);
		}).fail(function(data){
			setResultDisplay(data);
		});
		event.preventDefault();
		return false;
	}

	function decodeParameters(querystring){
		var qs = ('{"' + decodeURI(querystring).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}').replace(/([\{,]"[^"]*?"(?=[,\}]))/g, '$1:true');
		try{
			return JSON.parse(qs);
		} catch(e){
			console.warn("Could not parse query string:",qs,e);
			return {};
		}
	}
})(Ithoughts.v3);