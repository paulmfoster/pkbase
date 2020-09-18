
<form action="nodedel.php" method="post">

<?php $form->hidden('page'); ?>

<h2>Are you SURE you want to delete this page?</h2>
<?php $form->submit('s1'); ?>
<?php form::abandon('index.php'); ?>

<h1><?php echo $_GET['title']; ?></h1>

<p>

<?php echo nl2br($content); ?>

</p>

</form>
