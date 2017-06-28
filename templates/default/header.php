<?php use Aura\Html\Escaper as e; ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title><?= $GLOBALS['CONFIG']['title'] ?> - <?= e::h($this->page_title) ?></title>
    <script type="text/javascript">
    <!--
    function popup(mylink, windowname)
    {
        if (! window.focus)return true;
        var href;
        if (typeof(mylink) == 'string')
            href=mylink;
        else
            href=mylink.href;
        window.open(href, windowname, 'width=300,height=500,scrollbars=yes');
        return false;
    }
    //-->
    </script>

    <!-- Must Include This File -->
<?php include "./templates/common/head_include.php"; ?>

    <link type="text/css" rel="stylesheet" href="<?= $this->base_url ?>/templates/default/css/default.css">
</head>
<body>
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Default header</code><br />
    <!-- ----------------begin_draw_menu----------------- -->
    <!-- ----------------UID is <?= $_SESSION['uid'] ?> ----------------- -->
    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td>
                <a href="<?= $this->base_url ?>/out.php">
                    <img src="<?= $this->base_url ?>/images/logo.gif" title="<?= $this->site_title ?>" alt="<?= $this->site_title ?>">
                </a>
            </td>
            <td align="right" >
                <p>
                <div class="buttons">
<?php if ($this->can_checkin || $this->isadmin == 'yes'): ?>
                    <a class="regular" href="<?= $this->base_url ?>/in.php"><img src="<?= $this->base_url ?>/images/import-2.png" width="16" height="16" alt="check in"/><?= e::h(msg('button_check_in')) ?></a>
<?php endif; ?>
                    <a class="regular" href="<?= $this->base_url ?>/search.php"><img src="<?= $this->base_url ?>/images/find-new-users.png" width="16" height="16" alt="search"/><?= e::h(msg('search')) ?></a>
<?php if ($this->can_add || $this->isadmin == 'yes'): ?>
                    <a class="regular" href="<?= $this->base_url ?>/add.php"><img src="<?= $this->base_url ?>/images/plus.png" width="16" height="16" alt="add file"/><?= e::h(msg('button_add_document')) ?></a>
<?php endif; ?>
<?php if ($this->isadmin == 'yes'): ?>
                    <a class="positive" href="<?= $this->base_url ?>/admin.php"><img src="<?= $this->base_url ?>/images/control.png" width="16" height="16" alt="admin"/><?= e::h(msg('label_admin')) ?></a>
<?php endif; ?>
                    <a class="negative" href="<?= $this->base_url ?>/logout.php"><?= e::h(msg('logout')) ?></a>
                </div>
                </p>
            </td>
        </tr>
    </table>
<?php if ($GLOBALS['CONFIG']['demo'] == 'True'): ?>
    <h1>Demo resets once per hour</h1>
<?php endif; ?>
    <!-- ----------------end_draw_menu----------------- -->
    <link rel="stylesheet" type="text/css" href="<?= $this->base_url ?>/linkcontrol.css">
    <table id="menu" width="100%" border="0" cellspacing="0" cellpadding="5">
        <tr>
<?php if ($this->userName): ?>
            <td bgcolor="#0000A0" align="left" valign="middle" width="10">
                <span class="statusbar"><?= $this->userName ?></span></td>
<?php endif; ?>
            <td bgcolor="#0000A0" align="left" valign="middle" width="10">
                <a class="statusbar" href="<?= $this->base_url ?>/out.php" style="text-decoration:none"><?= e::h(msg('home')) ?></a></td>
            <td bgcolor="#0000A0" align="left" valign="middle" width="10">
                <a class="statusbar" href="<?= $this->base_url ?>/profile.php" style="text-decoration:none"><?= e::h(msg('preferences')) ?></a></td>
            <td bgcolor="#0000A0" align="left" valign="middle" width="10">
                <a class="statusbar" href="<?= $this->base_url ?>/help.html" onClick="return popup(this, 'Help')" style="text-decoration:none"><?= e::h(msg('help')) ?></a></td>
            <td bgcolor="#0000A0" align="middle" valign="middle" width="0"><font size="3" face="Arial" color="White">|</font></td>
            <td bgcolor="#0000A0" align="left" valign="middle"><?= $this->breadCrumb ?></td>
            <td bgcolor="#0000A0" align="right" valign="middle">
                <b><font size="-2" face="Arial" color="White"><?= e::h(msg('message_last_message')) ?>: <?= e::h($this->lastmessage) ?></font></b>
            </td>
        </tr>
    </table>
    <div id="content">
<?php if ($this->lastmessage != ''): ?>
        <div id="last_message"><?= e::h($this->lastmessage) ?></div>
<?php endif; ?>
