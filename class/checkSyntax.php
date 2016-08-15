<?php

/**
 * @file Will contain CodeChecker
 *
 * @author Gerkin
 * @copyright 2016 iThoughts Informatique
 * @license https://raw.githubusercontent.com/iThoughts-Informatique/iThoughts-Advanced-Code-Editor/master/LICENSE GPL3.0
 * @package ithoughts_advanced_code_editor
 *
 * @version 1.2.10
 */


namespace ithoughts\ace;

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

function checkSyntax($content, $file){
	
}
/*
class(?:[\s\n]+)(\w+)(?:(?:[\s\n]+)(extends|implements)(?:[\s\n]+)([\w\\]+))?(?:[\s\n]*){

$reflFunc = new ReflectionFunction('function_name');

print $reflFunc->getFileName() . ':' . $reflFunc->getStartLine();
*/