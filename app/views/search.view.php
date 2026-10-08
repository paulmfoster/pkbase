<?php include VIEWDIR . 'head.view.php'; ?>

<h2>Files which contained your search criteria:</h2>

<?php foreach ($results as $link): ?>

<a href="show.php?page=<?php echo $link['filename']; ?>"><?php echo $link['title']; ?></a><br>

<?php endforeach; ?>

<?php include VIEWDIR . 'foot.view.php'; ?>
