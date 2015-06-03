<link rel="stylesheet" href="/css/404.css">

<article class="container">
    <h1 class="oaerror danger">Houston!<br> we have a code <?php echo $this->content['code'] ?>!</h1>
    <?php
        $env = \System\MVC\Core::getEnviroment();
        if($env == 'Development')
        {
          ?>
            <p class="oaerror info"> <?php echo ($this->content['message']); ?> </p>
            <input type="checkbox" checked id="backtrace" role="button">
                <label for="backtrace" onclick=""><span>Read system BackTrace</span></label>
                <section id="trace">
                    <?php \Modules\Debug::prettify($this->content['trace']); ?>
                </section>
        <?php
        } else {
            ?>
            <p class="oaerror info"><em>And it's an error, that's all we know.</em></p>
            <?php
    }
    ?>
    <script>
        $('#backtrace').change(function(){
            this.checked ? $('#trace').show() : $('#trace').hide();
        });
        </script>
    <div class="dinotainer">
        <div class='dino'></div>
        <div class='eye'></div>
        <div class='mouth'></div>
        <div class='ground'></div>
        <div class='comets'></div>
    </div>
</article>