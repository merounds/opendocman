<?php use Aura\Html\Escaper as e; ?>
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Common _filePermissions</code><br />
<style type="text/css">
.accordion {
   margin: 10px;
   dt, dd {
      padding: 10px;
      border: 1px solid black;
      border-bottom: 0;
      &:last-of-type {
        border-bottom: 1px solid black;
      }
      a {
        display: block;
        color: black;
        font-weight: bold;
      }
   }
  dd {
     border-top: 0;
     font-size: 12px;
     &:last-of-type {
       border-top: 1px solid white;
       position: relative;
       top: -1px;
     }
  }
}
</style>

<dl class="accordion">
    <dt><a href=""><?= e::h(msg('filepermissionspage_edit_department_permissions')) ?>...</a></dt>
    <dd>
        <table id="department_permissions_table" class="display">
            <thead>
                <tr>
                    <th><?= e::h(msg('label_department')) ?></th>
                    <th><?= e::h(msg('addpage_forbidden')) ?></th>
                    <th><?= e::h(msg('addpage_none')) ?></th>
                    <th><?= e::h(msg('addpage_view')) ?></th>
                    <th><?= e::h(msg('addpage_read')) ?></th>
                    <th><?= e::h(msg('addpage_write')) ?></th>
                    <th><?= e::h(msg('addpage_admin')) ?></th>
                </tr>
            </thead>
            <tbody>
<!-- avail_depts rows do not have a rights key -->
<!-- avail_depts rows do not have a selected key -->
<?php foreach ($this->avail_depts as $dept): ?>
<?php
  if (!isset($dept['rights'])) { $dept['rights'] = ''; }
  if (!isset($dept['selected'])) { $dept['selected'] = ''; }
  if ($dept['selected'] == 'selected'):
    $selected = 'checked="checked"';
    $notselected = '';
  else:
    $selected = '';
    $notselected = 'checked="checked"';
  endif;
?>
                <tr>
                    <td><?= e::h($dept['name']) ?></td>
                    <td><input type="radio" name="department_permission[<?= e::h($dept['id']) ?>]" value="-1" <?php if ($dept['rights'] == '-1') echo 'checked="checked"' ?> /></td>
                    <td><input type="radio" name="department_permission[<?= e::h($dept['id']) ?>]" value="0" <?php if ($dept['rights'] == '0') echo 'checked="checked"' ?> <?= $notselected ?> /></td>
                    <td><input type="radio" name="department_permission[<?= e::h($dept['id']) ?>]" value="1" <?php if ($dept['rights'] == '1') echo 'checked="checked"' ?> <?= $selected ?> /></td>
                    <td><input type="radio" name="department_permission[<?= e::h($dept['id']) ?>]" value="2" <?php if ($dept['rights'] == '2') echo 'checked="checked"' ?> /></td>
                    <td><input type="radio" name="department_permission[<?= e::h($dept['id']) ?>]" value="3" <?php if ($dept['rights'] == '3') echo 'checked="checked"' ?> /></td>
                    <td><input type="radio" name="department_permission[<?= e::h($dept['id']) ?>]" value="4" <?php if ($dept['rights'] == '4') echo 'checked="checked"' ?> /></td>
                </tr>
<?php endforeach; ?>
            </tbody>
        </table>
    </dd>
    <hr style="width:60%; margin: 0.5em 0; clear:both;" />
    <dt><a href=""><?= e::h(msg('filepermissionspage_edit_user_permissions')) ?>...</a></dt>
    <dd>
        <table id="user_permissions_table" class="display">
            <thead>
                <tr>
                    <th><?= e::h(msg('label_user')) ?></th>
                    <th><?= e::h(msg('addpage_forbidden')) ?></th>
                    <th><?= e::h(msg('addpage_view')) ?></th>
                    <th><?= e::h(msg('addpage_read')) ?></th>
                    <th><?= e::h(msg('addpage_write')) ?></th>
                    <th><?= e::h(msg('addpage_admin')) ?></th>
                </tr>
            </thead>
            <tbody>
<!-- avail_users rows do not have a rights key -->
<?php foreach ($this->avail_users as $user): ?>
<?php
  if (!isset($user['rights'])) { $user['rights'] = ''; }
  if ($user['rights'] == ''):
    $selected = 'checked="checked"';
  endif;
  // $selected is never used...
?>
                <tr>
                    <td><?= e::h($user['last_name']) ?>, <?= e::h($user['first_name']) ?></td>
                    <td><input type="radio" name="user_permission[<?= e::h($user['id']) ?>]" value="-1" <?php if ($user['rights'] == '-1') echo 'checked="checked"' ?> /></td>
                    <td><input type="radio" name="user_permission[<?= e::h($user['id']) ?>]" value="1" <?php if ($user['rights'] == '1') echo 'checked="checked"' ?> /></td>
                    <td><input type="radio" name="user_permission[<?= e::h($user['id']) ?>]" value="2" <?php if ($user['rights'] == '2') echo 'checked="checked"' ?> /></td>
                    <td><input type="radio" name="user_permission[<?= e::h($user['id']) ?>]" value="3" <?php if ($user['rights'] == '3') echo 'checked="checked"' ?> /></td>
                    <td><input type="radio" name="user_permission[<?= e::h($user['id']) ?>]" value="4" <?php if ($user['rights'] == '4' || ($user['id'] == $this->user_id && $user['rights'] == '')) echo 'checked="checked"' ?> /></td>
                </tr>
<?php endforeach; ?>
            </tbody>
        </table>
    </dd>
</dl>

<script type="text/javascript">
  $(document).ready(function() {

    (function($) {
      var allPanels = $('.accordion > dd').hide();
      $('.accordion > dt > a').click(function() {
//          allPanels.slideUp();
          $('.accordion > dd').not($(this).parent().next()).slideUp();
          $(this).parent().next().slideToggle();
          return false;
      });
    })(jQuery);

    $department_permissions_table = $('#department_permissions_table');
    if ($department_permissions_table && $department_permissions_table.length > 0) {
      var oTable = $department_permissions_table.dataTable({
        "sScrollY": "300px",
        "bScrollCollapse": true,
        "bPaginate": false,
        "bAutoWidth": false,
        "oLanguage": {
          "sUrl": "includes/language/DataTables/datatables." + langLanguage + ".txt"
        }
      });
    }

    $user_permissions_table = $('#user_permissions_table');
    if ($user_permissions_table && $user_permissions_table.length > 0) {
      var oTable2 = $user_permissions_table.dataTable({
        "sScrollY": "300px",
        "bScrollCollapse": true,
        "bPaginate": false,
        "bAutoWidth": false,
        "oLanguage": {
          "sUrl": "includes/language/DataTables/datatables." + langLanguage + ".txt"
        }
      });
    }

  });
</script>
