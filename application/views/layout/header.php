<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?><!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Saveit - <?php echo $title; ?></title>

    <?php $this->render->get_resources('css'); ?>

    <link rel="shortcut icon" href="/favicon.ico?v=1" type="image/x-icon" />

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<div class="container">
    <nav class="navbar navbar-fixed-top navbar-default">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Activar navegación</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">
                    Saveit
                </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <?php if ($logged_in === true): ?>
                <?php /* <ul class="nav navbar-nav">
                    <?php foreach ($sections as $sName => $sec): ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                           role="button" aria-haspopup="true" aria-expanded="false"><?php echo $sName; ?>
                        <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php foreach ($sec as $sub): ?>
                                <?php if (($sub['visible'] === true) && ($this->user->check_group($sub['group'], false))): ?>
                                    <li><a href="<?php echo $sub['url']; ?>"><?php echo $sub['name']; ?></a></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php endforeach; ?>
                </ul> */ ?>

                    <ul class="nav navbar-nav navbar-right">
                        <li><p class="navbar-text"><?php echo $name; ?> (<a class="navbar-link"
                                                    href="/logout">Logout</a>)</p></li>
                    </ul>
                <?php else: ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a class="navbar-link" href="/login"><span class="icon icon-user_orange"></span>Login</a></li>
                    </ul>
                <?php endif; ?>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <div class="row">

    <!-- <div class="col-xs-12">
        <div class="page-header">
            <h1><?php echo $title; ?></h1>
        </div>
    </div> -->

        <div id="alerts" class="col-xs-12">
        <?php if (isset($messages)): ?>
            <?php foreach ($messages as $msg): ?>
                        <div class="alert alert-<?php echo $msg['type']; ?>" role="alert">
                            <?php echo $msg['content']; ?>
                        </div>
            <?php endforeach; ?>
        <?php endif; ?>
        </div>
