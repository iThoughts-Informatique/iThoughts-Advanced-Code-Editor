<?php

/**
 * @file Main plugin includer file
 *
 * @author Gerkin
 * @copyright 2016 iThoughts Informatique
 * @license https://raw.githubusercontent.com/iThoughts-Informatique/iThoughts-Advanced-Code-Editor/master/LICENSE GPL3.0
 * @package ithoughts_advanced_code_editor
 *
 * @version 1.2.8
 */


/*
Plugin Name:	iThoughts Advanced Code Editor
Plugin URI:		http://www.gerkindevelopment.net/en/portfolio/ithoughts-advanced-code-editor/
Description:	Integrate the Code Editor Ace into your WordPress editors to help you write efficient code!
Version:		undefined
Author:			Gerkin
License:		GPLv2 or later
Text Domain:	ithoughts-advanced-code-editor
Domain Path:	/lang
*/

require_once( dirname(__FILE__) . '/submodules/iThoughts-WordPress-Plugin-Toolbox/class/includer.php' );
require_once( dirname(__FILE__) . '/class/Backbone.class.php' );
ithoughts\ace\Backbone::get_instance( dirname(__FILE__) );
if(is_admin()){
	require_once( dirname(__FILE__) . '/class/Admin.class.php' );
	ithoughts\ace\Admin::get_instance();
}
