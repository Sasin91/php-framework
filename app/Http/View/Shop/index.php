<div class="container">
    <h1>Please choose a category</h1>

    <div class="col-1-1">
        <?php
        foreach ($this->content['Categories'] as $category) {
            ?>
            <div class="col-1-6">
                <h1><?php echo $category->category ?></h1>
                <a href="/shop/<?php echo $category->category_link ?>">
                    <img class="img-rounded" style="height: 400px; width: 400px;" src="/img/shop/categories/<?php echo $category->category_pic ?>">
                </a>
            </div>
        <?php } ?>
    </div>
</div>
