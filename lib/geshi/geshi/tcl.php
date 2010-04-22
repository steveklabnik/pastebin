<?php
/*************************************************************************************
 * very basic TCL syntax
 * by Paul Dixon, who knows nothing about TCL
 * with thanks to Donal K.Fellows, who does
 ************************************************************************************/

$language_data = array (
	'LANG_NAME' => 'TCL',
	'COMMENT_SINGLE' => array(1 => '#'),
	'COMMENT_MULTI' => array('/*' => '*/'),
	'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
	'QUOTEMARKS' => array("'", '"'),
	'ESCAPE_CHAR' => '\\',
	'KEYWORDS' => array(
		1 => array(
			'proc',  'while', 'if', 'else', 'elseif', 'while', 'switch',
			'break', 'continue', 'return', 'foreach', 'package',
			'namespace', 'catch', 'error'),
	
  
		2 => array('global', 'set', 'variable', 'upvar', 'uplevel'),
		//built in functions?
		3 => array('open', 'close', 'incr', 'decr', 'join', 'list', 'regexp', 'regsub',
			'eof', 'gets', 'eval'),
		
		//built in types
		4 => array(),
		),
	'SYMBOLS' => array(
		'(', ')', '{', '}', '[', ']', '=', '+', '-', '*', '/', '!', '%', '^', '&', ':'
		),
	'CASE_SENSITIVE' => array(
		GESHI_COMMENTS => true,
		1 => false,
		2 => false,
		3 => false,
		4 => false,
		),
	'STYLES' => array(
		'KEYWORDS' => array(
			1 => 'color:#0000FF; font-weight:bold;',
			2 => 'color:#0000FF; font-weight:bold;',
			3 => 'color:#CC0066; font-weight:bold;',
			4 => 'color: #0000ff;'
			),
		'COMMENTS' => array(
			1 => 'color: #008800;',
			2 => 'color: #008800;',
			'MULTI' => 'color: #ff0000; font-style: italic;'
			),
		'ESCAPE_CHAR' => array(
			0 => 'color: #666666; font-weight: bold;'
			),
		'BRACKETS' => array(
			0 => 'color: #000000;'
			),
		'STRINGS' => array(
			0 => 'color: #666666;'
			),
		'NUMBERS' => array(
			0 => 'color: #0000dd;'
			),
		'METHODS' => array(
			1 => 'color: #000000;',
			2 => 'color: #000000;'
			),
		'SYMBOLS' => array(
			0 => 'color: #000000;'
			),
		'REGEXPS' => array(
			),
		'SCRIPT' => array(
			)
		),
	'URLS' => array(
		),
	'OOLANG' => true,
	'OBJECT_SPLITTERS' => array(
		1 => '.',
		2 => '::'
		),
	'REGEXPS' => array(
		),
	'STRICT_MODE_APPLIES' => GESHI_NEVER,
	'SCRIPT_DELIMITERS' => array(
		),
	'HIGHLIGHT_STRICT_BLOCK' => array(
		)
);

?>


