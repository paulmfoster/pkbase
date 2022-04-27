<!DOCTYPE html>
<head>
<title><?php echo $this->title; ?></title>
<link rel="shortcut icon" href="favicon.ico" >
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo VIEWDIR; ?>style.css?v=<?php echo date('His'); ?>" />
</head>
<body>
	<a name="top"></a>	
<div class="container">
	<div id="header">				
			
	<div id="site-title"><?php echo $this->cfg['site_title']; ?></div>	
	<div id="slogan"><?php echo $this->cfg['slogan']; ?></div> 
		
		<form method="post" id="searchform" class="searchform" action="index.php?url=search/page">
			<p>
			<input type="text" name="search_query" class="textbox" />
			<input type="submit" name="search" class="button" value="Search" />
			</p>
		</form>

	</div> <!-- header -->	

	<div id="locator">
<?php if (!empty($this->page)): ?>
<?php echo '<div id="locator-text">'. $this->page . '</div>'; ?>
<?php endif; ?>

	</div> <!-- locator -->
											
	<div id="left-nav" >							
		<?php echo $this->sidebar; ?>
	</div>
			
	<div id="main">	

	<!-- MESSAGES ------------------------------>
	<?php show_messages(); ?>
	<!-- END OF MESSAGES ---------------------->

