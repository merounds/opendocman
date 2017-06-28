<?php use Aura\Html\Escaper as e; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title><?= e::h($GLOBALS['CONFIG']['title']) ?> - <?= e::h($this->page_title) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="" />

    <!-- Le styles -->

    <link rel="stylesheet" type="text/css" href="<?= $this->base_url ?>/templates/tweeter/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?= $this->base_url ?>/templates/tweeter/css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="<?= $this->base_url ?>/templates/tweeter/css/tweeter.css" />
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
    </style>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="<?= $this->base_url ?>/templates/tweeter/js/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="<?= $this->base_url ?>/templates/tweeter/images/favicon.ico" />

    <!-- these are missing in the v1.3.5 package -->
    <link rel="apple-touch-icon" href="<?= $this->base_url ?>/templates/tweeter/images/apple-touch-icon.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="<?= $this->base_url ?>/templates/tweeter/images/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="<?= $this->base_url ?>/templates/tweeter/images/apple-touch-icon-114x114.png" />

    <!-- Must Include This File -->
<?php include "./templates/common/head_include.php"; ?>

  </head>

  <body>
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Tweeter header</code><br />

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="<?= $this->base_url ?>/out.php"><?= e::h($GLOBALS['CONFIG']['title']) ?></a>
          <div class="nav-collapse collapse">
            <ul class="nav">

              <li class="active"><a href="<?= $this->base_url ?>/out.php"><?= e::h(msg('home')) ?></a></li>
<?php if ($this->can_checkin || $this->isadmin == 'yes'): ?>
              <li><a href="<?= $this->base_url ?>/in.php"><?= e::h(msg('button_check_in')) ?></a></li>
<?php endif; ?>
              <li><a href="<?= $this->base_url ?>/search.php"><?= e::h(msg('search')) ?></a></li>
<?php if ($this->can_add || $this->isadmin == 'yes'): ?>
              <li><a href="<?= $this->base_url ?>/add.php"><?= e::h(msg('button_add_document')) ?></a></li>
<?php endif; ?>
<?php if ($this->isadmin == 'yes'): ?>
              <li><a href="<?= $this->base_url ?>/admin.php"><?= e::h(msg('label_admin')) ?></a></li>
<?php endif; ?>
              <li><a href="<?= $this->base_url ?>/logout.php"><?= e::h(msg('logout')) ?></a></li>
            </ul>
            <p class="navbar-text pull-right">
              <?= e::h(msg('label_logged_in_as')) ?>
              <a href="<?= $this->base_url ?>/profile.php"><?= $this->userName ?></a>
            </p>
          </div><!--/.nav-collapse -->
        </div>
      </div>

    </div>
<?php if ($GLOBALS['CONFIG']['demo'] == 'True'): ?>
    <h1>Demo resets once per hour</h1>
<?php endif; ?>
      <div class="container">
        <div class="row">
            <div class="span4">
                You are here: <?= $this->breadCrumb ?>
            </div>
        </div>
        <p></p>
<?php if ($this->lastmessage != ''): ?>
        <div id="last_message"><?= e::h($this->lastmessage) ?></div>
<?php endif; ?>
