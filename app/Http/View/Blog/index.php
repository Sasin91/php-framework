<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 05-05-15
 * Time: 15:43
 */

$categories = $this->content->get('categories');
$posts = $this->content->get('posts');
?>
<div class="container">
<div class="row-fluid">
    <?php foreach($posts as $post) {
    ?><div class="well blog-bg" >
        <div class="media" >
            <?php echo $this->md($post['content']);?>
            </div>
        </div>

    <?php } ?>
</div>
</div>