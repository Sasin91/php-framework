<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo $this->title; ?> | <?php $title =$this->content['title'];
        if (isset($title)) echo $title ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/font-awesome.css">

    <script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>

    <!--[if lt IE 7]>
    <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
        your browser</a> to improve your experience.</p>
    <![endif]-->

    <div class="menu">
        <ul>
            <li><a href="/">Home</a></li>
            <li><a href="/calender">Calender</a></li>
            <li><a href="/teams">Teams</a></li>
            <li><a href="/shop">Products</a></li>
            <li class="push-right"><a href="#">Users</a>
                <ul class="dropdown">
                    <?php if (\System\Authentication\Auth::check()) { ?>
                        <li><a href="/users/logout"> Sign out</a></li>
                        <li><a href="/dashboard"> Profile</a></li>
                    <?php } else { ?>
                        <li><a href="/users/register"> Sign up</a></li>
                        <li> <a href="/users/login"> Sign in </a></li>
                    <?php } ?>

                </ul>
            </li>
    </div>
</head>
<body>

<script src="/js/jquery.js"></script>
<?php $this->renderFeedbackMessages(); ?>


