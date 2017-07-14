<?php use Aura\Html\Escaper as e; ?>
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Common udf_add</code><br />
        <form id="udfAddForm" action="udf.php?last_message=<?= e::h($this->last_message) ?>" method="GET" enctype="multipart/form-data">
        <table id="udf_table" border="0" cellspacing="5" cellpadding="5">
            <tbody>
                <tr>
                    <td><label for="udfname"><?= e::h(msg('label_name')) . ' (' . e::h(msg('label_limit')) ?>): </label></td>
                    <td><input id="udfname" class="required" type="text" name="table_name" maxlength="5" /></td>
                </tr>
                <tr>
                    <td><label for="udfdisplay"><?= e::h(msg('label_display')) . ' ' . e::h(msg('label_name')) ?>: </label></td>
                    <td><input id="udfdisplay" class="required" type="text" name="display_name" maxlength="16" /></td>
                </tr>
                <tr>
                    <td><label for="udftype"><?= e::h(msg('label_type_pr_sec')) ?>: </label></td>
                    <td><select id="udftype" name="field_type">
                            <option value="1"><?= e::h(msg('select')) . ' ' . e::h(msg('list')) ?></option>
                            <option value="4"><?= e::h(msg('label_sub_select_list')) ?></option>
                            <option value="2"><?= e::h(msg('label_radio_button')) ?></option>
                            <option value="3"><?= e::h(msg('label_text')) ?></option>
                        </select>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td><!-- buttons --></td>
                    <td>
                        <div class="buttons">
                            <button class="positive" type="Submit" name="submit" value="Add User Defined Field"><?= e::h(msg('button_save')) ?></button>
                            <button class="negative cancel" type="Submit" name="cancel" value="Cancel"><?= e::h(msg('button_cancel')) ?></button>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
        </form>

<script>
  $(document).ready(function(){
    $('#udfAddForm').validate();
  });
</script>
