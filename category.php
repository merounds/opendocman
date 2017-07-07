<?php
use Aura\Html\Escaper as e;
use Aura\Html\Escaper\AttrEscaper as a;
/*
category.php - Administer Categories
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

// check for valid session
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
// Check to see if user is admin
if (!$user_obj->isAdmin()) {
    header('Location:error.php?ec=4');
    exit;
}

$last_message = (isset($_REQUEST['last_message']) ? $_REQUEST['last_message'] : '');

if (isset($_GET['submit']) && $_GET['submit'] == 'add') {
//    draw_header(msg('area_add_new_category'), $last_message);
    view_header(msg('area_add_new_category'), $last_message);
?>

        <form id="categoryAddForm" action="category.php" method="GET" enctype="multipart/form-data">
        <table id="category_table">
            <tbody>
                <tr>
                    <td><label for="ca"><?= msg('category')?>: </label></td>
                    <td><input id="ca" class="required" type="text" name="category" maxlength="40" /></td>
                    <td>
                        <div class="buttons">
                            <button class="positive" type="Submit" name="submit" value="Add Category"><?= msg('button_add_category') ?></button>
                            <button class="negative cancel" type="submit" name="cancel" value="Cancel"><?= msg('button_cancel') ?></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        </form>

  <script>
  $(document).ready(function(){
    $('#categoryAddForm').validate();
  });
  </script>

<?php
//    draw_footer();
    view_footer();
} elseif (isset($_REQUEST['submit']) && $_REQUEST['submit']=='Add Category') {
    // Make sure they are an admin
    if (!$user_obj->isAdmin()) {
        header('Location:error.php?ec=4');
        exit;
    }

    $query = "INSERT INTO {$GLOBALS['CONFIG']['db_prefix']}category (name) VALUES (:category)";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(':category' => $_REQUEST['category']));

    // back to main page
    $last_message = msg('message_category_successfully_added');
    header('Location:admin.php?last_message=' . urlencode($last_message));
    exit;
} elseif (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'delete') {
    // If demo mode, don't allow them to update the demo account
    if ($GLOBALS['CONFIG']['demo'] == 'True') {
//        draw_header(msg('area_delete_category'), $last_message);
        view_header(msg('area_delete_category'), $last_message);
        echo msg('message_sorry_demo_mode');
//        draw_footer();
        view_footer();
        exit;
    }

//    draw_header(msg('area_delete_category'), $last_message);
    view_header(msg('area_delete_category'), $last_message);

    $item = (int) $_REQUEST['item'];

    // query to show item
    $query = "SELECT id, name FROM {$GLOBALS['CONFIG']['db_prefix']}category WHERE id = :item";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(':item' => $_REQUEST['item']));
    $cat = $stmt->fetch();

    // query to show re-assignment options
    $query = "SELECT id, name FROM {$GLOBALS['CONFIG']['db_prefix']}category WHERE id != :item  ORDER BY name";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(':item' => $_REQUEST['item']));
    $result = $stmt->fetchAll();
?>

        <form action="category.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= e::h($item) ?>" />
        <table id="category_table">
            <tbody>
                <tr>
                    <td><label><?= msg('label_id') ?> #: </label></td>
                    <td><?= e::h($cat['id']) ?></td>
                </tr>
                <tr>
                    <td><label><?= msg('label_name') ?>: </label></td>
                    <td><?= e::h($cat['name']) ?></td>
                </tr>
                <tr>
                    <td><label for="cd"><?= msg('label_reassign_to') ?>: </label></td>
                    <td>
                        <select id="cd" name="assigned_id">
<?php foreach ($result as $row): ?>
                            <option value="<?= e::h($row['id']) ?>"><?= e::h($row['name']) ?></option>
<?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><?= msg('message_are_you_sure_remove') ?></td>
                    <td>
                        <div class="buttons">
                            <button class="positive" type="submit" name="deletecategory" value="Yes"><?= msg('button_yes') ?></button>
                            <button class="negative cancel" type="submit" name="cancel" value="Cancel"><?= msg('button_cancel') ?></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        </form>

<?php
//    draw_footer();
    view_footer();
} elseif (isset($_REQUEST['deletecategory'])) {
    // Delete category
    //
    //
    // Make sure they are an admin
    if (!$user_obj->isAdmin()) {
        header('Location:error.php?ec=4');
        exit;
    }

    $query = "DELETE FROM {$GLOBALS['CONFIG']['db_prefix']}category where id=:id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(':id' => $_REQUEST[id]));

    // Set all old category_id's to the new re-assigned category
    $query = "UPDATE {$GLOBALS['CONFIG']['db_prefix']}data SET category = :assigned_id WHERE category = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(
        ':assigned_id' => $_REQUEST['assigned_id'],
        ':id' => $_REQUEST[id]
    ));

    // back to main page
    $last_message = msg('message_category_successfully_deleted') . ' id:' . $_REQUEST['id'];
    header('Location: admin.php?last_message=' . urlencode($last_message));
    exit;
} elseif (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'deletepick') {
// not used
    $deletepick='';
//    draw_header(msg('area_delete_category'). ' : ' .msg('choose'), $last_message);
    view_header(msg('area_delete_category'). ' : ' .msg('choose'), $last_message);

    $query = "SELECT id, name FROM {$GLOBALS['CONFIG']['db_prefix']}category ORDER BY name";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll();
?>

        <form action="category.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="state" value="<?= (e::h($_REQUEST['state']+1)) ?>" />
        <table id="category_table">
            <tbody>
                <tr>
                    <td><label for="cd"><?= msg('category')?>: </label></td>
                    <td>
                        <select id="cd" name="item">
<?php foreach ($result as $row): ?>
                            <option value="<?= e::h($row['id']) ?>"><?= e::h($row['name']) ?></option>
<?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <div class="buttons">
                            <button class="positive" type="submit" name="submit" value="delete"><?= msg('button_delete') ?></button>
                            <button class="negative cancel" type="submit" name="cancel" value="Cancel"><?= msg('button_cancel') ?></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        </form>

<?php
//    draw_footer();
    view_footer();
} elseif (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'Show Category') {
//    draw_header(msg('area_view_category'), $last_message);
    view_header(msg('area_view_category'), $last_message);

    // query to show item
    $category_id = (int) $_REQUEST['item'];

    // Select category name
    $query = "SELECT name FROM {$GLOBALS['CONFIG']['db_prefix']}category WHERE id = :category_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(
        ':category_id' => $category_id
    ));
    $cat = $stmt->fetch();
?>

        <form action="admin.php" method="POST" enctype="multipart/form-data">
        <table id="category_table" name="main">
            <tbody>
                <tr>
                    <td><label><?= msg('category') ?> <?= msg('label_name') ?>: </label></td>
                    <td><?= e::h($cat['name']) ?></td>
                    <td>
                        <div class="buttons">
                            <button class="regular" type="submit" name="submit" value="Back"><?= msg('button_back') ?></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        </form>

<?php
    // add the list of files here
    $query = "SELECT id, realname FROM `{$GLOBALS['CONFIG']['db_prefix']}data` WHERE category = :category_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(
        ':category_id' => $category_id
    ));
    $result = $stmt->fetchAll();

    if ($stmt->rowCount() <= 0) {
        echo '<p>' . msg('categoryviewpage_list_of_files_none') . '</p>';
    } else {
        echo '<p>' . msg('categoryviewpage_list_of_files_title') . '</p>';
        foreach ($result as $row) {
            echo 'ID #' . e::h($row['id']) . ': <a href="edit.php?id=' . e::h($row['id']) . '&state=3">' . e::h($row['realname']) . '</a><br />';
        }
    }

//    draw_footer();
    view_footer();
} elseif (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'showpick') {
//    draw_header(msg('area_view_category') . ' : ' . msg('choose'), $last_message);
    view_header(msg('area_view_category') . ' : ' . msg('choose'), $last_message);

    $query = "SELECT id, name FROM {$GLOBALS['CONFIG']['db_prefix']}category ORDER BY name";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll();
?>

        <form action="category.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="state" value="<?= (e::h($_REQUEST['state']+1)); ?>" />
        <table id="category_table">
            <tbody>
                <tr>
                    <td><label for="cs"><?= msg('category')?>: </label></td>
                    <td>
                        <select id="cs" name="item">
<?php foreach ($result as $row): ?>
                            <option value="<?= e::h($row['id']) ?>"><?= e::h($row['name']) ?></option>
<?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <div class="buttons">
                            <button class="positive" type="Submit" name="submit" value="Show Category"><?= msg('area_view_category') ?></button>
                            <button class="negative cancel" type="Submit" name="cancel" value="Cancel"><?= msg('button_cancel') ?></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        </form>

<?php
//    draw_footer();
    view_footer();
} elseif (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'Update') {
//    draw_header(msg('area_update_category'), $last_message);
    view_header(msg('area_update_category'), $last_message);

    $item = (int)$_REQUEST['item'];
    // query to get a category
    $query = "SELECT id, name FROM {$GLOBALS['CONFIG']['db_prefix']}category where id = :item";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(
        ':item' => $item
    ));
    $cat = $stmt->fetch();
?>

        <form id="updateCategoryForm" action="category.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= e::h($cat['id']) ?>" />
        <table id="category_table">
            <tbody>
                <tr>
                    <td><label for="cu"><?= msg('category') ?>: </label></td>
                    <td><input id="cu" class="required" type="text" name="name" value="<?= e::h($cat['name']) ?>" maxlength="40" /></td>
                    <td>
                        <div class="buttons">
                            <button class="positive" type="Submit" name="updatecategory" value="Modify Category"><?= msg('area_update_category') ?></button>
                            <button class="negative cancel" type="Submit" name="cancel" value="Cancel"><?= msg('button_cancel') ?></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        </form>

  <script>
  $(document).ready(function(){
    $('#updateCategoryForm').validate();
  });
  </script>

<?php
//    draw_footer();
    view_footer();
} elseif (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'updatepick') {
//    draw_header(msg('area_update_category'). ': ' .msg('choose'), $last_message);
    view_header(msg('area_update_category'). ': ' .msg('choose'), $last_message);

    // query to get a list of categories
    $query = "SELECT id, name FROM {$GLOBALS['CONFIG']['db_prefix']}category ORDER BY name";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll();
?>

        <form action="category.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="state" value="<?php echo(e::h($_REQUEST['state']+1)); ?>" />
        <table id="category_table">
            <tbody>
                <tr>
                    <td><label for="cup"><?= msg('choose')?> <?= msg('category')?>: </label></td>
                    <td>
                        <select id="cup" name="item">
<?php foreach ($result as $row): ?>
                            <option value="<?= e::h($row['id']) ?>"><?= e::h($row['name']) ?></option>
<?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <div class="buttons">
                            <button class="positive" type="submit" name="submit" value="Update"><?= msg('choose')?></button>
                            <button class="negative cancel" type="submit" name="cancel" value="Cancel"><?= msg('button_cancel')?></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        </form>

<?php
//    draw_footer();
    view_footer();
} elseif (isset($_REQUEST['updatecategory'])) {
    // Make sure they are an admin
    if (!$user_obj->isAdmin()) {
        header('Location: error.php?ec=4');
        exit;
    }
    $id = (int) $_REQUEST['id'];

    $query = "UPDATE {$GLOBALS['CONFIG']['db_prefix']}category SET name = :name where id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(
        ':name' => $_REQUEST['name'],
        ':id' => $id
    ));

    // back to main page
    $last_message = msg('message_category_successfully_updated') .' : ' . $_REQUEST['name'];
    header('Location: admin.php?last_message=' . urlencode($last_message));
    exit;
} elseif (isset($_REQUEST['cancel']) && $_REQUEST['cancel'] == 'Cancel') {
    $last_message = msg('message_action_cancelled');
    header('Location: admin.php?last_message=' . urlencode($last_message));
    exit;
}
