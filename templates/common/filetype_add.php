<?php use Aura\Html\Escaper as e; ?>
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Common filetypes_add</code><br />
        <form action="filetypes.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="Submit" value="add" />
        <table id="filetype_table">
            <thead>
                <tr>
                    <th colspan="3"><?= e::h(msg('label_add')) . "&nbsp;" . e::h(msg('label_filetype')) ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="text" name="filetype" placeholder="application/pdf" /> ex.: application/pdf</td>
                    <td>
                        <div class="buttons">
                            <button class="positive" type="submit" name="submit" value="AddNewSave"><?= e::h(msg('button_save')) ?></button>
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