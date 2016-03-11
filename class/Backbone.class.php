<?php
/**
 * @file Backbone class. Init options & handle public functions
 *
 * @copyright 2015-2016 iThoughts Informatique
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.fr.html GPLv2
 * @package ithoughts_advanced_code_editor
 *
 * @version 1.2.1
 */

namespace ithoughts\ace;


class Backbone extends \ithoughts\v1_1_1\Backbone{

	function __construct($plugin_base) {
		if(defined("WP_DEBUG") && WP_DEBUG)
			$this->minify = "";
		$this->optionsName		= "ithoughts_ace";
		$this->base_path		= $plugin_base;
		$this->base_class_path	= $plugin_base . '/class';
		$this->base_lang_path	= $plugin_base . '/lang';
		$this->base_url			= plugins_url( '', dirname(__FILE__) );

		$this->defaultOptions	= array(
			"enable_shortcode"	=> false,
			"theme"				=> "monokai",
			"autocompletion"	=> array(
				"autocompletion_ondemand"	=> true,
				"autocompletion_live" 		=> true
			)
		);

		add_shortcode("ace_editor", array(&$this, "ace_editor_shortcode"));

		add_action( 'plugins_loaded',	array( &$this,	'localisation')	);
		add_action( 'init',				array( &$this,	'register_scripts_and_styles' )	);

		parent::__construct();
	}

	public function compose_ace_js_obj($opts = NULL){
		if($opts == NULL)
			$opts = $this->get_options();
		var_dump($opts);
		unset($opts["enable_shortcode"]);
		if(!isset($opts["autocomplete"]))
			$opts["autocomplete"] = array();
		$opts["basepath"] = $this->get_base_url() . "/submodules/ace-builds/src-min-noconflict";
		$opts["langs"] = array(
			"php"	=> "php",
			"js"	=> "javascript",
			"html"	=> "html",
			"less"	=> "less",
			"css"	=> "css"
		);
		$opts["ajax"] = admin_url('admin-ajax.php');
		if(class_exists("\\ithoughts\\ace\\Admin")){
			$opts["ignoreReplacement"] = apply_filters("ithoughts_ace-ignore_replaced", array());
		}
		return $opts;
	}

	public function ace_editor_shortcode($atts, $content = ""){
		$opts = $this->get_options();
		if($opts["enable_shortcode"]){
			wp_localize_script(
				"ithoughts-ace-comon",
				"ithoughts_ace",
				$this->compose_ace_js_obj($opts)
			);

			wp_enqueue_script("ithoughts-ace-comon");
			wp_enqueue_style('ithoughts-ace');

			if(isset($atts["lang"]) && $atts["lang"]){
				$atts["data-lang"] = $atts["lang"];
				unset($atts["lang"]);
			}
			if(isset($atts["class"]) && $atts["class"])
				$atts["class"] .= "ace-editor";
			else
				$atts["class"] = "ace-editor";

			if(!(isset($atts["id"]) && $atts["id"]))
				$atts["id"] = "ace_editor-".substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 15);

			$attrsStr = \ithoughts\v1_1\Toolbox::concat_attrs($atts);
			return "<textarea $attrsStr>$content</textarea>";
		} else {
			return "";
		}
	}

	public function register_scripts_and_styles(){
		wp_register_script(
			"ace-editor",
			$this->get_base_url() . "/submodules/ace-builds/src-min-noconflict/ace.js",
			array(),
			"1.0.0"
		);
		wp_register_script(
			"ace-autocomplete",
			$this->get_base_url() . "/submodules/ace-builds/src-min-noconflict/ext-language_tools.js",
			array("ace-editor"),
			"1.0.0"
		);
		wp_register_script(
			"ace-beautify",
			$this->get_base_url() . "/submodules/ace-builds/src-min-noconflict/ext-beautify.js",
			array("ace-editor"),
			"1.0.1"
		);
		wp_register_style(
			'ithoughts-ace',
			$this->get_base_url() . "/resources/ithoughts_ace{$this->get_minify()}.css",
			array(),
			"1.1.0"
		);
		wp_register_script(
			'ithoughts-ace-comon',
			$this->get_base_url() . "/resources/ithoughts_ace-comon{$this->get_minify()}.js",
			array("ace-editor","ace-beautify","ace-autocomplete", "jquery","ithoughts_aliases"),
			"1.1.0"
		);
	}

	public function localisation(){
		load_plugin_textdomain( 'ithoughts-advanced-code-editor', false, plugin_basename( dirname( __FILE__ ) )."/../lang" );
	}
}