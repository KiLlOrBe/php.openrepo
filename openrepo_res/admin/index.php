<?php
session_start();
if($_SESSION['or_god']){
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Admin</title>
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
				<?php
				if(isset($_SESSION['or_pseudo'])){
					echo '<span id="pseudo">'.$_SESSION['or_pseudo'].'</span> <a href="../../?logout">Déconnexion</a>';
				} ?>
			</div>
			<table>
				<thead>
					<tr>
						<th>Manage</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><a href="users.php" title="Manage Users">Manage Users</a></td>
					</tr>
					<tr>
						<td><a href="dir.php" title="Manage directories">Manage Directories</a></td>
					</tr>
				</tbody>
			</table>
		</section>
	</body>
</html>
<?php
}
?>