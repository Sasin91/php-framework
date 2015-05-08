    <div class="container">
        <div class="col-md-12 col-xs-12" align="center">
            <?php $this->renderFeedbackMessages(); ?>
            <h1>Hi Guest</h1>
        </div>
        <div class="col-md-6 col-xs-6 follow line" align="center">
            <h3>
                Sign in, <br/> <span>Using your credentials below.</span>
            </h3>
        </div>
        <div class="col-md-6 col-xs-6 follow line" align="center">
            <h3><span class="text-capitalize">wheter you login with Username or Email is completely optional.</span></h3>
        </div>

        <div class="col-md-12 col-xs-12 login_control">
            <form class="form-horizontal" method="post">
                <div class="control">
                    <div class="label">Optional: Username</div>
                    <input type="text" name="username" class="form-control" placeholder="CookieWarrior57"/>
                </div>

                <div class="control">
                    <div class="label">Optional: Email</div>
                    <input type="text" name="email" class="form-control" placeholder="Cookie@warrior.dom"/>
                </div>

                <div class="control">
                    <div class="label">Required: Password</div>
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
                    <button type="submit" class="btn btn-orange">Login</button>
                </div>
            </form>
        </div>
    </div>
