<?php
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>OpenRepo</title>
		<link rel="stylesheet" href="style.css" />
		<link rel="stylesheet" href="vendor/normalize.min.css" />
		<link rel="icon" type="image/png" href="favicon.png" />
	</head>
	<body>
		<header>
			<a href="../" title="OpenRepo"><img src="logo.png" /></a>
		</header>
		<section>
			<h1>OpenRepo</h1>
			<a href=".." title="Dossier Parent">Dossier Parent</a><br>
			<?php
			if(isset($_SESSION['or_god'])){
				if($_SESSION['or_god']){
					echo '<a href="admin/users.php" title="Manage users">Manage users</a>';
				}
			} 
			?>
		</section>
	</body>
</html>