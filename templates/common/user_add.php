<?php use Aura\Html\Escaper as e; ?>
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Common user_add</code><br />
        <form id="add_user" name="add_user" action="user.php" method="POST" enctype="multipart/form-data">
        <table id="user_table">
            <thead>
<?php echo $this->onBeforeAddUser ?>
            </thead>
            <tbody>
                <tr>
                    <td><label for="uln"><?= e::h(msg('userpage_last_name')) ?>: </label></td>
                    <td><input id="uln" class="required" type="text" name="last_name" minlength="2" maxlength="255" /></td>
                </tr>
                <tr>
                    <td><label for="ufn"><?= e::h(msg('userpage_first_name')) ?>: </label></td>
                    <td><input id="ufn" class="required" type="text" name="first_name" minlength="2" maxlength="255" /></td>
                </tr>
                <tr>
                    <td><label for="uu"><?= e::h(msg('userpage_username')) ?>: </label></td>
                    <td><input id="uu" class="required" type="text" name="username" minlength="2" maxlength="25" /></td>
                </tr>
                <tr>
                    <td><label for="upn"><?= e::h(msg('userpage_phone_number')) ?>: </label></td>
                    <td><input id="upn" type="text" name="phonenumber" placeholder="999 9999999" maxlength="20" /></td>
                </tr>
                <tr>
                    <td><?= e::h(msg('label_example')) ?>: </td>
                    <td>999 9999999</td>
                </tr>
<?php if ($this->mysql_auth): ?>
                <tr>
                    <td><label for="up"><?= e::h(msg('userpage_password')) ?>: </label></td>
                    <td><input id="up" class="required" type="text" name="password" value="<?= $this->rand_password ?>" minlength="5" maxlength="32" /></td>
                </tr>
<?php endif; ?>
                <tr>
                    <td><label for="ue"><?= e::h(msg('label_email_address')) ?>: </label></td>
                    <td><input id="ue" class="required email" type="text" name="Email" maxlength="50" /></td>
                </tr>
                <tr>
                    <td><label for="ud"><?= e::h(msg('userpage_department')) ?>: </label></td>
                    <td>
                        <select id="ud" name="department">
<?php foreach ($this->department_list as $dept): ?>
                            <option value="<?= e::h($dept['id']) ?>"><?= e::h($dept['name']) ?></option>
<?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="cb_admin"><?= e::h(msg('label_is_admin')) ?>: </label></td>
                    <td><input id="cb_admin" name="admin" type="checkbox" value="1" /></td>
                </tr>
                <tr id="userReviewDepartmentRow">
                    <td id="userReviewDepartmentLabelTd"><label for="userReviewDepartmentsList"><?= e::h(msg('label_reviewer_for')) ?>: </label></td>
                    <td id="userReviewDepartmentListTd">
                        <select id="userReviewDepartmentsList" class="multiView" name="department_review[]" multiple="multiple">
<?php foreach ($this->department_list as $dept): ?>
                            <option value="<?= e::h($dept['id']) ?>"><?= e::h($dept['name']) ?></option>
<?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="cb_can_add"><?= e::h(msg('userpage_can_add')) ?>? </label></td>
                    <td><input id="cb_can_add" type="checkbox" name="can_add" value="1" checked="checked" /></td>
                </tr>
                <tr>
                    <td><label for="cb_can_checkin"><?= e::h(msg('userpage_can_checkin')) ?>? </label></td>
                    <td><input id="cb_can_checkin" type="checkbox" name="can_checkin" value="1" checked="checked" /></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td><!-- buttons --></td>
                    <td>
                        <div class="buttons">
                            <button id="submitButton" class="positive" type="Submit" name="submit" value="Add User"><?= e::h(msg('userpage_button_add_user')) ?></button>
                            <button id="cancelButton" class="negative cancel" type="Submit" name="cancel" value="Cancel"><?= e::h(msg('userpage_button_cancel')) ?></button>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
        </form>

<script type="text/javascript">
  $(document).ready(function(){
    $('#submitButton').click(function(){
      $('#add_user').validate();
    })
  });
</script>
