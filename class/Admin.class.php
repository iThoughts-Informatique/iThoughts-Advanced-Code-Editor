<?php

/**
 * @file Admin class file
 *
 * @author Gerkin
 * @copyright 2015-2016 iThoughts Informatique
 * @license https://raw.githubusercontent.com/iThoughts-Informatique/iThoughts-Advanced-Code-Editor/master/LICENSE GPL3.0
 * @package ithoughts_advanced_code_editor
 *
 * @version 1.2.10
 */

namespace ithoughts\ace;
use \ithoughts\v1_2\Toolbox as Toolbox;

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

class Admin extends \ithoughts\v1_0\Singleton{

	public function __construct() {
		add_action( 'admin_enqueue_scripts',				array(&$this,	"register_scripts"), 0,999999);
		add_action( 'admin_menu',							array(&$this,	"menu_page") );
		add_action( 'wp_ajax_ithoughts_ace_update_options',	array(&$this,	'update_options') );
		add_action( 'wp_ajax_ithoughts_ace_check_syntax',	array(&$this,	'check_syntax_ajax') );
		add_action( 'wp_ajax_ithoughts_ace_report_send',	array(&$this,	'send_report') );
	}

	public function check_syntax_ajax(){
		if(!isset($_POST["code"]))
			wp_die("Error");
		$code = $_POST["code"];
		if(!isset($_POST["file"]))
			wp_die("Error");
		$file = $_POST["file"];

		$ret = $this->check_syntax($code);

		$response = array(
			"included_files" => get_included_files()
		);
		if($ret == ""){
			$response["text"] = __("Everything seems ok.", 'ithoughts-advanced-code-editor' ).'<form class="ithoughts-ace-report" method="POST" target="_blank" action="'.admin_url('options-general.php?page=ithoughts_ace_report').'"><input type="hidden" value="fn" name="type"/><input type="hidden" name="code" value="'.htmlspecialchars($code).'"/><input type="hidden" name="file" value="'.htmlspecialchars($file).'"/><input type="hidden" name="included" value="'.htmlspecialchars(json_encode($response["included_files"])).'"/><button class="button button-secondary" id="ithoughts_ace-falseresult"><span class="dashicons dashicons-warning"></span> '.__("Report a false negative?", 'ithoughts-advanced-code-editor' ).'</button></form>';
			wp_send_json_success($response);
		} else {
			$response["text"] = __("An error has been found. Here are the lasts words from the server:", 'ithoughts-advanced-code-editor' )."<pre>".$ret."</pre>".'<form class="ithoughts-ace-report" method="POST" target="_blank" action="'.admin_url('options-general.php?page=ithoughts_ace_report').'"><input type="hidden" value="fp" name="type"/><input type="hidden" name="code" value="'.htmlspecialchars($code).'"/><input type="hidden" name="file" value="'.htmlspecialchars($file).'"/><input type="hidden" name="included" value="'.htmlspecialchars(json_encode($response["included_files"])).'"/><button class="button button-secondary" id="ithoughts_ace-falseresult"><span class="dashicons dashicons-warning"></span> '.__("Report a false positive?", 'ithoughts-advanced-code-editor' ).'</button></form>';
			wp_send_json_error($response);
		}
	}


	/**
	 * Runs the check process on the provided $code
	 * @author Gerkin
	 * @param  String $code The PHP code to check. HTML mode by default (enable PHP by openning the <?php ?> tag)
	 * @return String Output string
	 * @uses error_reporting
	 * @uses ini_set
	 */
	private function check_syntax($code){
		// First, force setup of error logging
		$oldValues = array(
			"d_e" => ini_get("display_errors"),
			"e_r" => error_reporting(E_ALL)
		);
		ini_set("display_errors", 1);

		$code = "?>".stripslashes($code);
		{
			$regex = "/(namespace\s+[\\w\\\\]+;)/";
			$returner = "return;if(false){ ";
			if(preg_match($regex, $code)){
				$code = preg_replace($regex,"$1\n".$returner,$code);
				if(strripos($code, "?>") > strripos($code, "<?php")){
					//echo "Append PHP with namespace";
					$code .= "<?php";
				}
				$code .= " }";
			} else {
				if(strripos($code, "?>") > strripos($code, "<?php")){
					//echo "Append PHP without namespace";
					$code .= "<?php";
				}
				$code = $returner.$code." }";
			}
		}

		/*var_dump($code);
		echo "\n\n";*/
		
		
		$regex = "/ in ".preg_quote("<b>".__FILE__, '/')."\(\d+\) : eval\(\)'d code\<\/b\>/";
		register_shutdown_function(array(&$this, "handle_fatal"));
		ob_start(array(&$this, "handle_fatal"));
		//var_dump($code);
		$ret = eval($code);
		$out = ob_get_clean();

		// Restore original log
		error_reporting($oldValues["e_r"]);
		ini_set("display_errors", $oldValues["d_e"]);

		return str_replace("<br />","",preg_replace($regex, "", $out));
	}

	public function handle_fatal($buffer = null){
		$error=error_get_last();
		if($buffer == null || $error['type'] == 1){
			//var_dump($error);
			//debug_print_backtrace();
			$err = $error["message"]." on line ".$error["line"];
			if(!isset($_POST["code"]))
				wp_die("Error");
			$code = $_POST["code"];
			if(!isset($_POST["file"]))
				wp_die("Error");
			$file = $_POST["file"];
			$response = array(
				"included_files" => get_included_files()
			);
			$response["text"] = __("An error has been found. Here are the lasts words from the server:", 'ithoughts-advanced-code-editor' )."<pre>".$err."</pre>".'<form class="ithoughts-ace-report" method="POST" target="_blank" action="'.admin_url('options-general.php?page=ithoughts_ace_report').'"><input type="hidden" value="fp" name="type"/><input type="hidden" name="code" value="'.htmlspecialchars($code).'"/><input type="hidden" name="file" value="'.htmlspecialchars($file).'"/><input type="hidden" name="included" value="'.htmlspecialchars(json_encode($response["included_files"])).'"/><button class="button button-secondary" id="ithoughts_ace-falseresult"><span class="dashicons dashicons-warning"></span> '.__("Report a false positive?", 'ithoughts-advanced-code-editor' ).'</button></form>';
			$response = array("success"=>false,"data"=>$response);
			if(!headers_sent()){
				header('Content-type: application/json');
			}
			return json_encode($response);
		}
		return $buffer;
	}

	/**
	 * Register Admin scripts and styles for the plugin.
	 * @uses wp_register_script
	 * @uses wp_register_style
	 * @at_action admin_enqueue_scripts
	 * @author Gerkin
	 */
	public function register_scripts(){
		$backbone = \ithoughts\ace\Backbone::get_instance();

		$opts = $backbone->compose_ace_js_obj();
		wp_localize_script(
			"ithoughts-ace-comon",
			"ithoughts_ace",
			$opts
		);

		wp_register_script(
			'ithoughts-ace-admin',
			$backbone->get_base_url() . "/resources/ithoughts_ace_admin{$backbone->get_minify()}.js",
			array('ithoughts-core-v3', 'ace-editor', 'ace-autocomplete', "ithoughts-ace-comon"),
			"1.2.8",
			false
		);
		wp_enqueue_script('ithoughts-ace-admin');
		wp_register_script(
			'ithoughts-ace-options',
			$backbone->get_base_url() . "/resources/ithoughts_ace_options{$backbone->get_minify()}.js",
			array('ithoughts-core-v3', 'ace-editor', 'ace-autocomplete', 'ithoughts-simple-ajax-v3', "ithoughts-ace-comon"),
			"1.1.0"
		);
		wp_enqueue_style('ithoughts-ace');
	}

	/**
	 * Generates menu pages for the plugin.
	 * @uses add_options_page
	 * @uses add_submenu_page
	 * @at_action admin_menu
	 * @author Gerkin
	 */
	public function menu_page(){
		add_options_page(
			__("iThoughts Advanced Code Editor", 'ithoughts-advanced-code-editor' ),
			__("Advanced Code Editor", 'ithoughts-advanced-code-editor' ),
			"manage_options",
			"ithoughts_ace",
			array(&$this, "options")
		);
		add_submenu_page(
			null,
			__("Report false positive/negative", 'ithoughts-advanced-code-editor' ),
			__("Report false positive/negative", 'ithoughts-advanced-code-editor' ),
			'manage_options',
			"ithoughts_ace_report",
			array(&$this, "report")
		);
	}

	/**
	 * Handle options page display.
	 * @author Gerkin
	 */
	public function options(){
		$backbone = \ithoughts\ace\Backbone::get_instance();

		$ajax         = admin_url( 'admin-ajax.php' );
		$options      = $backbone->get_options();

		/* Add required scripts for WordPress Spoilers (AKA PostBox) */
		wp_enqueue_script('postbox');
		wp_enqueue_script('post');
		wp_enqueue_script('ithoughts-ace-options');

		$optionsInputs = array(
			"enable_shortcode"	=> Toolbox::generate_input_check(
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
			"theme"				=> Toolbox::generate_input_select(
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
			"autocompletion"	=> Toolbox::generate_input_check(
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

	/**
	 * Handle options page edit.
	 * @uses ithoughts\ace\Backbone::set_options
	 * @author Gerkin
	 */
	public function update_options(){
		$backbone = \ithoughts\ace\Backbone::get_instance();
		$ace_options = $backbone->get_options();

		$postValues = $_POST;
		$postValues['enable_shortcode']			= Toolbox::checkbox_to_bool($postValues,'enable_shortcode',	"enabled");
		$postValues["autocompletion"]	= Toolbox::checkbox_to_bool($postValues,'autocompletion',	array("autocompletion_ondemand", "autocompletion_live"));

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

	/**
	 * Handles report page display
	 * @author Gerkin
	 */
	public function report(){
		$backbone = \ithoughts\ace\Backbone::get_instance();
		$ajax         = admin_url( 'admin-ajax.php' );
		wp_enqueue_script('ithoughts-simple-ajax');

		$vals = array(
			"type" => null,
			"file" => null,
			"code" => null,
			"included" => implode("\n",get_included_files())
		);
		if(isset($_POST["type"]) && isset($_POST["file"]) && isset($_POST["code"]) && isset($_POST["included"])){
			$included = json_decode(stripcslashes(htmlspecialchars_decode($_POST["included"])));
			$vals = array(
				"type" => $_POST["type"],
				"file" => $_POST["file"],
				"code" => $_POST["code"],
				"included" => implode("\n",$included)
			);
		}
		$user = wp_get_current_user();
		$opts = array(
			"name"				=> Toolbox::generate_input_text(
				"name",
				array(
					"value" => (strlen(trim($user->user_lastname . " " . $user->user_firstname)) > 0) ? $user->user_lastname . " " . $user->user_firstname : $user->display_name,
				)
			),
			"email"				=> Toolbox::generate_input_text(
				"email",
				array(
					"value" => $user->user_email,
					"type" => "email"
				)
			),
			"type"				=> Toolbox::generate_input_select(
				"type",
				array(
					"selected" => $vals["type"],
					"options" => array(
						"fn" => __("False Negative: there is an unspotted error", 'ithoughts-advanced-code-editor' ),
						"fp" => __("False Positive: an inexistent error was spotted", 'ithoughts-advanced-code-editor' )
					),
					"allow_blank" => __("Please select", "ithoughts-advanced-code-editor"),
					"required" => true
				)
			),
			"file"				=> Toolbox::generate_input_text(
				"file",
				array(
					"value" => $vals["file"],
					"required" => true
				)
			),
			"code"				=> Toolbox::generate_input_text(
				"code",
				array(
					"value" => $vals["code"],
					"textarea" => true,
					"attributes" => array(
						"class" => "ace-editor",
						"pattern" => ".{10,}",
						"title" => __("Please provide a bit of code.", "ithoughts-advanced-code-editor")
					),
					"required" => true
				)
			),
			"included"			=> Toolbox::generate_input_text(
				"included",
				array(
					"textarea" => true,
					"value" => $vals['included'],
					"attributes" => array(
						"style" => "resize:vertical;width:100%;min-height:100px;"
					),
					"required" => true
				)
			),
			"comment"			=> Toolbox::generate_input_text(
				"comment",
				array(
					"textarea" => true,
					"attributes" => array(
						"style" => "resize:vertical;width:100%;min-height:100px;"
					),
					"required" => true
				)
			)
		);
		require($backbone->get_base_path() . "/templates/report.php");
	}

	/**
	 * Generates mail from template and sends the report
	 * @uses wp_mail
	 * @author Gerkin
	 */
	public function send_report(){
		$backbone = \ithoughts\ace\Backbone::get_instance();
		if(!isset($_POST["type"]) || ($_POST["type"] != "fn" && $_POST["type"] != "fp")){
			wp_send_json_error(
				array(
					"text" => "<p>".__("You must specify if the report is a false positive or negative test.", "ithoughts-advanced-code-editor")."</p>"
				)
			);
		}
		if(!isset($_POST["code"]) || strlen($_POST["code"]) < 10){
			wp_send_json_error(
				array(
					"text" => "<p>".__("Please provide the code to check with a bit of length.", "ithoughts-advanced-code-editor")."</p>"
				)
			);
		}
		if(!isset($_POST["file"]) || strlen($_POST["file"]) < 3){
			wp_send_json_error(
				array(
					"text" => "<p>".__("Please provide the file name to help debugging in context.", "ithoughts-advanced-code-editor")."</p>"
				)
			);
		}
		if(!isset($_POST["comment"]) || strlen($_POST["comment"]) < 20){
			wp_send_json_error(
				array(
					"text" => "<p>".__("Please describe precisely the problem you encounter.", "ithoughts-advanced-code-editor")."</p>"
				)
			);
		}
		if(!isset($_POST["included"]) || strlen($_POST["included"]) < 10){
			wp_send_json_error(
				array(
					"text" => "<p>".__("Please omit only critical files.", "ithoughts-advanced-code-editor")."</p>"
				)
			);
		}

		$name = (isset($_POST["name"]) && strlen(trim($_POST["name"])) > 1 ? trim($_POST["name"]) : "Not specified");
		$email = (isset($_POST["email"]) && strlen(trim($_POST["email"])) > 1  && is_email($_POST["email"]) ? "<a href=\"mailto:".trim($_POST["email"])."\">".trim($_POST["email"])."</a>" : "Not specified");
		$code = $_POST["code"];
		$comment = $_POST["comment"];
		$filename = $_POST["file"];
		$included = $_POST["included"];
		$response = $this->check_syntax($code);
		$type = ($_POST["type"] == "fp" ? "false positive" : "false negative");
		$siteurl = get_site_url();
		$datetime = new \DateTime();
		$datetime = $datetime->format("g:ia \o\\n l jS F Y");
		ob_start();
		require($backbone->get_base_path() . "/templates/mail_report.php");
		$mail = ob_get_clean();
		add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
		$sent = wp_mail("agermain@gerkindevelopment.net", "A $type was spotted in codecheck", $mail);
		if($sent){
			wp_send_json_success(array("text"=>"<p>".__("Your report was sent. Thank you for your feedback.", "ithoughts-advanced-code-editor")."</p>","redirect"=>admin_url("options-general.php?page=ithoughts_ace")));
		} else {
			wp_send_json_error(array("text"=>"<p>".__("Your report was not sent. Please check your server & WordPress mail configuration or retry later.", "ithoughts-advanced-code-editor")."</p>"));
		}
	}
}