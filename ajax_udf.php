<?php
use Aura\Html\Escaper as e;
/*
ajax_udf.php - update UDF sub-select field options
             - output of this is inserted as innerHTML
               of #txtHint in udf_edit.php template
Copyright (C) 2014 Stephen Lawrence Jr.

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

include('odm-load.php');

$pdo = $GLOBALS['pdo'];

if (isset($_GET['q'])) {
    $q = $_GET['q'];
}

if (isset($_GET['add_value'])) {
    //$add_value = preg_replace('/ /', '', $_GET['add_value']);
    $add_value = $_GET['add_value'];
}

if (isset($_GET['table'])) {
    $table_name = $_GET['table'];
}

echo '<!-- start ajax -->';

// Find out if the passed argument matches an actual tablename
$udf_table_names = "SELECT table_name FROM {$GLOBALS['CONFIG']['db_prefix']}udf";
$stmt = $pdo->prepare($udf_table_names);
$stmt->execute();
$udf_tables_names_result = $stmt->fetchAll();

// called from showdivs()
if ($q != "" && $add_value != "add" && $add_value != "edit") {

    $explode_add_value = explode('_', $add_value);
    if (isset($explode_add_value[2])) {
        $field_name = $explode_add_value[2];
    } else {
        $field_name = '';
    }
    if ($add_value != '' && $field_name != '') {
        $white_listed = false;
        foreach ($udf_tables_names_result as $white_list) {
            if ($add_value == $white_list['table_name']) {
                $white_listed = true;
            }
        }
        reset($udf_tables_names_result);

        if ($white_listed) {
            $stmt = $pdo->prepare("SELECT * FROM $add_value");
            $stmt->execute();
            $result = $stmt->fetchAll();

            if ($result && $q != 'primary') {
                // show primary select options
?>
                <tr>
                    <td><label for="udfpri"><?= msg('label_primary_type') ?>: </label></td>
                    <td>
                        <select id="udfpri" class="required" name="primary_type" onchange="showdivs(this.value,'<?= e::h($add_value) ?>')">
                            <option value="0"><?= msg('label_select_one') ?></option>
<?php foreach ($result as $row): ?>
                            <option value="<?= e::h($row[0]) ?>" <?= ($row[0] == $q ? "selected" : "") ?>><?= e::h($row[1]) ?></option>
<?php endforeach; ?>
                        </select>
                    </td>
                </tr>
<?php
            }

            if ($q == 'secondary') {
                $table_name = '_secondary';
            } elseif ($q == 'primary') {
                $table_name = '_primary';
            } else {
                $table_name = '_secondary WHERE pr_id = "' . e::h($q) . '"';
            }
?>
                <tr><td colspan="2"><hr /></td></tr>
                <tr class="header">
                    <th><?= e::h(msg('label_delete')) ?>? </th>
                    <th><?= e::h(msg('value')) ?></th>
                </tr>
<?php
            if ((((int) $q == $q && (int) $q > 0) || $q == 'primary')) {
                // Find out if the passed argument matches an actual tablename

                $full_table_name = $GLOBALS['CONFIG']['db_prefix'] . 'udftbl_' . $field_name . $table_name;
                $white_listed = false;
                foreach ($udf_tables_names_result as $white_list) {
                    if ($full_table_name == $white_list['table_name']) {
                        $white_listed = true;
                    }
                }
                // should this be $white_listed? Because $white_list will evaluate to true after looping
                if ($white_list) {
                    $stmt = $pdo->prepare("SELECT * FROM $full_table_name");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                    foreach ($result as $row) {
                        // show existing options
?>
                <tr class="show">
                    <td><input id="c<?= e::h($row[0]) ?>" type="checkbox" name="x<?= e::h($row[0]) ?>" /></td>
                    <td><label for="c<?= e::h($row[0]) ?>"><?= e::h($row[1]) ?></label></td>
                </tr>
<?php
                    }
                }
            }
        }
    }
}

// called from showdropdowns()
if ($add_value == "add") {
    $add_table_name = $GLOBALS['CONFIG']['db_prefix'] . 'udftbl_' . $table_name . '_secondary';

    $white_listed = false;
    foreach ($udf_tables_names_result as $white_list) {
        if ($add_table_name == $white_list['table_name']) {
            $white_listed = true;
        }
    }
    // should this be $white_listed? Because $white_list will evaluate to true after looping
    if ($white_list) {
        if ($q > 0) {
            $stmt = $pdo->prepare("SELECT * FROM $add_table_name WHERE pr_id = :q");
            $stmt->execute(array(':q' => $q));
            $result = $stmt->fetchAll();

            echo "<!-- showdropdowns(add) q = $q -->";
            echo '<select id="' . e::h($add_table_name) . '" name="' . e::h($add_table_name) . '">';
            foreach ($result as $subrow) {
                echo '<option value="' . e::h($subrow[0]) . '">' . e::h($subrow[1]) . '</option>';
            }
            echo '</select>';
        } else {
            echo e::h(msg('label_secondary_items_here'));
        }
    }
}

// identical functionallity to $add_value == "add" above, just uses different variable names.
// called from showdropdowns()
if ($add_value == "edit") {
    $edit_tablename = $GLOBALS['CONFIG']['db_prefix'] . 'udftbl_' . $table_name . '_secondary';

    $white_listed = false;
    foreach ($udf_tables_names_result as $white_list) {
        if ($edit_tablename == $white_list['table_name']) {
            $white_listed = true;
        }
    }
    // should this be $white_listed? Because $white_list will evaluate to true after looping
    if ($white_list) {
        if ($q > 0) {
            $stmt = $pdo->prepare("Select * FROM $edit_tablename WHERE pr_id = :q");
            $stmt->execute(array(':q' => $q));
            $result = $stmt->fetchAll();

            echo "<!-- showdropdowns(edit) q = $q -->";
            echo '<select id="' . e::h($edit_tablename) . '" name="' . e::h($edit_tablename) . '">';
            foreach ($result as $subrow) {
                echo '<option value="' . e::h($subrow[0]) . '">' . e::h($subrow[1]) . '</option>';
            }
            echo '</select>';
        } else {
            echo e::h(msg('label_secondary_items_here'));
        }
    }
}
?>
<!-- end ajax -->
