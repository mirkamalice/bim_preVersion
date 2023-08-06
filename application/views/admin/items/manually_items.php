<div class="panel panel-custom">
    <header class="panel-heading ">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                    class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><strong><?= lang('new_items') ?></strong></h4>
    </header>
    <form role="form" id="manuallyItemForm" data-parsley-validate="" novalidate="" enctype="multipart/form-data"
          method="post" class="form-horizontal row">
        <div class="col-sm-7">
            <div class="form-group">
                <label class="col-lg-3 control-label"><?= lang('item_name') ?> <span
                            class="text-danger">*</span></label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" value="" name="item_name" required="">
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label"><?= lang('description') ?></label>
                <div class="col-lg-9">
                    <textarea class="form-control" name="item_desc"></textarea>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-lg-3 col-md-3 col-sm-3 control-label"><?= lang('qty') ?> <span
                            class="text-danger">*</span></label>
                <div class="col-lg-9">
                    <input type="text" data-parsley-type="number" class="form-control" value="1" name="qty" required="">
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 col-md-3 col-sm-3 control-label"><?= lang('price') ?> <span
                            class="text-danger">*</span></label>
                <div class="col-lg-9">
                    <input type="text" data-parsley-type="number" class="form-control" value="" name="rate" required="">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-lg-3 control-label"><?= lang('unit') . ' ' . lang('type') ?></label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" value="" placeholder="<?= lang('unit_type_example') ?>"
                           name="unit">
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label"><?= lang('tax') ?></label>
                <div class="col-lg-9">
                    <?php
                    $taxes = $this->db->order_by('tax_rate_percent', 'ASC')->get('tbl_tax_rates')->result();
                    if (!empty($items_info->tax_rates_id) && !is_numeric($items_info->tax_rates_id)) {
                        $tax_rates_id = json_decode($items_info->tax_rates_id);
                    }
                    $select = '<select class="selectpicker" data-width="100%" name="tax_rates_id[]" multiple data-none-selected-text="' . lang('no_tax') . '">';
                    foreach ($taxes as $tax) {
                        $selected = '';
                        if (!empty($tax_rates_id) && is_array($tax_rates_id)) {
                            if (in_array($tax->tax_rates_id, $tax_rates_id)) {
                                $selected = ' selected ';
                            }
                        }
                        $select .= '<option value="' . $tax->tax_rates_id . '"' . $selected . 'data-taxrate="' . $tax->tax_rate_percent . '" data-taxname="' . $tax->tax_rate_name . '" data-subtext="' . $tax->tax_rate_name . '">' . $tax->tax_rate_percent . '%</option>';
                    }
                    $select .= '</select>';
                    echo $select;
                    ?>
                </div>
            </div>
            <div class="form-group mt-lg">
                <label class="col-lg-3 control-label"></label>
                <div class="col-lg-9">
                    <div class="btn-bottom-toolbar">
                        <?php
                        if (!empty($items_info)) { ?>
                            <button type="submit" class="btn btn-sm btn-primary"><?= lang('updates') ?></button>
                            <button type="button" onclick="goBack()"
                                    class="btn btn-sm btn-danger"><?= lang('cancel') ?></button>
                        <?php } else {
                            ?>
                            
                            <button type="submit" id=""
                                    class="btn btn-sm btn-primary itemManualy"><?= lang('added') . ' ' . lang('manually') ?></button>
                            <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal">Close
                            </button>
                        <?php }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>