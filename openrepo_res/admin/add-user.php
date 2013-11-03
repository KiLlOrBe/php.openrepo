<?php
session_start();
if($_SESSION['or_god']){
	if(isset($_POST['login'], $_POST['password'])){
		$login = htmlspecialchars($_POST['login']);
		$password = md5(htmlspecialchars($_POST['password']));
		if(isset($_POST['view'])){
			$view = 1;
		}
		else{
			$view = 0;
		}
		if(isset($_POST['upload'])){
			$upload = 1;
		}
		else{
			$upload = 0;
		}
		if(isset($_POST['delete'])){
			$delete = 1;
		}
		else{
			$delete = 0;
		}
		if(isset($_POST['god'])){
			$god = 1;
		}
		else{
			$god = 0;
		}
		
		include("../config.php");
		try {
			$pdo = new PDO("mysql:host=$mysql_host;dbname=$mysql_db", $mysql_login, $mysql_password );
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch (Exception $e){
			echo "Can't connect to MySQL : ", $e->getMessage();
			die();
		}
		$reponse = $pdo->prepare('INSERT INTO `'.$mysql_table.'`(`login`, `password`, `view`, `upload`, `delete`, `god`) VALUES (:login,:password,:view,:upload,:delete,:god)');
		$reponse->bindParam(":login", $login);
		$reponse->bindParam(":password", $password);
		$reponse->bindParam(":view", $view);
		$reponse->bindParam(":upload", $upload);
		$reponse->bindParam(":delete", $delete);
		$reponse->bindParam(":god", $god);
		$reponse->execute();
		header("Location: users.php");
	}
}