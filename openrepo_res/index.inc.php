<?php
if(!(isset($exeInIndex))){
	exit;
}
$formatComment = "";
//Fonctions:
function size($fichier, $lang){
	$sizea = (filesize(htmlspecialchars($fichier)));
	if ($sizea >= 1024*1024*1024) {
		$size = round(($sizea / 1024)/1024/1024, 2)." ".$lang['giga'];
	}
	elseif ($sizea >= 1024*1024){
		$size = round(($sizea / 1024)/1024, 2)." ".$lang['mega'];
	}
	elseif ($sizea >= 1024){
		$size = round(($sizea / 1024), 2)." ".$lang['kilo'];
	}
	else{
		$size = $sizea." ".$lang['bytes'];
	}
	return $size;
}
if(isset($_GET["logout"])){
	unset($_SESSION['or_pseudo']);
	unset($_SESSION['or_view']);
	unset($_SESSION['or_upload']);
	unset($_SESSION['or_delete']);
	unset($_SESSION['or_god']);
	header("Location: .");
}
if(file_exists($uri.$resPath."config.php")){
	include($uri.$resPath."config.php");
	include($uri.$resPath."lang/$lang.php");
}
else{
	exit("Check if (".$resPath."config.php) exist.");
}
if(file_exists($uri.$resPath."auth.inc.php")){
	include($uri.$resPath."auth.inc.php");
}
else{
	exit("Check if (".$resPath."auth.inc.php) exist.");
}
if($private){
	if(isset($_SESSION['or_pseudo'])){
		if(in_array($_SESSION['or_pseudo'], $visibility) OR $god){
			$visible = true;
		}
		else{
			$visible = false;
		}
	}
	else{
		$visible = false;
	}
}
else{
	$visible = true;
}
if(!($visible)){
	echo '<!DOCTYPE html>
	<html>
		<head>
			<title>OpenRepo - '.$lang_others['errPrivate'].'</title>
			<link rel="stylesheet" href="'.$uri.$resPath.'style.css" />
			<link rel="stylesheet" href="'.$uri.$resPath.'vendor/normalize.min.css" />
		<link rel="icon" type="image/png" href="'.$uri.$resPath.'favicon.png" />
		</head>
		<body>
			<header>
				<a href="'.$uri.$pathToTheRepo.'" title="OpenRepo"><img src="'.$uri.$resPath.'logo.png" /></a>
			</header>
			<section>
				<h1>'.$lang_others['errPrivate'].'</h1>
				<a href=".." title="'.$lang_others['dirup'].'">'.$lang_others['dirup'].'</a>
			</section>
		</body>
	</html>';
	exit;
}
if($allowUpload){
	if($whitelist){
		$formatComment="<em>".$lang_uploadForm['whitelistExtension']." ";
		foreach($whitelistExtension as $elt){
			$formatComment .= strtoupper($elt)." ";
		}
		$formatComment .= "</em>";
	}
	elseif($blacklist){
		$formatComment="<em>".$lang_uploadForm['blacklistExtension']." ";
		foreach($blacklistExtension as $elt){
			$formatComment .= strtoupper($elt)." ";
		}
		$formatComment .= "</em>";
	}
}
if(isset($_GET["upload"])){
	if(file_exists($uri.$resPath."upload.inc.php")){
		include($uri.$resPath."upload.inc.php");
	}
	else{
		exit("Check if (".$resPath."upload.inc.php) exist.");
	}
}
if(isset($_GET["delete"])){
	if(file_exists($uri.$resPath."delete.inc.php")){
		include($uri.$resPath."delete.inc.php");
	}
	else{
		exit("Check if (".$resPath."delete.inc.php) exist.");
	}
}
if($god){
	include($uri.$resPath."admin/manage-dir.inc.php");
}
if(!(is_dir($resDir))){
	$thead = '<tr class="dossier" ondblclick="window.location = \'..\';"><td class="dirup"></td><td><a href=".." title="'.$lang_others['dirup'].'">['.$lang_others['dirup'].']</a></td><td></td><td></td></tr>';
}
else{
	$thead = "";
}
if($voir){
	$dir = '';
	$files ="";
	$contenu = "";
	$nb = 0;
	$dossier = opendir(".");
	while($fichier = readdir($dossier))  {
		if(($fichier != '.'  AND $fichier != '..' AND !(preg_match("@\.php$@", $fichier))) AND (($fichier != "openrepo_res" OR $god))) {
			if(is_dir($fichier)){
				if($fichier=="openrepo_res"){
					$thead .= '<tr class="dossier" ondblclick="window.location = \'openrepo_res/admin/users.php\';"><td class="user"></td><td><a href="openrepo_res/admin/users.php" title="'.$lang_others['users'].'">['.$lang_others['users'].']</a></td><td></td><td></td></tr>' ;
				}
				else {
					$dir .= '<tr class="dossier" ondblclick="window.location = \''.htmlspecialchars($fichier).'\';"><td class="dir"><span style="display:none">Dossier</span></td><td><a href="'.htmlspecialchars($fichier).'">'.htmlspecialchars($fichier).'</a></td><td>-</td><td>-</td></tr>' ;
				}
			}
			else {
				if(($supprimer AND $allowDelete) OR $god) {
					$files .='<tr class="fichier" ondblclick="window.open(\''.htmlspecialchars($fichier).'\');" value="'.htmlspecialchars($fichier).'"><td title="Supprimer '.htmlspecialchars($fichier).'" onclick="supprimer(\''.htmlspecialchars($fichier).'\')" class="file delete"><span style="display:none">Fichier</span></td><td><a href="'.htmlspecialchars($fichier).'" target="_blank">'.htmlspecialchars($fichier).'</a></td><td title="Ajouté le '.date ("d/m/Y à H:i", filemtime($fichier)).'">'.date ("d/m/Y", filemtime($fichier)).'</td><td><span style="display:none">'.filesize($fichier).'</span> '.size($fichier, $lang_size).'</td></tr>';
				}
				else {
					$files .='<tr class="fichier" ondblclick="window.open(\''.htmlspecialchars($fichier).'\');" value="'.htmlspecialchars($fichier).'"><td class="file"><span style="display:none">Fichier</span></td><td><a href="'.htmlspecialchars($fichier).'" target="_blank">'.htmlspecialchars($fichier).'</a></td><td title="Ajouté le '.date ($lang_formatDateTime, filemtime($fichier)).'">'.date ($lang_formatDate, filemtime($fichier)).'</td><td><span style="display:none">'.filesize($fichier).'</span> '.size($fichier, $lang_size).'</td></tr>';
				}
			} 
		} 
	}
	$contenu = $dir.$files;
	$form = "";
	if(($uploader AND $allowUpload) OR $god) {
		$form = '<div id="upload">'.$formatComment.'<form method="post" action="#">
		<div id="uploaddiv">
			<input id="input_text_file" class="inputText" readonly="readonly" type="text" onClick="return false" />
			<input type="button" value="'.$lang_uploadForm['browseButton'].'" onClick="return false" />
			<input onmousedown="return false" onkeydown="return false" onchange="document.getElementById(\'input_text_file\').value = this.value" type="file" name="fichier" id="fichier" />
			<input type="hidden" name="upload" />
		</div>
		<input type="button" value="'.$lang_uploadForm['uploadButton'].'" id="uploadSubmit" />
		</form><div id="loadingupload" style="display:none;"><img src="'.$uri.$resPath.'upload.gif" title="'.$lang_others['uploadLoading'].'" /></div></div>';
	}
}
 ?><!DOCTYPE html>
<html>
	<head>
		<title>OpenRepo - <?php echo $_SERVER['REQUEST_URI']; ?></title>
		<link rel="stylesheet" href="<?php echo $uri.$resPath; ?>style.css" />
		<link rel="stylesheet" href="<?php echo $uri.$resPath; ?>vendor/normalize.min.css" />
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
		<script src="<?php echo $uri.$resPath; ?>vendor/jquery.tablesorter.min.js"></script>
		<script src="<?php echo $uri.$resPath; ?>vendor/jquery.upload-1.0.2.min.js"></script>
		<link rel="icon" type="image/png" href="<?php echo $uri.$resPath; ?>favicon.png" />
		<script>
			var upload=false;
			<?php if(($supprimer AND $allowDelete) OR $god){ ?>
			function supprimer(nom){
				if(confirm('<?php echo $lang_others['deleteFileAlert']; ?> "'+nom+'"?')) {
					$.get( "index.php?delete="+nom, function(data) {
						if(data=="true"){
							$("tr[value='"+nom+"']").hide('slow', function(){
								$("tr[value='"+nom+"']").remove();
							});
							
						}
						else{
							alert(data);
						}
					});
				}
			}
			<?php } ?>
			$(function() {
				$("#fichier").change(function(){
					$('#uploadSubmit').effect("highlight", {color: '#4451ff'}, 3000);
				});
				$("#dirfiles").tablesorter();
				var fixHelper = function(e, ui) {
					ui.children().each(function() {
						$(this).width($(this).width());
					});
					return ui;
				};
				$("#contenu").sortable({
					helper: fixHelper,
					cancel: ".dossier",
					items: "tr:not(.fixe)",
					start: function(e, ui) {
						var fichier = ui.item.attr("value");
						$("tr[value='"+fichier+"']").addClass("border");
					},
					stop: function(e, ui) {
						var fichier = ui.item.attr("value");
						$("tr[value='"+fichier+"']").removeClass("border");
					},
					receive: function(event, ui) {
						sortableIn = 1;
					},
					over: function(e, ui) {
						sortableIn = 1;
					},
					out: function(e, ui) {
						sortableIn = 0;
					},
					beforeStop: function(e, ui) {
						if (sortableIn == 0) {
							<?php if(($supprimer AND $allowDelete) OR $god){ ?>
							var fichierASupprimer = ui.item.attr("value");
							supprimer(fichierASupprimer);
							<?php } ?>
						}
					}
					
				}).disableSelection();
				$('#uploadSubmit').click(function() {
					$("#upload form").hide(function() {$("#loadingupload").show();});
					
					var upload=true;
					$("#uploaddiv").upload('?upload', function(data) {
						var upload=false;
						if(data=='true'){
							window.location = ".";
						}
						else{
							$("#loadingupload").hide(function() {$("#upload form").show();});
							alert(data);
						}
					}, 'html');
				});
			});
		</script>
	</head>
	<body>
		<header>
			<a href="<?php echo $uri.$pathToTheRepo; ?>" title="OpenRepo"><img src="<?php echo $uri.$resPath; ?>logo.png" /></a>
		</header>
		<section>
			<div id="">
				<?php echo $lang_others['perm']; ?>: <span class="perm"><?php if($voir){
					echo '<span id="vperm" title="'.$lang_permissions['view'].'">V</span>';
				}
				else{
					echo "-";
				}
				if($uploader){
					echo '<span id="uperm"  title="'.$lang_permissions['upload'].'">U</span>';
				}
				else{
					echo "-";
				}
				if($supprimer){
					echo '<span id="dperm" title="'.$lang_permissions['delete'].'">D</span>';
				}
				else{
					echo "-";
				}
				if($god){
					echo '<span id="gperm"  title="'.$lang_permissions['god'].'">G</span>';
				}
				else{
					echo "-";
				}
				?></span>
				<?php
				if(isset($_SESSION['or_pseudo'])){
					echo ' | <span id="pseudo">'.$_SESSION['or_pseudo'].'</span> <a href="?logout" title="'.$lang_others['logout'].'">'.$lang_others['logout'].'</a>';
				} ?>
			</div>
			<table id="dirfiles">
				<thead><tr><th style="width:20px;"></th><th style="width:50%;"><?php echo $lang_filesListLabel['name']; ?></th><th style="width:30%;"><?php echo $lang_filesListLabel['date']; ?></th><th><?php echo $lang_filesListLabel['size']; ?></th></tr><?php echo $thead; ?></thead>
				<tbody id="contenu"><?php echo $contenu; ?></tbody>
			</table>
		</section>
		<?php if(isset($form)){echo $form;} if($god){echo $adminForm;} ?>
	</body>
</html>