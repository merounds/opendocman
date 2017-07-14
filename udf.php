<?php
/*
udf.php - Administer User Defined Fields
Copyright (C) 2007 Stephen Lawrence Jr., Jonathan Miner
Copyright (C) 2008-2015 Stephen Lawrence Jr.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 3
of the License, or any later version.

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

include('odm-load.php');
$view_registry->prependPath(
    __DIR__ . '/templates/' . $GLOBALS['CONFIG']['theme']
);

if (!isset($_SESSION['uid'])) {
    redirect_visitor();
}

$last_message = (isset($_REQUEST['last_message']) ? htmlspecialchars($_REQUEST['last_message']) : '');

$user_obj = new User($_SESSION['uid'], $pdo);
if (!$user_obj->isAdmin()) {
    header('Location: error.php?ec=4');
    exit;
}

// never happens...
if (isset($_REQUEST['cancel']) and $_REQUEST['cancel'] != 'Cancel') {
    draw_menu($_SESSION['uid']);
}

if (isset($_GET['submit']) && $_GET['submit'] == 'add') {
    // show add udf form
    //draw_header(msg('area_add_new_udf'), $last_message);
    view_header(msg('area_add_new_udf'), $last_message);

    //$GLOBALS['smarty']->assign('last_message', $last_message);
    //display_smarty_template('udf/add.tpl');
    $view->setData([
        'last_message' => $last_message
    ]);
    $view->setView('udf_add');
    echo $view->__invoke();

    //draw_footer();
    view_footer();
} elseif (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'Add User Defined Field') {
    // add new user defined field
    udf_functions_add_udf();

    $last_message = msg('message_udf_successfully_added') . ': ' . $_REQUEST['display_name'];
    header('Location: admin.php?last_message=' . urlencode($last_message));
    exit;
} elseif (isset($_REQUEST['submit']) && ($_REQUEST['submit'] == 'delete') && (isset($_REQUEST['item']))) {
    // show are you sure confirmation
    //draw_header(msg('label_delete') . ' ' . msg('label_user_defined_fields'), $last_message);
    view_header(msg('label_delete') . ' ' . msg('label_user_defined_fields'), $last_message);

    $query = "
      SELECT
        id,
        table_name,
        display_name,
        field_type
      FROM
        {$GLOBALS['CONFIG']['db_prefix']}udf
      WHERE
        table_name = :item
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(':item' => $_REQUEST['item']));
    $udf = $stmt->fetch();

    //$GLOBALS['smarty']->assign('udf', $udf);
    //display_smarty_template('udf/delete_form.tpl');
    $view->setData([
        'udf' => $udf
    ]);
    $view->setView('udf_delete');
    echo $view->__invoke();

    //draw_footer();
    view_footer();
} elseif (isset($_REQUEST['deleteudf'])) {
    // do the deletion
    // Make sure they are an admin
    if (!$user_obj->isAdmin()) {
        header('Location: error.php?ec=4');
        exit;
    }
    udf_functions_delete_udf();

    // back to main page
    $last_message = msg('message_udf_successfully_deleted') . ': id=' . $_REQUEST['id'];
    header('Location: admin.php?last_message=' . urlencode($last_message));
    exit;
} elseif (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'deletepick') {
    // show form to select which udf to delete
    //draw_header(msg('select') . ' ' . msg('label_user_defined_fields'), $last_message);
    view_header(msg('select') . ' ' . msg('label_user_defined_fields'), $last_message);

    $query = "
      SELECT
        table_name,
        display_name
      FROM
        {$GLOBALS['CONFIG']['db_prefix']}udf
      ORDER BY
        id
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array());
    $result = $stmt->fetchAll();

    if ($stmt->rowCount() > 0) {
        //$GLOBALS['smarty']->assign('state', $_REQUEST['state'] + 1);
        //$GLOBALS['smarty']->assign('udfs', $result);
        //display_smarty_template('udf/delete_pick.tpl');
        $view->setData([
            'state' => $_REQUEST['state'] + 1,
            'udfs'  => $result
        ]);
        $view->setView('udf_delete_pick');
        echo $view->__invoke();
    } else {
        echo "<p>" . msg('message_udf_none_to_delete') . "</p>";
    }

    //draw_footer();
    view_footer();
} elseif (isset($_REQUEST['cancel']) && $_REQUEST['cancel'] == 'Cancel') {
    // clicked cancel button
    $last_message = msg('message_action_cancelled');
    header('Location: admin.php?last_message=' . urlencode($last_message));
    exit;
} elseif (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'edit') {
    // show form to edit udf
    //draw_header(msg('edit') . ' ' . msg('label_user_defined_field'), $last_message);
    view_header(msg('edit') . ' ' . msg('label_user_defined_field'), $last_message);

    if (!empty($_REQUEST['udf']) && !preg_match('/^\w+$/', $_REQUEST['udf'])) {
        header('Location: admin.php?last_message=Error+:+Invalid+Name+(A-Z 0-9 Only)');
        exit;
    }

    $query = "
      SELECT
        table_name,
        field_type,
        display_name
      FROM
        {$GLOBALS['CONFIG']['db_prefix']}udf
      WHERE
        table_name = :udf
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(':udf' => $_REQUEST['udf']));
    $result = $stmt->fetch();

    $display_name = $result[2];
    $field_type = $result[1];

    if ($field_type == 1 || $field_type == 2) {
        // select list or radio button
        // Do Updates
        if (!empty($_REQUEST['display_name'])) {
            $query = "
              UPDATE
                {$GLOBALS['CONFIG']['db_prefix']}udf
              SET
                display_name = :display_name
              WHERE
                table_name = :udf
            ";
            $stmt = $pdo->prepare($query);
            $stmt->execute(array(
                ':display_name' => $_REQUEST['display_name'],
                ':udf' => $_REQUEST['udf']
            ));
            $display_name = $_REQUEST['display_name'];
        }

        // Do Inserts
        if (!empty($_REQUEST['newvalue'])) {
            $query = "
              INSERT INTO {$_REQUEST['udf']}
                (value)
              VALUES
                (:newvalue)
            ";
            $stmt = $pdo->prepare($query);
            $stmt->execute(array(':newvalue' => $_REQUEST['newvalue']));
        }

        // Do Deletes
        $query = "
          SELECT
            max(id)
          FROM
            {$_REQUEST['udf']}
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchColumn();

        $max = $result;

        while ($max > 0) {
            if (isset($_REQUEST['x' . $max]) && $_REQUEST['x' . $max] == "on") {
                $query = "
                  DELETE FROM
                    {$_REQUEST['udf']}
                  WHERE
                    id = $max
                ";
                $stmt = $pdo->prepare($query);
                $stmt->execute();
            }
            $max--;
        }

        // Fill Form
        $query = "
          SELECT
            id,
            value
          FROM
            {$_REQUEST['udf']}
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute(array());
        $rows1 = $stmt->fetchAll();

        //$GLOBALS['smarty']->assign('udf', $_REQUEST['udf']);
        //$GLOBALS['smarty']->assign('display_name', $display_name);
        //$GLOBALS['smarty']->assign('rows', $rows1);
        //display_smarty_template('udf/edit_types_1_and_2.tpl');
        $view->setData([
            'udf'          => $_REQUEST['udf'],
            'display_name' => $display_name,
            'form'         => 'type1and2',
            'rows'         => $rows1
        ]);
        $view->setView('udf_edit');
        echo $view->__invoke();
    }

    if ($field_type == 3) {
        // text
        echo '<p>' . msg('message_udf_no_edit_options') . '</p>';
        echo '<div class="buttons" onclick="location.href=\'admin.php\'"><button>' . msg('button_back') . '</button></div>';
        echo '<p>&nbsp;</p><br class="clear: both;">';
    }

    if ($field_type == 4) {
        // sub select list
        $type_pr_sec = isset($_REQUEST['type_pr_sec']) ? $_REQUEST['type_pr_sec'] : '';

        // Do Updates
        if (isset($_REQUEST['display_name']) && $_REQUEST['display_name'] != "") {
            $query = "
              UPDATE
                {$GLOBALS['CONFIG']['db_prefix']}udf
              SET
                display_name = :display_name
              WHERE
                table_name = :udf
            ";
            $stmt = $pdo->prepare($query);
            $stmt->execute(array(
                ':display_name' => $_REQUEST['display_name'],
                ':udf' => $_REQUEST['udf']
            ));
            $display_name = $_REQUEST['display_name'];
        }

        $explode_udf = explode('_', $_REQUEST['udf']);
        $field_name = $explode_udf[2];

        // Do Inserts
        if ($type_pr_sec == 'primary') {
            $tablename = '_primary';
        } else {
            $tablename = '_secondary';
            $sec_values = 'pr_id';
        }
        $udf_table_name = $GLOBALS['CONFIG']['db_prefix'] . 'udftbl_' . $field_name . $tablename;

        if (isset($_REQUEST['newvalue']) && $_REQUEST['newvalue'] != "") {
            if ($type_pr_sec == 'primary') {
                $query = "
                  INSERT INTO $udf_table_name
                    (value)
                  VALUES
                    (:newvalue)
                ";
                $stmt = $pdo->prepare($query);
                $stmt->execute(array(':newvalue' => $_REQUEST['newvalue']));
            } else {
                $query = "
                  INSERT INTO $udf_table_name
                  (
                    value,
                    pr_id
                  ) VALUES (
                    :newvalue,
                    :primary_type
                  )
                ";
                $stmt = $pdo->prepare($query);
                $stmt->execute(array(
                    ':newvalue' => $_REQUEST['newvalue'],
                    ':primary_type' => $_REQUEST['primary_type']
                ));
            }
        }

        // Do Deletes
        $query = "
          SELECT
            max(id)
          FROM
            $udf_table_name
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchColumn();

        $max = $result;

        while ($max > 0) {
            if (isset($_REQUEST['x' . $max]) && $_REQUEST['x' . $max] == "on") {
                $query = "
                  DELETE FROM
                    {$GLOBALS['CONFIG']['db_prefix']}udftbl_{$field_name}{$tablename}
                  WHERE
                    id = $max
                ";
                $stmt = $pdo->prepare($query);
                $stmt->execute();
            }
            $max--;
        }

        // Fill Form
        $query = "
          SELECT
            *
          FROM
            {$_REQUEST['udf']}
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $rows2 = $stmt->fetchAll();

        //$GLOBALS['smarty']->assign('udf', $_REQUEST['udf']);
        //$GLOBALS['smarty']->assign('display_name', $display_name);
        //$GLOBALS['smarty']->assign('rows', $rows2);
        //display_smarty_template('udf/edit_type_4.tpl');
        $view->setData([
            'udf'          => $_REQUEST['udf'],
            'display_name' => $display_name,
            'form'         => 'type4',
            'rows'         => $rows2
        ]);
        $view->setView('udf_edit');
        echo $view->__invoke();

    }

    //draw_footer();
    view_footer();
} else {
    //draw_header(msg('label_user_defined_field'), $last_message);
    view_header(msg('label_user_defined_field'), $last_message);

    echo '<p>' . msg('message_nothing_to_do') . '</p>';
    echo '<div class="buttons" onclick="location.href=\'admin.php\'"><button>' . msg('button_back') . '</button></div>';
    echo '<p>&nbsp;</p><br class="clear: both;">';

    //draw_footer();
    view_footer();
}
