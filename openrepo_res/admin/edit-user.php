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
		
			if(isset($_GET['view'])){
				$view = $_GET['view'];
				if($view){
					$view = 0;
				}
				else{
					$view = 1;
				}
				$reponse = $pdo->prepare('UPDATE `'.$mysql_table.'` SET `view`=:view WHERE `id`=:id');
				$reponse->bindParam(":view", $view);
				$reponse->bindParam(":id", $id);
				$reponse->execute();
			}
			elseif(isset($_GET['upload'])){
				$upload = $_GET['upload'];
				if($upload){
					$upload = 0;
				}
				else{
					$upload = 1;
				}
				$reponse = $pdo->prepare('UPDATE `'.$mysql_table.'` SET `upload`=:upload WHERE `id`=:id');
				$reponse->bindParam(":upload", $upload);
				$reponse->bindParam(":id", $id);
				$reponse->execute();
			}
			elseif(isset($_GET['delete'])){
				$delete = $_GET['delete'];
				if($delete){
					$delete = 0;
				}
				else{
					$delete = 1;
				}
				$reponse = $pdo->prepare('UPDATE `'.$mysql_table.'` SET `delete`=:delete WHERE `id`=:id');
				$reponse->bindParam(":delete", $delete);
				$reponse->bindParam(":id", $id);
				$reponse->execute();
			}
			elseif(isset($_GET['god'])){
				$god = $_GET['god'];
				if($god){
					$god = 0;
				}
				else{
					$god = 1;
				}
				$reponse = $pdo->prepare('UPDATE `'.$mysql_table.'` SET `god`=:god WHERE `id`=:id');
				$reponse->bindParam(":god", $god);
				$reponse->bindParam(":id", $id);
				$reponse->execute();
			}
			header("Location: users.php");
		}
	}
}