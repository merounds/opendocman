<?php use Aura\Html\Escaper as e; ?>
<?php if ($GLOBALS['CONFIG']['demo'] == 'True'): ?>
<script type="text/javascript"><!--
  google_ad_client = "ca-pub-3696425351841264";
  /* 728x90_ODM_Demo */
  google_ad_slot = "8419809005";
  google_ad_width = 728;
  google_ad_height = 90;
  //-->
</script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<?php endif; ?>
        <br style="clear: both;" />
    </div> <!-- /content -->
    <hr />

    <footer>
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Default footer</code><br />
        <a href="mailto:<?= e::h($GLOBALS['CONFIG']['site_mail']) ?>"><?= e::h($GLOBALS['CONFIG']['title']) ?></a><p />
        <a href="http://www.opendocman.com/" target="_blank">OpenDocMan</a><br />
        Copyright &copy; 2000-2015 Stephen Lawrence Jr.<br />
        <p>
            <a href="http://www.opendocman.com/forum/" target="_blank"><?= e::h(msg('footer_support')) ?></a> |
            <a href="http://opendocman.uservoice.com" target="_blank"><?= e::h(msg('footer_feedback')) ?></a> |
            <a href="https://github.com/opendocman/opendocman/issues" target="_blank"><?= e::h(msg('footer_bugs')) ?></a>
        </p>
    </footer>
</body>
</html>
