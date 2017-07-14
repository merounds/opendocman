<?php use Aura\Html\Escaper as e; ?>
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Common details</code><br />
        <form name="data">
            <input type="hidden" name="to" value="<?= e::h($this->file_detail['to_value']); ?>" />
            <input type="hidden" name="subject" value="<?= e::h($this->file_detail['subject_value']); ?>" />
            <input type="hidden" name="comments" value="<?= e::h($this->file_detail['comments_value']); ?>" />
        </form>
        <table id="filedetails" width="100%">
            <tbody>
                <tr>
                    <th>
<?php if ($this->file_detail['file_unlocked']): ?>
                        <img src="images/file_unlocked.png" width="16" height="16" alt="icon" title="<?= e::h(msg('accesslogpage_file_checked_in')); ?>" />
<?php else: ?>
                        <img src="images/file_locked.png" width="16" height="16" alt="icon" title="<?= e::h(msg('accesslogpage_file_checked_out')); ?>" />
<?php endif; ?>
                    </th>
                    <td>
                        <span style="font-size: larger;"><?= e::h($this->file_detail['realname']); ?></span>
                    </td>
                </tr>
                <tr>
                    <th><?= e::h(msg('category')); ?>:</th>
                    <td><?= e::h($this->file_detail['category']); ?></td>
                </tr>
<?= $this->file_detail['udf_details_display']; ?>
                <tr>
                    <th><?= e::h(msg('label_size')); ?>:</th>
                    <td><?= e::h($this->file_detail['filesize']); ?></td>
                </tr>
                <tr>
                    <th><?= e::h(msg('label_created_date')); ?>:</th>
                    <td><?= e::h($this->file_detail['created']); ?></td>
                </tr>
                <tr>
                    <th><?= e::h(msg('owner')); ?>:</th>
                    <td><a href="mailto:<?= e::h($this->file_detail['owner_email']); ?>?Subject=Regarding%20your%20document:%20<?= e::h($this->file_detail['realname']); ?>&Body=Hello%20<?= e::h($this->file_detail['owner_fullname']); ?>"><?= e::h($this->file_detail['owner']); ?></a></td>
                </tr>
                <tr>
                    <th><?= e::h(msg('label_description')); ?>:</th>
                    <td><?= e::h($this->file_detail['description']); ?></td>
                </tr>
                <tr>
                    <th><?= e::h(msg('label_comment')); ?>:</th>
                    <td><?= e::h($this->file_detail['comment']); ?></td>
                </tr>
                <tr>
                    <th><?= e::h(msg('revision')); ?>:</th>
                    <td><span id="details_revision"><?= e::h($this->file_detail['revision']); ?></span></td>
                </tr>
<?php if ($this->file_detail['file_under_review']): ?>
                <tr>
                    <th><?= e::h(msg('label_reviewer')); ?>:</th>
                    <td><?= e::h($this->file_detail['reviewer']); ?> (<a href='javascript:showMessage()'><?= e::h(msg('message_reviewers_comments_re_rejection')); ?></a>)</td>
                </tr>
<?php endif; ?>

<?php if ($this->file_detail['status'] > 0): ?>
                <tr>
                    <th><?= e::h(msg('detailspage_file_checked_out_to')); ?>:</th>
                    <td><a href="mailto:<?= e::h($checkout_person_email); ?>?Subject=Regarding%20your%20checked-out%20document:%20<?= e::h($this->file_detail['realname']); ?>&Body=Hello%20<?= e::h($checkout_person_full_name.$fullname[0]); ?>"> <?= e::h($checkout_person_full_name[1]); ?>, <?= e::h($checkout_person_full_name[0]); ?></a></td>
                </tr>
<?php endif; ?>
            </tbody>
        </table>

<!-- detail available actions -->
<div id="fileactions" width="100%">
    <!-- no links will be generated from history.php, so no content -->
<?php if ($this->view_link != ''): ?>
    <span class="buttons">
        <a class="positive" href="<?= e::h($this->view_link) ?>"><img src="images/view.png" width="16" height="16" alt="view" /><?= e::h(msg('detailspage_view')); ?></a>
    </span>
<?php endif; ?>
<?php if ($this->check_out_link != ''): ?>
    <span class="buttons">
        <a class="regular" href="<?= e::h($this->check_out_link); ?>"><img src="images/check-out.png" width="16" height="16" alt="check out" /><?= e::h(msg('detailspage_check_out')); ?></a>
    </span>
<?php endif; ?>
<?php if ($this->edit_link != ''): ?>
    <span class="buttons">
        <a class="regular" href="<?= e::h($this->edit_link); ?>"><img src="images/edit.png" width="16" height="16" alt="edit" /><?= e::h(msg('detailspage_edit')); ?></a>
    </span>
    <span class="buttons">
        <a class="negative" href="javascript:my_delete()"><img src="images/delete.png" width="16" height="16" alt="delete" /><?= e::h(msg('detailspage_delete')); ?></a>
    </span>
<?php endif; ?>
<?php if ($this->history_link != ''): ?>
    <span class="buttons">
        <a class="regular" href="<?= e::h($this->history_link); ?>"><img src="images/history.png" width="16" height="16" alt="history" /><?= e::h(msg('detailspage_history')); ?></a>
    </span>
<?php endif; ?>
</div>

<script type="text/javascript">
  var message_window;
  var mesg_window_frm;
  function my_delete()
  {
    if(window.confirm("<?= e::h(msg('detailspage_are_sure')); ?>")) {
      window.location = "<?= e::h($this->my_delete_link); ?>";
    }
  }
  function sendFields()
  {
    mesg_window_frm = message_window.document.author_note_form;
    if(mesg_window_frm) {
      mesg_window_frm.to.value = document.data.to.value;
      mesg_window_frm.subject.value = document.data.subject.value;
      mesg_window_frm.comments.value = document.data.comments.value;
    }
  }
  function showMessage()
  {
    message_window = window.open('<?= e::h($this->comments_link); ?>' , 'comment_wins', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=no,width=450,height=200');
    message_window.focus();
    setTimeout("sendFields();", 500);
  }
</script>
