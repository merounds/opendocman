<?php
use Aura\Html\Escaper as e;

/*
rejects.php - Show rejected files
Copyright (C) 2002, 2003, 2004 Stephen Lawrence Jr., Khoa Nguyen
Copyright (C) 2005-2011 Stephen Lawrence Jr.

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

//print_r($_REQUEST);
session_start();

include('./odm-load.php');
$view_registry->prependPath(
    __DIR__ . '/templates/' . $GLOBALS['CONFIG']['theme']
);

if (!isset($_SESSION['uid'])) {
    redirect_visitor();
}

// includes
$with_caption = false;

$last_message = (isset($_REQUEST['last_message']) ? $_REQUEST['last_message'] : '');

if (!isset($_POST['submit'])) {
    //draw_header(msg('message_documents_rejected'), $last_message);
    $head = header_init(msg('message_documents_rejected'), $last_message);
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

    $user_obj = new User($_SESSION['uid'], $pdo);
    $user_perms_obj = new UserPermission($_SESSION['uid'], $pdo);
    if ($user_obj->isAdmin() && @$_REQUEST['mode'] == 'root') {
        $fileid_array = $user_obj->getAllRejectedFileIds();
    } else {
        $fileid_array = $user_obj->getRejectedFileIds();
    }

    $list_status = list_files($fileid_array, $user_perms_obj, $GLOBALS['CONFIG']['dataDir'], true, true);

    if ($list_status != -1) {
        // Call the plugin API
        callPluginMethod('onBeforeListFiles', $list_status['file_list_arr']);

        // The out template already has an open form tag - this creates nested forms which is invalid html.
        if (@$_REQUEST['mode']=='root') {
            echo '<form name="author_note_form" action="rejects.php?mode=root" method="post">';
        } else {
            echo '<form name="author_note_form" action="rejects.php" method="post">';
        }

        $view->setData([
            'showCheckBox'  => $list_status['showCheckBox'],
            'limit_reached' => $list_status['limit_reached'],
            'file_list_arr' => $list_status['file_list_arr']
        ]);
        $view->setView('out');
        echo $view->__invoke();
?>
        <div class="buttons">
            <button class="positive" type="submit" name="submit" value="resubmit"><?= e::h(msg('button_resubmit_for_review')); ?></button>
            <button class="negative" type="submit" name="submit" value="delete"><?= e::h(msg('button_delete')); ?></button>
        </div>
    </form>
<?php
        callPluginMethod('onAfterListFiles');
    }

    //draw_footer();
    $view->setView('footer');
    echo $view->__invoke();

} elseif (isset($_POST['submit']) && $_POST['submit'] == 'resubmit') {
    if (!isset($_REQUEST['checkbox'])) {
        header('Location:rejects.php?last_message=' . urlencode(msg('message_you_did_not_enter_value')));
        exit;
    }

    if (isset($_POST["checkbox"])) {
        foreach ($_POST['checkbox'] as $cbox) {
            $fileid = $cbox;
            $file_obj = new FileData($fileid, $pdo);
            $file_obj->Publishable(0);
        }
    }
    header('Location:rejects.php?mode=' . urlencode(@$_REQUEST['mode']) . '&last_message='. urlencode(msg('message_file_authorized')));

} elseif ($_POST['submit'] == 'delete') {
    if (!isset($_REQUEST['checkbox'])) {
        header('Location: rejects.php?last_message=' . urlencode(msg('message_you_did_not_enter_value')));
        exit;
    }

    $url = 'delete.php?mode=tmpdel&';
    $id = 0;
    if (isset($_POST["checkbox"])) {
        $loop = 0;
        foreach ($_POST['checkbox'] as $num=>$cbox) {
            $fileid = $cbox;
            $url .= 'id'.  $num . '='.$fileid.'&';
            $id ++;
            $loop++;
        }
        $url = substr($url, 0, strlen($url)-1);
    }
    header('Location:'. urlencode($url) .'&num_checkboxes=' . urlencode($loop));
}

?>
<script type="text/javascript">
  function closeWindow(close_window_in_ms)
  {
    setTimeout(window.close, close_window_in_ms);
  }

  function checkedBoxesNumber()
  {
    counter=0;
    record="";
    for(j=0; j<document.forms[0].elements.length; j++)
    {
      if(document.forms[0].elements[j].type == "checkbox")
      {
        counter++;
      }
    }
    for(i=1; i<counter; i++)
    {
      if(eval('document.forms[0].checkbox' + i + '.checked') == true)
      {
        id=(eval('document.forms[0].checkbox' + i + '.value'));
        document.table.fileid.value +="" + id +" ";
        record +="" + i +" ";
      }
    }

    document.table.checkedboxes.value = record;
    document.table.checkednumber.value = counter;
    alert("boxes " + document.table.checkedboxes.value  + " are selected");
  }
</script>
