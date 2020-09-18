
<form method="post" action="nodeadd.php">

<label>Parent</label>&nbsp;<?php $form->select('parent'); ?>
<br/>
<label>Title</label>&nbsp;<?php $form->text('title'); ?>
<br/>
<label>Content</label>
<br/>
<?php $form->textarea('content'); ?>
<br/>
<?php $form->submit('s1'); ?>
<?php form::abandon('index.php'); ?>

</form>
