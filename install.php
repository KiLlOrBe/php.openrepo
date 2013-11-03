<?php
if(isset($_GET['install'])){
	if(isset($_POST['mysql_host'],$_POST['mysql_login'],$_POST['mysql_password'],$_POST['mysql_db'],$_POST['mysql_alias'],$_POST['admin_pseudo'],$_POST['admin_password'])){
		$mysql_host = htmlspecialchars($_POST['mysql_host'], ENT_QUOTES);
		$mysql_login = htmlspecialchars($_POST['mysql_login'], ENT_QUOTES);
		$mysql_password = htmlspecialchars($_POST['mysql_password'], ENT_QUOTES);
		$mysql_db = htmlspecialchars($_POST['mysql_db'], ENT_QUOTES);
		$mysql_alias = htmlspecialchars($_POST['mysql_alias'], ENT_QUOTES);
		$admin_pseudo = htmlspecialchars($_POST['admin_pseudo'], ENT_QUOTES);
		$admin_password = md5(htmlspecialchars($_POST['admin_password'], ENT_QUOTES));
		try {
			$pdo = new PDO("mysql:host=$mysql_host;dbname=$mysql_db", $mysql_login, $mysql_password );
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch ( Exception $e ){
			echo "Can't connect to MySQL : ", $e->getMessage();
			die();
		}
		unlink("openrepo_res/config.php");
		$configFile = "<?php\n";
		$configFile .= '$mysql_host = "'.$mysql_host.'";'."\n";
		$configFile .= '$mysql_login = "'.$mysql_login.'";'."\n";
		$configFile .= '$mysql_password = "'.$mysql_password.'";'."\n";
		$configFile .= '$mysql_db = "'.$mysql_db.'";'."\n";
		$configFile .= '$mysql_alias = "'.$mysql_alias.'";'."\n";
		$configFile .= '$mysql_table = $mysql_alias."users";'."\n";
		$configFile .= '$lang = "fr";'."\n";
		$configFile .= '$auth = true;'."\n";
		$configFile .= '$unLoggedPermissions = array("view","upload","delete");';
		
		$writeConfig=fopen("openrepo_res/config.php","w");
		fwrite($writeConfig,utf8_encode($configFile));
		fclose($writeConfig);
		
		$pathToTheRepo = preg_replace("@/[^/]+?$@i","/",$_SERVER['REQUEST_URI']);
		$pathToTheRepo = preg_replace("@^/@i","",$pathToTheRepo);
		
		$indexFile = "<?php\n";
		$indexFile .= '$pathToTheRepo = "'.$pathToTheRepo.'";'."\n";
		$indexFile .= '$resDir = "openrepo_res/";'."\n";
		$indexFile .= '$resPath = $pathToTheRepo.$resDir;'."\n";
		$indexFile .= '$whitelist = false;'."\n";
		$indexFile .= '$whitelistExtension = array();'."\n";
		$indexFile .= '$blacklist = false;'."\n";
		$indexFile .= '$blacklistExtension = array();'."\n";
		$indexFile .= '$private = false;'."\n";
		$indexFile .= '$visibility = array();'."\n";
		$indexFile .= '$allowUpload = false;'."\n";
		$indexFile .= '$allowDelete = false;'."\n";
		$indexFile .= 'session_start();'."\n".
		'$uri = $_SERVER[\'REQUEST_URI\'];'."\n".
		'$uri = preg_replace("@^/@i", "", $uri);'."\n".
		'$uri = preg_replace("@([^/]+?)\/@i", "../", $uri);'."\n".
		'$uri = preg_replace("@/[^/]+?$@i", "/", $uri);'."\n".
		'$exeInIndex = true;'."\n".
		'include($uri.$resPath."index.inc.php");'."\n".
		'exit;';
		unlink("index.php");
		$writeIndex=fopen("index.php","w");
		fwrite($writeIndex, pack("CCC",0xef,0xbb,0xbf)); 
		fwrite($writeIndex,utf8_encode($indexFile));
		fclose($writeIndex); 
		
		$req = $pdo->prepare("CREATE TABLE IF NOT EXISTS `".$mysql_alias."users` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`login` varchar(50) NOT NULL,
			`password` text NOT NULL,
			`view` tinyint(1) NOT NULL DEFAULT '0',
			`upload` tinyint(1) NOT NULL DEFAULT '0',
			`delete` tinyint(1) NOT NULL DEFAULT '0',
			`god` tinyint(1) NOT NULL DEFAULT '0',
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
		$req->execute();
		$reponse = $pdo->prepare("INSERT INTO `".$mysql_alias."users`(`login`, `password`, `view`, `upload`, `delete`, `god`) VALUES (:login,:password,1,1,1,1)");
		$reponse->bindParam(":login", $admin_pseudo);
		$reponse->bindParam(":password", $admin_password);
		$reponse->execute();
		unlink("install.php");
		header("Location: .");
	}
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>OpenRepo - Install</title>
		<link rel="stylesheet" href="openrepo_res/style.css" />
		<link rel="stylesheet" href="openrepo_res/vendor/normalize.min.css" />
		<link rel="icon" type="image/png" href="openrepo_res/favicon.png" />
	</head>
	<body>
		<header>
			<img src="openrepo_res/logo.png" />
		</header>
		<section>
			<h1>OpenRepo - Installation</h1>
			<form action="?install" method="post">
				<table>
					<tr>
						<td>mysql_host</td>
						<td><input type="text" name="mysql_host" id="mysql_host" required /></td>
					</tr>
					<tr>
						<td>mysql_login</td>
						<td><input type="text" name="mysql_login" id="mysql_login" required /></td>
					</tr>
					<tr>
						<td>mysql_password</td>
						<td><input type="text" name="mysql_password" id="mysql_password" /></td>
					</tr>
					<tr>
						<td>mysql_db</td>
						<td><input type="text" name="mysql_db" id="mysql_db" required /></td>
					</tr>
					<tr>
						<td>mysql_alias</td>
						<td><input type="text" name="mysql_alias" id="mysql_alias" value="or_" required /></td>
					</tr>
					<tr>
						<td>admin_pseudo</td>
						<td><input type="text" name="admin_pseudo" id="admin_pseudo" value="admin" required /></td>
					</tr>
					<tr>
						<td>admin_password</td>
						<td><input type="text" name="admin_password" id="admin_password" /></td>
					</tr>
					<tr>
						<td></td>
						<td><input type="submit" value="Continuer" /></td>
					</tr>
				</table>
			</form>
		</section>
	</body>
</html>