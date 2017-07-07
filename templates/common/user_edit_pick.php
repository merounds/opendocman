<?php use Aura\Html\Escaper as e; ?>
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Common user_edit_pick</code><br />
        <form action="user.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="state" value="<?= e::h($this->state) ?>" />
        <table id="user_table">
            <tbody>
                <tr>
                    <td><label for="uu"><?= e::h(msg('userpage_user')) ?>: </label></td>
                    <td colspan=3>
                        <select id="uu" name="item">
<?php foreach ($this->user_list as $user): ?>
                            <option value="<?= e::h($user['id']) ?>"><?= e::h($user['last_name']) ?>, <?= e::h($user['first_name']) ?> - <?= e::h($user['username']) ?></option>
<?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <div class="buttons">
                            <button class="positive" type="Submit" name="submit" value="Modify User"><?= e::h(msg('userpage_button_modify')) ?></button>
                        </div>
                    </td>
                    <td>
                        <div class="buttons">
                            <button class="negative" type="Submit" name="cancel" value="Cancel"><?= e::h(msg('userpage_button_cancel')) ?></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        </form>