<h2>Files which contained your search criteria:</h2>
<?php foreach ($results as $link): ?>
<a href="show.php?page=<?php echo $link['name'] . '&title=' . $link['title']; ?>"><?php echo $link['title']; ?></a><br>
<?php endforeach; ?>
