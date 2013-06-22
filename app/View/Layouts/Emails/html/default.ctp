<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
<title><?php echo $title_for_layout;?></title>
</head>
<body style="background: #999; color: #fff; padding: 30px; font-size: 1.1em; font-family: arial" align="center">
    <div id="email_contents" style="background: #fff; padding: 20px; color: #2a2a2a; text-align: left; width: 600px; font-weight: lighter; font-size: 1.1em; box-shadow: 0 0 4px #ccc">
	<p style="font-size: 2em; color: green"><?php echo APP_NAME; ?></p> 
        <?php echo $this->fetch('content');?>
    <p style="text-align: center">
    <strong><?php echo "&copy;" . date("Y") . " " . APP_NAME; ?></strong>
    </p>
    </div>

</body>
</html>