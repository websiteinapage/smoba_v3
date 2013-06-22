<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
            <?php echo APP_NAME . " : " . $title_for_layout; ?>
	</title>
        <link href="<?php echo SMOBA_ASSET_BASE . "img/favicon.ico"; ?>" rel="icon" type="image/x-icon" />           
	
            <meta name="author" content="SMOBA Nigeria Online">
            <meta charset="UTF-8">            
            <?php
		//echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');

		echo $this->fetch('meta');
		echo $this->fetch('css'); 
		echo $this->fetch('script');
	?>
    <link rel="stylesheet" href="<?php echo COMMON_ASSET_BASE . "jquery-ui/css/redmond/jquery-ui-1.10.0.custom.css"; ?>" />
    <script src="<?php echo COMMON_ASSET_BASE . "jquery-ui-stable/jquery-1.8.3.js" ?>"></script>
    <script src="<?php echo COMMON_ASSET_BASE . "jquery-ui-stable/jquery-ui.js"; ?>"></script>
    <link rel="stylesheet" href="<?php echo COMMON_ASSET_BASE . "css/ajax.config.css"; ?>" />
    <script src="<?php echo COMMON_ASSET_BASE . "js/spin.min.js"; ?>"></script>
    <script src="<?php echo COMMON_ASSET_BASE . "js/ajax.config.js"; ?>"></script>
    <script src="<?php echo COMMON_ASSET_BASE . "js/jsfxns.js"; ?>"></script>
    <link rel="stylesheet" href="<?php echo COMMON_ASSET_BASE . "jMenu/css/jMenu.jquery.css"; ?>" />
    <script src="<?php echo COMMON_ASSET_BASE . "jMenu/js/jMenu.jquery.js"; ?>" media="screen"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#jMenu").jMenu({
                openClick : false,
                ulWidth : 'auto',
                effects : {
                    effectSpeedOpen : 150,
                    effectSpeedClose : 150,
                    effectTypeOpen : 'slide',
                    effectTypeClose : 'hide',
                    effectOpen : 'linear',
                    effectClose : 'linear'
                },
                TimeBeforeOpening : 100,
                TimeBeforeClosing : 11,
                animatedText : false,
                paddingLeft: 1
            });
        })
    </script>   
    
</head>
<body>
    <?php echo $this->Element("navbar"); ?>
	<div id="container">

            <?php echo $this->Session->flash(); ?>

            <?php echo $this->fetch('content'); ?>

	</div>
    
    <!--<script src="<?php echo APP_BASE . "js/jstasks.js"; ?>"></script>-->
</body>
</html>
