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
                echo '<span class="pricetag">' . $item->price  * $item->qty . ' kr</span>';
                echo '<button class="btn btn-red">Slet</button>'
                ?>
            </form>
        <?php
        }
        ?>
    </div>
    <?php
    }
    ?>

    <div id="main">
    <form class="form" method="POST" action="/shop/upcycles/checkout/final">

        <input name="token" type="hidden" value="<?php echo $this->content->get('token'); ?>">
        <div  class="label">Addresse</div>
        <input data-validate="alphaNumeric required" type="text" name="address" type="text" placeholder="Addresse">
        <div class="label">Modtager</div>
        <input data-validate="alphaNumeric required" type="text" name="recipient" placeholder="Modtager">
        <div class="label">Kommentare</div>
        <textarea data-validate="alphaNumeric required" name="comments"></textarea>
        <input type="checkbox" id="TermsOfService" data-validate="agreement">
        <button class="btn btn-green">færdiggør køb!</button>
    </form>
        <script src="/Assets/js/verify.js"></script>
    </div>
</div>