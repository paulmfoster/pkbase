<?php include VIEWDIR . 'head.view.php'; ?>
<?php extract($data); ?>
<h2>Files which contained your search criteria:</h2>

<?php foreach ($results as $link): ?>

<a href="index.php?url=show/page/<?php echo $this->pkb->hide($link['filename']); ?>"><?php echo $link['title']; ?></a><br>

<?php endforeach; ?>
<?php include VIEWDIR . 'foot.view.php'; ?>
