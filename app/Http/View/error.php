
<div id="container">
                <h1 class="oaerror danger">Houston we got a problem!</h1>
                <?php
                \Modules\Debug::prettify($this->content);
                ?>
</div>
