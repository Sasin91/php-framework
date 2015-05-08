<div id="container">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinysort/2.2.2/tinysort.min.js">
        tinysort('form',{selector:'h2',data:'category'});
    </script>

    <?php echo $this->renderFeedbackMessages(); ?>

    <div class="store">
        <?php
        foreach($this->content->get('purchase') as $item)
        {
            ?>
            <div class="item">
                <?php
                $pic = $item->pictures[0];
                echo  '<h2 class="category" data-category="'.$item->category.'">'.$item->category.'</h1>';
                echo  '<h3 class="name">'.$item->label.'</h1>';
                echo '<img class="product" src="/Assets/img/products/family/'.$pic['path'].'"</img>';
                echo  '<p class="description">'.$item->description.'</p>';
                echo  '<p class="quantity">'.'på lager:'.$item->qty.'</p>';
                echo  '<span class="pricetag">'.$item->price.' kr</span>';
                echo  '<button class="btn btn-blue">Køb</button>'
                ?>
            </div>
        <?php
        }
        ?>
    </div>
</div> <!-- container -->