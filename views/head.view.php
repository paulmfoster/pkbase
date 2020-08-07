<!DOCTYPE html>
<head>
<title><?php echo $title; ?></title>
<link rel="shortcut icon" href="favicon.ico" >
<link rel="stylesheet" type="text/css" media="screen" href="style.css" />
</head>
<body>
	<a name="top"></a>	
	<div id="header">				
			
	<div id="site-title"><?php echo $cfg['site_title']; ?></div>	
	<div id="slogan"><?php echo $cfg['slogan']; ?></div> 
		
		<form method="post" id="searchform" class="searchform" action="search.php">
			<p><input type="text" name="search_query" class="textbox" />
  			<input type="submit" name="search" class="button" value="Search" /></p>
		</form>
			
	</div>	

	<div id="locator">
<?php if (!empty($page)) echo $page . $cfg['suffix']; ?>
	</div>
											
	<div id="leftbar" >							
		<a href="rebuild.php"><button type="button">Rebuild Index</button></a>
		&nbsp;
		<a href="index.php"><button type="button">Random</button></a>
		<?php echo $pkb->index; ?>
	</div>
			
	<div id="main">	

	<!-- MESSAGES ------------------------------>
	<?php show_messages(); ?>
	<!-- END OF MESSAGES ---------------------->

	<h1><?php echo $title; ?></h1>
