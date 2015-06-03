<script src="//code.jquery.com/jquery-2.1.1.min.js" xmlns="http://www.w3.org/1999/html"></script>
<script src="<?php echo $this->app->config()->url; ?>Assets/js/select2.js"></script>
<script src="<?php echo $this->app->config()->url; ?>Assets/js/select2_locale_da.js"></script>
<link href="<?php echo $this->app->config()->url; ?>Assets/css/select2-bootstrap.css" rel="stylesheet"/>
<div class="jumbotron container" style="border-radius: 4px;">
    <?php echo $this->renderFeedbackMessages(); ?>
    <?php
    $profile = $this->content->get('StaffProfile');
    #$this->prettyOutput($profile['user'][0]);
    ?>
    <div class="container-fluid pull-left">
        <div class="row-fluid">
            <div class="col-md-2">
                <img class="img img-rounded img-responsive"
                     src="<?php echo $this->app->config()->url; ?>/img/users/<?php echo $profile['user'][0]->image; ?>"
                     width="200px" height="200px">
            </div>

            <div class="col-md-4">
                <h1>Greetings,  <?php echo $profile['user'][0]->username; ?></h1>
                <blockquote>
                    <p>Rank: <?php echo $profile['rank'][0]->name; ?>,
                        Position: <?php echo $profile['membership'][0]->position; ?></p>
                    <small><cite title="Source Title"><?php echo $profile['user'][0]->location; ?>  <i
                                class="glyphicon glyphicon-map-marker"></i></cite></small>
                </blockquote>
                <p><i class="glyphicon glyphicon-envelope"></i> <?php echo $profile['user'][0]->email; ?>
                    <br
                        /> <i class="glyphicon glyphicon-globe"></i> <?php echo $profile['user'][0]->url; ?>
                    <br/> <i class="glyphicon glyphicon-gift"></i> <?php echo $profile['user'][0]->joindate; ?></p>
            </div>

            <div class="col-md-6">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <span class="glyphicon glyphicon-bookmark"></span> Quick Shortcuts</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="#" class="btn btn-danger btn-lg" role="button"><span
                                        class="glyphicon glyphicon-list-alt"></span> <br/>Apps</a>
                                <a href="#" class="btn btn-warning btn-lg" role="button"><span
                                        class="glyphicon glyphicon-bookmark"></span> <br/>Bookmarks</a>
                                <a href="#" class="btn btn-primary btn-lg" role="button"><span
                                        class="glyphicon glyphicon-signal"></span> <br/>Reports</a>
                                <a href="#" class="btn btn-primary btn-lg" role="button"><span
                                        class="glyphicon glyphicon-comment"></span> <br/>Comments</a>
                            </div>
                            <div class="col-md-6">
                                <a href="#" class="btn btn-success btn-lg" role="button"><span
                                        class="glyphicon glyphicon-user"></span> <br/>Users</a>
                                <a href="#" class="btn btn-info btn-lg" role="button"><span
                                        class="glyphicon glyphicon-file"></span> <br/>Notes</a>
                                <a href="#" class="btn btn-primary btn-lg" role="button"><span
                                        class="glyphicon glyphicon-picture"></span> <br/>Photos</a>
                                <a href="#" class="btn btn-primary btn-lg" role="button"><span
                                        class="glyphicon glyphicon-tag"></span> <br/>Tags</a>
                            </div>
                        </div>
                        <a href="http://www.jquery2dotnet.com/" class="btn btn-success btn-lg btn-block"
                           role="button"><span class="glyphicon glyphicon-globe"></span> Website</a>
                    </div>
                </div>
            </div>
            <hr>
            <br>
            <br>
        </div>
        <!-- /row -->
    </div>
</div>
