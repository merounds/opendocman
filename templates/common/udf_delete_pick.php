<?php use Aura\Html\Escaper as e; ?>
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Common udf_delete_pick</code><br />
        <form action="udf.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="state" value="<?= e::h($this->state) ?>" />
        <table id="udf_table" border="0" cellspacing="5" cellpadding="5">
            <tbody>
                <tr>
                    <td><label for="udfi"><?= e::h(msg('label_user_defined_field')) ?>: </label></td>
                    <td>
                        <select id="udfi" name="item">
<?php foreach ($this->udfs as $item): ?>
                            <option value="<?= e::h($item['table_name']) ?>"><?= e::h($item['display_name']) ?></option>
<?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <div class="buttons">
                            <button class="positive" type="Submit" name="submit" value="delete"><?= e::h(msg('button_delete')) ?></button>
                        </div>
                    </td>
                    <td>
                        <div class="buttons">
                            <button class="negative cancel" type="Submit" name="cancel" value="Cancel"><?= e::h(msg('button_cancel')) ?></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        </form>
