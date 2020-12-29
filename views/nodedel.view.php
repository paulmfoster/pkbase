<form action="nodedel.php" method="post">

<h2>Are you SURE you want to delete this page?
<?php $form->submit('s1'); ?>
<?php form::abandon('index.php'); ?>
</h2>

<h1><?php echo $title; ?></h1>

<?php $form->hidden('page'); ?>

<p>

<?php echo $content; ?>

</p>

</form>
