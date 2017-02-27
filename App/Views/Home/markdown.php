<?php include $header;?>
    <h1 align="center">Markdown example</h1>
    <p><?php echo htmlentities($markdown, ENT_QUOTES, 'UTF-8');?></p>
<?php include $footer;?>