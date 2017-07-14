<?php
use Aura\Html\Escaper as e;

/*
   forgot_password.php - utility to reset a user password
   Copyright (C) 2005-2006 Glowball Solutions
   Copyright (C) 2005-2012 Stephen Lawrence Jr.
   This page was added to the core files for this utility.

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

include_once('odm-load.php');
$view_registry->prependPath(
    __DIR__ . '/templates/' . $GLOBALS['CONFIG']['theme']
);

if (isset($GLOBALS['CONFIG']['allow_password_reset']) && $GLOBALS['CONFIG']['allow_password_reset'] != 'True') {
    echo msg('message_sorry_not_allowed');
    exit;
}

$last_message = (isset($_REQUEST['last_message']) ? $_REQUEST['last_message'] : '');

view_header(msg('forgotpassword'), $last_message);

if (
    isset($_POST['password'])
    && strlen($_POST['password'])
    && isset($_POST['username'])
    && strlen($_POST['username'])
    && isset($_POST['code'])
    && (strlen($_POST['code']) == 32)
    && isset($_POST['user_id'])
    && $_POST['user_id'] + 0 > 0
) {
    // reset their password and code
    $newPass = trim($_POST['password']);
    $oldCode = str_replace(' ', '', $_POST['code']);
    $username = str_replace(' ', '', $_POST['username']);
    $user_id = (int) $_POST['user_id']+0;

    // reset the password
    $query = "
      UPDATE
        {$GLOBALS['CONFIG']['db_prefix']}user
      SET
        password = md5(:new_pass),
        pw_reset_code = NULL
      WHERE
        id = :user_id
      AND
        username = :username
      AND
        pw_reset_code = :old_code
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(
        ':new_pass' => $newPass,
        ':user_id' => $user_id,
        ':username' => $username,
        ':old_code' => $oldCode
    ));

    $redirect = 'index.php?last_message=' . urlencode(msg('message_your_password_has_been_changed'));
    header("Location: $redirect");
    exit;
} elseif (
    isset($_GET['username'])
    && strlen($_GET['username'])
    && isset($_GET['code'])
    && (strlen($_GET['code']) == 32)
) {
    // they have clicked on the link we sent them
    $username = trim($_GET['username']);
    $code = trim($_GET['code']);

    // make sure we have a match
    $query = "
      SELECT
        id
      FROM
        {$GLOBALS['CONFIG']['db_prefix']}user
      WHERE
        username = :username
      AND
        pw_reset_code = :code
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(
        ':username' => $username,
        ':code' => $code
    ));

    if ($stmt->rowCount() < 1) {
        $redirect = 'forgot_password.php?last_message=' . urlencode(msg('message_the_code_you_are_using'));
        header("Location: $redirect");
        exit;
    } else {
        $userInfo = $stmt->fetch();
        $user_id = $userInfo['id'];

        if (strlen($last_message)) {
            draw_error($last_message);
        }
?>

        <p><?= e::h(msg('message_set_your_new_password')) ?></p>
        <form action="forgot_password.php" method="post">
        <input type="hidden" name="action" value="forgot">
        <input type="hidden" name="user_id" value="<?= e::h($user_id); ?>">
        <input type="hidden" name="username" value="<?= e::h($username); ?>">
        <input type="hidden" name="code" value="<?= e::h($code); ?>">
        <table border="0">
            <tbody>
                <tr>
                    <td><label for="uu"><?= e::h(msg('label_new_password')) ?>: </label></td>
                    <td><input id="uu" class="required" type="password" name="password" maxlength="32" /></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td><!-- buttons --></td>
                    <td>
                        <div class="buttons">
                            <button class="positive" type="Submit" name="adduser" value="Reset Password"><?= e::h(msg('area_reset_password')) ?></button>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
        </form>

<?php
    }
} elseif (isset($_POST['username']) && strlen($_POST['username']) > 0) {
    // they have sent an username (default form submitted)
    $username = trim($_POST['username']);

    // find them in the database
    $query = "
      SELECT
        id,
        Email
      FROM
        {$GLOBALS['CONFIG']['db_prefix']}user
      WHERE
        username = :username
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(':username' => $username));

    // send them back if we didn't find the username
    if ($stmt->rowCount() == 0) {
        $redirect = 'forgot_password.php?last_message=' . urlencode(msg('message_the_username_you_entered'));
        header("Location: $redirect");
        exit;
    } else {
        $user_info = $stmt->fetch();
        $user_id = $user_info['id'];
        $email = $user_info['Email'];

        // create a reset code
        $salt = "abcdefghjkmnpqrstuvwxyz23456789ABCDEFGHJKLMNPQRSTUVWXYZ23456789";
        $i = 0;
        $randstring = '';
        while ($i <= 7) {
            $num = rand() % 63;
            $tmp = substr($salt, $num, 1);
            $randstring .= $tmp;
            $i++;
        }
        $reset_code = md5($randstring);

        // add the reset code to the database for this user
        $query = "
          UPDATE
            {$GLOBALS['CONFIG']['db_prefix']}user
          SET
            pw_reset_code = :reset_code
          WHERE
            id = :user_id
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(
            ':reset_code' => $reset_code,
            ':user_id' => $user_id
        ));

        // generate the link
        $resetLink = $GLOBALS['CONFIG']['base_url'] . '/forgot_password.php?username=' . e::h($username) . '&code=' . e::h($reset_code);
        $mail_headers  = "From: " . $GLOBALS['CONFIG']['site_mail'] . PHP_EOL;
        $mail_headers .= "Content-Type: text/plain; charset=UTF-8" . PHP_EOL;
        $mail_body  = msg('email_someone_has_requested_password').PHP_EOL . PHP_EOL;
        $mail_body .= $resetLink . PHP_EOL . PHP_EOL;
        $mail_body .= msg('email_thank_you') . PHP_EOL . PHP_EOL;
        $mail_body .= msg('area_admin') . PHP_EOL . PHP_EOL;

        // send the email
        if ($GLOBALS['CONFIG']['demo'] == 'False') {
            mail($email, msg('area_reset_password'), $mail_body, $mail_headers);
        }

        $redirect = 'forgot_password.php?last_message=' . urlencode(msg('message_an_email_has_been_sent'));
        header("Location: $redirect");
        exit;

        // just show the message instead of reloading this page again
        echo '<div id="last_message">' . e::h(msg('message_an_email_has_been_sent')) . '</div>';
    }
} else {
    //if (strlen($last_message)) {
    //    draw_error($last_message);
    //}
    // default form
?>

        <p><?= e::h(msg('message_this_site_has_high_security')) ?></p>
        <form action="forgot_password.php" method="post">
        <table border="0">
            <tbody>
                <tr>
                    <td><label for="uu"><?= e::h(msg('username')) ?>: </label></td>
                    <td><input id="uu" class="required" type="text" name="username" minlength="2" maxlength="25" /></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td><!-- buttons --></td>
                    <td>
                        <div class="buttons">
                            <button class="positive" type="Submit" name="adduser" value="Reset Password"><?= e::h(msg('area_reset_password')) ?></button>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
        </form>

<?php
}

view_footer();
