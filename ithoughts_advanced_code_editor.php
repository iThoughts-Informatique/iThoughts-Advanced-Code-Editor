<?php

/**
 * @file Main plugin includer file
 *
 * @author Gerkin
 * @copyright 2016 iThoughts Informatique
 * @license https://raw.githubusercontent.com/iThoughts-Informatique/iThoughts-Advanced-Code-Editor/master/LICENSE GPL3.0
 * @package ithoughts_advanced_code_editor
 *
 * @version 1.2.10
 */


/*
Plugin Name:	iThoughts Advanced Code Editor
Plugin URI:		http://www.gerkindevelopment.net/en/portfolio/ithoughts-advanced-code-editor/
Description:	Integrate the Code Editor Ace into your WordPress editors to help you write efficient code!
Version:		1.2.10
Author:			Gerkin
License:		GPL3.0
Text Domain:	ithoughts-advanced-code-editor
Domain Path:	/lang
*/

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

require_once( dirname(__FILE__) . '/submodules/iThoughts-WordPress-Plugin-Toolbox/class/includer.php' );
require_once( dirname(__FILE__) . '/class/Backbone.class.php' );
ithoughts\ace\Backbone::get_instance( __FILE__ );
if(is_admin()){
	require_once( dirname(__FILE__) . '/class/Admin.class.php' );
	ithoughts\ace\Admin::get_instance();
}
