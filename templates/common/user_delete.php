<?php use Aura\Html\Escaper as e; ?>
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Common user_delete</code><br />
        <form action="user.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= e::h($this->user_id) ?>" />
        <table id="user_table">
            <tbody>
                <tr>
                    <td><?= e::h(msg('userpage_are_sure')) ?> <?= e::h($this->full_name[0]) ?> <?= e::h($this->full_name[1]) ?>?</td>
                    <td>
                        <div class="buttons">
                            <button class="positive" type="Submit" name="submit" value="Delete User"><?= e::h(msg('button_delete')) ?></button>
                            <button class="negative" type="Submit" name="cancel" value="Cancel"><?= e::h(msg('button_cancel')) ?></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        </form>
