<?php use Aura\Html\Escaper as e; ?>
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Common edit</code><br />
        <!-- file upload form using ENCTYPE -->
        <form id="addeditform" class="display dataTable" name="main" action="{$smarty.server.PHP_SELF) ?>" method="POST" enctype="multipart/form-data" onsubmit="return checksec();">
        <input type="hidden" id="db_prefix" value="<?= e::h($this->db_prefix) ?>" />
<?php for ($i = 0; $i < count($this->t_name); $i++): ?>
<!-- {assign var='i' value='0'} -->
<!-- {foreach from=$t_name item=name name='loop1'} -->
        <input id="secondary<?= e::h($i) ?>" type="hidden" name="secondary<?= e::h($i) ?>" value="" /> <!-- CHM hidden and onsubmit added-->
        <input id="tablename<?= e::h($i) ?>" type="hidden" name="tablename<?= e::h($i) ?>" value="<?= e::h($this->t_name[$i]) ?>" /> <!-- CHM hidden and onsubmit added-->
    <!-- {assign var='i' value=$i+1} -->
<!-- {/foreach} -->
<?php endfor; ?>
        <input type="hidden" id="id" name="id" value="<?= e::h($this->file_id) ?>" />
        <input id="i_value" type="hidden" name="i_value" value="{$i) ?>" /> <!-- CHM hidden and onsubmit added-->
        <table id="permission_table" border="0" cellspacing="5" cellpadding="5">
            <tbody>
                <tr>
                    <td><label><?= e::h(msg('label_name')) ?>: </label></td>
                    <td><b><?= e::h($this->realname) ?></b></td>
                </tr>
<?php if ($this->is_admin == true): ?>
                <tr>
                    <td><label for="editowner"><?= e::h(msg('editpage_assign_owner')) ?>: </label></td>
                    <td>
                        <select id="editowner" name="file_owner">
<?php foreach ($this->avail_users as $user): ?>
                            <option value="<?= e::h($user['id']) ?>" <?php if ($this->pre_selected_owner == $user['id']){echo 'selected="selected"';} ?>><?= e::h($user['last_name']) ?>, <?= e::h($user['first_name']) ?></option>
<?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="editdept"><?= e::h(msg('editpage_assign_department')) ?>: </label></td>
                    <td>
                        <select id="editdept" name="file_department">
<?php foreach ($this->avail_depts as $dept): ?>
                            <option value="<?= e::h($dept['id']) ?>" <?php if ($this->pre_selected_department == $dept['id']){echo 'selected="selected"';} ?>><?= e::h($dept['name']) ?></option>
<?php endforeach; ?>
                        </select>
                    </td>
                </tr>
<?php endif; ?>
                <tr>
                    <td>
                         <label for="editcat"><a class="body" style="text-decoration:none;" href="help.html#Add_File_-_Category" onClick="return popup(this, 'Help')"><?= e::h(msg('category')) ?>: </a></label>
                    </td>
                    <td>
                        <select id="editcat" name="category" tabindex="2">
<?php foreach ($this->cats_array as $cat): ?>
                            <option value="<?= e::h($cat['id']) ?>" <?php if ($this->pre_selected_category == $cat['id']){echo 'selected="selected"';} ?>><?= e::h($cat['name']) ?></option>
<?php endforeach; ?>
                        </select>
                    </td>
                </tr>

                <?= $this->udf_edit_form ?>

                <!-- Set Department rights on the file -->
                <tr id="departmentSelect">
                    <td>
                        <label><a class="body" style="text-decoration:none;" href="help.html#Add_File_-_Department" onClick="return popup(this, 'Help')"><?= e::h(msg('addpage_permissions')) ?>: </a>
                    </td>
                    <td>
                        <hr />
<?php include "./templates/common/_filePermissions.php"; ?>
                        <hr style="clear:both;" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="editdesc"><a class="body" style="text-decoration:none;" href="help.html#Add_File_-_Description" onClick="return popup(this, 'Help')"><?= e::h(msg('label_description')) ?>: </a></label>
                    </td>
                    <td>
                        <input id="editdesc" type="text" name="description" value="<?= e::h($this->description) ?>" size="50" tabindex="5" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="editcomment"><a class="body" style="text-decoration:none;" href="help.html#Add_File_-_Comment" onClick="return popup(this, 'Help')"><?= e::h(msg('label_comment')) ?>: </a></label>
                    </td>
                    <td>
                        <textarea id="editcomment" name="comment" cols="48" rows="4" onchange="this.value=enforceLength(this.value, 255);" tabindex="6"><?= e::h($this->comment) ?></textarea>
                    </td>
                </tr>
            <tbody>
            <tfoot>
                <tr>
                    <td><!-- buttons --></td>
                    <td>
                        <div class="buttons">
                            <button class="positive" type="Submit" name="submit" value="Update Document Properties"><?= e::h(msg('button_save')) ?></button>
                            <button class="negative" type="Reset" name="reset" value="Reset"><?= e::h(msg('button_reset')) ?></button>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
        </form>

<script type="text/javascript" src="functions.js"></script>
