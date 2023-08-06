<?= message_box('success'); ?>
<?= message_box('error'); ?>
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-tagsinput/fm.tagator.jquery.js"></script>

<div id="transaction_deposit_state_report_div">
    <?php //$this->load->view("admin/transactions/transaction_deposit_state_report"); 
    ?>
</div>

<?php
$created = can_action('30', 'created');
$edited = can_action('30', 'edited');
$deleted = can_action('30', 'deleted');
$income_category = $this->db->get('tbl_income_category')->result();
$id = $this->uri->segment(5);
if (!empty($created) || !empty($edited)) {
?>
<div class="row">
    <div class="col-sm-12">


        <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs">
                <li class="<?= $active == 1 ? 'active' : ''; ?>"><a
                            href="<?= base_url('admin/transactions/deposit') ?>"><?= lang('all_deposit') ?></a>
                </li>
                <li class="<?= $active == 2 ? 'active' : ''; ?>"><a
                            href="<?= base_url('admin/transactions/create_deposit') ?>"><?= lang('new_deposit') ?></a>
                </li>
                <li><a class="import"
                       href="<?= base_url() ?>admin/transactions/import/Income"><?= lang('import') . ' ' . lang('deposit') ?></a>
                </li>
            </ul>
            <style type="text/css">
                .custom-bulk-button {
                    display: initial;
                }
            </style>
            <div class="tab-content no-padding  bg-white">

                <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
                    <form role="form" data-parsley-validate="" novalidate="" enctype="multipart/form-data" id="form"
                          action="<?php echo base_url(); ?>admin/transactions/save_deposit/<?php
                          if (!empty($deposit_info)) {
                              echo $deposit_info->transactions_id;
                          }
                          ?>"
                          method="post" class="form-horizontal ">
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('deposit') . ' ' . lang('prefix') ?></label>
                            <div class="col-lg-5">
                                <input type="text" required name="transaction_prefix" class="form-control"
                                       value="<?php
                                       if (!empty($deposit_info)) {
                                           echo $deposit_info->transaction_prefix;
                                       } else {
                                           if (empty(config_item('deposit_number_format'))) {
                                               echo config_item('deposit_prefix');
                                               $this->load->helper('string');
                                               echo random_string('nozero', 6);
                                           }
                                           //                                                   if (config_item('increment_invoice_number') == 'FALSE') {
                                           //                                                       $this->load->helper('string');
                                           //                                                       echo random_string('nozero', 6);
                                           //                                                   } else {
                                           echo $this->transactions_model->generate_transactions_number('Income');
                                           //                                                   }
                                       }
                                       ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('name') . '/' . lang('title') ?></label>
                            <div class="col-lg-5">
                                <input type="text" required
                                       placeholder="<?= lang('enter') . ' ' . lang('name') . '/' . lang('title') . ' ' . lang('for_personal') ?>"
                                       name="name" class="form-control"
                                       value="<?php
                                       if (!empty($deposit_info->name)) {
                                           echo strip_html_tags($deposit_info->name);
                                       } ?>">
                            </div>
                        </div>

                        <div class="form-group ">
                            <label class="col-lg-3 control-label mt-lg"><?= lang('account') ?> <span
                                        class="text-danger">*</span> </label>
                            <div class="col-lg-5 mt-lg">
                                <div class="input-group">
                                    <select class="form-control select_box" style="width: 100%" name="account_id"
                                            required
                                        <?php
                                        if (!empty($deposit_info) && $deposit_info->account_id != '0') {
                                            echo 'disabled';
                                        }
                                        ?>>
                                        <?php
                                        $account_info = $this->db->order_by('account_id', 'DESC')->get('tbl_accounts')->result();
                                        if (!empty($account_info)) {
                                            foreach ($account_info as $v_account) {
                                                ?>
                                                <option value="<?= $v_account->account_id ?>" <?php
                                                if (!empty($deposit_info->account_id)) {
                                                    echo $deposit_info->account_id == $v_account->account_id ? 'selected' : '';
                                                }
                                                ?>>
                                                    <?= $v_account->account_name ?></option>
                                                <?php
                                            }
                                        }
                                        $acreated = can_action('36', 'created');
                                        ?>
                                    </select>
                                    <?php if (!empty($acreated)) { ?>
                                        <div class="input-group-addon"
                                             title="<?= lang('new') . ' ' . lang('account') ?>"
                                             data-toggle="tooltip" data-placement="top">
                                            <a data-toggle="modal" data-target="#myModal"
                                               href="<?= base_url() ?>admin/account/new_account"><i
                                                        class="fa fa-plus"></i></a>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('date') ?></label>
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <input type="text" name="date" class="form-control datepicker" value="<?php
                                    if (!empty($deposit_info->date)) {
                                        echo $deposit_info->date;
                                    } else {
                                        echo jdate('Y-m-d');
                                    }
                                    ?>"
                                           data-date-format="<?= config_item('date_picker_format'); ?>">
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group terms">
                            <label class="col-lg-3 control-label"><?= lang('notes') ?> </label>
                            <div class="col-lg-5">
                                <textarea name="notes" class="form-control"><?php
                                    if (!empty($deposit_info)) {
                                        echo $deposit_info->notes;
                                    }
                                    ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('amount') ?> <span
                                        class="text-danger">*</span>
                            </label>
                            <div class="col-lg-5">
                                <div class="input-group  ">
                                    <input class="form-control " data-parsley-type="number" type="text" value="<?php
                                    if (!empty($deposit_info)) {
                                        echo $deposit_info->amount;
                                    }
                                    ?>"
                                           name="amount" required=""
                                        <?php
                                        if (!empty($deposit_info)) {
                                            echo 'disabled';
                                        }
                                        ?>>
                                </div>
                            </div>
                        </div>
                        <?php if (!empty($deposit_info)) { ?>
                            <input class="form-control " type="hidden" value="<?php echo $deposit_info->amount; ?>"
                                   name="amount">
                            <input class="form-control " type="hidden" value="<?php
                            if (!empty($deposit_info)) {
                                echo $deposit_info->account_id;
                            }
                            ?>" name="old_account_id">
                        <?php } ?>
                        <div class="more_option">
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?= lang('deposit_category') ?> </label>
                                <div class="col-lg-5">
                                    <div class="input-group">
                                        <select class="form-control select_box" style="width: 100%" name="category_id">
                                            <option value="0"><?= lang('none') ?></option>
                                            <?php
                                            $category_info = $this->db->order_by('income_category_id', 'DESC')->get('tbl_income_category')->result();
                                            if (!empty($category_info)) {
                                                foreach ($category_info as $v_category) {
                                                    ?>
                                                    <option value="<?= $v_category->income_category_id ?>" <?php
                                                    if (!empty($deposit_info->category_id)) {
                                                        echo $deposit_info->category_id == $v_category->income_category_id ? 'selected' : '';
                                                    }
                                                    ?>>
                                                        <?= $v_category->income_category ?></option>
                                                    <?php
                                                }
                                            }
                                            $created = can_action('125', 'created');
                                            ?>
                                        </select>
                                        <?php if (!empty($created)) { ?>
                                            <div class="input-group-addon"
                                                 title="<?= lang('new') . ' ' . lang('deposit_category') ?>"
                                                 data-toggle="tooltip" data-placement="top">
                                                <a data-toggle="modal" data-target="#myModal"
                                                   href="<?= base_url() ?>admin/transactions/categories/income"><i
                                                            class="fa fa-plus"></i></a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?= lang('paid_by') ?> </label>
                                <div class="col-lg-5">
                                    <div class="input-group">
                                        <select class="form-control select_box" style="width: 100%" name="paid_by">
                                            <option value="0"><?= lang('select_payer') ?></option>
                                            <?php
                                            $all_client = $this->db->get('tbl_client')->result();
                                            if (!empty($all_client)) {
                                                foreach ($all_client as $v_client) {
                                                    ?>
                                                    <option value="<?= $v_client->client_id ?>" <?php
                                                    if (!empty($deposit_info)) {
                                                        echo $deposit_info->paid_by == $v_client->client_id ? 'selected' : '';
                                                    }
                                                    ?>>
                                                        <?= ucfirst($v_client->name) ?></option>
                                                    <?php
                                                }
                                            }
                                            $acreated = can_action('4', 'created');
                                            ?>
                                        </select>
                                        <?php if (!empty($acreated)) { ?>
                                            <div class="input-group-addon"
                                                 title="<?= lang('new') . ' ' . lang('paid_by') ?>"
                                                 data-toggle="tooltip"
                                                 data-placement="top">
                                                <a data-toggle="modal" data-target="#myModal"
                                                   href="<?= base_url() ?>admin/client/new_client"><i
                                                            class="fa fa-plus"></i></a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?= lang('payment_method') ?> </label>
                                <div class="col-lg-5">
                                    <div class="input-group">
                                        <select class="form-control select_box" style="width: 100%"
                                                name="payment_methods_id">
                                            <option value="0"><?= lang('select_payment_method') ?></option>
                                            <?php
                                            $payment_methods = $this->db->order_by('payment_methods_id', 'DESC')->get('tbl_payment_methods')->result();
                                            if (!empty($payment_methods)) {
                                                foreach ($payment_methods as $p_method) {
                                                    ?>
                                                    <option value="<?= $p_method->payment_methods_id ?>" <?php
                                                    if (!empty($deposit_info)) {
                                                        echo $deposit_info->payment_methods_id == $p_method->payment_methods_id ? 'selected' : '';
                                                    }
                                                    ?>>
                                                        <?= $p_method->method_name ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                        <div class="input-group-addon"
                                             title="<?= lang('new') . ' ' . lang('payment_method') ?>"
                                             data-toggle="tooltip" data-placement="top">
                                            <a data-toggle="modal" data-target="#myModal"
                                               href="<?= base_url() ?>admin/settings/inline_payment_method"><i
                                                        class="fa fa-plus"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?= lang('reference') ?> </label>
                                <div class="col-lg-5">

                                    <input class="form-control " type="text" value="<?php
                                    if (!empty($deposit_info)) {
                                        echo $deposit_info->reference;
                                    }
                                    ?>" name="reference">

                                    <input class="form-control " type="hidden" value="<?php
                                    if (!empty($deposit_info)) {
                                        echo $deposit_info->account_id;
                                    }
                                    ?>" name="old_account_id">

                                    <span class="help-block"><?= lang('reference_example') ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?= lang('tags') ?> </label>
                                <div class="col-lg-5">
                                    <input type="text" name="tags" data-role="tagsinput" class="form-control"
                                           value="<?php
                                           if (!empty($deposit_info->tags)) {
                                               echo $deposit_info->tags;
                                           }
                                           ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 0px">
                            <label for="field-1" class="col-sm-3 control-label"><?= lang('attachment') ?></label>

                            <div class="col-sm-5">
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
                                if (!empty($deposit_info->attachement)) {
                                    $uploaded_file = json_decode($deposit_info->attachement);
                                }
                                if (!empty($uploaded_file)) {
                                    foreach ($uploaded_file as $v_files_image) { ?>
                                        <div class="pull-left mt pr-lg mb" style="width:100px;">
                                    <span data-dz-remove class="pull-right existing_image" style="cursor: pointer"><i
                                                class="fa fa-times"></i></span>
                                            <?php if ($v_files_image->is_image == 1) { ?>
                                                <img data-dz-thumbnail
                                                     src="<?php echo base_url() . $v_files_image->path ?>"
                                                     class="upload-thumbnail-sm"/>
                                            <?php } else { ?>
                                                <span data-toggle="tooltip" data-placement="top"
                                                      title="<?= $v_files_image->fileName ?>"
                                                      class="mailbox-attachment-icon"><i
                                                            class="fa fa-file-text-o"></i></span>
                                            <?php } ?>

                                            <input type="hidden" name="path[]"
                                                   value="<?php echo $v_files_image->path ?>">
                                            <input type="hidden" name="fileName[]"
                                                   value="<?php echo $v_files_image->fileName ?>">
                                            <input type="hidden" name="fullPath[]"
                                                   value="<?php echo $v_files_image->fullPath ?>">
                                            <input type="hidden" name="size[]"
                                                   value="<?php echo $v_files_image->size ?>">
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

                                        var fileSerial = 0;
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

                        <?php
                        if (!empty($deposit_info)) {
                            $transactions_id = $deposit_info->transactions_id;
                        } else {
                            $transactions_id = null;
                        }
                        ?>
                        <?= custom_form_Fields(1, $transactions_id); ?>

                        <?php
                        $permissionL = null;
                        if (!empty($deposit_info->permission)) {
                            $permissionL = $deposit_info->permission;
                        }
                        ?>
                        <?= get_permission(3, 5, $permission_user, $permissionL, ''); ?>
                        <div class="btn-bottom-toolbar text-right">
                            <?php
                            if (!empty($deposit_info)) { ?>
                                <button type="submit" id="file-save-button"
                                        class="btn btn-sm btn-primary"><?= lang('updates') ?></button>
                                <button type="button" onclick="goBack()"
                                        class="btn btn-sm btn-danger"><?= lang('cancel') ?></button>
                            <?php } else {
                                ?>
                                <button type="submit" id="file-save-button"
                                        class="btn btn-sm btn-primary"><?= lang('save') ?></button>
                            <?php }
                            ?>
                        </div>
                    </form>
                </div>
                <?php } else { ?>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
</div>