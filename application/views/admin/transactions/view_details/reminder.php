<?php
$edited = can_action('30', 'edited');
?>

<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="active"><a href="#reminder_manage" data-toggle="tab"><?= lang('reminder') . ' ' . lang('list') ?></a>
        </li>
        <?php if (!empty($edited)) { ?>
            <li class=""><a href="#reminder_create" data-toggle="tab"><?= lang('set') . ' ' . lang('reminder') ?></a>
            </li>
        <?php } ?>
    </ul>
    <div class="tab-content bg-white">
        <!-- ************** general *************-->
        <div class="tab-pane active" id="reminder_manage">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th><?= lang('description') ?></th>
                            <th><?= lang('date') ?></th>
                            <th><?= lang('remind') ?></th>
                            <th><?= lang('notified') ?></th>
                            <th class="col-options no-sort"><?= lang('action') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $all_reminder = get_result('tbl_reminders', array('module_id' => $expense_info->transactions_id));

                        if (!empty($all_reminder)) {
                            foreach ($all_reminder as $v_reminder) :
                                $remind_user_info = $this->db->where('user_id', $v_reminder->user_id)->get('tbl_account_details')->row();
                        ?>
                                <tr id="leads_reminder_<?= $v_reminder->reminder_id ?>">
                                    <td><?= $v_reminder->description ?></td>
                                    <td><?= strftime(config_item('date_format'), strtotime($v_reminder->date)) . ' ' . display_time($v_reminder->date) ?>
                                    </td>
                                    <td>
                                        <a href="<?= base_url() ?>admin/user/user_details/<?= $v_reminder->user_id ?>">
                                            <?= $remind_user_info->fullname ?></a>
                                    </td>
                                    <td><?= $v_reminder->notified ?></td>
                                    <td>
                                        <?php echo ajax_anchor(base_url("admin/invoice/delete_reminder/" . $v_reminder->module . '/' . $v_reminder->module_id . '/' . $v_reminder->reminder_id), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#leads_reminder_" . $v_reminder->reminder_id)); ?>
                                    </td>
                                </tr>
                            <?php
                            endforeach;
                        } else {
                            ?>
                            <tr>
                                <td colspan="5"><?= lang('nothing_to_display') ?></td>
                            </tr>
                        <?php }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane" id="reminder_create">
            <form role="form" data-parsley-validate="" novalidate="" enctype="multipart/form-data" id="form" action="<?php echo base_url(); ?>admin/invoice/reminder/expense/<?= $expense_info->transactions_id ?>/<?php
                                                                                                                                                                                                                    if (!empty($reminder_info)) {
                                                                                                                                                                                                                        echo $reminder_info->reminder_id;
                                                                                                                                                                                                                    }
                                                                                                                                                                                                                    ?>" method="post" class="form-horizontal  ">
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('date_to_notified') ?> <span class="text-danger">*</span></label>
                    <div class="col-lg-5">
                        <div class="input-group">
                            <input type="text" name="date" class="form-control datetimepicker" value="<?php
                                                                                                        if (!empty($reminder_info->date)) {
                                                                                                            echo $reminder_info->date;
                                                                                                        } else {
                                                                                                            echo jdate('Y-m-d h:i');
                                                                                                        }
                                                                                                        ?>" data-date-min-date="<?= jdate('Y-m-d'); ?>">
                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-calendar"></i></a>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- End discount Fields -->
                <div class="form-group terms">
                    <label class="col-lg-3 control-label"><?= lang('description') ?> </label>
                    <div class="col-lg-5">
                        <textarea name="description" class="form-control"><?php
                                                                            if (!empty($reminder_info)) {
                                                                                echo $reminder_info->description;
                                                                            }
                                                                            ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('set_reminder_to') ?> <span class="text-danger">*</span></label>
                    <div class="col-lg-5">
                        <select class="form-control select_box" name="user_id" style="width: 100%">
                            <?php
                            $permission_user = $this->transactions_model->all_permission_user('4');
                            if (!empty($permission_user)) {
                                foreach ($permission_user as $key => $v_users) {
                            ?>
                                    <option <?php
                                            if (!empty($reminder_info)) {
                                                echo $reminder_info->user_id == $v_users->user_id ? 'selected' : null;
                                            }
                                            ?> value="<?= $v_users->user_id ?>">
                                        <?= $v_users->fullname ?></option>
                            <?php }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group terms">
                    <label class="col-lg-3 control-label"></label>
                    <div class="col-lg-5">
                        <div class="checkbox c-checkbox">
                            <label class="needsclick">
                                <input type="checkbox" value="Yes" <?php if (!empty($reminder_info) && $reminder_info->notify_by_email == 'Yes') {
                                                                        echo 'checked';
                                                                    } ?> name="notify_by_email">
                                <span class="fa fa-check"></span>
                                <?= lang('send_also_email_this_reminder') ?>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"></label>
                    <div class="col-lg-5">
                        <button type="submit" class="btn btn-purple"><?= lang('update') ?></button>
                        <button type="button" class="btn btn-primary pull-right" data-dismiss="modal"><?= lang('close') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datetimepicker/jquery.datetimepicker.min.css">
<?php include_once 'assets/plugins/datetimepicker/jquery.datetimepicker.full.php'; ?>

<script type="text/javascript">
    init_datepicker();

    // Date picker init with selected timeformat from settings
    function init_datepicker() {
        var datetimepickers = $('.datetimepicker');
        if (datetimepickers.length == 0) {
            return;
        }
        var opt_time;
        // Datepicker with time
        $.each(datetimepickers, function() {
            opt_time = {
                lazyInit: true,
                scrollInput: false,
                format: 'Y-m-d H:i',
            };

            opt_time.formatTime = 'H:i';
            // Check in case the input have date-end-date or date-min-date
            var max_date = $(this).data('date-end-date');
            var min_date = $(this).data('date-min-date');
            if (max_date) {
                opt_time.maxDate = max_date;
            }
            if (min_date) {
                opt_time.minDate = min_date;
            }
            // Init the picker
            $(this).datetimepicker(opt_time);
        });
    }
</script>