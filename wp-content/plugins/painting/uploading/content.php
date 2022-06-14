<div class="container">
	<?php	
	$jsondata = file_get_contents('types.json'); 
	$jsondata = json_decode($jsondata, true); 
	if(empty($_GET)){
	?>
		<h3>Frame App</h3>
		<p>Chose any category from menu and go with options</p>
	<?php
	}else if(!empty($_GET) && $_GET['page'] == 'mirror'){
		include_once('mirror/index.php');
	}
	else if(!empty($_GET) && $_GET['page'] == 'glass'){
		include_once('glass/index.php');
	}
	else if(!empty($_GET) && $_GET['page'] == 'other_products'){
		include_once('other_products/index.php');
	}
	else if(!empty($_GET) && $_GET['page'] == 'frames'){
		include_once('frames/index.php');
	}
	else if(!empty($_GET) && $_GET['page'] == 'prints'){
		include_once('prints/index.php');
	}
	?>

  </div>