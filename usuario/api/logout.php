<?php
	session_start();
	$a= session_destroy();
	echo $a;
	if(session_destroy()){
		echo "se destruyo";
		header('location:..');
	}
?>