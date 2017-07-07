<?php use Aura\Html\Escaper as e; ?>
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Common filetypes</code><br />
        <form action="filetypes.php" method="POST" enctype="multipart/form-data">
        <table id="filetype_table" class="form-tablexx" style="width: 200px;">
            <thead>
                <tr>
                    <th colspan="3"><?= e::h(msg('label_allowed')) . "&nbsp;" . e::h(msg('label_filetypes')) ?>
                        <!-- &nbsp;|&nbsp;
                        <a href="filetypes.php?submit=AddNew"><?= e::h(msg('label_add')) . "&nbsp;" . e::h(msg('label_filetype')) ?></a>
                        &nbsp;|&nbsp;
                        <a href="filetypes.php?submit=DeleteSelect"><?= e::h(msg('label_delete')) . "&nbsp;" . e::h(msg('label_filetype')) ?></a> -->
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select id="types" class="multiView" multiple="multiple" name="types[]">
<?php foreach ($this->filetypes_array as $i): ?>
                            <option value="<?= e::h($i['id']) ?>" <?php if ($i['active'] == '1'){echo 'selected="selected"';} ?>><?= e::h($i['type']) ?></option>
<?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <div class="buttons">
                            <button class="positive" type="submit" name="submit" value="Save"><?= e::h(msg('button_save')) ?></button>
                        </div>
                    </td>
                    <td>
                        <div class="buttons">
                            <button class="negative" type="Submit" name="submit" value="Cancel"><?= e::h(msg('button_cancel')) ?></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <a href="filetypes.php?submit=AddNew"><?= e::h(msg('label_add')) . "&nbsp;" . e::h(msg('label_filetype')) ?></a>
                        &nbsp;|&nbsp;
                        <a href="filetypes.php?submit=DeleteSelect"><?= e::h(msg('label_delete')) . "&nbsp;" . e::h(msg('label_filetype')) ?></a>
                    </td>
                </tr>
            </tbody>
        </table>
        </form>