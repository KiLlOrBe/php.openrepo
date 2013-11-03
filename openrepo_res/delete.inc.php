<?php
$deleteWasExed = true;
if(!($supprimer) AND !($god)){
	exit($lang_deleteErrors['perm']);
}
if(!($allowDelete) AND !($god)){
	exit($lang_deleteErrors['disable']);
}
if(isset($_GET['delete'])) {
	$fichier = str_replace("/", "", urldecode($_GET['delete']));
	if(file_exists($fichier) AND !(preg_match("/.php$/",  $fichier))) {
		if(unlink($fichier)){
			exit('true');
		}
		else{
			exit($lang_deleteErrors['delete']);
		}
	}
	else{
		exit($lang_deleteErrors['notFound']);
	}
}