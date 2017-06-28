<?php use Aura\Html\Escaper as e; ?>
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Common out</code><br />
<?php if ($this->showCheckBox): ?>
    <form name="table" method="post" action="<?= e::h($_SERVER['PHP_SELF']); ?>">
<?php endif; ?>
        <div id="filetable_wrapper">
            <table id="filetable" class="display" border="0" cellpadding="1" cellspacing="1">
                <thead>
                    <tr>
<?php if ($this->showCheckBox): ?>
                        <th class="sorting_disabled"><input type="checkbox" id="checkall" /></th>
<?php endif; ?>
                        <th class="sorting">ID</th>
                        <th class="sorting_disabled"><?= e::h(msg('label_view')) ?></th>
                        <th class="sorting"><?= e::h(msg('label_file_name')) ?></th>
                        <th class="sorting"><?= e::h(msg('label_description')) ?></th>
                        <th class="sorting"><?= e::h(msg('label_rights')) ?></th>
                        <th class="sorting"><?= e::h(msg('label_created_date')) ?></th>
                        <th class="sorting"><?= e::h(msg('label_modified_date')) ?></th>
                        <th class="sorting"><?= e::h(msg('label_author')) ?></th>
                        <th class="sorting"><?= e::h(msg('label_department')) ?></th>
                        <th class="sorting"><?= e::h(msg('label_size')) ?></th>
                        <th class="sorting_disabled"><?= e::h(msg('label_status')) ?></th>
                    </tr>
                </thead>
                <tbody>
<?php foreach ($this->file_list_arr as $item): ?>
                    <tr<?php if($item['lock'] == true): ?> class="gradeX"<?php endif; ?>>
<?php if ($item['showCheckbox']): ?>
                        <td><input type="checkbox" class="checkbox" value="<?= e::h($item['id']) ?>" name="checkbox[]" /></td>
<?php endif; ?>
                        <td class="center"><?= e::h($item['id']) ?></td>
                        <td class="center" style="width: 50px;">
<?php if ($item['view_link'] == 'none'): ?>
                            &nbsp;
<?php else: ?>
                            <a href="<?= e::h($item['view_link']) ?>"><?= e::h(msg('outpage_view')) ?></a></td>
<?php endif; ?>
                        <td><a href="<?= e::h($item['details_link']) ?>"><?= e::h($item['filename']) ?></a></td>
                        <td><?= e::h($item['description']) ?></td>
                        <td class="center"><?= e::h($item['rights'][0][1]) . " | " . e::h($item['rights'][1][1]) . " | " . e::h($item['rights'][2][1]) ?></td>
                        <td><?= e::h($item['created_date']) ?></td>
                        <td><?= e::h($item['modified_date']) ?></td>
                        <td><?= e::h($item['owner_name']) ?></td>
                        <td><?= e::h($item['dept_name']) ?></td>
                        <td><?= e::h($item['filesize']) ?></td>
                        <td class="center">
<?php if ($item['lock'] == false): ?>
                            <img src="<?= $GLOBALS['CONFIG']['base_url'] ?>/images/file_unlocked.png" width="16" height="16" alt="unlocked" />
<?php else: ?>
                            <img src="<?= $GLOBALS['CONFIG']['base_url'] ?>/images/file_locked.png" width="16" height="16" alt="locked" />
<?php endif; ?>
                        </td>
                    </tr>
<?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
<?php if ($this->showCheckBox): ?>
                        <th></th>
<?php endif; ?>
                        <th>ID</th>
                        <th><?= e::h(msg('label_view')) ?></th>
                        <th><?= e::h(msg('label_file_name')) ?></th>
                        <th><?= e::h(msg('label_description')) ?></th>
                        <th><?= e::h(msg('label_rights')) ?></th>
                        <th><?= e::h(msg('label_created_date')) ?></th>
                        <th><?= e::h(msg('label_modified_date')) ?></th>
                        <th><?= e::h(msg('label_author')) ?></th>
                        <th><?= e::h(msg('label_department')) ?></th>
                        <th><?= e::h(msg('label_size')) ?></th>
                        <th><?= e::h(msg('label_status')) ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
<?php if ($this->showCheckBox != true): ?>
    </form>
<?php endif; ?>
<?php if ($this->limit_reached): ?>
        <div class="text-warning"><?= e::h(msg('message_max_number_of_results')) ?></div>
<?php endif; ?>
