<?php
include($uri.$resPath."config.php");
$confWasExed = true;
if(isset($exeInIndex)){
	if(isset($_POST['login'], $_POST['password']) AND !(isset($_SESSION['or_pseudo']))){
		$login = $_POST['login'];
		$password = $_POST['password'];
		
		
		extract($_POST);
		try {
			$pdo = new PDO("mysql:host=$mysql_host;dbname=$mysql_db", $mysql_login, $mysql_password );
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch ( Exception $e ){
			echo "Can't connect to MySQL : ", $e->getMessage();
			die();
		}
		$login= htmlspecialchars($_POST['login']);
		$password = md5(htmlspecialchars($_POST['password']));
		$reponse = $pdo->prepare('SELECT * FROM '.$mysql_table.' WHERE login=:login AND password=:password');
		$reponse->bindParam(":login", $login);
		$reponse->bindParam(":password", $password);
		$reponse->execute();
		$data = $reponse->fetch();
		if (($data['login']) AND ($data['password'])){
			$_SESSION['or_pseudo'] = $data['login'];
			$_SESSION['or_view'] = $data['view'];
			$_SESSION['or_upload'] = $data['upload'];
			$_SESSION['or_delete'] = $data['delete'];
			$_SESSION['or_god'] = $data['god'];
			$reponse->closeCursor();
		}
		else{
			$reponse->closeCursor();
		}
		header("Location: .");
	}
	if(isset($_SESSION['or_pseudo'])){
		if($_SESSION['or_view']){
			$voir=true;
		}
		else{
			$voir=false;
		}
		if($_SESSION['or_upload']){
			$uploader=true;
		}
		else{
			$uploader=false;
		}
		if($_SESSION['or_delete']){
			$supprimer=true;
		}
		else{
			$supprimer=false;
		}
		if($_SESSION['or_god']){
			$god=true;
		}
		else{
			$god=false;
		}
	}
	elseif($auth){
		echo '<!DOCTYPE html>
		<html>
			<head>
				<title>OpenRepo - '.$lang_login['title'].'</title>
				<link rel="stylesheet" href="'.$uri.$resPath.'style.css" />
				<link rel="stylesheet" href="'.$uri.$resPath.'vendor/normalize.min.css" />
				<link rel="icon" type="image/png" href="'.$uri.$resPath.'favicon.png" />
			</head>
			<body>
				<header>
					<a href="'.$uri.$pathToTheRepo.'" title="OpenRepo"><img src="'.$uri.$resPath.'logo.png" /></a>
				</header>
				<section>
					<h1>'.$lang_login['action'].'</h1>
					<form method="post" action=".">
						<label for="login">'.$lang_others['login'].': </label><br>
						<input type="text" name="login" id="login"><br>
						<label for="password">'.$lang_others['password'].': </label><br>
						<input type="password" name="password" id="password"><br>
						<input type="submit" value="'.$lang_login['loginButton'].'">
					</form>
				</section>
			</body>
		</html>';
		exit;
	}
	else{
		if(in_array("view", $unLoggedPermissions)){
			$voir=true;
		}
		else{
			$voir=false;
		}
		if(in_array("upload", $unLoggedPermissions)){
			$uploader=true;
		}
		else{
			$uploader=false;
		}
		if(in_array("delete", $unLoggedPermissions)){
			$supprimer=true;
		}
		else{
			$supprimer=false;
		}
		$god=false;
	}
}