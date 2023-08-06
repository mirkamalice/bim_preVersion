<div class="panel panel-custom">
    <div class="panel-heading">
        <div class="panel-title"><?= lang('all') . ' ' . lang('notification'); ?></div>
    </div>
    <div class="panel-body">

        <table class="table" id="Transation_DataTables">
            <thead>
                <tr>
                    <th><a href="#" onclick="mark_all_as_read(); return false;"><?php echo lang('mark_all_as_read'); ?></a>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $user_notifications = $this->common_model->get_user_notifications(false, true);
                if (!empty($user_notifications)) {
                    foreach ($user_notifications as $notification) {
                        if (!empty($notification->link)) {
                            $link = base_url() . $notification->link;
                        } else {
                            $link = '#';
                        }
                ?>
                        <tr>
                            <td class="<?php if ($notification->read_inline == 0) {
                                            echo 'unread';
                                        } ?>">
                                <?php
                                $description = lang($notification->description, $notification->value);
                                if ($notification->from_user_id != 0) {
                                    $description = fullname($notification->from_user_id) . ' - ' . $description;
                                }
                                echo '<span class="n-title text-sm block">' . $description . '</span>'; ?>
                                <small class="text-muted pull-left" style="margin-top: -4px"><i class="fa fa-clock-o"></i> <?php echo time_ago($notification->date); ?>
                                </small>
                                <?php if ($notification->read_inline == 0) { ?>
                                    <span class="text-muted pull-right mark-as-read-inline" onclick="read_inline(<?php echo $notification->notifications_id; ?>);" data-placement="top" data-toggle="tooltip" data-title="<?php echo lang('mark_as_read'); ?>">
                                        <small><i class="fa fa-circle-thin"></i></small>
                                    </span>
                                <?php } ?>
                            </td>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>