<?php use Aura\Html\Escaper as e; ?>
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Common user_show</code><br />
        <form action="admin.php" method="POST" enctype="multipart/form-data">
        <table id="user_table">
            <thead>
                <tr><th><?= e::h(msg('userpage_user_info')) ?></th></tr>
            </thead>
            <tbody>
                <tr><td><?= e::h(msg('userpage_id')) ?>: </td>          <td><?= e::h($this->user['id']) ?></td></tr>
                <tr><td><?= e::h(msg('userpage_last_name')) ?>: </td>   <td><?= e::h($this->last_name) ?></td></tr>
                <tr><td><?= e::h(msg('userpage_first_name')) ?>: </td>  <td><?= e::h($this->first_name) ?></td></tr>
                <tr><td><?= e::h(msg('userpage_username')) ?>: </td>    <td><?= e::h($this->user['username']) ?></td></tr>
                <tr><td><?= e::h(msg('userpage_department')) ?>: </td>  <td><?= e::h($this->user['department']) ?></td></tr>
                <tr><td><?= e::h(msg('userpage_email')) ?>: </td>       <td><?= e::h($this->user['email']) ?></td></tr>
                <tr><td><?= e::h(msg('userpage_phone_number')) ?>: </td><td><?= e::h($this->user['phone']) ?></td></tr>
                <tr><td><?= e::h(msg('userpage_admin')) ?>: </td>
<?php if ($this->isAdmin): ?>
                    <td><?= e::h(msg('userpage_yes')) ?></td>
<?php else: ?>
                    <td><?= e::h(msg('userpage_no')) ?></td>
<?php endif; ?>
                </tr>
                <tr><td><?= e::h(msg('userpage_reviewer')) ?>: </td>
<?php if ($this->isReviewer): ?>
                    <td><?= e::h(msg('userpage_yes')) ?></td>
<?php else: ?>
                    <td><?= e::h(msg('userpage_no')) ?></td>
<?php endif; ?>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td><!-- buttons --></td>
                    <td>
                        <div class="buttons">
                            <button class="regular" type="Submit" name="" value="Back"><?= e::h(msg('userpage_back')) ?></button>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
        </form>
