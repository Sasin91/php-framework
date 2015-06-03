<link rel="stylesheet" href="/css/dashboard_shop.css">
<div class="grid grid-pad">
    <ul class="breadcrumb">
        <?php

        $array = app::currentUrl(false);
        array_shift($array);
        foreach ($array as $url) {
            if($url == app::currentUrl(true, true))
            {
                ?>
                <li class="active"><?php echo $url ?></li>
            <?php
            } else {
                $base = app::baseUrl();
                if($url != 'dashboard')
                {
                    $path = $base.DS.'dashboard'.DS.$url;
                } else {
                    $path = $base.DS.$url;
                }
                ?>
                <li><a href="<?php echo $path ?>"><?php echo $url ?></a> <span class="divider">/</span></li>
            <?php
            }
        }

        ?>
    </ul>
    <select id="shop_options" class="minimal">
        <option>Select which part of shop you want.</option>
        <option id="orders">Orders</option>
        <option id="products">Products</option>
        <option id="categories">Categories</option>
    </select>
    <script>

        $("#shop_options").change(function(){
            var url = this.options[this.selectedIndex].id;
            window.location.replace('<?php echo app::currentUrl(false, true) ?>'+url);
        });
    </script>

    </div>
