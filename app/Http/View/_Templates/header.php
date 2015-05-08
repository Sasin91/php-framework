<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo $this->app->config()->fetch()->Base->title;?> | <?php $title = $this->content->get('title'); if(isset($title)) echo $title ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="/Assets/css/all.min.css">

        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
               <!-- Fixed navbar -->
        <div class="navbar navbar-primary navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="navbar-collapse collapse">
                    <?php
                    echo $this->Menu();
                    ?>
                </div><!--/.nav-collapse -->
            </div>
        </div>
    </head>
    <body>
    <div class="container-fluid" style="margin-top: 12%;"></div>
    <script src="/Assets/js/vendor/jquery.js"></script>
    <script src="/Assets/js/vendor/bootstrap.min.js"></script>
