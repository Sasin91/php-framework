<div id="container">
    <div id="content">
        <div align="center">
            <div class="line"><h3>Server time: <?php echo date('h:i:s a'); ?></h3></div>
            <div class="outter"><img src="/Assets/img/users/smiley.png" class="image-circle"/></div>
            <?php $this->renderFeedbackMessages(); ?>
            <h1>Hi Guest</h1>
        <div class="col-md-6 follow line" align="center">
            <h3>
                Sign in, <br/> <span>Using your credentials below.</span>
            </h3>
        </div>
        <div class="col-md-6 follow line" align="center">
            <h3><span class="text-capitalize">wheter you login with Username or Email is completely optional.</span></h3>
        </div>
        </div>

        <div class="login_control">
            <form class="form" method="post">
                <div class="control">
                    <div class="label">Username</div>
                    <input type="text" name="username" class="form-control" placeholder="CookieWarrior57"/>
                </div>

                <div class="control">
                    <div class="label">Email</div>
                    <input type="text" name="email" class="form-control" placeholder="Cookie@warrior.dom"/>
                </div>

                <div class="control">
                    <div class="label">Password</div>
                    <input type="password" name="password" class="form-control" placeholder="123456"/>
                </div>

                <div class="control">
                    <label class="label" for="type">Account Type</label>
                    <select name="type" id="input" class="form-control">
                        <option value="System">System Account</option>
                        <option value="TrinityCore">TrinityCore</option>
                        <option value="OAuth_Facebook">Facebook</option>
                        <option value="OAuth_Twitter">Twitter</option>
                        <option value="OAuth_Github">Github</option>
                    </select>
                </div>
                <div align="center">
                    <button type="submit" class="btn btn-orange">Create</button>
                </div>
            </form>

        </div>



    </div>
</div>