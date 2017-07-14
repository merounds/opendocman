<?php use Aura\Html\Escaper as e; ?>
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Common history</code><br />
        <table id="filehistory">
            <caption>
                <img src="images/revision.png" width="40" height="40" alt="icon"> <?= e::h(msg('historypage_history')) ?>
            </caption>
            <thead>
                <tr id="historyheader">
                    <th><?= e::h(msg('historypage_version')) ?></th>
                    <th><?= e::h(msg('historypage_modification')) ?></th>
                    <th><?= e::h(msg('historypage_by')) ?></th>
                    <th><?= e::h(msg('historypage_note')) ?></th>
                </tr>
            </thead>
            <tbody>
<?php foreach ($this->history_list_array as $item): ?>
                <tr>
<?php if ($item['version_link'] == 'none'): ?>
                    <td class="center"><?= e::h($item['revision']) ?> <?= e::h($item['extra_message']) ?></td>
<?php else: ?>
                    <td class="center"><a href="<?= e::h($item['version_link']) ?>"><?= e::h($item['version']) ?></a> <?= e::h($item['extra_message']) ?></td>
<?php endif; ?>
                    <td><?= e::h($item['modified_date']) ?></td>
                    <td><?= e::h($item['owner_name']) ?></td>
                    <td><?= e::h($item['note']) ?></td>
                </tr>
<?php endforeach; ?>
            </tbody>
        </table>
