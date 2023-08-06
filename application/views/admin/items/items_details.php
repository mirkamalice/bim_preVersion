<?php
$edited = can_action('39', 'edited');
$deleted = can_action('39', 'deleted');
?>

<div class="panel panel-custom" id="print_items">
    <header class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                    class="sr-only">Close</span></button>
        
        <button type="button" class="pull-right btn btn-danger btn-xs mr" onclick="print_items('print_items')">
            <i class="fa fa-print"></i>
        </button>
        <?php if (!empty($edited)) { ?>
            <a class="pull-right btn btn-primary btn-xs mr"
               href="<?= base_url('admin/items/new_items/' . $items_info->saved_items_id) ?>"><i
                        class="fa fa-pencil-square-o"></i></a>
        <?php } ?>
        <?= lang('items') . ' ' . lang('details') . ' ' . lang('of') . ' ' . $items_info->item_name ?>
    </header>
    
    <div class="modal-body">
        <?php
        $group = $this->db->where('customer_group_id', $items_info->customer_group_id)->get('tbl_customer_group')->row();
        $currency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
        $manufacturer_info = get_row('tbl_manufacturer', array('manufacturer_id' => $items_info->manufacturer_id));
        
        
        ?>
        <div class="row">
            <div class="col-xs-4">
                <style>
                    .image,
                    .thumb {
                        width: 100%;
                        height: 100%;
                    }

                    #product-image-wrap {
                        position: relative;
                        float: left;
                        width: 300px;
                        height: 325px;
                    }

                    #product-image {
                        position: relative;
                        float: left;
                        width: 300px;
                        height: 250px;
                        border: 1px solid #d0d0d0;
                        padding: 5px;
                        cursor: pointer;
                        display: inline-block;
                        transition: 0.9s;
                    }

                    #product-image:hover {
                        opacity: 0.7;
                    }

                    .product-image-thumbnail {
                        position: relative;
                        float: left;
                        width: 55px;
                        height: 55px;
                        border: 1px solid #d0d0d0;
                        margin-top: 10px;
                        margin-right: 6px;
                        padding: 2px;
                        cursor: pointer;
                        display: inline-block;
                        transition: 0.9s;
                    }

                    .product-image-thumbnail:hover {
                        opacity: 0.7;
                    }

                    .product-image-thumbnail-spacer {
                        position: relative;
                        float: left;
                        width: 10px;
                        height: 130px;
                    }
                </style>
                <div id='product-image-wrap'>
                    
                    <?php
                    if (!empty($items_info->upload_file)) {
                        $uploaded_file = json_decode($items_info->upload_file);
                    }
                    if (!empty($uploaded_file)) {
                        if (!empty($uploaded_file[0]->is_image) && $uploaded_file[0]->is_image == 1) {
                            $imgaeURL = base_url($uploaded_file[0]->path);
                        } else {
                            $imgaeURL = base_url('assets/img/filepreview.jpg');
                        }
                        ?>
                        <!-- Main -->
                        <div id='product-image'><img src='<?= $imgaeURL ?>' id='0' class='image'
                                                     alt='<?= $uploaded_file[0]->fileName ?>'></div>
                        
                        <?php
                        foreach ($uploaded_file as $key => $v_files_image) {
                            if (!empty($v_files_image->is_image) && $v_files_image->is_image == 1) {
                                $imgaeURL = base_url($v_files_image->path);
                            } else {
                                $imgaeURL = base_url('assets/img/filepreview.jpg');
                            }
                            ?>
                            <div class='product-image-thumbnail'><img src='<?php echo $imgaeURL ?>' id='<?= $key + 1 ?>'
                                                                      class='thumb' onclick='preview(this)'
                                                                      alt='<?= $v_files_image->fileName ?>'></div>
                        <?php }
                    } ?>
                </div>
                <script type="text/javascript">
                    var lastImg = 1;
                    document.getElementById(lastImg).className = "thumb selected";

                    function preview(img) {
                        document.getElementById(lastImg).className = "thumb";
                        img.className = "thumb selected";
                        document.getElementById(0).src = img.src;
                        lastImg = img.id;
                    }
                </script>
            </div>
            <div class="col-xs-8">
                <div class="table-responsive">
                    <table class="table table-striped ">
                        <tbody>
                        <tr class="hidden-print">
                            <td class="col-xs-4 text-right"><strong><?= lang('barcode') ?> : </strong></td>
                            <td class="col-xs-8"><?= $barcode ?></td>
                        </tr>
                        <tr>
                            <td class="col-xs-4 text-right"><strong><?= lang('name') ?> : </strong></td>
                            <td class="col-xs-8"><?= $items_info->item_name ?></td>
                        </tr>
                        <tr>
                            <td class="text-right"><strong><?= lang('code') ?> : </strong></td>
                            <td><?= $items_info->code ?></td>
                        </tr>
                        <tr>
                            <td class="text-right"><strong><?= lang('barcode_symbology') ?> : </strong></td>
                            <td><?= $items_info->barcode_symbology ?></td>
                        </tr>
                        <tr>
                            <td class="text-right"><strong><?= lang('group') ?> : </strong></td>
                            <td><?= (!empty($group->customer_group) ? $group->customer_group : '-'); ?></td>
                        </tr>
                        <?php $admin = admin();
                        if (!empty($admin)) { ?>
                            <tr>
                            <td class="text-right"></td>
                            <td class="text-left">
                                <table class="table table-striped ">
                                    <tbody>
                                    <tr style="color:#357EBD ">
                                        <th class="text-center">Warehouse Name</th>
                                        <th class="text-center">Quentity</th>
                                    
                                    
                                    </tr>
                                    <?php
                                    $i = 1;
                                    foreach ($warehouse_info
                                    
                                    as $Warehouse) { ?>
                                    
                                    <tr>
                                        <td>
                                            
                                            
                                            <?= $i ?>.<span style="color:red ;">
                                                        <?= $Warehouse->warehouse_name ?> </span>
                                        </td>
                                        <td>
                                                    <span style="color:blue ;">
                                                        <?= $Warehouse->quantity ?></span>
                                            
                                            
                                            <?php $i++;
                                            } ?>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                            <?php
                        } else if (!empty($staff_warehouse_info)) {
                            ?>
                            ?>
                            <td class=" text-right"> Name: <?= $staff_warehouse_info->warehouse_name ?>;<br>
                                Quntity: <?= $staff_warehouse_info->quantity; ?></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td class="text-right"><strong><?= lang('manufacturer') ?> : </strong></td>
                            <td><?= (!empty($manufacturer_info->manufacturer) ? $manufacturer_info->manufacturer : '-'); ?>
                            </td>
                        </tr>
                        <?php
                        $invoice_view = config_item('invoice_view');
                        if (!empty($invoice_view) && $invoice_view == '2') {
                            ?>
                            <tr>
                                <td class="text-right"><strong><?= lang('hsn_code') ?> : </strong></td>
                                <td><?= $items_info->hsn_code ?></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td class="text-right"><strong><?= lang('cost_price') ?> : </strong></td>
                            <td><?= display_money($items_info->cost_price, $currency->symbol); ?></td>
                        </tr>
                        <tr>
                            <td class="text-right"><strong><?= lang('unit_price') ?> : </strong></td>
                            <td><?= display_money($items_info->unit_cost, $currency->symbol); ?></td>
                        </tr>
                        <tr>
                            <td class="text-right"><strong><?= lang('unit') . ' ' . lang('type') ?> : </strong></td>
                            <td><?= $items_info->unit_type; ?></td>
                        </tr>
                        <tr>
                            <td class="text-right"><strong><?= lang('tax') ?> : </strong></td>
                            <td>
                                <?php
                                if (!is_numeric($items_info->tax_rates_id)) {
                                    $tax_rates = json_decode($items_info->tax_rates_id);
                                } else {
                                    $tax_rates = null;
                                }
                                if (!empty($tax_rates)) {
                                    foreach ($tax_rates as $key => $tax_id) {
                                        $taxes_info = $this->db->where('tax_rates_id', $tax_id)->get('tbl_tax_rates')->row();
                                        if (!empty($taxes_info)) {
                                            echo $key + 1 . '. ' . $taxes_info->tax_rate_name . '&nbsp;&nbsp; (' . $taxes_info->tax_rate_percent . '% ) <br>';
                                        }
                                    }
                                }
                                ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php if (!empty($items_info->item_desc)) { ?>
                <div class="col-xs-12 mt-lg">
                    <div class="panel panel-custom">
                        <div class="panel-heading"><?= lang('description') ?></div>
                        <div class="panel-body">
                            <?= strip_html_tags($items_info->item_desc) ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    
    </div>
</div>

<script type="text/javascript">
    function print_items(print_items) {
        var printContents = document.getElementById(print_items).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>