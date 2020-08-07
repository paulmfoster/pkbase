<h2>Files which contained your search criteria:</h2>
<?php foreach ($results as $link): ?>
<a href="show.php?page=<?php echo $link[0] . '&title=' . $link[1]; ?>"><?php echo $link[1]; ?></a><br>
<?php endforeach; ?>
