<?php
function e7061($e){
	$ed = base64_decode($e);
	$n = openssl_decrypt("$ed","AES-256-CBC","9427912345678912",0,"9427912345678912");
	return $n;
}
?>