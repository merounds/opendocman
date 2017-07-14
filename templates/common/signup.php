<?php use Aura\Html\Escaper as e; ?>
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Common signup</code><br />

        <form name="add_user" action="signup.php" method="POST" enctype="multipart/form-data">
<?php if ($this->mysql_auth): ?>
        <input type="hidden" name="password" value="<?= $this->rand_password ?>" />
<?php endif; ?>

        <table id="user_table" border="0" cellspacing="5" cellpadding="5">
            <thead>
                <tr>
                    <th colspan="2"><h1><?= e::h(msg('signup')) ?></h1></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><label for="uln"><?= e::h(msg('label_last_name')) ?>: </label></td>
                    <td><input id="uln" class="required" type="text" name="last_name" minlength="2" maxlength="255" /></td>
                </tr>
                <tr>
                    <td><label for="ufn"><?= e::h(msg('label_first_name')) ?>: </label></td>
                    <td><input id="ufn" class="required" type="text" name="first_name" minlength="2" maxlength="255" /></td>
                </tr>
                <tr>
                    <td><label for="uu"><?= e::h(msg('username')) ?>: </label></td>
                    <td><input id="uu" class="required" type="text" name="username" minlength="2" maxlength="25" /></td>
                </tr>
                <tr>
                    <td><label for="upn"><?= e::h(msg('label_phone_number')) ?>: </label></td>
                    <td><input id="upn" type="text" name="phonenumber" placeholder="999 9999999" maxlegnth="20" /></td>
                </tr>
                <tr>
                    <td><?= e::h(msg('label_example')) ?>: </td>
                    <td>999 9999999</td>
                </tr>
                <tr>
                    <td><label for="ue"><?= e::h(msg('label_email_address')) ?>: </label></td>
                    <td><input id="ue" class="required email" type="text" name="Email" maxlength="50" /></td>
                </tr>
                <tr>
                    <td><label for="ud"><?= e::h(msg('label_department')) ?>: </label></td>
                    <td>
                        <select id="ud" name="department">
<?php foreach ($this->department_list as $dept): ?>
                            <option value="<?= e::h($dept['id']) ?>"><?= e::h($dept['name']) ?></option>
<?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td><!-- buttons --></td>
                    <td>
                        <div class="buttons">
                            <button class="positive" type="Submit" name="adduser" value="Submit" onClick="return validatemod(add_user);"><?= e::h(msg('submit')) ?></button>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
        </form>

<script type="text/javascript" src="FormCheck.js"></script>
