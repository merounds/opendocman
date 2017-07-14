<?php use Aura\Html\Escaper as e; ?>
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Common add</code><br />
        <!-- file upload form using ENCTYPE -->
        <form id="addeditform" name="main" action="add.php" method="POST" enctype="multipart/form-data" onsubmit="return checksec();">
        <input id="db_prefix" type="hidden" value="<?= e::h($this->db_prefix) ?>" />
<?php for ($i = 0; $i < count($this->t_name); $i++): ?>
<!-- {assign var='i' value='0'} -->
<!-- {foreach from=$t_name item=name name='loop1'} -->
        <input id="secondary<?= e::h($i) ?>" type="hidden" name="secondary<?= e::h($i) ?>" value="" /> <!-- CHM hidden and onsubmit added-->
        <input id="tablename<?= e::h($i) ?>" type="hidden" name="tablename<?= e::h($i) ?>" value="<?= e::h($this->t_name[$i]) ?>" /> <!-- CHM hidden and onsubmit added-->
    <!-- {assign var='i' value=$i+1} -->
<!-- {/foreach} -->
<?php endfor; ?>
        <input id="i_value" name="i_value" type="hidden" value="<?= e::h($i) ?>" /> <!-- CHM hidden and onsubmit added-->
        <table id="permission_table" border="0" cellspacing="5" cellpadding="5">
            <tbody>
                <tr>
                    <td>
                        <label for="addfile"><a class="body" style="text-decoration:none;" href="help.html#Add_File_-_File_Location" onClick="return popup(this, 'Help')"><?= e::h(msg('label_file_location')) ?>: </a></label>
                    </td>
                    <td>
                        <input id="addfile" type="file" name="file[]" multiple="multiple" tabindex="1" />
                    </td>
                </tr>
<?php if ($this->is_admin == true): ?>
                <tr>
                    <td><label for="addowner"><?= e::h(msg('editpage_assign_owner')) ?>: </label></td>
                    <td>
                        <select id="addowner" name="file_owner">
<?php foreach ($this->avail_users as $user): ?>
                            <option value="<?= e::h($user['id']) ?>" <?= e::h($user['selected']) ?>><?= e::h($user['last_name']) ?>, <?= e::h($user['first_name']) ?></option>
<?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="adddept"><?= e::h(msg('editpage_assign_department')) ?>: </label></td>
                    <td>
                        <select id="adddept" name="file_department">
<?php foreach ($this->avail_depts as $dept): ?>
                            <option value="<?= e::h($dept['id']) ?>" <?= e::h($dept['selected']) ?>><?= e::h($dept['name']) ?></option>
<?php endforeach; ?>
                        </select>
                    </td>
                </tr>
<?php endif; ?>
                <tr>
                    <td>
                         <label for="addcat"><a class="body" style="text-decoration:none;" href="help.html#Add_File_-_Category" onClick="return popup(this, 'Help')"><?= e::h(msg('category')) ?>: </a></label>
                    </td>
                    <td>
                        <select id="addcat" name="category" tabindex="2">
<?php foreach ($this->cats_array as $cat): ?>
                            <option value="<?= e::h($cat['id']) ?>"><?= e::h($cat['name']) ?></option>
<?php endforeach; ?>
                        </select>
                    </td>
                </tr>

                <?= $this->udf_add_form; ?>

                <!-- Set Department rights on the file -->
                <tr id="departmentSelect">
                    <td>
                        <label><a class="body" style="text-decoration:none;" href="help.html#Add_File_-_Department" onClick="return popup(this, 'Help')"><?= e::h(msg('addpage_permissions')) ?>: </a></label>
                    </td>
                    <td>
                        <hr />
<?php include "./templates/common/_filePermissions.php"; ?>
                        <hr style="clear:both;" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="adddesc"><a class="body" style="text-decoration:none;" href="help.html#Add_File_-_Description" onClick="return popup(this, 'Help')"><?= e::h(msg('label_description')) ?>: </a></label>
                    </td>
                    <td>
                        <input id="adddesc" type="text" name="description" size="50" tabindex="5" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="addcomment"><a class="body" style="text-decoration:none;" href="help.html#Add_File_-_Comment" onClick="return popup(this, 'Help')"><?= e::h(msg('label_comment')) ?>: </a></label>
                    </td>
                    <td>
                        <textarea id="addcomment" name="comment" cols="48" rows="4" onchange="this.value=enforceLength(this.value, 255);" tabindex="6"></textarea>
                    </td>
                </tr>
            <tbody>
            <tfoot>
                <tr>
                    <td><!-- buttons --></td>
                    <td>
                        <div class="buttons">
                            <button class="positive" type="Submit" name="submit" value="Add Document" tabindex="7"><?= e::h(msg('submit')) ?></button>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
        </form>

<script type="text/javascript" src="functions.js"></script>
