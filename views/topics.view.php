
<form action="<?php echo $return; ?>" method="post">

<h2>Add Topic</h2>

<table>
<tr>
<td class="tdlabel">Parent Directory</td>
<td><?php $form->select('parent'); ?></td>
</tr>
<tr>
<td class="tdlabel">New Topic</td>
<td><?php $form->text('newtopic'); ?></td>
</tr>
<tr>
<td class="tdlabel"></td>
<td><?php $form->submit('s1'); ?></td>
</tr>
</table>

<hr/>

<h2>Delete Topic</h2>

<table>
<tr>
<td class="tdlabel">Directory/Topic</td>
<td><?php $form->select('delete'); ?></td>
</tr>
<tr>
<td class="tdlabel"></td>
<td><?php $form->submit('s2'); ?></td>
</tr>
</table>

</form>
