<?php
    require_once('config.php');
    $jsondata = file_get_contents(ROOT_DIRECTORY.'/types.json');
    $jsondata = json_decode($jsondata, true);
?>
<head>
    <!-- Google Tag Manager -->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Shaked-g.com printing admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" id="main-stylesheet" data-version="1.3.1" href="styles/shards-dashboards.1.3.1.min.css">
    <link rel="stylesheet" href="styles/extras.1.3.1.min.css">
    <link rel="stylesheet" href="styles/admin.css">
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </head>
