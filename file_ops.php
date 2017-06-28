<?php
use Aura\Html\Escaper as e;

/*
file_ops.php - admin file operations
Copyright (C) 2002-2004 Stephen Lawrence Jr, Khoa Nguyen
Copyright (C) 2005-2015 Stephen Lawrence Jr.

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

include('odm-load.php');
$view_registry->prependPath(
    __DIR__ . '/templates/' . $GLOBALS['CONFIG']['theme']
);

if (!isset($_SESSION['uid'])) {
    redirect_visitor();
}

$last_message = (isset($_REQUEST['last_message']) ? $_REQUEST['last_message'] : '');

// get a list of documents the user has "view" permission for
// get current user''s information-->department
$user_obj = new User($_SESSION['uid'], $pdo);
if (!$user_obj->isRoot()) {
    header('Location:error.php?ec=24');
}
$flag = 0;
if (isset($_GET['submit']) && $_GET['submit'] == 'view_checkedout') {
    //draw_header(msg('label_checked_out_files'), $last_message);
    $head = header_init(msg('label_checked_out_files'), $last_message);
    $view->setData([
        'breadCrumb'  => $head['breadCrumb'],
        'site_title'  => $head['site_title'],
        'base_url'    => $head['base_url'],
        'page_title'  => $head['page_title'],
        'lastmessage' => $head['lastmessage']
    ]);
    if ($head['userName']) {
        $view->addData([
            'userName'    => $head['userName'],
            'can_add'     => $head['can_add'],
            'can_checkin' => $head['can_checkin']
        ]);
    }
    if ($head['isadmin']) {
        $view->addData([
            'isadmin' => $head['isadmin']
        ]);
    }
    $view->setView('header');
    echo $view->__invoke();

    // this is not referenced anywhere
    $page_url = 'file_ops.php?';

    $file_id_array = $user_obj->getCheckedOutFiles();
    $user_perm_obj = new UserPermission($_SESSION['uid'], $pdo);

    $list_status = list_files($file_id_array, $user_perm_obj, $GLOBALS['CONFIG']['dataDir'], true, true);

    if ($list_status != -1) {
        // Call the plugin API
        callPluginMethod('onBeforeListFiles', $list_status['file_list_arr']);

        // The out template already has an open form tag - this creates nested forms which is invalid html.
        echo '<form name="table" action="file_ops.php" method="POST">' . PHP_EOL;
        echo '<input name="submit" type="hidden" value="Clear Status">' . PHP_EOL;

        $view->setData([
            'showCheckBox'  => $list_status['showCheckBox'],
            'limit_reached' => $list_status['limit_reached'],
            'file_list_arr' => $list_status['file_list_arr']
        ]);
        $view->setView('out');
        echo $view->__invoke();
?>
        <br />
        <div class="buttons">
            <button class="positive" type="submit" name="submit" value="Clear Status"><?= e::h(msg('button_clear_status')) ?></button>
        </div>
        <br />
    </form>
<?php
        callPluginMethod('onAfterListFiles');
    }

    //draw_footer();
    $view->setView('footer');
    echo $view->__invoke();

} elseif (isset($_POST['submit']) && $_POST['submit'] == 'Clear Status') {
    if (isset($_POST["checkbox"])) {
        foreach ($_POST['checkbox'] as $cbox) {
            $file_id = $cbox;
            $file_obj = new FileData($file_id, $pdo);
            $file_obj->setStatus(0);
        }
    }
    header('Location:file_ops.php?state=2&submit=view_checkedout');
} else {
    echo 'Nothing to do';
}
