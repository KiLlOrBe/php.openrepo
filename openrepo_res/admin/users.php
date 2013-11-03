<?php
session_start();
if($_SESSION['or_god']){
	include("../config.php");
	include("../lang/$lang.php");
	try {
		$pdo = new PDO("mysql:host=$mysql_host;dbname=$mysql_db", $mysql_login, $mysql_password );
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch ( Exception $e ){
		echo "Can't connect to MySQL : ", $e->getMessage();
		die();
	}
	$contenu = "";
	$sql = 'SELECT * FROM '.$mysql_table.' ORDER BY id DESC';
	foreach ($pdo->query($sql) as $user) {
		$contenu .= '<tr>
						<td>'.$user['login'].'</td>
						<td><input type="button" value="'.$lang_boolean[$user['view']].'" onClick="window.location = \'edit-user.php?id='.$user['id'].'&view='.$user['view'].'\';" /></td>
						<td><input type="button" value="'.$lang_boolean[$user['upload']].'" onClick="window.location = \'edit-user.php?id='.$user['id'].'&upload='.$user['upload'].'\';" /></td>
						<td><input type="button" value="'.$lang_boolean[$user['delete']].'" onClick="window.location = \'edit-user.php?id='.$user['id'].'&delete='.$user['delete'].'\';" /></td>
						<td><input type="button" value="'.$lang_boolean[$user['god']].'" onClick="window.location = \'edit-user.php?id='.$user['id'].'&god='.$user['god'].'\';" /></td>
						<td><input type="button" value="'.$lang_manageUsers['deleteUser'].'" onClick="if(confirm(\''.stripslashes($lang_manageUsers['deleteUserAlert']).'\')){window.location = \'delete-user.php?id='.$user['id'].'\';}else{return false;}" /></td>
					</tr>';
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>OpenRepo - <?php echo $lang_others['users'];?></title>
		<link rel="stylesheet" href="../style.css" />
		<link rel="stylesheet" href="../vendor/normalize.min.css" />
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
		<script src="../vendor/jquery.tablesorter.min.js"></script>
		<link rel="icon" type="image/png" href="../favicon.png" />
	</head>
	<body>
		<header>
			<a href="../../" title="OpenRepo"><img src="../logo.png" /></a>
		</header>
		<section>
			<div id="">
				<a href="../../" title="<?php echo $lang_others['back']; ?>"><?php echo $lang_others['back']; ?></a> | <?php
				if(isset($_SESSION['or_pseudo'])){
					echo '<span id="pseudo">'.$_SESSION['or_pseudo'].'</span> <a href="../../?logout">'.$lang_others['logout'].'</a>';
				} ?>
			</div>
			<table>
				<thead><tr><th><?php echo $lang_others['login']; ?></th><th><?php echo $lang_permissions['view']; ?></th><th><?php echo $lang_permissions['upload']; ?></th><th><?php echo $lang_permissions['delete']; ?></th><th><?php echo $lang_permissions['god']; ?></th><th></th></tr></thead>
				<tbody id="contenu">
					<tr>
					<form action="add-user.php" method="post">
						<td><input type="text" name="login" placeholder="<?php echo $lang_others['login']; ?>" /><input type="text" name="password" placeholder="<?php echo $lang_others['password']; ?>" /></td>
						<td><input type="checkbox" value="View" name="view" /></td>
						<td><input type="checkbox" value="Upload" name="upload" /></td>
						<td><input type="checkbox" value="Delete" name="delete" /></td>
						<td><input type="checkbox" value="God" name="god" /></td>
						<td><input type="submit" value="<?php echo $lang_manageUsers['addUser']; ?>" /></td>
					</form>
					</tr>
					<?php echo $contenu; ?>
				</tbody>
			</table>
		</section>
	</body>
</html>
<?php
}
?>