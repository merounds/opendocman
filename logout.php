<?php
/*
logout.php - provides logout functionality
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

include('odm-load.php');

session_start();
// Unset all of the session variables.
$_SESSION = array();
// Finally, destroy the session.
session_destroy();

// If kerbauth, then display warning about shutting down browser
if ($GLOBALS["CONFIG"]["authen"] =='kerbauth') {
?>
<html>
    <body background="images/background_blue.gif">
        <div style="width:620px; margin-left:20px;">
            <div style="width:100%; background-color:#31639C;">
                <img src="images/blue_left.gif" width="5" height="16" align="top" />
                <font face="Arial" size="-1" color="#ffffff"><b>&nbsp;Thank you for using OpenDocMan</b></font>
            </div>
            <div style="height: 74px; background-color: #ffce31;">
                <img src="images/logout_logo.gif" width="79" height="74" align="left" /><h2 style="margin-top: 0; border-top: 4px solid #31639c;">Logging off...</h2>
            </div>
            <img src="/images/white_dot.gif" height="8" />
            <p>OpenDocMan, and other campus web systems, use a cookie to store your credentials for access.  This cookie is kept only in your computers memory and not saved to disk for security purposes.  In order to remove this cookie from memory you must completely exit your browser.  The LOGOUT button below will close the current browser window, but this may not exit your browser software completely.</p>
            <p><b>Macintosh Users:</b> Choose 'Quit' from the 'File' menu to be sure the browser is completely exited.</p>
            <p><b>PC/Windows Users:</b> Close off all browser windows by clicking the 'X' icon in the upper right of the window.  Be sure all browser windows are closed.</p>
            <form name="CM">
                <p><font face="Arial" color="#000000" size="-2">&nbsp;<input type="button" value="LOGOUT" onclick="top.close();"></font></p>
            </form>
        </div>
<?php
//draw_footer();
view_footer();
} else {
    // mysql auth, so just kill session and show login prompt
    unset($_SESSION['uid']);

    // Call the plugin API
    callPluginMethod('onAfterLogout');

    header('Location:index.php');
}
