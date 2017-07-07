<?php
use Aura\Html\Escaper as e;

/*
   department.php - Administer Departments
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

$last_message = (isset($_REQUEST['last_message']) ? $_REQUEST['last_message'] : '');

// Make sure user is admin
$user_obj = new User($_SESSION['uid'], $pdo);

//If the user is not an admin and he/she is trying to access other account that
// is not his, error out.
if (!$user_obj->isAdmin() == true) {
    header('Location:error.php?ec=4');
    exit;
}

/*
   Add A New Department
*/
if (isset($_GET['submit']) && $_GET['submit']=='add') {
//    draw_header(msg('area_add_new_department'), $last_message);
    view_header(msg('area_add_new_department'), $last_message);
?>

        <form id="addDepartmentForm" action="department.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="submit" value="Add Department" />
        <table id="department_table">
            <tbody>
                <tr>
                    <td><label for="da"><?= msg('department') ?>: </label></td>
                    <td><input id="da" class="required" type="text" name="department" minlength="2" /></td>
<?php
    // Call the plugin API
    callPluginMethod('onDepartmentAddForm');
?>
                    <td>
                        <div class="buttons">
                            <button class="positive" type="submit" name="submit" value="Add Department"><?= msg('button_add_department') ?></button>
                            <button class="negative cancel" type="submit" name="submit" value="Cancel"><?= msg('button_cancel') ?></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        </form>

  <script>
  $(document).ready(function(){
    $('#addDepartmentForm').validate();
  });
  </script>

<?php
//    draw_footer();
    view_footer();
} elseif (isset($_POST['submit']) && $_POST['submit'] == 'Add Department') {
    //Add Departments
    //
    // Make sure they are an admin
    if (!$user_obj->isAdmin()) {
        header('Location:error.php?ec=4');
        exit;
    }

    $department = (isset($_POST['department']) ? $_POST['department'] : '');
    if ($department == '') {
        $last_message = msg('departmentpage_department_name_required');
        header('Location: admin.php?last_message=' . urlencode($last_message));
        exit;
    }
    //Check to see if this department is already in DB
    $query = "SELECT name FROM {$GLOBALS['CONFIG']['db_prefix']}department where name = :department";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(':department' => $department));
    $result = $stmt->fetchAll();

    if ($stmt->rowCount() != 0) {
        header('Location: error.php?ec=3&message=' . urlencode($department) . ' already exist in the database');
        exit;
    }

    $query = "INSERT INTO {$GLOBALS['CONFIG']['db_prefix']}department (name) VALUES (:department)";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(':department' => $department));

    // back to main page
    $last_message = urlencode(msg('message_department_successfully_added'));
    /////////Give New Department data's default rights///////////
    ////Get all default rights////
    $query = "SELECT id, default_rights FROM {$GLOBALS['CONFIG']['db_prefix']}data";
    $stmt = $pdo->prepare($query);
    $result = $stmt->fetchAll();

    $num_rows = $stmt->rowCount();
    $data_array = array();

    $index = 0;
    foreach ($result as $row) {
        $data_array[$index][0] = $row[0];
        $data_array[$index][1] = $row[1];
        $index++;
    }

    //////Get the new department's id////////////
    $query = "SELECT id FROM {$GLOBALS['CONFIG']['db_prefix']}department WHERE name = :department";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(':department' => $department));
    $result = $stmt->fetchAll();

    $num_rows = $stmt->rowCount();
    if ($num_rows != 1) {
        header('Location: error.php?ec=14&message=' . urlencode('unable to identify ' . $department));
        exit;
    }

    $newly_added_dept_id = $result[0]['id'];

    ////Set default rights into department//////
    $num_rows = sizeof($data_array);
    for ($index = 0; $index < $num_rows; $index++) {
        $query = "
          INSERT INTO {$GLOBALS['CONFIG']['db_prefix']}dept_perms
          (
            fid,
            dept_id,
            rights
          ) values(
            :index0,
            :newly_added_dept_id,
            :index1
            )";
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(
            ':index0' => $data_array[$index][0],
            ':newly_added_dept_id' => $newly_added_dept_id,
            ':index1' => $data_array[$index][1]
        ));
    }

    // Call the plugin API
    callPluginMethod('onDepartmentAddSave', $result['id']);

    header('Location: admin.php?last_message=' . urlencode($last_message));
} elseif (isset($_POST['submit']) && $_POST['submit'] == 'Show Department') {
    // query to show item
//    draw_header(msg('area_department_information'), $last_message);
    view_header(msg('area_department_information'), $last_message);

    //select department name
    $query = "SELECT name,id FROM {$GLOBALS['CONFIG']['db_prefix']}department where id = :item";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(':item' => $_POST['item']));
    $result = $stmt->fetch();
?>

        <table id="department_table" name="main">
            <thead>
                <tr><th>ID</th>
                    <th><?= msg('department') ?></th>
                </tr>
            </thead>
            <tbody>
                <tr><td><?= e::h($result['id']) ?></td>
                    <td><?= e::h($result['name']) ?></td>
                </tr>
            </tbody>
        </table>

<?php
    // Display all users assigned to this department
    $query = "
      SELECT
        dept.id,
        u.first_name,
        u.last_name
      FROM
        {$GLOBALS['CONFIG']['db_prefix']}department dept,
        {$GLOBALS['CONFIG']['db_prefix']}user u
      WHERE
        dept.id = :item
      AND
        u.department = :item
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(':item' => $_POST['item']));
    $result = $stmt->fetchAll();

    if ($stmt->rowCount() <= 0) { ?>
        <p><b><?= msg('departmentpage_no_users_in_department') ?></b></p>
<?php } else { ?>
        <p><b><?= msg('departmentpage_users_in_department') ?></b></p>
<?php } ?>
        <p>
<?php foreach ($result as $row): ?>
        <?= e::h($row['first_name']) . ' ' . e::h($row['last_name']) ?><br />
<?php endforeach; ?>
        </p>
        <form action="admin.php" method="POST" enctype="multipart/form-data">
            <div class="buttons">
                <button class="regular" type="submit" name="submit" value="Back"><?= msg('button_back') ?></button>
            </div>
        </form>

<?php
//    draw_footer();
    view_footer();
} elseif (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'showpick') {
//    draw_header(msg('area_choose_department'), $last_message);
    view_header(msg('area_choose_department'), $last_message);
// not used
    $showpick='';

    $query = "SELECT id, name FROM {$GLOBALS['CONFIG']['db_prefix']}department ORDER BY name";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll();
?>

        <form action="department.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="state" value="<?= (e::h($_GET['state']+1)); ?>" />
        <table id="department_table">
            <tbody>
                <tr>
                    <td><label for="dd"><?= msg('department')?>: </label></td>
                    <td>
                        <select id="dd" name="item">
<?php foreach ($result as $row): ?>
                            <option value="<?= e::h($row['id']) ?>"><?= e::h($row['name']) ?></option>
<?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <div class="buttons">
                            <button class="positive" type="submit" name="submit" value="Show Department"><?= msg('button_view_department') ?></button>
                            <button class="negative" type="Submit" name="submit" value="Cancel"><?= msg('button_cancel') ?></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        </form>

<?php
//    draw_footer();
    view_footer();
} elseif (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'delete') {
//    draw_header(msg('department') . ': ' . msg('label_delete'), $last_message);
    view_header(msg('department') . ': ' . msg('label_delete'), $last_message);

// not used
    $delete='';

    // Pull up a list of departments excluding the one being deleted
    $reassign_list_query = "SELECT id, name FROM {$GLOBALS['CONFIG']['db_prefix']}department WHERE id != :item ORDER BY name";
    $stmt = $pdo->prepare($reassign_list_query);
    $stmt->execute(array(':item' => $_REQUEST['item']));
    $reassign_list_query_result = $stmt->fetchAll();

    // If the above statement returns less than 1 row they will need to create another category to re-assign to so display error
    if ($stmt->rowCount() < 1) {
?>
        <p><?= msg('message_need_one_department'); ?></p>
        <form action="admin.php" method="POST">
            <div class="buttons">
                <button class="regular" type="submit" name="submit" value="Back"><?= msg('button_back') ?></button>
            </div>
        </form>
<?php
        view_footer();
        exit;
    }

    // query to show item
    $query = "SELECT id, name FROM {$GLOBALS['CONFIG']['db_prefix']}department where id = :item";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(':item' => $_REQUEST['item']));
    $dept = $stmt->fetch();
?>

        <form action="department.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= e::h((int) $_REQUEST['item']) ?>" />
        <table id="department_table">
            <tbody>
                <tr>
                    <td><label><?= msg('label_id') ?> #: </label></td>
                    <td><?= e::h($dept['id']) ?></td>
                </tr>
                <tr>
                    <td><label><?= msg('label_name') ?>: </label></td>
                    <td><?= e::h($dept['name']) ?></td>
                </tr>
                <tr>
                    <td><label for="dd"><?= msg('label_reassign_to') ?>: </label></td>
                    <td>
                        <select id="dd" name="assigned_id">
<?php foreach ($reassign_list_query_result as $row): ?>
                            <option value="<?= e::h($row['id']) ?>"><?= e::h($row['name']) ?></option>
<?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><?= msg('message_are_you_sure_remove') ?></td>
                    <td>
                        <div class="buttons">
                            <button class="positive" type="submit" name="deletedepartment" value="Yes"><?= msg('button_yes') ?></button>
                            <button class="negative" type="submit" name="submit" value="Cancel"><?= msg('button_cancel') ?></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        </form>

<?php

//    draw_footer();
    view_footer();
} elseif (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'deletepick') {
//    draw_header(msg('department') . ': ' . msg('label_delete'), $last_message);
    view_header(msg('department') . ': ' . msg('label_delete'), $last_message);

    $query = "SELECT id, name FROM {$GLOBALS['CONFIG']['db_prefix']}department ORDER BY name";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll();

// not used
    $deletepick='';
?>

        <form action="department.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="state" value="<?= (e::h($_REQUEST['state']+1)); ?>" />
        <table id="department_table">
            <tbody>
                <tr>
                    <td><label for="dd"><?= msg('department')?>: </label></td>
                    <td>
                        <select id="dd" name="item">
<?php foreach ($result as $row): ?>
                            <option value="<?= e::h($row['id']) ?>"><?= e::h($row['name']) ?></option>
<?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <div class="buttons">
                            <button class="positive" type="submit" name="submit" value="delete"><?= msg('button_delete') ?></button>
                            <button class="negative cancel" type="submit" name="submit" value="Cancel"><?= msg('button_cancel') ?></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        </form>

<?php
//    draw_footer();
    view_footer();
} elseif (isset($_REQUEST['deletedepartment'])) {
    // Make sure they are an admin
    if (!$user_obj->isAdmin()) {
        header('Location: error.php?ec=4');
        exit;
    }

    // Set all old dept_id's to the new re-assigned dept_id or remove the old dept_id

    // Update entries in data table
    $query = "UPDATE {$GLOBALS['CONFIG']['db_prefix']}data SET department = :assigned_id WHERE department = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(
        ':assigned_id' => $_REQUEST['assigned_id'],
        ':id' => $_REQUEST['id']
    ));

    // Update entries in user
    $query = "UPDATE {$GLOBALS['CONFIG']['db_prefix']}user SET department = :assigned_id WHERE department = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(
        ':assigned_id' => $_REQUEST['assigned_id'],
        ':id' => $_REQUEST['id']
    ));

    // Update entries in dept perms
    $query = "UPDATE {$GLOBALS['CONFIG']['db_prefix']}dept_perms SET dept_id = :assigned_id WHERE dept_id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(
        ':assigned_id' => $_REQUEST['assigned_id'],
        ':id' => $_REQUEST['id']
    ));

    // Update entries in dept_reviewer
    $query = "UPDATE {$GLOBALS['CONFIG']['db_prefix']}dept_reviewer SET dept_id = :assigned_id WHERE dept_id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(
        ':assigned_id' => $_REQUEST['assigned_id'],
        ':id' => $_REQUEST['id']
    ));

    // Delete from department
    $query = "DELETE FROM {$GLOBALS['CONFIG']['db_prefix']}department where id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(':id' => $_REQUEST['id']));

    // back to main page
    $last_message = msg('message_all_actions_successfull') . ' id:' . (int) $_REQUEST['id'];
    header('Location: admin.php?last_message=' . urlencode($last_message));
    exit;
} elseif (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'modify') {
    $dept_obj = new Department($_REQUEST['item'], $pdo);
//    draw_header(msg('area_update_department') .': ' . $dept_obj->getName(), $last_message);
    view_header(msg('area_update_department') .': ' . $dept_obj->getName(), $last_message);

    $query = "SELECT id, name FROM {$GLOBALS['CONFIG']['db_prefix']}department where id = :item";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(':item' => $_REQUEST['item']));
    $dept = $stmt->fetch();
?>
        <form id="modifyDeptForm" action="department.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= e::h($dept['id']) ?>" />
        <table id="department_table">
            <tbody>
                <tr>
                    <td><label for="dm"><?= msg('department') ?>: </label></td>
                    <td><input id="dm" class="required" type="text" name="name" value="<?= e::h($dept['name']) ?>" maxlength="40" /></td>
<?php
    // Call the plugin API
    callPluginMethod('onDepartmentEditForm', $dept['id']);
?>
                    <td>
                        <div class="buttons">
                            <button class="positive" type="Submit" name="submit" value="Update Department"><?= msg('button_save') ?></button>
                            <button class="negative cancel" type="Submit" name="submit" value="Cancel"><?= msg('button_cancel') ?></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        </form>

  <script>
  $(document).ready(function(){
    $('#modifyDeptForm').validate();
  });
  </script>

<?php
//    draw_footer();
    view_footer();
} elseif (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'updatepick') {
//    draw_header(msg('area_choose_department'), $last_message);
    view_header(msg('area_choose_department'), $last_message);

    // query to get a list of departments
    $query = "SELECT id, name FROM {$GLOBALS['CONFIG']['db_prefix']}department ORDER BY name";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll();
?>

        <form action="department.php" method="GET" enctype="multipart/form-data">
        <input type="hidden" name="state" value="<?= (e::h($_REQUEST['state']+1)); ?>" />
        <table id="department_table">
            <tbody>
                <tr>
                    <td><label for="dup"><?= msg('label_department_to_modify')?>: </label></td>
                    <td>
                        <select id="dup" name="item">
<?php foreach ($result as $row): ?>
                            <option value="<?= e::h($row['id']) ?>"><?= e::h($row['name']) ?></option>
<?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <div class="buttons">
                            <button class="positive" type="submit" name="submit" value="modify"><?= msg('button_modify_department') ?></button>
                            <button class="negative" type="Submit" name="submit" value="Cancel"><?= msg('button_cancel') ?></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        </form>

<?php
//    draw_footer();
    view_footer();
} elseif (isset($_POST['submit']) && $_POST['submit'] == 'Update Department') {
    // UPDATE Department
    //
    //
    // Make sure they are an admin
    if (!$user_obj->isAdmin()) {
        header('Location: error.php?ec=4');
        exit;
    }

    $name = (isset($_POST['name']) ? $_POST['name'] : '');
    if ($name == '') {
        $last_message = msg('departmentpage_department_name_required');
        header('Location: admin.php?last_message=' . urlencode($last_message));
        exit;
    }

    //Check to see if this department is already in DB
    $query = "SELECT name FROM {$GLOBALS['CONFIG']['db_prefix']}department WHERE name = :name AND id != :id ";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(
        ':id' => $_POST['id'],
        ':name' => $_POST['name']
    ));
    $result = $stmt->fetchAll();

    if ($stmt->rowCount() != 0) {
        header('Location: error.php?ec=3&last_message=' . urlencode($_POST['name'] . ' already exist in the database'));
        exit;
    }

    $query = "UPDATE {$GLOBALS['CONFIG']['db_prefix']}department SET name = :name WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(
        ':id' => $_POST['id'],
        ':name' => $_POST['name']
    ));

    // Call the plugin API
    callPluginMethod('onDepartmentModifySave', $_REQUEST);

    // back to main page
    $last_message = msg('message_department_successfully_updated') . ' - ' . htmlentities($name) . '- id=' . (int) $_POST['id'];
    header('Location: admin.php?last_message=' . urlencode($last_message));
    exit;
} elseif (isset($_REQUEST['submit']) and $_REQUEST['submit'] == 'Cancel') {
    header('Location: admin.php?last_message=' . urlencode(msg('message_action_cancelled')));
    exit;
} else {
    header('Location: admin.php?last_message="' . urlencode(msg('message_nothing_to_do')));
    exit;
}
