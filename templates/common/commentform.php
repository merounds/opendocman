<?php use Aura\Html\Escaper as e; ?>
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Common commentform</code><br />
    <?php if (isset($mode)): e::h($mode); endif; ?>
    <p><?= e::h(msg('email_note_to_authors')) ?></p>
    <form name="author_note_form" action="toBePublished.php<?php if (isset($mode) && $mode == 'root'): ?>?mode=root'<?php endif; ?>" method="POST">
        <input type="hidden" name="checkbox" value="<?php foreach ($this->checkbox as $id): echo $id . ' '; endforeach; ?>" />
        <table name="author_note_table">
            <tbody>
                <tr>
                    <td><label for="customto"><?= e::h(msg('email_to')) ?>: </label></td>
                    <td><input id="customto" type="text" name="to" placeholder="Author(s)" size='45' <?php if (isset($access_mode)): e::h($access_mode); endif; ?>></td>
                </tr>
                <tr>
                    <td><label for="customsubject"><?= e::h(msg('email_subject')) ?>: </label></td>
                    <td><input id="customsubject" type="text" name="subject" placeholder="Authorized files" size='45' <?php if (isset($access_mode)): e::h($access_mode); endif; ?>></td>
                </tr>
                <tr>
                    <td style="vertical-align: top;"><label for="customcomment"><?= e::h(msg('email_custom_comment')) ?>: </label></td>
                    <td><textarea id="customcomment" name="comments" cols="45" rows="7" size="220" <?php if (isset($access_mode)): e::h($access_mode); endif; ?>></textarea></td>
                </tr>
                <tr><td><br />&nbsp;&nbsp;</td></tr>
                <tr>
                    <td><label for="allusers"><?= e::h(msg('email_email_all_users')) ?></label></td>
                    <td><input id="allusers" type="checkbox" name="send_to_all" onchange="send_to_dept.disabled = !send_to_dept.disabled; author_note_form.elements['send_to_users[]'].disabled = !author_note_form.elements['send_to_users[]'].disabled;"></td>
                </tr>
                <tr>
                    <td><label for="alldepartment"><?= e::h(msg('email_email_whole_department')) ?></label></td>
                    <td><input id="alldepartment" type="checkbox" name="send_to_dept" onchange="check(this.form.elements['send_to_users[]'], this, send_to_all);"></td>
                </tr>
                <tr>
                    <td style="vertical-align: top;"><label for="customsubject"><?= e::h(msg('email_email_these_users')) ?>: </label></td>
                    <td>
                        <select name="send_to_users[]" multiple onchange="check(this, send_to_dept, send_to_all);">
                            <option value="0">no one</option><!-- need language value -->
                            <option value="owner" selected="selected">file owner</option><!-- need language value -->
<?php foreach ($this->user_info as $user): ?>
                            <option value="<?= $user['id'] ?>"><?= e::h($user['last_name']) ?>, <?= e::h($user['first_name']) ?></option>
<?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
        <br />
        <div class="buttons">
            <button class="positive" type="submit" name="submit" value="<?= e::h($this->submit_value) ?>"><?= e::h($this->submit_value) ?></button>
            <button class="negative" type="submit" name="submit" value="Cancel"><?= e::h(msg('button_cancel')) ?></button>
        </div>
        <br /><br />
    </form>

<script type="text/javascript">
  function check(select, send_dept, send_all)
  {
    if(send_dept.checked || select.options[select.selectedIndex].value != "0")
      send_all.disabled = true;
    else
    {
      send_all.disabled = false;
      for(var i = 1; i < select.options.length; i++)
        select.options[i].selected = false;
    }
  }
</script>
