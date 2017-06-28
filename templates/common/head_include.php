<?php use Aura\Html\Escaper as e; ?>
    <!-- Always include this file inside the <head></head> of your header.php file -->
    <link rel="stylesheet" type="text/css" href="<?= $this->base_url ?>/templates/common/css/system.css" />
    <link rel="stylesheet" type="text/css" href="<?= $this->base_url ?>/includes/DataTables/media/css/demo_table.css" />

    <link rel="stylesheet" type="text/css" href="<?= $this->base_url ?>/templates/common/multiSelect112/jquery-ui-1.8.18.custom.css" />
    <link rel="stylesheet" type="text/css" href="<?= $this->base_url ?>/templates/common/multiSelect112/smoothness/jquery-ui-1.8.18.custom.css" />

    <script type="text/javascript" src="<?= $this->base_url ?>/includes/jquery.min.js"></script>
    <script type="text/javascript" src="<?= $this->base_url ?>/includes/DataTables/media/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="<?= $this->base_url ?>/templates/common/multiSelect112/jquery-ui-1.8.18.custom.min.js"></script>
    <script type="text/javascript" src="<?= $this->base_url ?>/includes/jquery.validate.min.js"></script>
    <script type="text/javascript" src="<?= $this->base_url ?>/includes/additional-methods.min.js"></script>

    <link rel="stylesheet" type="text/css" href="<?= $this->base_url ?>/templates/common/multiSelect112/jquery.multiselect.css" />
    <link rel="stylesheet" type="text/css" href="<?= $this->base_url ?>/templates/common/multiSelect112/jquery.multiselect.filter.css" />
    <link rel="stylesheet" type="text/css" href="<?= $this->base_url ?>/templates/common/multiSelect112/jquery.multiselect.css" />
    <script type="text/javascript" src="<?= $this->base_url ?>/templates/common/multiSelect112/jquery.multiselect.js"></script>
    <script type="text/javascript" src="<?= $this->base_url ?>/templates/common/multiSelect112/jquery.multiselect.filter.js"></script>

    <script type="text/javascript" src="<?= $this->base_url ?>/includes/default.js"></script>
    <script>
        // Here are the translations for the multiselect area of this page
        var langUncheckAll = '<?= e::h(msg('editpage_uncheck_all')) ?>';
        var langCheckAll = '<?= e::h(msg('editpage_check_all')) ?>';
        var langOf = '<?= e::h(msg('editpage_of')) ?>';
        var langSelected = '<?= e::h(msg('editpage_selected')) ?>';
        var langLanguage = '<?= e::h($GLOBALS['CONFIG']['language']) ?>';
        var langNoneSelected = '<?= e::h(msg('editpage_none_selected')) ?>';
    </script>
    <!--[if lt IE 9]>
        <script type="text/javascript" src="<?= $this->base_url ?>/templates/common/js/buttonfix.js"></script>
    <![endif]-->