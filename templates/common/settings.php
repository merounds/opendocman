<?php use Aura\Html\Escaper as e; ?>
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Common settings</code><br />
        <form id="settingsForm" action="settings.php" method="POST" enctype="multipart/form-data">
        <table class="form-table">
            <thead>
                <tr>
                    <th><?= e::h(msg('label_name')) ?></th>
                    <th><?= e::h(msg('value')) ?></th>
                    <th><?= e::h(msg('label_description')) ?></th>
                </tr>
            </thead>
            <tbody>
<?php foreach ($this->settings_array as $item): ?>
                <tr>
                    <td><?= e::h($item['name']) ?></td>
                    <td>
                        <?php if ($item['validation'] == 'bool'): ?>
                        <select name="<?= e::h($item['name']) ?>">
                            <option value="True"<?php if ($item['value'] == 'True'): ?> selected="selected"<?php endif; ?>>True</option>
                            <option value="False"<?php if ($item['value'] == 'False'): ?> selected="selected"<?php endif; ?>>False</option>
                        </select>
                        <?php elseif ($item['name'] == 'theme'): ?>
                        <select name="theme">
                            <?php foreach ($this->themes as $theme): ?>
                            <option value="<?= e::h($theme) ?>"<?php if ($item['value'] == $theme): ?> selected="selected"<?php endif; ?>><?= e::h($theme) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php elseif ($item['name'] == 'language'): ?>
                        <select name="language">
                            <?php foreach ($this->languages as $language): ?>
                            <option value="<?= e::h($language) ?>"<?php if ($item['value'] == $language): ?> selected="selected"<?php endif; ?>><?= e::h($language) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php elseif ($item['name'] == 'file_expired_action'): ?>
                        <select name="file_expired_action">
                            <option value="1"<?php if ($item['value'] == '1'): ?> selected="selected"<?php endif; ?>>Remove from file list until renewed</option>
                            <option value="2"<?php if ($item['value'] == '2'): ?> selected="selected"<?php endif; ?>>Show in file list but non-checkoutable</option>
                            <option value="3"<?php if ($item['value'] == '3'): ?> selected="selected"<?php endif; ?>>Send email to reviewer only</option>
                            <option value="4"<?php if ($item['value'] == '4'): ?> selected="selected"<?php endif; ?>>Do Nothing</option>
                        </select>
                        <?php elseif ($item['name'] == 'authen'): ?>
                        <select name="authen">
                            <option value="mysql"<?php if ($item['value'] == 'mysql'): ?> selected="selected"<?php endif; ?>>MySQL</option>
                        </select>
                        <?php elseif ($item['name'] == 'root_id'): ?>
                        <select name="root_id">
                            <?php foreach ($this->useridnums as $useridnum): ?>
                            <option value="<?= e::h($useridnum[0]) ?>" <?php if ($item['value'] == $useridnum[0]): ?> selected="selected"<?php endif; ?>><?= e::h($useridnum[1]) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php else: ?>
                        <input type="text" size="40" name="<?= e::h($item['name']) ?>" value="<?= e::h($item['value']) ?>" />
                        <?php endif; ?>
                    </td>
                    <td><em><?= e::h($item['description']) ?></em></td>
                </tr>
<?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td style="center">
                        <div class="buttons">
                            <button class="positive" type="submit" name="submit" value="Save"><?= e::h(msg('button_save')) ?></button>
                        </div>
                    </td>
                    <td style="center">
                        <div class="buttons">
                            <button class="negative" type="submit" name="submit" value="Cancel"><?= e::h(msg('button_cancel')) ?></button>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
        </form>
