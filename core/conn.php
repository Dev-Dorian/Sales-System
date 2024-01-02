<?php
	$conn = oci_connect('proyecto1', 'proyecto1', 'localhost:1521/orcl');
	if (!$conn) {
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
?>