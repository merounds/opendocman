<?php use Aura\Html\Escaper as e; ?>
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Common filetypes_deleteshow</code><br />
        <form action="filetypes.php" method="POST" enctype="multipart/form-data">
        <table id="filetype_table" class="form-table" style="width: 200px;">
            <thead>
                <tr>
                    <th colspan="3"><?= e::h(msg('label_delete')) . "&nbsp;" . e::h(msg('label_filetypes')) . "&nbsp;-&nbsp;" . e::h(msg('choose')) ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select id="types" class="multiView" multiple="multiple" name="types[]">
<?php foreach ($this->filetypes_array as $i): ?>
                            <option value="<?= e::h($i['id']) ?>"><?= e::h($i['type']) ?></option>
<?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <div class="buttons">
                            <button class="positive" type="submit" name="submit" value="Delete"><?= e::h(msg('button_delete')) ?></button>
                        </div>
                    </td>
                    <td >
                        <div class="buttons">
                            <button class="negative" type="Submit" name="submit" value="Cancel"><?= e::h(msg('button_cancel')) ?></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>