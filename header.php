<?php 
    include("includes/db.inc.php");
?>
<html>

    <head>
        <title>Hong Kong Housing Analysis - <?php echo $pageTitle; ?></title>

        <!-- Mini.css Prerequisites -->
        <link rel="stylesheet" href="https://cdn.rawgit.com/Chalarangelo/mini.css/v3.0.1/dist/mini-default.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mini.css/3.0.1/mini-default.min.css">
    
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>

    <body>
    <header>
        <a href="index.php" class="logo">HK Housing Analysis</a>
        <a href="charts.php" class="button">Data Charts</a>
        <a href="markets.php" class="button">HK Capital Markets</a>
        <a href="about.php" class="button">About</a>
        <a href="https://github.com/angusleung100/"><button>Github</button></a>
    </header>
    <h1><?php echo $pageTitle; ?></h1>
    <hr>
    <div class="container">
        <div class="row">
            <div class="col-sm col-md-9 col-lg-9">