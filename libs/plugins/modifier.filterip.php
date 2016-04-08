<?php 

function smarty_modifier_filterip( $params) {
	$reg = '~(\d+)\.(\d+)\.(\d+)\.(\d+)~';
	return preg_replace($reg, "$1.$2.*.*", $params);
}
 ?>