<?php echo message_box('success'); ?>
<?php echo message_box('error');
$created = can_action('81', 'created');
$edited = can_action('81', 'edited');
$deleted = can_action('81', 'deleted');
if (!empty($created) || !empty($edited)) {
?>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs" style="margin-top: 1px">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs">
            <li class="<?= $active == 1 ? 'active' : '' ?>"><a
                    href="<?= base_url('admin/stock/stock_list') ?>"><?= lang('all') . ' ' . lang('stock') ?></a>
            </li>
            <li class="<?= $active == 2 ? 'active' : '' ?>"><a
                    href="<?= base_url('admin/stock/create_stocklist') ?>"><?= lang('new') . ' ' . lang('stock') ?></a>
            </li>
        </ul>
        <div class="tab-content bg-white">
            <div class="tab-pane <?= $active == 1 ? 'active' : '' ?>" id="task_list" style="position: relative;">
                <?php } else { ?>
                <div class="panel panel-custom">
                    <header class="panel-heading ">
                        <div class="panel-title"><strong><?= lang('all') . ' ' . lang('stock') ?></strong></div>
                    </header>
                    <?php } ?>
                    <div class="row">
                        <?php $key = 0 ?>
                        <?php if (!empty($all_stock_info)) : ?>
                        <?php foreach ($all_stock_info as $category => $v_stock_info) :
                                if (!empty($category)) {
                                    $category = $category;
                                } else {
                                    $category = lang('undefined_category');
                                }
                            ?>
                        <div class="col-sm-6">
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                                <?php if (!empty($v_stock_info)) : ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingOne">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" class="collapsed" data-parent="#accordion"
                                                href="#<?php echo $key ?>" aria-expanded="false"
                                                aria-controls="collapseOne">
                                                <i class="fa fa-plus"> </i> <?php echo $category; ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="<?php echo $key ?>" class="panel-collapse collapse" role="tabpanel"
                                        aria-labelledby="headingOne">
                                        <?php foreach ($v_stock_info as $sub_category => $v_stock) : ?>
                                        <div class="panel-body">
                                            <table class="table table-bordered" style="margin-bottom: 0px;">
                                                <thead>
                                                    <tr>
                                                        <th colspan="3"
                                                            style="background-color: #E3E5E6;color: #000000 ">
                                                            <strong><?php echo $sub_category; ?></strong>
                                                        </th>
                                                    </tr>
                                                    <tr style="font-size: 13px;color: #000000">
                                                        <th><?= lang('item_name') ?></th>
                                                        <th><?= lang('total_stock') ?></th>
                                                        <?php if (!empty($deleted) || !empty($edited)) { ?>
                                                        <th class="col-sm-2"><?= lang('action') ?></th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody style="margin-bottom: 0px;background: #FFFFFF;font-size: 12px;">
                                                    <?php foreach ($v_stock as $stock) : ?>
                                                    <tr>
                                                        <td><?php echo $stock->item_name; ?></td>
                                                        <td><?php echo $stock->total_stock ?></td>
                                                        <?php if (!empty($deleted) || !empty($edited)) { ?>
                                                        <td>
                                                            <?php if (!empty($edited)) { ?>
                                                            <?php echo btn_edit('admin/stock/create_stocklist/' . $stock->stock_id); ?>
                                                            <?php }
                                                                                    if (!empty($deleted)) { ?>
                                                            <?php echo btn_delete('admin/stock/delete_stock/' . $stock->stock_id); ?>
                                                            <?php } ?>
                                                        </td>
                                                        <?php } ?>
                                                    </tr>
                                                    <?php endforeach; ?>

                                                </tbody>
                                            </table>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php $key++; ?>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

    </ul>
</div>
<link href="<?php echo base_url() ?>assets/plugins/typehead/typehead.css" rel="stylesheet" />
<script src="<?php echo base_url() ?>assets/plugins/typehead/typehead.js"></script>

<?php $all_stock = $this->db->get('tbl_stock')->result(); ?>
<script type="text/javascript">
$('#query').typeahead({
    local: [<?php if (!empty($all_stock)) {
                        foreach ($all_stock as $v_stock) { ?> "<?= $v_stock->item_name ?>", <?php }
                                                                                    } ?>]
});
// $('.tt-query').css('background-color', '#fff');
</script>