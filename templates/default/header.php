<?php use Aura\Html\Escaper as e; ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title><?= $GLOBALS['CONFIG']['title'] ?> - <?= e::h($this->page_title) ?></title>

    <!-- Must Include This File -->
<?php include "./templates/common/head_include.php"; ?>

    <link rel="stylesheet" type="text/css" href="<?= $this->base_url ?>/linkcontrol.css">
    <link rel="stylesheet" type="text/css" href="<?= $this->base_url ?>/templates/default/css/default.css">
</head>
<body>
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Default header</code><br />
    <!-- ----------------begin_draw_menu----------------- -->
    <!-- ------------------UID is <?= isset($_SESSION['uid']) ? $_SESSION['uid'] : 'n/a' ?> ------------------- -->
    <table id="mainmenu">
        <tbody>
            <tr>
                <td>
                    <a href="<?= $this->base_url ?>/out.php">
                        <img src="<?= $this->base_url ?>/images/logo.gif" title="<?= $this->site_title ?>" alt="<?= $this->site_title ?>" />
                    </a>
                </td>
                <td>
                    <div class="buttons">
<?php if ($this->can_checkin || $this->isadmin == 'yes'): ?>
                        <a class="regular" href="<?= $this->base_url ?>/in.php"><img src="<?= $this->base_url ?>/images/import-2.png" width="16" height="16" alt="check in" /><?= e::h(msg('button_check_in')) ?></a>
<?php endif; ?>
<?php if ($this->userName): ?>
                        <a class="regular" href="<?= $this->base_url ?>/search.php"><img src="<?= $this->base_url ?>/images/find-new-users.png" width="16" height="16" alt="search" /><?= e::h(msg('search')) ?></a>
<?php endif; ?>
<?php if ($this->can_add || $this->isadmin == 'yes'): ?>
                        <a class="regular" href="<?= $this->base_url ?>/add.php"><img src="<?= $this->base_url ?>/images/plus.png" width="16" height="16" alt="add file" /><?= e::h(msg('button_add_document')) ?></a>
<?php endif; ?>
<?php if ($this->isadmin == 'yes'): ?>
                        <a class="positive" href="<?= $this->base_url ?>/admin.php"><img src="<?= $this->base_url ?>/images/control.png" width="16" height="16" alt="admin" /><?= e::h(msg('label_admin')) ?></a>
<?php endif; ?>
<?php if ($this->userName): ?>
                        <a class="negative" href="<?= $this->base_url ?>/logout.php"><?= e::h(msg('logout')) ?></a>
<?php endif; ?>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
<?php if ($GLOBALS['CONFIG']['demo'] == 'True'): ?>
    <h1><?= e::h(msg('message_demo_resets')) ?></h1>
<?php endif; ?>
    <!-- ----------------end_draw_menu----------------- -->
    <table id="sub-menu">
        <tbody>
            <tr>
<?php if ($this->userName): ?>
                <td>
                    <span class="statusbar"><?= $this->userName ?></span></td>
                <td>
                    <a class="statusbar" href="<?= $this->base_url ?>/out.php"><?= e::h(msg('home')) ?></a></td>
                <td>
                    <a class="statusbar" href="<?= $this->base_url ?>/profile.php"><?= e::h(msg('preferences')) ?></a></td>
                <td>
                    <a class="statusbar" href="<?= $this->base_url ?>/help.html" onClick="return popup(this, 'Help')"><?= e::h(msg('help')) ?></a></td>
                <td class="center"><span class="statusbar" style="font-size:medium;">|</span></td>
<?php endif; ?>
                <td id="crumb"><?= $this->breadCrumb ?></td>
<?php if ($this->lastmessage != ''): ?>
                <td class="right" id="lmsg">
                    <span class="statusbar"><?= e::h(msg('message_last_message')) ?>: <?= e::h($this->lastmessage) ?></span>
                </td>
<?php endif; ?>
            </tr>
        </tbody>
    </table>
    <div id="content">
        <br style="clear: both;" />
<?php if ($this->lastmessage != ''): ?>
        <div id="last_message"><?= e::h($this->lastmessage) ?></div>
<?php endif; ?>
