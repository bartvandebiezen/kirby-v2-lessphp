<?php

// Using 'realpath' seems to work best in different situations.
$root = realpath(__DIR__ . "/../..");

// Your main Less file.
$sourceFile = $root . "/assets/less/style.less";

// Your final CSS file.
$compiledFile = $root . "/assets/css/style.css";

// Compile only when needed.
if ( !file_exists($compiledFile) or filemtime($sourceFile) > filemtime($compiledFile) ) {

	// Set compression provided by library.
	$options = array('compress'=>true);

	// Activate library.
	require "site/plugins/lessphp/Less.php";
	$parser = new Less_Parser($options);

	// Compile content in buffer
	$parser->parseFile($sourceFile);
	$buffer = $parser->getCss();

	// Remove all CSS comments.
	$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);

	// Remove lines and tabs.
	$buffer = str_replace(array("\r\n", "\r", "\n", "\t"), '', $buffer);

	// Remove unnecessary spaces.
	$buffer = preg_replace('!\s+!', ' ', $buffer);
	$buffer = str_replace(': ', ':', $buffer);
	$buffer = str_replace('} ', '}', $buffer);
	$buffer = str_replace('{ ', '{', $buffer);
	$buffer = str_replace('; ', ';', $buffer);
	$buffer = str_replace(', ', ',', $buffer);
	$buffer = str_replace(' }', '}', $buffer);
	$buffer = str_replace(' {', '{', $buffer);
	$buffer = str_replace(' )', ')', $buffer);
	$buffer = str_replace(' (', '(', $buffer);
	$buffer = str_replace(') ', ')', $buffer);
	$buffer = str_replace('( ', '(', $buffer);
	$buffer = str_replace(' ;', ';', $buffer);
	$buffer = str_replace(' ,', ',', $buffer);

	// Fix spacing in media queries.
	$buffer = str_replace('and(', 'and (', $buffer);
	$buffer = str_replace(')and', ') and', $buffer);

	// Remove last semi-colon within a CSS rule.
	$buffer = str_replace(';}', '}', $buffer);

	// Update your CSS file.
	file_put_contents($compiledFile, $buffer);
}

?>
<link rel="stylesheet" href="<?php echo url('assets/css/style.css') ?>">
