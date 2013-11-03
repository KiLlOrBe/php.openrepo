<?php
session_start();
if($_SESSION['or_god']){
	if(isset($_GET['id'])){
		$id = $_GET['id'];
		if(is_numeric($id)){
			include("../config.php");
			try {
				$pdo = new PDO("mysql:host=$mysql_host;dbname=$mysql_db", $mysql_login, $mysql_password );
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
			catch (Exception $e){
				echo "Can't connect to MySQL : ", $e->getMessage();
				die();
			}
			$reponse = $pdo->prepare('DELETE FROM `'.$mysql_table.'` WHERE `id`=:id');
			$reponse->bindParam(":id", $id);
			$reponse->execute();
			header("Location: users.php");
		}
	}
}