<?php use Aura\Html\Escaper as e; ?>
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Common udf_edit</code><br />
        <form id="editUdfForm" method="post">
        <input type="hidden" name="submit" value="edit" />
        <input type="hidden" name="udf" value="<?= e::h($this->udf) ?>" />
        <table id="udf_table">
            <tbody>
                <tr>
                    <th><?= e::h(msg('label_name')) ?>: </th>
                    <td><?= e::h($this->udf) ?></td>
                </tr>
                <tr>
                    <td><label for="udfdisplay"><?= e::h(msg('label_display')) . ' ' . e::h(msg('label_name')) ?>: </label></td>
                    <td><input id="udfdisplay" class="required" type="text" name="display_name" value="<?= e::h($this->display_name) ?>" maxlength="16" /></td>
                </tr>
<?php if ($this->form == 'type4'): ?>
                <tr>
                    <td><label for="udfprsec"><?= e::h(msg('label_type_pr_sec')) ?>: </label></td>
                    <td>
                        <select id="udfprsec" class="required" name="type_pr_sec" onchange="showdivs(this.value,'<?= e::h($this->udf) ?>')">
                            <option value="primary"><?= e::h(msg('label_primary_items')) ?></option>
                            <option value="secondary"><?= e::h(msg('label_secondary_items')) ?></option>
                        </select>
                    </td>
                </tr>
<?php endif; ?>
            </tbody>
            <tbody id="txtHint">
                <tr><td colspan="2"><hr /></td></tr>
                <tr class="header">
                    <th><?= e::h(msg('label_delete')) ?>? </th>
                    <th><?= e::h(msg('value')) ?></th>
                </tr>
<?php foreach ($this->rows as $item): ?>
                <tr class="show">
                    <td><input id="c<?= e::h($item[0]) ?>" type="checkbox" name="x<?= e::h($item[0]) ?>" /></td>
                    <td><label for="c<?= e::h($item[0]) ?>"><?= e::h($item[1]) ?></label></td>
                </tr>
<?php endforeach; ?>
            </tbody>
            <tbody id="addnew">
                <tr>
                    <td><label for="udfnew"><?= e::h(msg('label_add')) . ' ' . e::h(msg('new')) ?>: </label></td>
                    <td><input id="udfnew" type="text" name="newvalue" maxlength="16" /></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td><!-- buttons --></td>
                    <td>
                        <div class="buttons">
                            <button class="positive" type="submit" value="Update"><?= e::h(msg('button_update')) ?></button>
                            <button class="negative" type="Submit" name="cancel" value="Cancel"><?= e::h(msg('button_cancel')) ?></button>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
        </form>

<script>
  $(document).ready(function(){
    $('#editUdfForm').validate();
  });
</script>
