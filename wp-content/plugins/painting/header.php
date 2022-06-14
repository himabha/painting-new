<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Frame App</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Frame App</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="<?php echo !isset($_GET['page']) ? 'active' : '' ?>"><a href="/index.php">Home</a></li>
	  <li class="dropdown <?php echo (isset($_GET['page']) & $_GET['page'] == 'mirror') ? 'active' : '' ?>"><a href="?page=mirror">Mirror</a></li>
	  <li class="dropdown <?php echo (isset($_GET['page']) & $_GET['page'] == 'frames') ? 'active' : '' ?>"><a href="?page=frames">Frames</a></li>
      <li class="dropdown <?php echo (isset($_GET['page']) & $_GET['page'] == 'prints') ? 'active' : '' ?>"><a href="?page=prints">Prints</a></li>
      <li class="dropdown <?php echo (isset($_GET['page']) & $_GET['page'] == 'glass') ? 'active' : '' ?>"><a href="?page=glass">Glass</a></li>
	  <li class="dropdown <?php echo (isset($_GET['page']) & $_GET['page'] == 'other_products') ? 'active' : '' ?>"><a href="?page=other_products">Other Products</a></li>
    </ul>
  </div>
</nav>
