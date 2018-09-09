<?php

function base_site($ext = null) {
	$url = 'http://localhost/github/MvcPadrao/public/';

	if($ext === null) {
		return $url;
	} else {
		return $url.$ext;
	}
}

function dd($value) {
	echo '<pre>';
	var_dump($value);
	exit();
}