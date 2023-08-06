<?= message_box('success'); ?>
<?= message_box('error'); ?>
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-tagsinput/fm.tagator.jquery.js"></script>
    
    <div id="transactions_state_report_div">
    
    </div>


<?php
$created = can_action('31', 'created');
$edited = can_action('31', 'edited');
$deleted = can_action('31', 'deleted');
$expense_category = $this->db->get('tbl_expense_category')->result();
$id = $this->uri->segment(5);
if (!empty($created) || !empty($edited)) {
?>
<div class="row">
    <div class="col-sm-12">

        <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs">
                <li class="<?= $active == 1 ? 'active' : ''; ?>"><a
                        href="<?= base_url('admin/transactions/expense') ?>"><?= lang('all_expense') ?></a>
                </li>
                <li class="<?= $active == 2 ? 'active' : ''; ?>"><a
                        href="<?= base_url('admin/transactions/create_expense') ?>"><?= lang('new_expense') ?></a>
                </li>
                <li><a class="import"
                        href="<?= base_url() ?>admin/transactions/import/Expense"><?= lang('import') . ' ' . lang('expense') ?></a>
                </li>
            </ul>
            <style type="text/css">
            .custom-bulk-button {
                display: initial;
            }
            </style>
            <div class="tab-content bg-white">

                <!-- Tabs within a box -->
                <ul class="nav nav-tabs">

                    <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
                        <form role="form" data-parsley-validate="" novalidate="" enctype="multipart/form-data"
                            action="<?php echo base_url(); ?>admin/transactions/save_expense/<?php
                                                                                                                                                                                    if (!empty($expense_info)) {
                                                                                                                                                                                        echo $expense_info->transactions_id;
                                                                                                                                                                                    }
                                                                                                                                                                                    ?>" method="post"
                            class="form-horizontal  ">

                            <div class="form-group">
                                <label
                                    class="col-lg-2 control-label"><?= lang('expense') . ' ' . lang('prefix') ?></label>
                                <div class="col-lg-4">
                                    <input type="text" required name="transaction_prefix" class="form-control"
                                        value="<?php
                                                                                                                            if (!empty($expense_info)) {
                                                                                                                                echo $expense_info->transaction_prefix;
                                                                                                                            } else {
                                                                                                                                if (empty(config_item('expense_number_format'))) {
                                                                                                                                    echo config_item('expense_prefix');
                                                                                                                                    $this->load->helper('string');
                                                                                                                                    echo random_string('nozero', 6);
                                                                                                                                }
                                                                                                                                echo $this->transactions_model->generate_transactions_number('Expense');
                                                                                                                            }
                                                                                                                            ?>">
                                </div>

                                <label class="col-lg-2 control-label"><?= lang('name') . '/' . lang('title') ?></label>
                                <div class="col-lg-4">
                                    <input type="text" required
                                        placeholder="<?= lang('enter') . ' ' . lang('name') . '/' . lang('title') . ' ' . lang('for_personal') ?>"
                                        name="name" class="form-control"
                                        value="<?php
                                                                                                                                                                                                                        if (!empty($expense_info->name)) {
                                                                                                                                                                                                                            echo $expense_info->name;
                                                                                                                                                                                                                        } ?>">
                                </div>
                            </div>
                            <?php $project_id = $this->uri->segment(5);
                                if (!empty($expense_info->project_id)) {
                                    $project_id = $expense_info->project_id;
                                }
                                $project = $this->db->where('project_id', $project_id)->get('tbl_project')->row();
                                if (!empty($project)) {
                                ?>
                            <div class="form-group">
                                <label class="col-lg-2 control-label"><?= lang('project') ?></label>
                                <div class="col-lg-5">
                                    <select class="form-control select_box" style="width: 100%" name="project_id">
                                        <option value="<?php echo $project_id; ?>"><?= $project->project_name ?>
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="form-group">
                                <label class="col-lg-2 control-label"><?= lang('date') ?></label>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <input type="text" name="date" class="form-control datepicker" value="<?php
                                                                                                                    if (!empty($expense_info->date)) {
                                                                                                                        echo $expense_info->date;
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

                                <label class="col-lg-2 control-label"><?= lang('amount') ?> <span
                                        class="text-danger">*</span>
                                </label>
                                <div class="col-lg-4">
                                    <div class="input-group  ">
                                        <input class="form-control " data-parsley-type="number" type="text"
                                            value="<?php
                                                                                                                        if (!empty($expense_info)) {
                                                                                                                            echo $expense_info->amount;
                                                                                                                        }
                                                                                                                        ?>" name="amount" required=""
                                            <?php
                                                                                                                                                        if (!empty($expense_info)) {
                                                                                                                                                            echo 'disabled';
                                                                                                                                                        }
                                                                                                                                                        ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label"><?= lang('deposit_category') ?> </label>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <select <?php
                                                    if (!empty($project)) {
                                                        echo 'required=""';
                                                    }
                                                    ?> class="form-control select_box" style="width: 100%"
                                            name="category_id">
                                            <option value="0"><?= lang('none') ?></option>
                                            <?php
                                                $category_info = $this->db->order_by('expense_category_id', 'DESC')->get('tbl_expense_category')->result();
                                                if (!empty($category_info)) {
                                                    foreach ($category_info as $v_category) {
                                                ?>
                                            <option value="<?= $v_category->expense_category_id ?>" <?php
                                                                                                                if (!empty($expense_info->category_id)) {
                                                                                                                    echo $expense_info->category_id == $v_category->expense_category_id ? 'selected' : '';
                                                                                                                }
                                                                                                                ?>>
                                                <?= $v_category->expense_category ?></option>
                                            <?php
                                                    }
                                                }
                                                $created = can_action('124', 'created');
                                                ?>
                                        </select>
                                        <?php if (!empty($created)) { ?>
                                        <div class="input-group-addon"
                                            title="<?= lang('new') . ' ' . lang('deposit_category') ?>"
                                            data-toggle="tooltip" data-placement="top">
                                            <a data-toggle="modal" data-target="#myModal"
                                                href="<?= base_url() ?>admin/transactions/categories/expense"><i
                                                    class="fa fa-plus"></i></a>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <label class="col-lg-2 control-label"><?= lang('account') ?> <span
                                        class="text-danger">*</span>
                                </label>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <select class="form-control select_box" style="width: 100%" name="account_id"
                                            required
                                            <?php
                                                                                                                                    if (!empty($expense_info) && $expense_info->account_id != '0') {
                                                                                                                                        echo 'disabled';
                                                                                                                                    }
                                                                                                                                    ?>>
                                            <?php
                                                $account_info = $this->db->order_by('account_id', 'DESC')->get('tbl_accounts')->result();
                                                if (!empty($account_info)) {
                                                    foreach ($account_info as $v_account) {
                                                ?>
                                            <option value="<?= $v_account->account_id ?>" <?php
                                                                                                        if (!empty($expense_info)) {
                                                                                                            echo $expense_info->account_id == $v_account->account_id ? 'selected' : '';
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
                                            title="<?= lang('new') . ' ' . lang('account') ?>" data-toggle="tooltip"
                                            data-placement="top">
                                            <a data-toggle="modal" data-target="#myModal"
                                                href="<?= base_url() ?>admin/account/new_account"><i
                                                    class="fa fa-plus"></i></a>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label"><?= lang('payment_method') ?> </label>
                                <div class="col-lg-4">
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
                                                                                                                if (!empty($expense_info)) {
                                                                                                                    echo $expense_info->payment_methods_id == $p_method->payment_methods_id ? 'selected' : '';
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

                                <label class="col-lg-2 control-label"><?= lang('paid_by') ?> </label>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <select class="form-control select_box" style="width: 100%" name="paid_by">
                                            <?php $all_client = $this->db->get('tbl_client')->result();
                                                if (!empty($project)) {
                                                    $client_name = $this->db->where('client_id', $project->client_id)->get('tbl_client')->row();
                                                ?>
                                            <option value="<?= $project->client_id ?>"><?= $client_name->name ?>
                                            </option>
                                            <?php } else { ?>
                                            <option value="0"><?= lang('select_payer') ?></option>
                                            <?php if (!empty($all_client)) {
                                                        foreach ($all_client as $v_client) {
                                                    ?>
                                            <option value="<?= $v_client->client_id ?>" <?php
                                                                                                        if (!empty($expense_info)) {
                                                                                                            echo $expense_info->paid_by == $v_client->client_id ? 'selected' : '';
                                                                                                        }
                                                                                                        ?>>
                                                <?= ucfirst($v_client->name); ?></option>
                                            <?php
                                                        }
                                                    }
                                                }
                                                $acreated = can_action('4', 'created');
                                                ?>
                                        </select>
                                        <?php if (!empty($acreated)) { ?>
                                        <div class="input-group-addon"
                                            title="<?= lang('new') . ' ' . lang('paid_by') ?>" data-toggle="tooltip"
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
                                <label class="col-lg-2 control-label"><?= lang('tags') ?> </label>
                                <div class="col-lg-4">
                                    <input type="text" name="tags" data-role="tagsinput" class="form-control"
                                        value="<?php
                                                                                                                            if (!empty($expense_info->tags)) {
                                                                                                                                echo $expense_info->tags;
                                                                                                                            }
                                                                                                                            ?>">
                                </div>

                                <label class="col-lg-2 control-label"><?= lang('reference') ?> </label>
                                <div class="col-lg-4">
                                    <input class="form-control " type="text" value="<?php
                                                                                        if (!empty($expense_info)) {
                                                                                            echo $expense_info->reference;
                                                                                        }
                                                                                        ?>" name="reference">
                                    <span class=""><?= lang('reference_example') ?></span>
                                </div>
                            </div>
                            <?php if (!empty($expense_info)) { ?>
                            <input class="form-control " type="hidden" value="<?php echo $expense_info->amount; ?>"
                                name="amount">
                            <?php } ?>


                            <div class="form-group" style="margin-bottom: 0px">
                                <label class="col-lg-2 control-label"><?= lang('notes') ?> </label>
                                <div class="col-lg-4">
                                    <textarea name="notes" class="form-control"><?php
                                                                                    if (!empty($expense_info)) {
                                                                                        echo $expense_info->notes;
                                                                                    }
                                                                                    ?></textarea>
                                </div>
                                <label for="field-1" class="col-lg-2 control-label"><?= lang('attachment') ?></label>
                                <div class="col-lg-4">
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
                                        if (!empty($expense_info->attachement)) {
                                            $uploaded_file = json_decode($expense_info->attachement);
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
                                            accept: function(file, done) {
                                                if (file.name.length > 200) {
                                                    done("Filename is too long.");
                                                    $(file.previewTemplate).find(".description-field").remove();
                                                }
                                                $.ajax({ //validate the file
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
                                                                )
                                                                .attr("name",
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
                                                                .find(
                                                                    "input").remove();
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
                                                    .removeAttr(
                                                        'disabled');

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
                                                        .removeClass(
                                                            "progress-striped").addClass(
                                                            "progress-bar-success");
                                                }, 1000);
                                            }
                                        });

                                    })
                                    </script>
                                </div>
                            </div>
                            <div class="form-group"
                                <?php if (isset($expense_info) && !empty($expense_info->recurring_from)) { ?>
                                data-toggle="tooltip"
                                data-title="<?php echo lang('create_recurring_from_child_error_message', [lang('expense_lowercase'), lang('expense_lowercase'), lang('expense_lowercase')]); ?>"
                                <?php } ?>>
                                <label class=" col-lg-2 control-label"><?php echo lang('repeat_every'); ?></label>
                                <div class="col-lg-4">
                                    <select name="repeat_every" id="repeat_every" class="selectpicker" data-width="100%"
                                        data-none-selected-text="<?php echo lang('none'); ?>"
                                        <?php if (isset($expense_info) && !empty($expense_info->recurring_from)) { ?>
                                        disabled <?php } ?>>
                                        <option value=""></option>
                                        <option value="1_week" <?php if (isset($expense_info) && $expense_info->repeat_every == 1 && $expense_info->recurring_type == 'week') {
                                                                        echo 'selected';
                                                                    } ?>><?php echo lang('week'); ?></option>
                                        <option value="2_week" <?php if (isset($expense_info) && $expense_info->repeat_every == 2 && $expense_info->recurring_type == 'week') {
                                                                        echo 'selected';
                                                                    } ?>>2 <?php echo lang('weeks'); ?></option>
                                        <option value="1_month" <?php if (isset($expense_info) && $expense_info->repeat_every == 1 && $expense_info->recurring_type == 'month') {
                                                                        echo 'selected';
                                                                    } ?>>1 <?php echo lang('month'); ?></option>
                                        <option value="2_month" <?php if (isset($expense_info) && $expense_info->repeat_every == 2 && $expense_info->recurring_type == 'month') {
                                                                        echo 'selected';
                                                                    } ?>>2 <?php echo lang('months'); ?></option>
                                        <option value="3_month" <?php if (isset($expense_info) && $expense_info->repeat_every == 3 && $expense_info->recurring_type == 'month') {
                                                                        echo 'selected';
                                                                    } ?>>3 <?php echo lang('months'); ?></option>
                                        <option value="6_month" <?php if (isset($expense_info) && $expense_info->repeat_every == 6 && $expense_info->recurring_type == 'month') {
                                                                        echo 'selected';
                                                                    } ?>>6 <?php echo lang('months'); ?></option>
                                        <option value="1_year" <?php if (isset($expense_info) && $expense_info->repeat_every == 1 && $expense_info->recurring_type == 'year') {
                                                                        echo 'selected';
                                                                    } ?>>1 <?php echo lang('year'); ?></option>
                                        <option value="custom" <?php if (isset($expense_info) && $expense_info->custom_recurring == 1) {
                                                                        echo 'selected';
                                                                    } ?>><?php echo lang('custom'); ?></option>
                                    </select>

                                    <div class="recurring_custom <?php if ((isset($expense_info) && $expense_info->custom_recurring != 1) || (!isset($expense_info))) {
                                                                            echo 'hide';
                                                                        } ?>">
                                        <div class="input-group">
                                            <?php $value = (isset($expense_info) && $expense_info->custom_recurring == 1 ? $expense_info->repeat_every : 1); ?>
                                            <input type="number" name="repeat_every_custom" class="form-control" min="1"
                                                value="<?= $value ?>">
                                            <div class="input-group-addon p0 b0">
                                                <select name="repeat_type_custom" id="repeat_type_custom"
                                                    class="selectpicker" data-width="100%"
                                                    data-none-selected-text="<?php echo lang('none'); ?>">
                                                    <option value="day" <?php if (isset($expense_info) && $expense_info->custom_recurring == 1 && $expense_info->recurring_type == 'day') {
                                                                                echo 'selected';
                                                                            } ?>><?php echo lang('days'); ?></option>
                                                    <option value="week" <?php if (isset($expense_info) && $expense_info->custom_recurring == 1 && $expense_info->recurring_type == 'week') {
                                                                                    echo 'selected';
                                                                                } ?>><?php echo lang('weeks'); ?>
                                                    </option>
                                                    <option value="month" <?php if (isset($expense_info) && $expense_info->custom_recurring == 1 && $expense_info->recurring_type == 'month') {
                                                                                    echo 'selected';
                                                                                } ?>><?php echo lang('months'); ?>
                                                    </option>
                                                    <option value="year" <?php if (isset($expense_info) && $expense_info->custom_recurring == 1 && $expense_info->recurring_type == 'year') {
                                                                                    echo 'selected';
                                                                                } ?>><?php echo lang('years'); ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="cycles_wrapper" class="<?php if (!isset($expense_info) || (isset($expense_info) && $expense_info->recurring == 'No')) {
                                                                        echo ' hide';
                                                                    } ?>">
                                    <?php $value = (isset($expense_info) ? $expense_info->total_cycles : 0); ?>
                                    <div class="recurring-cycles">
                                        <label class="col-lg-2 control-label"
                                            for="cycles"><?php echo lang('total_cycles'); ?>
                                        </label>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <input type="number" class="form-control" <?php if ($value == 0) {
                                                                                                    echo ' disabled';
                                                                                                } ?>
                                                    name="total_cycles" id="cycles" value="<?php echo $value; ?>"
                                                    <?php if (isset($expense_info) && $expense_info->done_cycles > 0) {
                                                                                                                                                                        echo 'min="' . ($expense_info->done_cycles) . '"';
                                                                                                                                                                    } ?>>
                                                <div class="input-group-addon">
                                                    <input data-toggle="tooltip"
                                                        title="<?php echo lang('cycles_infinity'); ?>" type="checkbox"
                                                        <?php if ($value == 0) {
                                                                                                                                                        echo ' checked';
                                                                                                                                                    } ?> id="unlimited_cycles">
                                                </div>
                                            </div>
                                            <?php if (isset($expense_info) && $expense_info->done_cycles > 0) {
                                                    echo '<small>' . lang('total_cycles_passed', $expense_info->done_cycles) . '</small>';
                                                }
                                                ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                if (!empty($expense_info)) {
                                    $transactions_id = $expense_info->transactions_id;
                                } else {
                                    $transactions_id = null;
                                }
                                ?>
                            <?= custom_form_Fields(2, $transactions_id, true); ?>
                            <?php if (!empty($project_id)) : ?>
                            <div class="form-group mt-lg">
                                <label for="field-1" class="col-sm-2 control-label"><?= lang('billable') ?>
                                    <span class="required">*</span></label>
                                <div class="col-sm-8">
                                    <input data-toggle="toggle" name="billable" value="Yes" <?php
                                                                                                    if (!empty($expense_info) && $expense_info->billable == 'Yes') {
                                                                                                        echo 'checked';
                                                                                                    }
                                                                                                    ?>
                                        data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>" data-onstyle="success"
                                        data-offstyle="danger" type="checkbox">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="field-1" class="col-sm-2 control-label"><?= lang('visible_to_client') ?>
                                    <span class="required">*</span></label>
                                <div class="col-sm-8">
                                    <input data-toggle="toggle" name="client_visible" value="Yes" <?php
                                                                                                            if (!empty($expense_info) && $expense_info->client_visible == 'Yes') {
                                                                                                                echo 'checked';
                                                                                                            }
                                                                                                            ?>
                                        data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>" data-onstyle="success"
                                        data-offstyle="danger" type="checkbox">
                                </div>
                            </div>
                            <?php endif ?>

                            <input class="form-control " type="hidden" value="<?php
                                                                                    if (!empty($expense_info)) {
                                                                                        echo $expense_info->account_id;
                                                                                    }
                                                                                    ?>" name="old_account_id">
                            <?php
                                $permissionL = null;
                                if (!empty($expense_info->permission)) {
                                    $permissionL = $expense_info->permission;
                                }
                                ?>
                            <?= get_permission(2, 5, $permission_user, $permissionL, ''); ?>
                            <div class="btn-bottom-toolbar text-right">
                                <?php
                                    if (!empty($expense_info)) { ?>
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