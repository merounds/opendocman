<?php
use Aura\Html\Escaper as e;

/*
search.php - main search logic
Copyright (C) 2002-2007 Stephen Lawrence Jr., Khoa Nguyen, Jon Miner
Copyright (C) 2008-2013 Stephen Lawrence Jr.

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

include('udf_functions.php');

$last_message = (isset($_REQUEST['last_message']) ? $_REQUEST['last_message'] : '');

/*$_GET['where']='department';
  $_GET['keyword']='Information Systems';
  $_SESSION['uid']=102;
  $_GET['submit']='submit';
  $_GET['exact_phrase']='on';
  $_GET['case_sensitivity']='';
*/
/// includes
$start_time = time();
//draw_header(msg('search'), $last_message);
view_header(msg('search'), $last_message);

if (!isset($_GET['submit'])) {
?>
        <form action="search.php" method="get">
        <table id="searchtable" border="0" cellspacing="5" cellpadding="5">
            <tbody>
                <tr>
                    <td style="vertical-align: top;"><label for="term"><b><?= e::h(msg('label_search_term')); ?></b></label></td>
                    <td><input id="term" type="text" name="keyword" size="50"></td>
                </tr>
                <tr>
                    <td style="vertical-align: top;"><label for="where"><b><?= e::h(msg('search')); ?></b></label></td>
                    <td><select id="where" name="where">
                            <option value="author"><?= e::h(msg('author'))."(".e::h(msg('label_last_name')).", ".e::h(msg('label_first_name')).")"; ?></option>
                            <option value="department"><?= e::h(msg('department')); ?></option>
                            <option value="category"><?= e::h(msg('category')); ?></option>
                            <option value="descriptions"><?= e::h(msg('label_description')); ?></option>
                            <option value="filenames"><?= e::h(msg('label_filename')); ?></option>
                            <option value="comments"><?= e::h(msg('label_comment')); ?></option>
                            <option value="file_id"><?= e::h(msg('file')); ?> #</option>
                            <?php udf_functions_search_options(); ?>
                            <option value="all" selected><?= e::h(msg('searchpage_all_meta')); ?></option>
                        </select></td>
                </tr>
                <tr>
                    <td><label for="phrase"><?= e::h(msg('label_exact_phrase')); ?>: </label>
                        <input id="phrase" type="checkbox" name="exact_phrase"></td>
                    <td><label for="case"><?= e::h(msg('label_case_sensitive')); ?>: </label>
                        <input id="case" type="checkbox" name="case_sensitivity"></td>
                </tr>
                <tr>
                    <td>
                        <div class="buttons">
                            <button class="positive" type="Submit" name="submit" value="Search"><?= e::h(msg('search')); ?></button>
                        </div>
                    </td>
                </tr>
            <tbody>
        </table>
        </form>

    <?php
    //echo '<br><b>Load Time: ' . time() - $start_time;

    //draw_footer();

} else {
    function search($where, $keyword, $exact_phrase, $case_sensitivity, $search_array)
    {
        global $pdo;

        $remain ='';
        if ($exact_phrase != 'on') {
            $keyword = '%' . $keyword . '%';
        }
        if ($case_sensitivity != 'on') {
            $equate = ' LIKE ';
        } else {
            $equate = ' LIKE BINARY ';
        }

        $query_pre = "
          SELECT
            d.id
          FROM
            {$GLOBALS['CONFIG']['db_prefix']}data as d,
            {$GLOBALS['CONFIG']['db_prefix']}user as u,
            {$GLOBALS['CONFIG']['db_prefix']}department dept,
            {$GLOBALS['CONFIG']['db_prefix']}category as c ";

        $query = "
            WHERE
                d.owner = u.id
            AND
                d.department = dept.id
            AND
                d.category = c.id AND (
        ";

        $author_first_name = '';
        $author_last_name = '';
        $use_uid = false;
        switch ($where) {
            // Put all the category for each of the OBJ in the OBJ array into an array
            // Notice, the index of the OBJ_array and the category array are synchronized.
            case 'author_locked_files':
                $use_uid = true;
                $query .= "d.status $equate :keyword AND d.owner = :uid ";
                break;

            // Put all the category for each of the OBJ in the OBJ array into an array
            // Notice, the index of the OBJ_array and the category array are synchronized.
            case 'category':
                $query .= "c.name $equate :keyword ";
                break;
            // Put all the author name for each of the OBJ in the OBJ array into an array
            // Notice, the index of the OBJ_array and the author name array are synchronized.
            case 'author':
                if ($exact_phrase=='on') {
                    $author_first_name = substr($keyword, strpos($keyword, ' ') +1);
                    $author_last_name = substr($keyword, 0, strpos($keyword, ' '));
                    $query .= " u.first_name $equate :author_first_name AND u.last_name  $equate :author_last_name ";
                } else {
                    $query .= " u.first_name $equate  :keyword OR u.last_name $equate :keyword ";
                }
                break;
            // Put all the department name for each of the OBJ in the OBJ array into an array
            // Notice, the index of the OBJ_array and the department name array are synchronized.case 'department':
            case 'department':
                $query .= "dept.name $equate  :keyword ";
                break;
            // Put all the description for each of the OBJ in the OBJ array into an array
            // Notice, the index of the OBJ_array and the description array are synchronized.
            case 'descriptions':
                $query .= "d.description $equate :keyword ";
                break;
            // Put all the file name for each of the OBJ in the OBJ array into an array
            // Notice, the index of the OBJ_array and the file name array are synchronized.
            case 'filenames':
                $query .= "d.realname $equate :keyword ";
                break;
            // Put all the comments for each of the OBJ in the OBJ array into an array
            // Notice, the index of the OBJ_array and the comments array are synchronized.
            case 'comments':
                $query .= "d.comment $equate :keyword ";
                break;
            case 'file_id':
                $query .= "d.id $equate :keyword ";
                break;
            case 'all':
                $query .= "c.name $equate  :keyword OR " .
                        "u.first_name $equate :keyword OR u.last_name $equate :keyword OR " .
                        "dept.name $equate :keyword OR " .
                        "d.description $equate :keyword OR " .
                        "d.realname $equate :keyword OR " .
                        "d.comment $equate :keyword ";
                break;

            default :
                list($query_pre, $query) = udf_functions_search($where, $query_pre, $query, $equate, $keyword);
                break;

        }

        $query .= ") ORDER BY d.id ASC";

        $final_query = $query_pre . $query;

        $stmt = $pdo->prepare($final_query);

        if (!empty($use_uid)) {
            $stmt->bindParam(':uid', $_SESSION['uid']);
            $stmt->bindParam(':keyword', $keyword);
        } elseif (!empty($author_last_name) && $exact_phrase == 'on') {
            $stmt->bindParam(':author_first_name', $author_first_name);
            $stmt->bindParam(':author_last_name', $author_last_name);
        } else {
            $stmt->bindParam(':keyword', $keyword);
        }

        $stmt->execute();
        $result = $stmt->fetchAll();

        $index = 0;
        $id_array = array();

        foreach ($result as $row) {
            $id_array[$index++] = $row['id'];
            $index++;
        }
        if (@$remain != '' && $exact_phrase != "on") {
            return array_values(array_unique(array_merge($id_array, search($where, substr($remain, 1), $exact_phrase, $case_sensitivity, $search_array))));
        }
        return array_values(array_intersect($id_array, $search_array));
    } // close function search()

    $current_user = new User($_SESSION['uid'], $pdo);
    $user_perms = new User_Perms($_SESSION['uid'], $pdo);
    $current_user_permission = new UserPermission($_SESSION['uid'], $pdo);
    //$s_getFTime = getmicrotime();
    if ($_GET['where'] == 'author_locked_files') {
        $view_able_files_id = $current_user->getExpiredFileIds();
    } else {
        $view_able_files_id = $current_user_permission->getViewableFileIds(false);
    }
    //$e_getFTime = getmicrotime();
    $id_array_len = sizeof($view_able_files_id);
    $query_array = array();
    $search_result = search(@$_GET['where'], @$_GET['keyword'], @$_GET['exact_phrase'], @$_GET['case_sensitivity'], $view_able_files_id);

    // Call the plugin API
    callPluginMethod('onSearch');

    $files = list_files($search_result, $current_user_permission, $GLOBALS['CONFIG']['dataDir'], false, false);

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
    echo '<br />';

    //draw_footer();

    //echo '<br> <b> Load Page Time: ' . (getmicrotime() - $start_time) . ' </b>';
    //echo '<br> <b> Load Permission Time: ' . ($e_getFTime - $s_getFTime) . ' </b>';
}

view_footer();
