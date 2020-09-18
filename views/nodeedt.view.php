<form action="nodeedt.php" method="post">

<?php $form->hidden('page'); ?>
<?php $form->hidden('title'); ?>

<h2><?php echo $title; ?></h2>
<label>New Title</label>&nbsp;<?php $form->text('newtitle', $title); ?>
<br/>
<?php $form->textarea('content', $content); ?>
<p>
<?php $form->submit('s1'); ?>
<?php form::abandon('index.php'); ?>
</p>
</form>
