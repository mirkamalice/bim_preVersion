<?= message_box('success'); ?>
<?= message_box('error'); ?>
<?php
include_once 'assets/admin-ajax.php'; ?>

<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class=""><a
                href="<?= base_url('admin/items/transferItem') ?>"><?= lang('manage') . ' ' . lang('transferItem') ?></a>
        </li>
        <li class="active"><a
                href="<?= base_url('admin/items/createTransferItem') ?>"><?= lang('new') . ' ' . lang('transferItem') ?></a>
        </li>
    </ul>
    <div class="tab-content bg-white">
        <form role="form" data-parsley-validate="" novalidate="" enctype="multipart/form-data" id="form"
            action="<?= base_url('admin/items/saveTransferItem/' . (!empty($items_info) ? $items_info->transfer_item_id : NULL)); ?>"
            method="post" class="form-horizontal  <?= (!empty($inline) ? 'myInline' : '') ?> ">
            <div class="mb-lg purchase accounting-template">
                <div class="row">
                    <div class="col-sm-6 col-xs-12 br pv">
                        <div class="row">
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?= lang('reference_no') ?> <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-7">
                                    <input type="text" class="form-control" value="<?php

                                                                                    if (!empty($items_info)) {
                                                                                        echo $items_info->reference_no;
                                                                                    } else {
                                                                                        if (empty(config_item('transfer_number_format'))) {
                                                                                            echo config_item('transfer_prefix');
                                                                                        }
                                                                                        if (config_item('increment_transfer_number') == 'FALSE') {
                                                                                            $this->load->helper('string');
                                                                                            echo random_string('nozero', 6);
                                                                                        } else {
                                                                                            echo $this->items_model->generateTransferItemNumber();
                                                                                        }
                                                                                    }
                                                                                    ?>" name="reference_no">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?= lang('date') ?></label>
                                <div class="col-lg-7">
                                    <?php
                                    if (!empty($items_info)) {
                                        $date = date('Y-m-d', strtotime($items_info->date));
                                    } else {
                                        $date = date('Y-m-d');
                                    }
                                    ?>
                                    <div class="input-group">
                                        <input class="form-control datepicker" type="text" value="<?= $date; ?>"
                                            name="date" data-date-format="<?= config_item('date_picker_format'); ?>">
                                        <div class="input-group-addon">
                                            <a href="#"><i class="fa fa-calendar"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 0px">
                                <label for="field-1" class="col-sm-3 control-label"><?= lang('attachment') ?></label>

                                <div class="col-sm-7">
                                    <div id="comments_file-dropzone" class="dropzone mb15">

                                    </div>
                                    <div id="comments_file-dropzone-scrollbar">
                                        <div id="comments_file-previews">
                                            <div id="file-upload-row" class="mt pull-left">

                                                <div class="preview box-content pr-lg" style="width:100px;">
                                                    <span data-dz-remove class="pull-right" style="cursor: pointer">
                                                        <i class="fa fa-times"></i>
                                                    </span>
                                                    <img data-dz-thumbnail class="upload-thumbnail-sm" />
                                                    <input class="file-count-field" type="hidden" name="files[]"
                                                        value="" />
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
                                    if (!empty($tickets_info->upload_file)) {
                                        $uploaded_file = json_decode($tickets_info->upload_file);
                                    }
                                    if (!empty($uploaded_file)) {
                                        foreach ($uploaded_file as $v_files_image) { ?>
                                    <div class="pull-left mt pr-lg mb" style="width:100px;">
                                        <span data-dz-remove class="pull-right existing_image"
                                            style="cursor: pointer"><i class="fa fa-times"></i></span>
                                        <?php if ($v_files_image->is_image == 1) { ?>
                                        <img data-dz-thumbnail src="<?php echo base_url() . $v_files_image->path ?>"
                                            class="upload-thumbnail-sm" />
                                        <?php } else { ?>
                                        <span data-toggle="tooltip" data-placement="top"
                                            title="<?= $v_files_image->fileName ?>" class="mailbox-attachment-icon"><i
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
                                    $(document).ready(function() {
                                        $(".existing_image").on("click", function() {
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
                                            accept: function(file, done) {
                                                if (file.name.length > 200) {
                                                    done("Filename is too long.");
                                                    $(file.previewTemplate).find(
                                                        ".description-field").remove();
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
                                                    success: function(response) {
                                                        if (response.success) {
                                                            fileSerial++;
                                                            $(file.previewTemplate)
                                                                .find(
                                                                    ".description-field"
                                                                ).attr("name",
                                                                    "comment_" +
                                                                    fileSerial);
                                                            $(file.previewTemplate)
                                                                .append(
                                                                    "<input type='hidden' name='file_name_" +
                                                                    fileSerial +
                                                                    "' value='" + file
                                                                    .name + "' />\n\
                                                                        <input type='hidden' name='file_size_" +
                                                                    fileSerial +
                                                                    "' value='" + file
                                                                    .size + "' />");
                                                            $(file.previewTemplate)
                                                                .find(
                                                                    ".file-count-field")
                                                                .val(fileSerial);
                                                            done();
                                                        } else {
                                                            $(file.previewTemplate)
                                                                .find("input").remove();
                                                            done(response.message);
                                                        }
                                                    }
                                                });
                                            },
                                            processing: function() {
                                                $("#file-save-button").prop("disabled", true);
                                            },
                                            queuecomplete: function() {
                                                $("#file-save-button").prop("disabled", false);
                                            },
                                            fallback: function() {
                                                //add custom fallback;
                                                $("body").addClass("dropzone-disabled");
                                                $('.modal-dialog').find('[type="submit"]')
                                                    .removeAttr('disabled');

                                                $("#comments_file-dropzone").hide();

                                                $("#file-modal-footer").prepend(
                                                    "<button id='add-more-file-button' type='button' class='btn  btn-default pull-left'><i class='fa fa-plus-circle'></i> " +
                                                    "<?php echo lang("add_more"); ?>" +
                                                    "</button>");

                                                $("#file-modal-footer").on("click",
                                                    "#add-more-file-button",
                                                    function() {
                                                        var newFileRow =
                                                            "<div class='file-row pb pt10 b-b mb10'>" +
                                                            "<div class='pb clearfix '><button type='button' class='btn btn-xs btn-danger pull-left mr remove-file'><i class='fa fa-times'></i></button> <input class='pull-left' type='file' name='manualFiles[]' /></div>" +
                                                            "<div class='mb5 pb5'><input class='form-control description-field'  name='comment[]'  type='text' style='cursor: auto;' placeholder='<?php echo lang("comment") ?>' /></div>" +
                                                            "</div>";
                                                        $("#comments_file-previews").prepend(
                                                            newFileRow);
                                                    });
                                                $("#add-more-file-button").trigger("click");
                                                $("#comments_file-previews").on("click",
                                                    ".remove-file",
                                                    function() {
                                                        $(this).closest(".file-row").remove();
                                                    });
                                            },
                                            success: function(file) {
                                                setTimeout(function() {
                                                    $(file.previewElement).find(
                                                            ".progress-striped")
                                                        .removeClass("progress-striped")
                                                        .addClass("progress-bar-success");
                                                }, 1000);
                                            }
                                        });

                                    })
                                    </script>
                                </div>
                            </div>
                            <?php
                            if (!empty($warehouse_info)) {
                                $warehouse_id = $warehouse_info->warehouse_id;
                                $permissionL = $warehouse_info->permission;
                            } else {
                                $warehouse_id = null;
                                $permissionL = null;
                            }
                            ?>

                            <?= get_permission(3, 9, $permission_user, $permissionL, ''); ?>


                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12 br pv">

                        <div class="row">
                            <div class="form-group">
                                <label for="field-1"
                                    class="col-sm-3 col-md-3 col-sm-3 control-label"><?= lang('FROM') . ' ' . lang('warehouse') ?></label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <?php
                                        $selected = ($items_info->from_warehouse_id) ? $items_info->from_warehouse_id : '';
                                        echo form_dropdown('from_warehouse_id', $warehouseList, $selected, array('class' => 'form-control selectpicker warehouse  mwarehouse', 'onchange' => 'getItemByWarehouse(this.value)', 'data-live-search' => true, 'style' => 'width:100%'));
                                        echo '<input type=' . 'hidden' . ' name=' . 'warehouse_id' . ' class=' . 'WarehouseValue' . ' value=' . $selected . '>';
                                        ?>
                                        <div class="input-group-addon"
                                            title="<?= lang('new') . ' ' . lang('warehouse') ?>" data-toggle="tooltip"
                                            data-placement="top">
                                            <a data-toggle="modal" data-target="#myModal_lg"
                                                href="<?= base_url() ?>admin/warehouse/create/0/from_warehouse_id"><i
                                                    class="fa fa-plus"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="field-1"
                                    class="col-sm-3 col-md-3 col-sm-3 control-label"><?= lang('To') . ' ' . lang('warehouse') ?></label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <?php
                                        $selected = ($items_info->to_warehouse_id) ? $items_info->to_warehouse_id : '';
                                        echo form_dropdown('to_warehouse_id', $warehouseList, $selected, array('class' => 'form-control selectpicker warehouse', 'data-live-search' => true, 'style' => 'width:100%'));
                                        ?>
                                        <div class="input-group-addon"
                                            title="<?= lang('new') . ' ' . lang('warehouse') ?>" data-toggle="tooltip"
                                            data-placement="top">
                                            <a data-toggle="modal" data-target="#myModal_lg"
                                                href="<?= base_url() ?>admin/warehouse/create/0/to_warehouse_id"><i
                                                    class="fa fa-plus"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="field-1" class="col-sm-3 control-label"><?= lang('status') ?></label>

                                <div class="col-sm-4">
                                    <?php
                                    $status = array('pending' => lang('pending'), 'completed' => lang('completed'), 'send' => lang('send'), 'approved' => lang('approved'), 'rejected' => lang('rejected'));

                                    $selected = ($items_info->status) ? $items_info->status : '';
                                    echo form_dropdown('status', $status, $selected, array('class' => 'form-control select_box', 'style' => 'width:100%'));
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-1 control-label"><?= lang('notes') ?> </label>
                                <div class="col-lg-11 row">
                                    <textarea name="notes" class="textarea"><?php
                                                                            if (!empty($items_info)) {
                                                                                echo $items_info->notes;
                                                                            } else {
                                                                                echo $this->config->item('transfer_notes');
                                                                            }
                                                                            ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <style type="text/css">
            .dropdown-menu>li>a {
                white-space: normal;
            }

            .dragger {
                background: url(<?= base_url() ?>assets/img/dragger.png) 10px 32px no-repeat;
                cursor: pointer;
            }

            <?php if ( !empty($items_info)) {
                ?>.dragger {
                    background: url(<?= base_url() ?>assets/img/dragger.png) 10px 32px no-repeat;
                    cursor: pointer;
                }

                <?php
            }

            ?>.input-transparent {
                box-shadow: none;
                outline: 0;
                border: 0 !important;
                background: 0 0;
                padding: 3px;
            }
            </style>



            <?php
            $pdata['itemType'] = 'transfer';
            if (!empty($items_info)) {
                $pdata['info'] = $items_info;
                $pdata['add_items'] = $this->items_model->ordered_items_by_id($items_info->transfer_item_id, true);;
            }
            $this->load->view('admin/items/selectItem', $pdata); ?>



        </form>
    </div>
</div>