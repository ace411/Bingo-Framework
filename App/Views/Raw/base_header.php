<!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <title><?php echo htmlspecialchars(isset($title) ? $title : 'Bingo Framework');?></title>
            <link rel="stylesheet" type="text/css" href="<?php echo htmlentities(isset($stylesheet) ? $stylesheet : '', ENT_QUOTES, 'UTF-8');?>">
            <link rel="stylesheet" type="text/css" href="<?php echo htmlentities(isset($font) ? $font : '', ENT_QUOTES, 'UTF-8');?>">
        </head>
        <body>
        <!--Customizable "Raw PHP" header file for the Bingo Framework-->    