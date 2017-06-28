<?php
use Aura\Html\Escaper as e;

/*
out.php - display a list of all available documents that user has permission to view (with file status)
Copyright (C) 2002, 2003, 2004 Stephen Lawrence Jr., Khoa Nguyen
Copyright (C) 2005-2012 Stephen Lawrence Jr.

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

// check to ensure valid session, else redirect
session_start();
//$_SESSION['uid']=102; $sort_by = 'author';
//$start_time = time();

// includes
$GLOBALS['state'] = 1;
require_once 'odm-load.php';
$view_registry->prependPath(
    __DIR__ . '/templates/' . $GLOBALS['CONFIG']['theme']
);

if (!isset($_SESSION['uid'])) {
    redirect_visitor();
}

$last_message = isset($_REQUEST['last_message']) ? $_REQUEST['last_message'] : '';

//draw_header(msg('label_file_listing'), $last_message);
$head = header_init(msg('label_file_listing'), $last_message);
$view->setData([
    'breadCrumb'  => $head['breadCrumb'],
    'site_title'  => $head['site_title'],
    'base_url'    => $head['base_url'],
    'page_title'  => $head['page_title'],
    'lastmessage' => $head['lastmessage'],
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

sort_browser();

$user_obj = new User($_SESSION['uid'], $pdo);

if ($user_obj->isAdmin()) {
    $reviewIdCount = sizeof($user_obj->getAllRevieweeIds());
} elseif ($user_obj->isReviewer()) {
    $reviewIdCount = sizeof($user_obj->getRevieweeIds());
} else {
    $reviewIdCount = 0;
}

if ($reviewIdCount > 0) {
    echo '<img src="images/exclamation.gif" /> <a href="toBePublished.php?state=1">'.msg('message_documents_waiting'). '</a>: ' . e::h($reviewIdCount)  . '</a><br />';
}

$rejected_files_obj = $user_obj->getRejectedFileIds();

if (isset($rejected_files_obj[0]) && $rejected_files_obj[0] != null) {
    echo '<img src="images/exclamation_red.gif" /> <a href="rejects.php?state=1">'. msg('message_documents_rejected') . '</a>: ' .sizeof($rejected_files_obj) . '<br />';
}

$llen = $user_obj->getNumExpiredFiles();
if ($llen > 0) {
    echo '<img src="images/exclamation_red.gif" /><a href="javascript:window.location=\'search.php?submit=submit&sort_by=id&where=author_locked_files&sort_order=asc&keyword=-1&exact_phrase=on\'">' .msg('message_documents_expired'). ': ' . e::h($llen) . '</a><br />';
}
// get a list of documents the user has "view" permission for
// get current user's information-->department



//set values
$user_perms = new UserPermission($_SESSION['uid'], $pdo);
//$start_P = getmicrotime();
$file_id_array = $user_perms->getViewableFileIds(true);
//$end_P = getmicrotime();

$files = list_files($file_id_array, $user_perms, $GLOBALS['CONFIG']['dataDir'], false);

if ($files != -1) {
    // Call the plugin API
    callPluginMethod('onBeforeListFiles', $files['file_list_arr']);

    $view->setData([
        'showCheckBox'  => $files['showCheckBox'],
        'limit_reached' => $files['limit_reached'],
        'file_list_arr' => $files['file_list_arr']
    ]);
    $view->setView('out');
    echo $view->__invoke();

    callPluginMethod('onAfterListFiles');
}

//draw_footer();
$view->setView('footer');
echo $view->__invoke();

//Fb::log('<br> <b> Load Page Time: ' . (getmicrotime() - $start_time) . ' </b>');
//echo '<br> <b> Load Permission Time: ' . ($end_P - $start_P) . ' </b>';
//echo '<br> <b> Load Sort Time: ' . ($lsort_e - $lsort_b) . ' </b>';
//echo '<br> <b> Load Table Time: ' . ($llist_e - $llist_b) . ' </b>';
