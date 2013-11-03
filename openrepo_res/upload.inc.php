<?php
$uploadWasExed = true;
if(!($uploader) AND !($god)){
	exit($lang_uploadErrors['perm']);
}
if(!($allowUpload)  AND !($god)){
	exit($lang_uploadErrors['disable']);
}
if(isset($_POST['upload'])) { 
	$tmp_file = $_FILES['fichier']['tmp_name'];
	if(!is_uploaded_file($tmp_file)) {
		exit($lang_uploadErrors['notFound']);
	}
	$name_file = $_FILES['fichier']['name'];
	if(preg_match("/\.php$/i", $name_file) AND preg_match("/^index\.htm([l]*)$/i", $name_file)){
		exit($lang_uploadErrors['extension']);
	}
	if(!($god)){
		$extension=strrchr($name_file,'.');
		$extension=strtolower(substr($extension,1));
		if($whitelist){
			if(in_array($extension, $whitelistExtension)){
			
			}
			else{
				exit($lang_uploadErrors['specificExtension']);
			}
		}
		elseif($blacklist){
			if(in_array($extension, $blacklistExtension)){
				exit($lang_uploadErrors['specificExtension']);
			}
		}
	}
	if(file_exists($name_file)){
		$i = 0;
		while(file_exists($i."-".$name_file)){
			$i++;
		}
		$name_file = $i."-".$name_file;
	}
	if(!move_uploaded_file($tmp_file, $name_file)) { 
		exit($lang_uploadErrors['copy']); 
	}
	exit("true"); 
}