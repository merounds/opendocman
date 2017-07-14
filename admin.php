<?php
use Aura\Html\Escaper as e;
/*
admin.php - provides admin interface
Copyright (C) 2007 Stephen Lawrence Jr., Jon Miner
Copyright (C) 2002-2011 Stephen Lawrence Jr.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

session_start();
// admin.php - administration functions for admin users
// check for valid session
// includes
include('odm-load.php');
$view_registry->prependPath(
    __DIR__ . '/templates/' . $GLOBALS['CONFIG']['theme']
);

if (!isset($_SESSION['uid'])) {
    redirect_visitor();
}

include('udf_functions.php');

// open a connection to the database
$user_obj = new User($_SESSION['uid'], $pdo);

// Check to see if user is admin
if (!$user_obj->isAdmin()) {
    header('Location:error.php?ec=4');
    exit;
}

$last_message = (isset($_REQUEST['last_message']) ? $_REQUEST['last_message'] : '');
//draw_header(msg('label_admin'), $last_message);
view_header(msg('label_admin'), $last_message);

$request_state = e::h(($_REQUEST['state']+1));
?>
        <table class="adminmenu">
            <thead>
                <tr>
                    <th><?= msg('users'); ?></th>
                    <th><?= msg('label_department'); ?></th>
                    <th><?= msg('category'); ?></th>
<?php if ($user_obj->isRoot()): ?>
                    <th><?= msg('file'); ?></th>
                    <?= udf_admin_header(); ?>
<?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <!-- User Admin -->
                        <div><b><a href="user.php?submit=adduser&state=<?= $request_state; ?>">
                            <?= msg('label_add'); ?></a></b></div>
                        <div><b><a href="user.php?submit=deletepick&state=<?= $request_state; ?>">
                            <?= msg('label_delete'); ?></a></b></div>
                        <div><b><a href="user.php?submit=updatepick&state=<?= $request_state; ?>">
                            <?= msg('label_update'); ?></a></b></div>
                        <div><b><a href="user.php?submit=showpick&state=<?= $request_state; ?>">
                            <?= msg('label_display'); ?></a></b></div>
                    </td>
                    <td>
                        <!-- Department Admin -->
                        <div><b><a href="department.php?submit=add&state=<?= $request_state; ?>">
                            <?= msg('label_add'); ?></a></b></div>
                        <div><b><a href="department.php?submit=deletepick&state=<?= $request_state; ?>">
                            <?= msg('label_delete'); ?></a></b></div>
                        <div><b><a href="department.php?submit=updatepick&state=<?= $request_state; ?>">
                            <?= msg('label_update'); ?></a></b></div>
                        <div><b><a href="department.php?submit=showpick&state=<?= $request_state; ?>">
                            <?= msg('label_display'); ?></a></b></div>
                    </td>
                    <td>
                        <!-- Category Admin -->
                        <div><b><a href="category.php?submit=add&state=<?= $request_state; ?>">
                            <?= msg('label_add'); ?></a></b></div>
                        <div><b><a href="category.php?submit=deletepick&state=<?= $request_state; ?>">
                            <?= msg('label_delete'); ?></a></b></div>
                        <div><b><a href="category.php?submit=updatepick&state=<?= $request_state; ?>">
                            <?= msg('label_update'); ?></a></b></div>
                        <div><b><a href="category.php?submit=showpick&state=<?= $request_state; ?>">
                            <?= msg('label_display'); ?></a></b></div>
                    </td>
<?php if ($user_obj->isRoot()): ?>
                    <td>
                        <!-- File Admin (Root-Only) -->
                        <div><b><a href="delete.php?mode=view_del_archive&state=<?= $request_state; ?>">
                            <?= msg('label_delete_undelete'); ?></a></b></div>
                        <div><b><a href="toBePublished.php?mode=root&state=<?= $request_state; ?>">
                            <?= msg('label_reviews'); ?></a></b></div>
                        <div><b><a href="rejects.php?mode=root&state=<?= $request_state; ?>">
                            <?= msg('label_rejections'); ?></a></b></div>
                        <div><b><a href="check_exp.php?&state=<?= $request_state; ?>">
                            <?= msg('label_check_expiration'); ?></a></b></div>
                        <div><b><a href="file_ops.php?&state=<?= $request_state; ?>&submit=view_checkedout">
                            <?= msg('label_checked_out_files'); ?></a></b></div>
                    </td>
                    <?php udf_admin_menu(); ?>
<?php endif; ?>
                </tr>
            </tbody>
        </table>
        <table class="adminmenu">
<?php if ($user_obj->isRoot()): ?>
            <thead>
                <tr>
                    <th><?= msg('label_settings'); ?></th>
                    <th><?= msg('adminpage_reports'); ?></th>
                    <th><?= msg('adminpage_about_section_title'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <!-- Settings Admin -->
                        <div><b><a href="settings.php?submit=update&state=<?= $request_state; ?>">
                            <?= msg('adminpage_edit_settings'); ?></a></b></div>
                        <div><b><a href="filetypes.php?submit=update&state=<?= $request_state; ?>">
                            <?= msg('adminpage_edit_filetypes'); ?></a></b></div>
                    </td>
                    <td>
                        <!-- Reports Admin -->
                        <div><b><a href="access_log.php?submit=update&state=<?= $request_state; ?>">
                            <?= msg('adminpage_access_log'); ?></a></b></div>
                        <div><b><a href="reports/file_list.php">
                            <?= msg('adminpage_reports_file_list'); ?></a></b></div>
                    </td>
                    <td>
                        <!-- About Admin -->
                        <div><b><?= msg('adminpage_about_section_app_version') . ": " . e::h($GLOBALS['CONFIG']['current_version']); ?></b></div>
                        <div><b><?= msg('adminpage_about_section_db_version') . ": " . e::h(Settings::get_db_version()); ?></b></div>
                        <div><b><?= msg('adminpage_about_section_jquery_version') ?>: <script type="text/javascript">document.write($().jquery);</script></b></div>
                    </td>
                </tr>
            </tbody>
<?php endif; ?>
        </table>

<?php if (is_array($GLOBALS['plugin']->getPluginsList()) && $user_obj->isRoot()): ?>
        <table class="adminmenu">
            <tbody>
                <tr>
                    <th><?= msg('label_plugins') ?></th>
                </tr>
                <tr>
                    <td>
                        <?php //Perform the admin loop section to add plugin menu items
                        callPluginMethod('onAdminMenu');
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
<?php endif; ?>

<?php
//draw_footer();
view_footer();
