<?php
use Aura\Html\Escaper as e;

/*
signup.php - allow user to create their own account
Copyright (C) 2002-2007 Stephen Lawrence Jr., Jon Miner
Copyright (C) 2008-2014 Stephen Lawrence Jr.

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
$view_registry->prependPath(
    __DIR__ . '/templates/' . $GLOBALS['CONFIG']['theme']
);

view_header(msg('area_add_new_user'), '');

if ($GLOBALS['CONFIG']['allow_signup'] == 'True') {

    // Submitted so insert data now
    if (isset($_REQUEST['adduser'])) {
        // Check to make sure user does not already exist
        $query = "
          SELECT
            username
          FROM
            {$GLOBALS['CONFIG']['db_prefix']}user
          WHERE
            username = :username
        ";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $_POST['username']);
        $stmt->execute();

        // If the above statement returns more than 0 rows, the user exists, so display error
        if ($stmt->rowCount() > 0) {
            echo '<p>' . msg('message_user_exists') . '</p>';
            echo '<p>' . msg('please') . ' <a href="signup.php">' . msg('message_try_again') . '</a>.</p>';
        } else {
            $phonenumber = (!empty($_REQUEST['phonenumber']) ? $_REQUEST['phonenumber'] : '');
            // INSERT into user
            $query = "
              INSERT INTO
                {$GLOBALS['CONFIG']['db_prefix']}user
                (
                  username,
                  password,
                  department,
                  phone,
                  Email,
                  last_name,
                  first_name
                ) VALUES (
                  :username,
                  md5(:password),
                  :department,
                  :phonenumber,
                  :email,
                  :last_name,
                  :first_name
                  )";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':username', $_POST['username']);
            $stmt->execute(array(
                ':username' => $_POST['username'],
                ':password' => $_POST['password'],
                ':department' => $_POST['department'],
                ':phonenumber' => $phonenumber,
                ':email' => $_POST['Email'],
                ':last_name' => $_POST['last_name'],
                ':first_name' => $_POST['first_name']
            ));

            // INSERT into admin
            $userid = $pdo->lastInsertId();

            // mail user telling him/her that his/her account has been created.
            echo '<p>' . msg('message_account_created') . '</p>';
            echo '<p>' . msg('label_username') . ': ' . $_POST['username'] . '</p>';
            if($GLOBALS['CONFIG']['authen'] == 'mysql')
            {
                echo '<p>' . msg('message_account_created_password') . ': ' . e::h($_REQUEST['password']) . '</p>' . PHP_EOL . PHP_EOL;
                echo '<p><a href="' . $GLOBALS['CONFIG']['base_url'] . '">' . msg('login') . '</a></p>';
            }
        }
    } else {
        // Not submitted so show form
        $mysql_auth = $GLOBALS["CONFIG"]["authen"] == 'mysql';
        $rand_password = makeRandomPassword();

        // query to get a list of departments
        $query = "
          SELECT
            id,
            name
          FROM
            {$GLOBALS['CONFIG']['db_prefix']}department
          ORDER BY
            name
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $department_list = $stmt->fetchAll();

        $view->setData([
            'mysql_auth'      => $mysql_auth,
            'rand_password'   => $rand_password,
            'department_list' => $department_list
        ]);
        $view->setView('signup');
        echo $view->__invoke();
    }
} else {
    echo "<p>" . msg('message_sorry_not_allowed') . "</p>";
}

view_footer();
