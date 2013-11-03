<?php
if($god){
	if(isset($_GET['newdir'])){
		if(isset($_POST['newdir'])){
			$dir_name = $_POST['newdir'];
			$dir_name = trim($dir_name);
			$dir_name = str_replace(" ", "_",$dir_name);
			$dir_name = str_replace("/", "",$dir_name);
			$dir_name = str_replace("\\", "",$dir_name);
			$dir_name = str_replace(":", "",$dir_name);
			$dir_name = str_replace("*", "",$dir_name);
			$dir_name = str_replace("<", "",$dir_name);
			$dir_name = str_replace(">", "",$dir_name);
			$dir_name = str_replace("|", "",$dir_name);
			if(!(empty($dir_name) OR $resDir==$dir_name.'/')){
				mkdir($dir_name ,0777);
				copy("index.php", $dir_name."/index.php");
			}
		}
		header("Location: .");
	}
	elseif(isset($_GET['updatedir'])){
		if(isset($_POST['whitelist'],$_POST['blacklist'],$_POST['private'])){
			$dir_newIndex = "<?php\n";
			$dir_newIndex .= '$pathToTheRepo = "'.$pathToTheRepo.'";'."\n";
			$dir_newIndex .= '$resDir = "'.$resDir.'";'."\n";
			$dir_newIndex .= '$resPath = $pathToTheRepo.$resDir;'."\n";
			
			if(isset($_POST['whitelist-cb'])){
				$dir_whitelist_cb = "true";
			}
			else{
				$dir_whitelist_cb = "false";
			}
			$dir_newIndex .= '$whitelist = '.$dir_whitelist_cb.';'."\n";
			$dir_whitelist = str_replace(" ","",$_POST['whitelist']);
			$dir_whitelist = preg_replace("@,$@","",$dir_whitelist);
			$dir_whitelist = preg_replace("@([^,]+)@","'$1'",$dir_whitelist);
			$dir_newIndex .= '$whitelistExtension = array('.$dir_whitelist.');'."\n";
			if(isset($_POST['blacklist-cb'])){
				$dir_blacklist_cb = "true";
			}
			else{
				$dir_blacklist_cb = "false";
			}
			$dir_newIndex .= '$blacklist = '.$dir_blacklist_cb.';'."\n";
			$dir_blacklist = str_replace(" ","",$_POST['blacklist']);
			$dir_blacklist = preg_replace("@,$@","",$dir_blacklist);
			$dir_blacklist = preg_replace("@([^,]+)@","'$1'",$dir_blacklist);
			$dir_newIndex .= '$blacklistExtension = array('.$dir_blacklist.');'."\n";
			if(isset($_POST['private-cb'])){
				$dir_private_cb = "true";
			}
			else{
				$dir_private_cb = "false";
			}
			$dir_newIndex .= '$private = '.$dir_private_cb.';'."\n";
			$dir_private = str_replace(" ","",$_POST['private']);
			$dir_private = preg_replace("@,$@","",$dir_private);
			$dir_private = preg_replace("@([^,]+)@","'$1'",$dir_private);
			$dir_newIndex .= '$visibility = array('.$dir_private.');'."\n";
			if(isset($_POST['upload'])){
				$dir_upload = "true";
			}
			else{
				$dir_upload = "false";
			}
			$dir_newIndex .= '$allowUpload = '.$dir_upload.';'."\n";
			if(isset($_POST['delete'])){
				$dir_delete = "true";
			}
			else{
				$dir_delete = "false";
			}
			$dir_newIndex .= '$allowDelete = '.$dir_delete.';'."\n";
			$dir_newIndex .= 'session_start();'."\n".'$uri = $_SERVER[\'REQUEST_URI\'];'."\n".'$uri = preg_replace("@^/@i", "", $uri);'."\n".'$uri = preg_replace("@([^/]+?)\/@i", "../", $uri);'."\n".'$uri = preg_replace("@/[^/]+?$@i", "/", $uri);'."\n".'$exeInIndex = true;'."\n".'include($uri.$resPath."index.inc.php");'."\n".'exit;';
			
			$ecriture=fopen("index.php","w");
			fwrite($ecriture, pack("CCC",0xef,0xbb,0xbf)); 
			fwrite($ecriture,utf8_encode($dir_newIndex));
			fclose($ecriture); 
		}
		header("Location: .");
	}
	elseif(isset($_GET['deletedir'])){
		function rrmdir($dir) {
			if(is_dir($dir)) {
				$objects = scandir($dir);
				foreach ($objects as $object) {
					if ($object != "." && $object != "..") {
						if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
					}
				}
				reset($objects);
				rmdir($dir);
			}
		}
		if(!(is_dir($resDir))){
			$dir = getcwd();
			chdir($uri);
			rrmdir($dir);
			header("Location: ..");
		}
		else{
			header("Location: .");
		}
	}
	if($allowUpload){
		$dir_upload = " checked";
	}
	else{
		$dir_upload = "";
	}
	if($allowDelete){
		$dir_delete = " checked";
	}
	else{
		$dir_delete = "";
	}
	if($whitelist){
		$dir_wl_cb = " checked";
	}
	else{
		$dir_wl_cb = "";
	}
	$dir_wl = implode(",", $whitelistExtension);
	if($blacklist){
		$dir_bl_cb = " checked";
	}
	else{
		$dir_bl_cb = "";
	}
	$dir_bl = implode(",", $blacklistExtension);
	if($private){
		$dir_private_cb = " checked";
	}
	else{
		$dir_private_cb = "";
	}
	$dir_private = implode(",", $visibility);
	$adminForm = '<div id="admin-div">
	<h2>'.$lang_manageDir['createDir'].'</h2>
	<form method="post" action="?newdir">
		<label for="newdir">'.$lang_manageDir['dirName'].'</label><br>
		<input type="text" name="newdir" id="newdir" /><br>
		<input type="submit" value="'.$lang_manageDir['createDirButton'].'" />
	</form>
	<h2>'.$lang_manageDir['manageDir'].'</h2>
	<form method="post" action="?updatedir">
		<label for="admin-upload">'.$lang_manageDir['allowUpload'].' </label><input type="checkbox" name="upload" id="admin-upload"'.$dir_upload.' /><br>
		<label for="admin-delete">'.$lang_manageDir['allowDelete'].' </label><input type="checkbox" name="delete" id="admin-delete"'.$dir_delete.' /><br>
		<label for="admin-wl-cb">'.$lang_manageDir['extWhitelist'].' </label><input type="checkbox" name="whitelist-cb" id="admin-wl-cb"'.$dir_wl_cb.' /><br>
		<input type="text" name="whitelist" id="admin-wl" placeholder="EX: exe,bat,sh,pl" value="'.$dir_wl.'" /><br>
		<label for="admin-bl-cb">'.$lang_manageDir['extBlacklist'].' </label><input type="checkbox" name="blacklist-cb" id="admin-bl-cb"'.$dir_bl_cb.' /><br>
		<input type="text" name="blacklist" id="admin-bl" placeholder="EX: exe,bat,sh,pl" value="'.$dir_bl.'" /><br>
		<label for="admin-private-cb">'.$lang_manageDir['private'].' </label><input type="checkbox" name="private-cb" id="admin-private-cb"'.$dir_private_cb.' /><br>
		<input type="text" name="private" id="admin-private"  placeholder="EX: user1,user2" value="'.$dir_private.'" /><br>
		<input type="submit" value="'.$lang_manageDir['updateDir'].'" />
	</form>
	<h2>'.$lang_manageDir['deleteDir'].'</h2>
	<input type="button" value="'.$lang_manageDir['deleteDirButton'].'" onClick="if(confirm(\''.stripslashes($lang_manageDir['deleteDirAlert']).'\')){window.location = \'?deletedir\';}else{return false;}" />
	</div>';
}