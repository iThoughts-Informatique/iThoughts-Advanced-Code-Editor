<?php
/**
  * @copyright 2015-2016 iThoughts Informatique
  * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.fr.html GPLv2
  */

namespace ithoughts\ace;


class Admin extends \ithoughts\v1_0\Singleton{

	public function __construct() {
		add_action( 'admin_enqueue_scripts',				array(&$this,	"register_scripts"), 0,999999);
		add_action( 'admin_menu',							array(&$this,	"menu_page"));
		add_action( 'wp_ajax_ithoughts_ace_update_options',	array(&$this,	'update_options') );
	}

	public function register_scripts(){
		$backbone = \ithoughts\ace\Backbone::get_instance();

		wp_register_script(
			'ithoughts-simple-ajax',
			$backbone->get_base_url() . '/submodules/iThoughts-WordPress-Plugin-Toolbox/js/simple-ajax-form'.$backbone->get_minify().'.js',
			array('jquery-form',"ithoughts_aliases"),
			"1.0.0"
		);
		wp_register_script(
			'ithoughts-ace-admin',
			$backbone->get_base_url() . "/resources/ithoughts_ace_admin{$backbone->get_minify()}.js",
			array('ithoughts_aliases', 'ace-editor', 'ace-autocomplete', "ithoughts-ace-comon"),
			"1.0.0",
			false
		);
		wp_enqueue_script('ithoughts-ace-admin');
		wp_register_script(
			'ithoughts-ace-options',
			$backbone->get_base_url() . "/resources/ithoughts_ace_options{$backbone->get_minify()}.js",
			array('ithoughts_aliases', 'ace-editor', 'ace-autocomplete', 'ithoughts-simple-ajax', "ithoughts-ace-comon"),
			"1.0.0"
		);
		wp_enqueue_style('ithoughts-ace');
	}

	public function menu_page(){
		add_options_page(
			__("iThoughts Advanced Code Editor", 'ithoughts-advanced-code-editor' ),
			__("iThoughts ACE", 'ithoughts-advanced-code-editor' ),
			"manage_options",
			"ithoughts_ace",
			array(&$this, "options")
		);
	}

	public function options(){
		$backbone = \ithoughts\ace\Backbone::get_instance();

		$ajax         = admin_url( 'admin-ajax.php' );
		$options      = $backbone->get_options();

		/* Add required scripts for WordPress Spoilers (AKA PostBox) */
		wp_enqueue_script('postbox');
		wp_enqueue_script('post');
		wp_enqueue_script('ithoughts-ace-options');

		$optionsInputs = array(
			"enable_shortcode"	=> \ithoughts\v1_0\Toolbox::generate_input_check(
				"enable_shortcode",
				array(
					"radio" => false,
					"selected" => $options["enable_shortcode"],
					"options" => array(
						"enabled" => array(
							"attributes" => array(
								"id" => "enable_shortcode"
							)
						)
					)
				)
			),
			"theme"				=> \ithoughts\v1_0\Toolbox::generate_input_select(
				"theme",
				array(
					"selected" => $options["theme"],
					"options" => array(
						"ambiance" => array(
							"text" => "Ambiance"
						),
						"chaos" => array(
							"text" => "Chaos"
						),
						"chrome" => array(
							"text" => "Chrome"
						),
						"clouds" => array(
							"text" => "Clouds"
						),
						"clouds_midnight" => array(
							"text" => "Clouds Midnight"
						),
						"cobalt" => array(
							"text" => "Cobalt"
						),
						"crimson_editor" => array(
							"text" => "Crimson Editor"
						),
						"dawn" => array(
							"text" => "Dawn"
						),
						"dreamweaver" => array(
							"text" => "Dreamweaver"
						),
						"eclipse" => array(
							"text" => "Eclipse"
						),
						"github" => array(
							"text" => "Github"
						),
						"idle_fingers" => array(
							"text" => "Idle Fingers"
						),
						"iplastic" => array(
							"text" => "Iplastic"
						),
						"katzenmilch" => array(
							"text" => "Katzenmilch"
						),
						"kr_theme" => array(
							"text" => "Kr Theme"
						),
						"kuroir" => array(
							"text" => "Kuroir"
						),
						"merbivore" => array(
							"text" => "Merbivore"
						),
						"merbivore_soft" => array(
							"text" => "Merbivore Soft"
						),
						"mono_industrial" => array(
							"text" => "Mono Industrial"
						),
						"monokai" => array(
							"text" => "Monokai"
						),
						"pastel_on_dark" => array(
							"text" => "Pastel On Dark"
						),
						"solarized_dark" => array(
							"text" => "Solarized Dark"
						),
						"solarized_light" => array(
							"text" => "Solarized Light"
						),
						"sqlserver" => array(
							"text" => "SqlServer"
						),
						"terminal" => array(
							"text" => "Terminal"
						),
						"textmate" => array(
							"text" => "Textmate"
						),
						"tomorrow" => array(
							"text" => "Tomorrow"
						),
						"tomorrow_night_blue" => array(
							"text" => "Tomorrow Night Blue"
						),
						"tomorrow_night_bright" => array(
							"text" => "Tomorrow Night Bright"
						),
						"tomorrow_night_eighties" => array(
							"text" => "Tomorrow Night Eighties"
						),
						"tomorrow_night" => array(
							"text" => "Tomorrow Night"
						),
						"twilight" => array(
							"text" => "Twilight"
						),
						"vibrant_ink" => array(
							"text" => "Vibrant Ink"
						),
						"xcode" => array(
							"text" => "XCode"
						),
					)
				)
			),
			"autocompletion"	=> \ithoughts\v1_0\Toolbox::generate_input_check(
				"autocompletion[]",
				array(
					"radio" => false,
					"selected" => $options["autocompletion"],
					"options" => array(
						"autocompletion_ondemand" => array(
						),
						"autocompletion_live" => array(
						),
					)
				)
			),
		);
		require($backbone->get_base_path() . "/templates/options.php");
	}

	public function update_options(){
		$backbone = \ithoughts\ace\Backbone::get_instance();
		$ace_options = $backbone->get_options();

		$postValues = $_POST;
		$postValues['enable_shortcode']			= \ithoughts\v1_0\Toolbox::checkbox_to_bool($postValues,'enable_shortcode',	"enabled");
		$postValues["autocompletion"]	= \ithoughts\v1_0\Toolbox::checkbox_to_bool($postValues,'autocompletion',	array("autocompletion_ondemand", "autocompletion_live"));

		$outtxt = "";
		$valid = true;
		$reload = false;

		if($backbone->set_options($postValues))
			$outtxt .= ('<p>' . __('Options updated', 'ithoughts-advanced-code-editor' ) . '</p>') ;
		else
			$outtxt .= ('<p>' . __('Could not update options', 'ithoughts-advanced-code-editor' ) . '</p>') ;

		die( json_encode(array(
			"reload" => $reload,
			"text" =>$outtxt,
			"valid" => $valid
		)));
	}
}