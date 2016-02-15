<?php
/**
  * @copyright 2015-2016 iThoughts Informatique
  * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.fr.html GPLv2
  */

namespace ithoughts\ace;


class Backbone extends \ithoughts\v1_0\Backbone{

	function __construct($plugin_base) {
		if(defined("WP_DEBUG") && WP_DEBUG)
			$this->minify = "";
		$this->optionsName		= "ithoughts_ace";
		$this->base_path		= $plugin_base;
		$this->base_class_path	= $plugin_base . '/class';
		$this->base_lang_path	= $plugin_base . '/lang';
		$this->base_url			= plugins_url( '', dirname(__FILE__) );

		$this->defaults			= array(
			"enable_shortcode"	=> false,
			"theme"				=> "monokai",
			"autocompletion"	=> array(
				"autocompletion_ondemand"	=> true,
				"autocompletion_live" 		=> true
			)
		);

		$this->options			= $this->initOptions();

		if($this->get_option("enable_shortcode")){
			add_shortcode("ace_editor", array(&$this, "ace_editor_shortcode"));
		}

		add_action( 'plugins_loaded',	array( &$this,	'localisation')	);
		add_action( 'init',				array( &$this,	'register_scripts_and_styles' )	);

		parent::__construct();
	}

	public function ace_editor_shortcode($atts, $content = ""){
		wp_enqueue_script("ithougts-ace-client");
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
		
		$attrsStr = \ithoughts\v1_0\Toolbox::concat_attrs($atts);
		return "<textarea $attrsStr>$content</textarea>";
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
		wp_register_style(
			'ithoughts-ace',
			$this->get_base_url() . "/resources/ithoughts_ace{$this->get_minify()}.css",
			array(),
			"1.0.0"
		);
		if($this->get_option("enable_shortcode")){
			wp_register_script(
				"ithougts-ace-client",
				$this->get_base_url() . "/resources/ithoughts_ace_client{$this->get_minify()}.js",
				array("ithoughts_aliases", "ace-autocomplete"),
				"1.0.0"
			);
			$opts = $this->get_options();
			unset($opts["enable_shortcode"]);
			wp_localize_script(
				"ithougts-ace-client",
				"ithoughts_ace",
				$opts
			);
		}
	}

	private function initOptions(){
		$opts = array_merge($this->get_options(true), get_option( $this->optionsName, $this->get_options(true) ));
		return $opts;
	}

	public function get_options($onlyDefaults = false){
		if($onlyDefaults)
			return $this->defaults;

		return $this->options;
	}

	public function get_option($name, $onlyDefaults = false){
		$arr = $this->options;
		if($onlyDefaults)
			return $this->defaults;

		if(isset($arr[$name]))
			return $arr[$name];
		else
			return NULL;
	}

	public function localisation(){
		load_plugin_textdomain( 'ithoughts-advanced-code-editor', false, plugin_basename( dirname( __FILE__ ) )."/../lang" );
	}
}