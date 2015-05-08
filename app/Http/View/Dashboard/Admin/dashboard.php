<div id="dashboard" class="container" style="border-radius: 4px;">
    <?php echo $this->renderFeedbackMessages();?>
        <?php
        $profile = $this->content->get('profile');
        ?>
            <div class="row-fluid">
                <div class="col-md-2">
                <img class="img img-rounded img-responsive" src="/Assets/img/users/<?php echo $profile->image;?>" width="200px" height="200px">
                </div>

                <div class="col-md-4">
                    <h1>Greetings,  <?php echo $profile->label;?></h1>
                    <blockquote>
                        <p>Rank: <?php echo $profile->name;?>, Position: <?php echo $profile->position; ?></p> <small><cite title="Source Title"><?php echo $profile->location;?>  <i class="glyphicon glyphicon-map-marker"></i></cite></small>
                    </blockquote>
                    <p> <i class="glyphicon glyphicon-envelope"></i> <?php echo $profile->email;?>
                        <br
                            /> <i class="glyphicon glyphicon-globe"></i> <?php echo $profile->url;?>
                        <br /> <i class="glyphicon glyphicon-gift"></i> <?php echo $profile->joindate;?></p>
                </div>

                <div class="col-md-6">
                    <div class="panel panel-gsurf">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <span class="glyphicon glyphicon-bookmark"></span> Quick Shortcuts</h3>
                        </div>
                        <div class="panel-body">
                            <?php
                            $file = $this->app->config()->fetch('file', 'Admin/Shortcuts');
                            $url = '/dashboard';
                            ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <?php
                                    foreach ($file['Left'] as $k => $v) {
                                    echo '<a href="'.$url.DS.$v.'" class="btn btn-primary btn-lg" role="button"><span class="glyphicon glyphicon-wrench"></span> <br/>'. $k .'</a>';
                                    }?>
                                </div>
                                <div class="col-md-6">
                                    <?php foreach ($file['Right'] as $k => $v) {
                                       echo '<a href="'.$url.DS.$v.'" class="btn btn-primary btn-lg" role="button"><span class="glyphicon glyphicon-wrench"></span> <br/>'. $k .'</a>';
                                    }?>
                                </div>
                            </div>
                            <a href="" class="btn btn-success btn-lg btn-block" role="button"><span class="glyphicon glyphicon-globe"></span> Return <?php echo $this->app->config()->fetch()->default_url; ?></a>
                        </div>
                    </div>
                </div>
                <hr>
                <br>
                <br>
            </div><!-- /row -->
    </div>
    <?php
    if($this->content->get('tbl')): ?>
    <div class="container-fluid">
        <?php echo $this->content->get('tbl')[0]; ?>
    </div>
        <script>
            $(document).ready(function() {
                $('#username').editable();
            });
        </script>
    <?php endif; ?>
