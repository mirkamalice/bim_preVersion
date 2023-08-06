<style type="text/css" media="print">
    @page {
        margin-top: 10px;
        margin-bottom: 10px;
        size: auto;
    }

    .content-heading {
        display: none !important;;
    }

    body {
        padding-top: 72px;
        padding-bottom: 72px;
    }

    a[href]:after {
        content: none !important;
    }
</style>
<div class="panel panel-custom">
    <div class="panel-heading hidden-print">
        <button type="button" class="pull-right btn btn-danger btn-xs mr" href="javascript:void();"
                onclick="window.print();">
            <i class="fa fa-print"></i>
        </button>
        <h4 class="panel-title" id="myModalLabel">
            <?= $title; ?>
        </h4>
    </div>
    <div class="modal-body" id="printableArea">
        <?= $html ?>
    </div>
    <div class="modal-footer no-print">
        <a href="<?= base_url('admin/items/items_list')?>" class="btn btn-default pull-left"><?= lang('close'); ?></a>
        <button class="btn btn-danger" href="javascript:void();" onclick="window.print();"><i
                class="fa fa-print"></i> <?= lang('print'); ?></button>
    </div>
</div>
