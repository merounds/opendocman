<?php use Aura\Html\Escaper as e; ?>
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Common udf_delete_pick</code><br />
        <form action="udf.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="type" value="<?= e::h($this->udf['field_type']) ?>">
        <input type="hidden" name="id" value="<?= e::h($this->udf['table_name']) ?>">
        <table id="udf_table">
            <tbody>
                <tr><th align=right><?= e::h(msg('label_name')) ?>: </th>
                    <td><?= e::h($this->udf['table_name']) ?></td>
                </tr>
                <tr><th align=right><?= e::h(msg('label_display')) ?>: </th>
                    <td><?= e::h($this->udf['display_name']) ?></td>
                </tr>
                <tr>
                    <td><?= e::h(msg('message_are_you_sure_remove')) ?></td>
                    <td>
                        <div class="buttons">
                            <button class="positive" type="Submit" name="deleteudf" value="Yes"><?= e::h(msg('button_yes')) ?></button>
                            <button class="negative" type="Submit" name="cancel" value="Cancel"><?= e::h(msg('button_cancel')) ?></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        </form>
