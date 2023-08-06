<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/plugins/dropzone/dropzone.min.css">
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/dropzone/dropzone.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/dropzone/dropzone.custom.js"></script>

<?php
$created = can_action('100', 'created');
$edited = can_action('100', 'edited');
if (!empty($created) || !empty($edited)) {
    ?>
    <div class="panel panel-custom">
        <div class="panel-heading">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel"><?= lang('new_termination') ?></h4>
        </div>
        <div class="modal-body wrap-modal wrap">
            <form role="form" id="form" data-parsley-validate="" novalidate="" enctype="multipart/form-data"
                  action="<?php echo base_url(); ?>admin/termination/save_termination/<?= (!empty($terminations->id) ? $terminations->id : ''); ?>"
                  method="post" class="form-horizontal form-groups-bordered">
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('employee_name') ?> <span
                                class="text-danger">*</span></label>
                    <div class="col-lg-8">
                        <select name="employee_id" class="form-control select_box" style="width: 100%" required>
                            <?php if (!empty($all_employee)) : ?>
                                <?php foreach ($all_employee as $dept_name => $v_all_employee) : ?>
                                    <optgroup label="<?php echo $dept_name; ?>">
                                        <?php if (!empty($v_all_employee)) : foreach ($v_all_employee as $v_employee) : ?>
                                            <option value="<?php echo $v_employee->user_id; ?>" <?php echo (!empty($terminations)) && $v_employee->user_id == $terminations->employee_id ? 'selected' : '';
                                            
                                            ?>><?php echo $v_employee->fullname . ' ( ' . $v_employee->designations . ' )' ?></option>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('termination_type') ?> <span
                                class="text-danger">*</span></label>
                    <div class="col-lg-8">
                        <select name="termination_type" class="form-control select_box" style="width: 100%" required>
                            <?php
                            $all_terminations = $this->db->where('type', 'termination')->get('tbl_customer_group')->result();
                            if (!empty($all_terminations)) {
                                foreach ($all_terminations as $v_termination) {
                                    ?>
                                    <option value="<?= $v_termination->customer_group_id ?>" <?php
                                    if (!empty($terminations) && $terminations->termination_type == $v_termination->customer_group_id) {
                                        echo 'selected';
                                    }
                                    ?>>
                                        <?= $v_termination->customer_group ?></option>
                                <?php }
                            } ?>
                        </select>
                    
                    </div>
                </div>
                
                <div class="form-group" style="margin-bottom: 8px">
                    <label for="field-1" class="col-sm-3 control-label"><?= lang('attachment') ?></label>
                    
                    <div class="col-sm-8">
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
                                             role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                            <div class="progress-bar progress-bar-success" style="width:0%;"
                                                 data-dz-uploadprogress></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        if (!empty($terminations->attachment)) {
                            $uploaded_file = json_decode($terminations->attachment);
                        }
                        if (!empty($uploaded_file)) {
                            foreach ($uploaded_file as $v_files_image) { ?>
                                <div class="pull-left mt pr-lg mb" style="width:100px;">
                                    <span data-dz-remove class="pull-right existing_image" style="cursor: pointer"><i
                                                class="fa fa-times"></i></span>
                                    <?php if ($v_files_image->is_image == 1) { ?>
                                        <img data-dz-thumbnail src="<?php echo base_url() . $v_files_image->path ?>"
                                             class="upload-thumbnail-sm"/>
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
                            $(document).on('loaded.bs.modal', function () {
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
                                            $(file.previewTemplate).find(".description-field").remove();
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
                                                    $(file.previewTemplate).find(".description-field").attr("name", "comment_" + fileSerial);
                                                    $(file.previewTemplate).append("<input type='hidden' name='file_name_" + fileSerial + "' value='" + file.name + "' />\n\
                                                                        <input type='hidden' name='file_size_" + fileSerial + "' value='" + file.size + "' />");
                                                    $(file.previewTemplate).find(".file-count-field").val(fileSerial);
                                                    done();
                                                } else {
                                                    $(file.previewTemplate).find("input").remove();
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
                                        $('.modal-dialog').find('[type="submit"]').removeAttr('disabled');

                                        $("#comments_file-dropzone").hide();

                                        $("#file-modal-footer").prepend("<button id='add-more-file-button' type='button' class='btn  btn-default pull-left'><i class='fa fa-plus-circle'></i> " + "<?php echo lang("add_more"); ?>" + "</button>");

                                        $("#file-modal-footer").on("click", "#add-more-file-button", function () {
                                            var newFileRow = "<div class='file-row pb pt10 b-b mb10'>" +
                                                "<div class='pb clearfix '><button type='button' class='btn btn-xs btn-danger pull-left mr remove-file'><i class='fa fa-times'></i></button> <input class='pull-left' type='file' name='manualFiles[]' /></div>" +
                                                "<div class='mb5 pb5'><input class='form-control description-field'  name='comment[]'  type='text' style='cursor: auto;' placeholder='<?php echo lang("comment") ?>' /></div>" +
                                                "</div>";
                                            $("#comments_file-previews").prepend(newFileRow);
                                        });
                                        $("#add-more-file-button").trigger("click");
                                        $("#comments_file-previews").on("click", ".remove-file", function () {
                                            $(this).closest(".file-row").remove();
                                        });
                                    },
                                    success: function (file) {
                                        setTimeout(function () {
                                            $(file.previewElement).find(".progress-striped").removeClass("progress-striped").addClass("progress-bar-success");
                                        }, 1000);
                                    }
                                });

                            })
                        </script>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?= lang('notice_date') ?> <span
                                class="required">*</span></label>
                    
                    <div class="col-sm-5">
                        <div class="input-group">
                            <input type="text" name="notice_date" required
                                   placeholder="<?= lang('enter') . ' ' . lang('notice_date') ?>"
                                   class="form-control start_date" value="<?php
                            if (!empty($terminations->notice_date)) {
                                echo $terminations->notice_date;
                            }
                            ?>">
                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-calendar"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?= lang('termination_date') ?> <span
                                class="required">*</span></label>
                    
                    <div class="col-sm-5">
                        <div class="input-group">
                            <input type="text" name="termination_date" required
                                   placeholder="<?= lang('enter') . ' ' . lang('notice_date') ?>"
                                   class="form-control start_date" value="<?php
                            if (!empty($terminations->termination_date)) {
                                echo $terminations->termination_date;
                            }
                            ?>">
                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-calendar"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label"><?= lang('description') ?></label>
                    
                    <div class="col-sm-8">
                        <textarea name="description"
                                  class="form-control textarea"><?= (!empty($terminations->description) ? $terminations->description : ''); ?></textarea>
                    </div>
                </div>
                
                
                <!--hidden input values -->
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-2">
                        <button type="submit" id="file-save-button"
                                class="btn btn-primary btn-block"><?= lang('save') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php } ?>