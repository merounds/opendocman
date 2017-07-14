<?php
use Aura\Html\Escaper as e;

/*
history.php - display revision history
Copyright (C) 2002, 2003, 2004 Stephen Lawrence Jr., Khoa Nguyen
Copyright (C) 2005-2013 Stephen Lawrence Jr.

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


// check session and $id
session_start();

include('odm-load.php');
$view_registry->prependPath(
    __DIR__ . '/templates/' . $GLOBALS['CONFIG']['theme']
);

if (!isset($_SESSION['uid'])) {
    redirect_visitor();
}

$last_message = (isset($_REQUEST['last_message']) ? $_REQUEST['last_message'] : '');

if (!isset($_REQUEST['id']) || $_REQUEST['id'] == '') {
    header('Location:error.php?ec=2');
    exit;
}

//draw_header(msg('area_view_history'), $last_message);
view_header(msg('area_view_history'), $last_message);

//revision parsing
if (strchr($_REQUEST['id'], '_')) {
    list($_REQUEST['id'], $revision_id) = explode('_', $_REQUEST['id']);
}
$file_data_obj = new FileData($_REQUEST['id'], $pdo);
$user_obj = new User($file_data_obj->getOwner(), $pdo);
// verify
if ($file_data_obj->getError() != null) {
    header('Location:error.php?ec=2');
    exit;
} else {
    // obtain data from resultset

    $real_name = $file_data_obj->getRealName();
    $category = $file_data_obj->getCategoryName();
    $created = $file_data_obj->getCreatedDate();
    $owner_name_array = $file_data_obj->getOwnerFullName();
    $owner_last_first = $owner_name_array[1] . ', ' . $owner_name_array[0];
    $owner_first_last = $owner_name_array[0] . ' ' . $owner_name_array[1];
    $description = $file_data_obj->getDescription();
    $comment = $file_data_obj->getComment();
    $status = $file_data_obj->getStatus();
    $reviewer = $file_data_obj->getReviewerName();
    $id = $_REQUEST['id'];

    // corrections
    if ($description == '') {
        $description = msg('message_no_description_available');
    }
    if ($comment == '') {
        $comment = msg('message_no_author_comments_available');
    }
    if ($file_data_obj->isArchived()) {
        $filename = $GLOBALS['CONFIG']['archiveDir'] . e::h($id) . '.dat';
    } else {
        $filename = $GLOBALS['CONFIG']['dataDir'] . e::h($id) . '.dat';
    }
    // display red or green icon depending on file status
    if ($status == 0) {
        $file_unlocked = true;
    } else {
        $file_unlocked = false;
    }
    if (isset($revision_id)) {
        if ($revision_id == 0) {
            $revision_value = msg('historypage_original_revision');
        } else {
            $revision_value = $revision_id;
        }
    } else {
        $revision_value = msg('historypage_latest');
    }

    $file_under_review = (($file_data_obj->isPublishable() == -1) ? true : false);

    $reviewer_comments_str = $file_data_obj->getReviewerComments();
    $reviewer_comments_fields = explode(';', $reviewer_comments_str);

    for ($i = 0; $i < sizeof($reviewer_comments_fields); $i++) {
        $reviewer_comments_fields[$i] = str_replace('"', '&quot;', $reviewer_comments_fields[$i]);
        $reviewer_comments_fields[$i] = str_replace('\\', '', $reviewer_comments_fields[$i]);
    }

    // No To? Give them the default
    if (isset($reviewer_comments_fields[0]) && strlen($reviewer_comments_fields[0]) <= strlen('To=')) {
        $reviewer_comments_fields[0] = 'To=Author(s)';
    }

    // No subject? Give them the default
    if (isset($reviewer_comments_fields[1]) && strlen($reviewer_comments_fields[1]) <= strlen('Subject=')) {
        $reviewer_comments_fields[1] = 'Subject=Comments regarding the review for your documentation';
    }

    $to_value = (isset($reviewer_comments_fields[0]) ? (substr($reviewer_comments_fields[0], 3)) : '');
    $subject_value = (isset($reviewer_comments_fields[1]) ? (substr($reviewer_comments_fields[1], 8)) : '');
    $comments_value = (isset($reviewer_comments_fields[2]) ? (substr($reviewer_comments_fields[2], 9)) : '');


    $file_detail_array = array(
        'file_unlocked'       => $file_unlocked,
        'realname'            => $real_name,
        'category'            => $category,
        'filesize'            => display_filesize($filename),
        'created'             => fix_date($created),
        'owner'               => $owner_last_first,
        'description'         => wordwrap($description, 50, '<br />'),
        'comment'             => wordwrap($comment, 50, '<br />'),
        'udf_details_display' => udf_details_display($id),
        'revision'            => $revision_value,
        'file_under_review'   => $file_under_review,
        'reviewer'            => $reviewer,
        'status'              => $status,

        'owner_fullname'      => $owner_first_last,
        'owner_email'         => $user_obj->getEmailAddress(),
        'to_value'            => $to_value,
        'subject_value'       => $subject_value,
        'comments_value'      => $comments_value
    );

    $view->setData([
        'file_detail'    => $file_detail_array,
        'view_link'      => '',
        'check_out_link' => '',
        'edit_link'      => '',
        'history_link'   => ''
    ]);

    if ($status > 0) {
        // status != 0 -> file checked out to another user.
        // status = uid of the check-out person
        // query to find out who...
        $checkout_person_obj = $file_data_obj->getCheckerOBJ();
        $view->addData([
            'checkout_person_full_name' => $checkout_person_obj->getFullName(),
            'checkout_person_email' => $checkout_person_obj->getEmailAddress()
        ]);
    }

    $view->setView('details');
    echo $view->__invoke();

    // query to obtain a list of modifications

    if (isset($revision_id)) {
        $query = "
          SELECT
            u.last_name,
            uuser.first_name,
            l.modified_on,
            l.note,
            l.revision
          FROM
            {$GLOBALS['CONFIG']['db_prefix']}log l,
            {$GLOBALS['CONFIG']['db_prefix']}user u
          WHERE
            l.id = :id
          AND
            u.username = l.modified_by
          AND
            l.revision <= :revision_id
          ORDER BY
            l.modified_on DESC
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(
            ':id' => $id,
            ':revision_id'=> $revision_id
        ));
        $result = $stmt->fetchAll();
    } else {
        $query = "
          SELECT
            u.last_name,
            u.first_name,
            l.modified_on,
            l.note,
            l.revision
          FROM
            {$GLOBALS['CONFIG']['db_prefix']}log l,
            {$GLOBALS['CONFIG']['db_prefix']}user u
          WHERE
            l.id = :id
          AND
            u.username = l.modified_by
          ORDER BY
            l.modified_on DESC
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(
            ':id' => $id
        ));
        $result = $stmt->fetchAll();
    }

    // not used...
    $current_revision = $stmt->rowCount();

    // iterate through resultset
    foreach ($result as $row) {
        $last_name = $row['last_name'];
        $first_name = $row['first_name'];
        $modified_on = $row['modified_on'];
        $note = $row['note'];
        $revision = $row['revision'];

        $extra_message = '';
        if (is_file($GLOBALS['CONFIG']['revisionDir'] . $id . '/' . $id . "_$revision.dat")) {
            $version_link = 'details.php?id=' . e::h($id) . '_' . e::h($revision) . '&state=' . (e::h($_REQUEST['state']));
        } else {
            $version_link = 'none';
        }

        $history_items = array(
            'version_link'  => $version_link,
            'version'       => ($revision + 1),
            'revision'      => $revision,
            'extra_message' => $extra_message,
            'modified_date' => fix_date($modified_on),
            'owner_name'    => $last_name . ', ' . $first_name,
            'note'          => $note
        );
        $history_list[] = $history_items;
    }

    $view->setData([
        'history_list_array' => $history_list
    ]);
    $view->setView('history');
    echo $view->__invoke();

    // Call the plugin API
    callPluginMethod('onAfterHistory', $file_data_obj->getId());

    //draw_footer();
    view_footer();
}
