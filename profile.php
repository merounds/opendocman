<?php
/*
profile.php - page for changing personal info
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

session_start();

include('odm-load.php');
$view_registry->prependPath(
    __DIR__ . '/templates/' . $GLOBALS['CONFIG']['theme']
);

if (!isset($_SESSION['uid'])) {
    redirect_visitor();
}

$last_message = (isset($_REQUEST['last_message']) ? $_REQUEST['last_message'] : '');

//draw_header(msg('area_personal_profile'), $last_message);
$head = header_init(msg('area_personal_profile'), $last_message);
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
?>

<!-- <html>
    <br><br>
    <INPUT type="hidden" name="callee" value="profile.php">
    <table name="list" align="center" border="0">
           <tr><td><a href="user.php?submit=Modify+User&item=<?php echo $_SESSION['uid']; ?>"><?php echo msg('profilepage_update_profile')?></a></td></tr>
                        </table> -->

    <p style="text-align: center;">
        <a href="user.php?submit=Modify+User&item=<?php echo $_SESSION['uid']; ?>"><?php echo msg('profilepage_update_profile')?></a>
    </p>
<?php
//draw_footer();
$view->setView('footer');
echo $view->__invoke();
