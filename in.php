<?php
use Aura\Html\Escaper as e;

/*
in.php - display files checked out to user, offer link to check back in
Copyright (C) 2002-2013 Stephen Lawrence Jr.

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


// check session
session_start();

// includes
include('odm-load.php');
$view_registry->prependPath(
    __DIR__ . '/templates/' . $GLOBALS['CONFIG']['theme']
);

if (!isset($_SESSION['uid'])) {
    redirect_visitor();
}

$user_obj = new User($_SESSION['uid'], $pdo);

if (!$user_obj->canCheckIn()) {
    redirect_visitor('out.php');
}

$last_message = (isset($_REQUEST['last_message']) ? $_REQUEST['last_message'] : '');

//draw_header(msg('button_check_in'), $last_message);
view_header(msg('button_check_in'), $last_message);

// query to get list of documents checked out to this user
$query = "
  SELECT
    d.id,
    u.last_name,
    u.first_name,
  d.realname,
    d.created,
    d.description,
    d.status
  FROM
    {$GLOBALS['CONFIG']['db_prefix']}data as d,
    {$GLOBALS['CONFIG']['db_prefix']}user as u
  WHERE
    d.status = :uid
  AND
    d.owner = u.id
";
$stmt = $pdo->prepare($query);
$stmt->execute(array(
    ':uid' => $_SESSION['uid']
));
$result = $stmt->fetchAll();

// how many records?
$count = $stmt->rowCount();
if ($count == 0) { ?>
        <p><img src="images/exclamation.gif" /> <?= msg('message_no_documents_checked_out') ?></p>
<?php } else { ?>
        <table id="filesout">
            <caption><?= msg('message_document_checked_out_to_you') ?>: <?= e::h($count) ?></caption>
            <thead>
                <tr>
                    <th><?= msg('button_check_in') ?></td>
                    <th><?= msg('label_file_name') ?></td>
                    <th><?= msg('label_description') ?></td>
                    <th><?= msg('label_created_date') ?></td>
                    <th><?= msg('owner') ?></td>
                    <th><?= msg('label_size') ?></td>
                </tr>
            </thead>
            <tbody>
<?php
    // iterate through resultset
    foreach ($result as $row) {
        $id = $row['id'];
        $last_name = $row['last_name'];
        $first_name = $row['first_name'];
        $realname = $row['realname'];
        $created = $row['created'];
        $description = $row['description'];
        $status = $row['status'];

        // correction
        if ($description == '') {
            $description = msg('message_no_information_available');
        }
        $filename = $GLOBALS['CONFIG']['dataDir'] . $id . '.dat';
        // display list
?>
                <tr>
                    <td class="listtable">
                        <div class="buttons">
                            <a class="regular" href="check-in.php?id=<?= e::h($id) . '&amp;state=' . e::h(($_REQUEST['state']+1)) ?>">
                                <img src="images/import-2.png" width="32" height="32" alt="checkin" /> <?= msg('button_check_in') ?>
                            </a>
                        </div>
                    </td>
                    <td class="listtable"><?= e::h($realname) ?></td>
                    <td class="listtable"><?= e::h($description) ?></td>
                    <td class="listtable"><?= fix_date(e::h($created)) ?></td>
                    <td class="listtable"><?= e::h($last_name) ?>, <?= e::h($first_name) ?></td>
                    <td class="listtable"><?= display_filesize(e::h($filename)) ?></td>
                </tr>
<?php } // close foreach ?>
            </tbody>
        </table>
<?php
} // close if count else

//draw_footer();
view_footer();
