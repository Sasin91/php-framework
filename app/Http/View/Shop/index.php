	<div class="container">
		<h1>Please choose a category</h1>
        <div class="col-lg-12">
        <?php
            foreach ($this->content->get('Categories') as $category)
            {
                ?>
                <div class="col-lg-6">
                <h3><?php echo $category->label ?></h3>
                <a  href="/shop/<?php echo $category->link ?>">
                    <img src="/Assets/img/Shop/Categories/<?php echo $category->picture ?>">
                </a>
                </div>
          <?php  } ?>
	    </div>
    </div>
