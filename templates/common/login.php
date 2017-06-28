<?php use Aura\Html\Escaper as e; ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title><?= $GLOBALS['CONFIG']['title'] ?></title>
    <style type="text/css">
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
</head>

<body bgcolor="White" style="margin-left:10px;">
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Common login</code><br />
    <p><img src="images/logo.gif" width="250" height="98" alt="Site Logo" border=0 /></p>

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
                <td colspan="2" style="vertical-align: top;">
                    <form action="index.php" method="post">
<?php if ($this->redirection): ?>
                    <input type="hidden" name="redirection" value="<?= $this->redirection ?>" />
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
