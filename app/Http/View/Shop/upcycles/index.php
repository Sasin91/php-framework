<div id="container-fluid">
        <?php echo $this->renderFeedbackMessages(); ?>
            <div class="container">
            <?php
            $cart = $this->content->get('cart');
            if(!empty($cart)){
            ?>
        <div class="cart">
            <h1>Indkøbskurv</h1>
            <?php
            foreach ($cart as $item) {
                ?>
                <form class="item" action="/shop/upcycles/remove/<?php echo $item->label ?>" method="post">
                    <?php
                    echo  '<h2 class="category" data-category="'.$item->category.'">'.$item->category.'</h1>';
                    echo '<h3 class="name">' . $item->label . '</h1>';
                    echo '<p class="description">' . $item->short_description . '</p>';
                    echo '<p class="quantity">' . 'I kurv:' . $item->qty . '</p>';
                    echo '<span class="pricetag">' . $item->price * $item->qty. ' kr</span>';
                    echo '<button class="btn btn-red">Slet</button>'
                    ?>
                </form>
            <?php
            }
            ?>
            <form action="/shop/upcycles/checkout" method="post" data-category="transaction">
                <input type="hidden" name="Token" value="<?php echo $this->content->get('token'); ?>">
                <button class="btn btn-green">Køb</button>
            </form>
        </div>
        <?php
        }
        ?>

        <div class="store">
            <?php
            foreach($this->content->get('store') as $item)
            {
                if($item->category == 'Upcycles'):
                ?>
                <form class="item" action="/shop/upcycles/add/<?php echo $item->label ?>" method="post">
                    <?php
                    $pic = \Core\Http\Toolbox\ArrayTools::getFirstIn($item->pictures[0]);
                    echo  '<h2 class="name">'.$item->label.'</h1>';
                    echo '<img class="img img-responsive product" src="/Assets/img/Shop/Products/Bike/'.$pic['path'].'"</img>';
                    echo  '<p class="description">'.$item->description.'</p>';
                    echo  '<p class="quantity">'.'på lager:'.$item->qty.'</p>';
                    echo  '<span class="pricetag">'.$item->price.' EUR</span>';
                    echo  '<button class="btn btn-blue">Purchase</button>';
                    ?>
                </form>
            <?php
            endif;
            }
            ?>
        </div>
    </div>
</div> <!-- container -->