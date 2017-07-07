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
        <hr />

        <footer>
<code>theme: <?= $GLOBALS['CONFIG']['theme'] ?></code><br />
<code>Tweeter footer</code><br />
            <a href="http://www.opendocman.com/">
                <img src="<?= e::h($GLOBALS['CONFIG']['base_url']) ?>/images/logo.gif" title="<?= e::h($GLOBALS['CONFIG']['title']) ?>" alt="<?= e::h($GLOBALS['CONFIG']['title']) ?>">
            </a>
            <p>Copyright &copy; 2000-2015 Stephen Lawrence</p>
            <p><a href="http://www.opendocman.com/" target="_blank">OpenDocMan</a> |
                <a href="http://www.opendocman.com/forum/" target="_blank"><?= e::h(msg('footer_support')) ?></a> |
                <a href="http://opendocman.uservoice.com" target="_blank"><?= e::h(msg('footer_feedback')) ?></a> |
                <a href="https://github.com/opendocman/opendocman/issues" target="_blank"><?= e::h(msg('footer_bugs')) ?></a>
            </p>
        </footer>
    </div> <!-- /container -->
    <script type="text/javascript" src="<?= e::h($GLOBALS['CONFIG']['base_url']) ?>/templates/tweeter/js/bootstrap.min.js"></script>
</body>
</html>
