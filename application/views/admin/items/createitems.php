<?= message_box('success'); ?>
<?= message_box('error');
$created = can_action('39', 'created');
$edited = can_action('39', 'edited');
$deleted = can_action('39', 'deleted');
if (!empty($created) || !empty($edited)) {
?>
<div class="nav-tabs-custom">

    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        
        <li class=""><a href="<?= base_url('admin/items/items_list') ?>"><?= lang('all_items') ?></a>
        </li>
        <li class="active"><a href="<?= base_url('admin/items/new_items') ?>"><?= lang('new_items') ?></a>
        </li>
        <li class=""><a
                    href="<?= base_url('admin/items/newitems_group') ?>"><?= lang('group') . ' ' . lang('list') ?></a>
        </li>
        
        
        <li class=""><a
                    href="<?= base_url('admin/items/items_manufacturerlist') ?>"><?= lang('manufacturer') . ' ' . lang('list') ?></a>
        </li>
        <li><a class="import" href="<?= base_url() ?>admin/items/import"><?= lang('import') . ' ' . lang('items') ?></a>
        </li>
    </ul>
    <style type="text/css">
        .custom-bulk-button {
            display: initial;
        }
    </style>
    <div class="tab-content bg-white">
        <!-- ************** general *************-->
        
        
        <div class="tab-pane active" id="create">
            <form role="form" data-parsley-validate="" novalidate="" enctype="multipart/form-data" id="form"
                  action="<?php echo base_url(); ?>admin/items/saved_items/<?php
                  if (!empty($items_info)) {
                      echo $items_info->saved_items_id;
                  }
                  ?>" method="post" class="form-horizontal row ">
                <div class="col-sm-7">
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('item_name') ?> <span
                                    class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" value="<?php
                            if (!empty($items_info)) {
                                echo $items_info->item_name;
                            }
                            ?>" name="item_name" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-md-3 col-sm-3 control-label"><?= lang('code') ?> <span
                                    class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" value="<?php
                            if (!empty($items_info)) {
                                echo $items_info->code;
                            }
                            ?>" name="code" required="" placeholder="<?= lang('item_code_help') ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-3 col-md-3 col-sm-3 control-label"><?= lang('barcode_symbology') ?>
                            <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <?php
                            if (!empty($items_info->barcode_symbology)) {
                                $barcode_symbology = $items_info->barcode_symbology;
                            } else {
                                $barcode_symbology = null;
                            }
                            $bs = array('code25' => 'Code25', 'code39' => 'Code39', 'code128' => 'Code128', 'ean8' => 'EAN8', 'ean13' => 'EAN13', 'upca ' => 'UPC-A', 'upce' => 'UPC-E');
                            echo form_dropdown('barcode_symbology', $bs, set_value('barcode_symbology', $barcode_symbology), 'class="form-control select2" id="barcode_symbology" required="required" style="width:100%;"');
                            ?>
                        </div>
                    </div>
                    <?php
                    $invoice_view = config_item('invoice_view');
                    if (!empty($invoice_view) && $invoice_view == '2') {
                        ?>
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('hsn_code') ?></label>
                            <div class="col-lg-9">
                                <input type="text" data-parsley-type="number" class="form-control" value="<?php
                                if (!empty($items_info)) {
                                    echo $items_info->hsn_code;
                                }
                                ?>" name="hsn_code" required="">
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="field-1"
                               class="col-sm-3 col-md-3 col-sm-3 control-label"><?= lang('manufacturer') ?></label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <?php
                                $selected = (!empty($items_info->manufacturer_id) ? $items_info->manufacturer_id : '');
                                echo form_dropdown('manufacturer_id', $all_manufacturer, $selected, array('class' => 'form-control select_box', 'style' => 'width:100%'));
                                ?>
                                <div class="input-group-addon" title="<?= lang('new') . ' ' . lang('manufacturer') ?>"
                                     data-toggle="tooltip" data-placement="top">
                                    <a data-toggle="modal" data-target="#myModal"
                                       href="<?= base_url() ?>admin/items/manufacturer"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $warehouse = true;
                    if (!empty($items_info)) {
                        $warehouse = false;
                        $check_assign_warehouse = get_any_field('tbl_warehouses_products', array('product_id' => $items_info->saved_items_id), 'warehouse_id');
                        if (empty($check_assign_warehouse)) {
                            $warehouse = true;
                        }
                    }
                    if (!empty($warehouse)) { ?>
                        <div class="form-group">
                            <label for="field-1"
                                   class="col-sm-3 col-md-3 col-sm-3 control-label"><?= lang('warehouse') ?><span
                                        class="text-danger">*</span></label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <?php
                                    $selected = (!empty($items_info->warehouse_id) ? $items_info->warehouse_id : '');
                                    echo form_dropdown('warehouse_id', $warehouseList, $selected, array('class' => 'form-control select_box required="required"', 'style' => 'width:100%'));
                                    ?>
                                    <div class="input-group-addon" title="<?= lang('new') . ' ' . lang('warehouse') ?>"
                                         data-toggle="tooltip" data-placement="top">
                                        <a data-toggle="modal" data-target="#myModal_lg"
                                           href="<?= base_url() ?>admin/warehouse/create/0/inline"><i
                                                    class="fa fa-plus"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label class="col-lg-3 col-md-3 col-sm-3 control-label"><?= lang('cost_price') ?> <span
                                    class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input type="number" data-parsley-type="number" step="0.01" class="form-control"
                                   value="<?php
                                   if (!empty($items_info->cost_price)) {
                                       echo $items_info->cost_price;
                                   }
                                   ?>" name="cost_price" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('unit_price') ?> <span
                                    class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input type="number" data-parsley-type="number" step="0.01" class="form-control"
                                   value="<?php
                                   if (!empty($items_info)) {
                                       echo $items_info->unit_cost;
                                   }
                                   ?>" name="unit_cost" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('unit') . ' ' . lang('type') ?></label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" value="<?php
                            if (!empty($items_info)) {
                                echo $items_info->unit_type;
                            }
                            ?>" placeholder="<?= lang('unit_type_example') ?>" name="unit_type">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('quantity') ?> <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input type="number" data-parsley-type="number" step="0.01" class="form-control"
                                   value="<?php
                                   if (!empty($items_info)) {
                                       echo $items_info->quantity;
                                   }
                                   ?>" name="quantity" required="">
                        </div>
                    
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('item') . ' ' . lang('group') ?> </label>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <?php
                                $selected = (!empty($items_info->customer_group_id) ? $items_info->customer_group_id : '');
                                echo form_dropdown('customer_group_id', $all_customer_group, $selected, array('class' => 'form-control select_box', 'style' => 'width:100%'));
                                ?>
                                <div class="input-group-addon"
                                     title="<?= lang('new') . ' ' . lang('item') . ' ' . lang('group') ?>"
                                     data-toggle="tooltip" data-placement="top">
                                    <a data-toggle="modal" data-target="#myModal"
                                       href="<?= base_url() ?>admin/items/items_group"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
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
                    <?php
                    if (!empty($items_info)) {
                        $saved_items_id = $items_info->saved_items_id;
                    } else {
                        $saved_items_id = null;
                    }
                    ?>
                    <?= custom_form_Fields(18, $saved_items_id); ?>
                    
                    
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
                                    <button type="submit" class="btn btn-sm btn-primary"><?= lang('save') ?></button>
                                <?php }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 pull-right">
                    <div class="panel panel-custom">
                        <div class="panel-heading">
                            <?= lang('image') ?> </div>
                        <div class="panel-body">
                            
                            <div id="comments_file-dropzone" class="dropzone mb15">
                            
                            </div>
                            <div id="comments_file-dropzone-scrollbar">
                                <div id="comments_file-previews">
                                    <div id="file-upload-row" class="mt pull-left">
                                        
                                        <div class="preview box-content pr-lg" style="width:100px;">
                                                <span data-dz-remove class="pull-right" style="cursor: pointer">
                                                    <i class="fa fa-times"></i>
                                                </span>
                                            <img data-dz-thumbnail class="upload-thumbnail-sm"/>
                                            <input class="file-count-field" type="hidden" name="files[]" value=""/>
                                            <div class="mb progress progress-striped upload-progress-sm active mt-sm"
                                                 role="progressbar" aria-valuemin="0" aria-valuemax="100"
                                                 aria-valuenow="0">
                                                <div class="progress-bar progress-bar-success" style="width:0%;"
                                                     data-dz-uploadprogress></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            if (!empty($items_info->upload_file)) {
                                $uploaded_file = json_decode($items_info->upload_file);
                            }
                            if (!empty($uploaded_file)) {
                                foreach ($uploaded_file as $v_files_image) { ?>
                                    <div class="pull-left mt pr-lg mb" style="width:100px;">
                                        <span data-dz-remove class="pull-right existing_image"
                                              style="cursor: pointer"><i class="fa fa-times"></i></span>
                                        <?php if ($v_files_image->is_image == 1) { ?>
                                            <img data-dz-thumbnail src="<?php echo base_url() . $v_files_image->path ?>"
                                                 class="upload-thumbnail-sm"/>
                                        <?php } else { ?>
                                            <span data-toggle="tooltip" data-placement="top"
                                                  title="<?= $v_files_image->fileName ?>"
                                                  class="mailbox-attachment-icon"><i
                                                        class="fa fa-file-text-o"></i></span>
                                        <?php } ?>
                                        
                                        <input type="hidden" name="path[]" value="<?php echo $v_files_image->path ?>">
                                        <input type="hidden" name="fileName[]"
                                               value="<?php echo $v_files_image->fileName ?>">
                                        <input type="hidden" name="fullPath[]"
                                               value="<?php echo $v_files_image->fullPath ?>">
                                        <input type="hidden" name="size[]" value="<?php echo $v_files_image->size ?>">
                                        <input type="hidden" name="is_image[]"
                                               value="<?php echo $v_files_image->is_image ?>">
                                    </div>
                                <?php }; ?>
                            <?php }; ?>
                            <script type="text/javascript">
                                $(document).ready(function () {
                                    $(".existing_image").on("click", function () {
                                        $(this).parent().remove();
                                    });

                                    fileSerial = 0;
                                    // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
                                    var previewNode = document.querySelector("#file-upload-row");
                                    previewNode.id = "";
                                    var previewTemplate = previewNode.parentNode.innerHTML;
                                    previewNode.parentNode.removeChild(previewNode);
                                    Dropzone.autoDiscover = false;
                                    var projectFilesDropzone = new Dropzone("#comments_file-dropzone", {
                                        url: "<?= base_url() ?>admin/common/upload_file",
                                        thumbnailWidth: 80,
                                        thumbnailHeight: 80,
                                        parallelUploads: 20,
                                        previewTemplate: previewTemplate,
                                        dictDefaultMessage: '<?php echo lang("file_upload_instruction"); ?>',
                                        autoQueue: true,
                                        previewsContainer: "#comments_file-previews",
                                        clickable: true,
                                        accept: function (file, done) {
                                            if (file.name.length > 200) {
                                                done("Filename is too long.");
                                                $(file.previewTemplate).find(".description-field")
                                                    .remove();
                                            }
                                            //validate the file
                                            $.ajax({
                                                url: "<?= base_url() ?>admin/common/validate_project_file",
                                                data: {
                                                    file_name: file.name,
                                                    file_size: file.size
                                                },
                                                cache: false,
                                                type: 'POST',
                                                dataType: "json",
                                                success: function (response) {
                                                    if (response.success) {
                                                        fileSerial++;
                                                        $(file.previewTemplate).find(
                                                            ".description-field")
                                                            .attr("name", "comment_" +
                                                                fileSerial);
                                                        $(file.previewTemplate).append(
                                                            "<input type='hidden' name='file_name_" +
                                                            fileSerial +
                                                            "' value='" + file
                                                                .name + "' />\n\
                                                                        <input type='hidden' name='file_size_" +
                                                            fileSerial +
                                                            "' value='" + file
                                                                .size + "' />");
                                                        $(file.previewTemplate).find(
                                                            ".file-count-field")
                                                            .val(fileSerial);
                                                        done();
                                                    } else {
                                                        $(file.previewTemplate).find(
                                                            "input").remove();
                                                        done(response.message);
                                                    }
                                                }
                                            });
                                        },
                                        processing: function () {
                                            $("#file-save-button").prop("disabled", true);
                                        },
                                        queuecomplete: function () {
                                            $("#file-save-button").prop("disabled", false);
                                        },
                                        fallback: function () {
                                            //add custom fallback;
                                            $("body").addClass("dropzone-disabled");
                                            $('.modal-dialog').find('[type="submit"]').removeAttr(
                                                'disabled');

                                            $("#comments_file-dropzone").hide();

                                            $("#file-modal-footer").prepend(
                                                "<button id='add-more-file-button' type='button' class='btn  btn-default pull-left'><i class='fa fa-plus-circle'></i> " +
                                                "<?php echo lang("add_more"); ?>" + "</button>");

                                            $("#file-modal-footer").on("click",
                                                "#add-more-file-button",
                                                function () {
                                                    var newFileRow =
                                                        "<div class='file-row pb pt10 b-b mb10'>" +
                                                        "<div class='pb clearfix '><button type='button' class='btn btn-xs btn-danger pull-left mr remove-file'><i class='fa fa-times'></i></button> <input class='pull-left' type='file' name='manualFiles[]' /></div>" +
                                                        "<div class='mb5 pb5'><input class='form-control description-field'  name='comment[]'  type='text' style='cursor: auto;' placeholder='<?php echo lang("comment") ?>' /></div>" +
                                                        "</div>";
                                                    $("#comments_file-previews").prepend(
                                                        newFileRow);
                                                });
                                            $("#add-more-file-button").trigger("click");
                                            $("#comments_file-previews").on("click", ".remove-file",
                                                function () {
                                                    $(this).closest(".file-row").remove();
                                                });
                                        },
                                        success: function (file) {
                                            setTimeout(function () {
                                                $(file.previewElement).find(
                                                    ".progress-striped").removeClass(
                                                    "progress-striped").addClass(
                                                    "progress-bar-success");
                                            }, 1000);
                                        }
                                    });

                                })
                            </script>
                        </div>
                    </div>
                    <div class="">
                        <label class=""><?= lang('description') ?> </label>
                        <div class="">
                                <textarea name="item_desc" class="form-control textarea_"><?php
                                    if (!empty($items_info)) {
                                        echo $items_info->item_desc;
                                    }
                                    ?></textarea>
                        </div>
                    </div>
                
                </div>
            </form>
        </div>
        
        
        <?php } ?>
    </div>