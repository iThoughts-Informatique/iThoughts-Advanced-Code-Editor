<?php
/*
Plugin Name: iThoughts Advanced Code Editor
Plugin URI: 
Description: 
Version:     1.0.0
Author:      Gerkin
License:     GPLv2 or later
Text Domain: ithoughts-advanced-code-editor
Domain Path: /lang
*/

require_once( dirname(__FILE__) . '/submodules/iThoughts-WordPress-Plugin-Toolbox/class/includer.php' );
require_once( dirname(__FILE__) . '/class/Backbone.class.php' );
ithoughts\ace\Backbone::get_instance( dirname(__FILE__) );
if(is_admin()){
	require_once( dirname(__FILE__) . '/class/Admin.class.php' );
	ithoughts\ace\Admin::get_instance();
}
