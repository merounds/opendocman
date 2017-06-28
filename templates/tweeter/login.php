<?php use Aura\Html\Escaper as e; ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title><?= e::h($GLOBALS['CONFIG']['title']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <!-- Le styles -->

    <link href="<?= e::h($this->base_url) ?>/templates/tweeter/css/bootstrap.css" rel="stylesheet">
    <link href="<?= e::h($this->base_url) ?>/templates/tweeter/css/tweeter.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
        margin-left: 10px;
      }
      .sitelogin {
        border: 0;
        border-collapse: separate;
        border-spacing: 5px;     /* cellspacing */
      }
      .sitelogin th,
      .sitelogin td {
        padding: 5px;            /* cellpadding */
      }
    </style>
    <link href="<?= e::h($this->base_url) ?>/templates/tweeter/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="<?= e::h($this->base_url) ?>/templates/tweeter/js/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="<?= e::h($this->base_url) ?>/templates/tweeter/images/favicon.ico" />

    <link rel="apple-touch-icon" href="<?= e::h($this->base_url) ?>/templates/tweeter/images/apple-touch-icon.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="<?= e::h($this->base_url) ?>/templates/tweeter/images/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="<?= e::h($this->base_url) ?>/templates/tweeter/images/apple-touch-icon-114x114.png" />

    <!-- Must Include This File -->
<?php include "./templates/common/head_include.php"; ?>

</head>
<body bgcolor="White">
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Tweeter login</code><br />
    <p><img src="images/logo.gif" width="250" height="98" alt="Site Logo" /></p>

    <table class="sitelogin">
        <tbody>
            <tr>
                <td colspan="2"><?= e::h(msg('welcome')) ?> <?= e::h(msg('welcome2')) ?></td>
            </tr>
<?php if ($GLOBALS['CONFIG']['demo'] == 'True'): ?>
            <tr>
                <th>Regular User:</th>
                <td>Username: demo / Password: demo</td>
            </tr>
            <tr>
                <th>Admin User:</th>
                <td>Username: admin / Password: admin</td>
            </tr>
<?php endif; ?>
            <tr>
                <td colspan="2" valign="top">
                    <form action="index.php" method="post">
<?php if ($this->redirection): ?>
                    <input type="hidden" name="redirection" value="<?= $this->redirection ?>">
<?php endif; ?>
                    <table class="sitelogin">
                        <tbody>
                            <tr>
                                <td><label for="user"><?= e::h(msg('username')) ?></label></td>
                                <td><input id="user" type="text" name="frmuser" size="15" /></td>
                            </tr>
                            <tr>
                                <td><label for="pass"><?= e::h(msg('password')) ?></label></td>
                                <td><input id="pass" type="password" name="frmpass" size="15" />
<?php if ($GLOBALS['CONFIG']['allow_password_reset'] == 'True'): ?>
                                    <a href="<?= e::h($this->base_url) ?>/forgot_password.php"><?= e::h(msg('forgotpassword')) ?></a>
<?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align:center;">
                                    <input type="submit" name="login" value="<?= e::h(msg('enter')) ?>" />
                                </td>
                            </tr>
<?php if ($GLOBALS['CONFIG']['allow_signup'] == 'True'): ?>
                            <tr>
                                <td colspan="2"><a href="<?= e::h($this->base_url) ?>/signup.php"><?= e::h(msg('signup')) ?></a></td>
                            </tr>
<?php endif; ?>
                        </tbody>
                    </table>
                    </form>
                </td>
            </tr>
        </tbody>
    </table>
