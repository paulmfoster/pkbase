
<form action="topics.php" method="post">

<h2>Add Topic</h2>

<label>Parent</label>
&nbsp;
<?php $form->select('parent'); ?>
<br/>
<label>New Topic</label>
&nbsp;
<?php $form->text('newtopic'); ?>
<br/>
<?php $form->submit('s1'); ?>

<hr/>

<h2>Delete Topic</h2>
(Note: deleting a topic deletes all pages under it.)
<br/>
<label>Topics To Delete</label>
&nbsp;
<?php $form->select('delete'); ?>
<br/>
<?php $form->submit('s2'); ?>

</form>
