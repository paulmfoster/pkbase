<form action="nodeedt.php" method="post">

<?php $form->hidden('page'); ?>

<h2><?php echo $title; ?></h2>
<label>New Title</label>&nbsp;<?php $form->text('newtitle', $title); ?>
<br/>
<label>New Extension</label>&nbsp;<?php $form->select('extension', $extension); ?>
<br/>
<?php $form->textarea('content', $content); ?>
<p>
<?php $form->submit('s1'); ?>
<?php form::abandon('index.php'); ?>
</p>
</form>
