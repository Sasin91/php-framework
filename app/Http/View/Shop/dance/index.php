<div id="container-fluid">
    <?php echo $this->renderFeedbackMessages(); ?>
    <?php
    ?>
    <div class="container">
        <?php
        $cart = $this->content['cart'];
        if (!empty($cart)) {
            ?>
            <div class="cart">
                <h1>Indkøbskurv</h1>
                <?php
                foreach ($cart as $item) {
                    ?>
                    <form class="item" action="/shop/dance/remove/<?php echo $item->label ?>" method="post">
                        <?php
                        echo '<h2 class="category" data-category="' . $item->category . '">' . $item->category . '</h1>';
                        echo '<h3 class="name">' . $item->label . '</h1>';
                        echo '<p class="description">' . $item->short_description . '</p>';
                        echo '<p class="quantity">' . 'I kurv:' . $item->qty . '</p>';
                        echo '<span class="pricetag">' . $item->price * $item->qty . ' kr</span>';
                        echo '<button class="btn btn-red">Slet</button>'
                        ?>
                    </form>
                <?php
                }
                ?>
                <form action="/shop/dance/checkout" method="post" data-category="transaction">
                    <input type="hidden" name="token" value="<?php echo $this->content['token']; ?>">
                    <button class="btn btn-green">Køb</button>
                </form>
            </div>
        <?php
        }
        ?>

        <div class="store">
            <?php
            foreach ($this->content['store'] as $item) {
                if ($item->category == 'dans'):
                    ?>
                    <form class="item" action="/shop/dance/add/<?php echo $item->label ?>" method="post">
                        <?php
                        $pic = \Toolbox\ArrayTools::getFirst('value')->In($item->pictures[0]);
                        echo '<h2 class="name">' . $item->label . '</h1>';
                        $path = str_replace(' ', '_', $item->label);
                        echo '<img class="img-rounded product" src="/img/shop/products/' . strtolower($path) . '/' . $pic['path'] . '"</img>';
                        echo '<p class="description">' . $item->description . '</p>';
                        echo '<p class="quantity">' . 'på lager:' . $item->qty . '</p>';
                        echo '<span class="pricetag">' . $item->price . ' EUR</span>';
                        echo '<button class="btn btn-blue">Purchase</button>';
                        ?>
                    </form>
                <?php
                endif;
            }
            ?>
        </div>
    </div>
</div> <!-- container -->