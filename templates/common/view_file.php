<?php use Aura\Html\Escaper as e; ?>
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Common view_file</code><br />
        <form action="view_file.php" name="view_file_form" method="get">
            <input type="hidden" name="id" value="<?= e::h($this->file_id) ?>" />
            <input type="hidden" name="mimetype" value="<?= e::h($this->mimetype) ?>" />
            <p><?= e::h(msg('message_to_view_your_file')) ?>, <a class="body" style="text-decoration:none" target="_blank" href="view_file.php?submit=view&id=<?= e::h($this->file_id) ?>&mimetype=<?= e::h($this->mimetype) ?>"><?= e::h(msg('button_click_here')) ?></a></p>
            <div class="buttons">
                <button class="regular" type="submit" name="submit" value="Download"><?= e::h(msg('message_if_you_are_unable_to_view2')) ?></button>
            </div>
            <p><?= e::h(msg('message_if_you_are_unable_to_view1')) ?>
               <?= e::h(msg('message_if_you_are_unable_to_view2')) ?><br />
               <?= e::h(msg('message_if_you_are_unable_to_view3')) ?></p>
        </form>
