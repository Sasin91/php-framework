<link rel="stylesheet" href="/css/login.css">

<div id="container">
    <div id="content">
        <div align="center">
            <div class="line"><h3>Server time: <?php echo date('h:i:s a'); ?></h3></div>
            <div class="outter"><img src="/img/users/smiley.png" class="image-circle"/></div>
            <?php $this->renderFeedbackMessages(); ?>
            <h1>Hi Guest</h1>

            <div class="col-1-1 follow line" align="center">
                <h3>
                    Create an account, <br/> <span>Using the form below.</span>
                </h3>
            </div>

        <div class="login_control">
            <form class="form" method="post">
                <div class="control">
                    <div class="label">Username</div>
                    <input class="slide" type="text" name="username"placeholder="CookieWarrior57"/>
                </div>

                <div class="control">
                    <div class="label">Email</div>
                    <input class="slide" type="text" name="email" placeholder="Cookie@warrior.dom"/>
                </div>

                <div class="control">
                    <div class="label">Password</div>
                    <input class="slide" type="password" name="password" placeholder="123456"/>
                </div>

                <div class="control">
                    <input class="slide" name="type" id="input" hidden="" value="System">
                </div>
                <div align="center">
                    <button type="submit" class="button green">Create</button>
                </div>
            </form>

        </div>


    </div>
</div>