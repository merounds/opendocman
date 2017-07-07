<?php use Aura\Html\Escaper as e; ?>
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Common user_edit</code><br />
        <form id="modifyUserForm" name="update" action="user.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= e::h($this->user->id) ?>" />
        <input type="hidden" name="set_password" value="0" />
        <table id="user_table">
            <tbody>
                <tr>
                    <td><label><?= e::h(msg('userpage_id')) ?>: </label></td>
                    <td><?= e::h($this->user->id) ?></td>
                </tr>
                <tr>
                    <td><label for="uln"><?= e::h(msg('userpage_last_name')) ?>: </label></td>
                    <td><input id="uln" class="required" type="text" name="last_name" value="<?= e::h($this->user->last_name) ?>" minlength="2" maxlength="255" /></td>
                </tr>
                <tr>
                    <td><label for="ufn"><?= e::h(msg('userpage_first_name')) ?>: </label></td>
                    <td><input id="ufn" class="required" type="text" name="first_name" value="<?= e::h($this->user->first_name) ?>" minlength="2" maxlength="255" /></td>
                </tr>
                <tr>
                    <td><label for="uu"><?= e::h(msg('userpage_username')) ?>: </label></td>
                    <td><input id="uu" class="required" type="text" name="username" value="<?= e::h($this->user->username) ?>" minlength="2" maxlength="25" /></td>
                </tr>
                <tr>
                    <td><label for="upn"><?= e::h(msg('userpage_phone_number')) ?>: </label></td>
                    <td><input id="upn" type="text" name="phonenumber" placeholder="999 9999999" value="<?= e::h($this->user->phone) ?>" maxlegnth="20" /></td>
                </tr>
<?php if ($this->mysql_auth): ?>
                <tr>
                    <td><label for="up"><?= e::h(msg('userpage_password')) ?>: </label></td>
                    <td>
                        <input id="up" type="password" name="password" maxlength="32" />
                        <?= e::h(msg('userpage_leave_empty')) ?>
                    </td>
                </tr>
<?php endif; ?>
                <tr>
                    <td><label for="ue"><?= e::h(msg('userpage_email')) ?>: </label></td>
                    <td><input id="ue" class="email required" type="text" name="Email" value="<?= e::h($this->user->email) ?>" maxlength="50" /></td>
                </tr>
                <tr>
                    <td><label for="ud"><?= e::h(msg('userpage_department')) ?>: </label></td>
                    <td><select id="ud" name="department" <?= e::h($this->mode) ?>>
<?php foreach ($this->department_list as $dept): ?>
<?php if ($dept['id'] == $this->user_department): ?>
                            <option selected value="<?= e::h($dept['id']) ?>"><?= e::h($dept['name']) ?></option>
<?php else: ?>
                            <option value="<?= e::h($dept['id']) ?>"><?= e::h($dept['name']) ?></option>
<?php endif; ?>
<?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="cb_admin"><?= e::h(msg('userpage_admin')) ?>: </label></td>
                    <td><input id="cb_admin" type="checkbox" name="admin" value="1" <?php if ($this->is_admin): ?>checked<?php endif; ?> <?= e::h($this->mode) ?> /></td>
                </tr>
                <tr id="userReviewDepartmentRow" <?php if ($this->display_reviewer_row): ?>style="display: none;"<?php endif; ?> >
                    <td id="userReviewDepartmentLabelTd"><label for="userReviewDepartmentsList"><?= e::h(msg('userpage_reviewer_for')) ?>: </label></td>
                    <td id="userReviewDepartmentListTd">
                        <select id="userReviewDepartmentsList" class="multiView" name="department_review[]" multiple="multiple" <?= e::h($this->mode) ?>>
<?php foreach ($this->department_select_options as $item): ?>
                            <?= $item; ?>
<?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="cb_can_add"><?= e::h(msg('userpage_can_add')) ?>? </label></td>
                    <td><input id="cb_can_add" name="can_add" type="checkbox" value="1" <?= e::h($this->can_add) ?> <?= e::h($this->mode) ?> /></td>
                </tr>
                <tr>
                    <td><label for="cb_can_checkin"><?= e::h(msg('userpage_can_checkin')) ?>? </label></td>
                    <td><input id="cb_can_checkin" name="can_checkin" type="checkbox" value="1" <?= e::h($this->can_checkin) ?> <?= e::h($this->mode) ?> /></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td><!-- buttons --></td>
                    <td>
                        <div class="buttons">
                            <button class="positive" type="Submit" name="submit" value="Update User"><?= e::h(msg('userpage_button_update')) ?></button>
                            <button class="negative cancel" type="Submit" name="cancel" value="Cancel"><?= e::h(msg('userpage_button_cancel')) ?></button>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
        </form>

<script type="text/javascript">
  $(document).ready(function () {
    $('#modifyUserForm').validate();
  });
</script>
