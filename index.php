<?php
require ('Categories.php');

$categories = new Categories();
?>
<h3>All Categories:</h3>
<pre>
    <?php print_r($categories->categoriesTree) ?>
</pre>
<br>
<h3>Problem Categories ID:</h3>
<pre>
    <?php print_r($categories->notFoundParents) ?>
</pre>