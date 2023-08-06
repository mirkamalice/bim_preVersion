<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<?php
$edited = can_action('56', 'edited');
$can_edit = $this->items_model->can_action('tbl_opportunities', 'edit', array('opportunities_id' => $opportunity_details->opportunities_id));

?>

<div class="panel panel-custom">
    <div class="panel-heading">
        <h3 class="panel-title"><?php
                                if (!empty($opportunity_details->opportunity_name)) {
                                    echo $opportunity_details->opportunity_name;
                                }
                                ?>
            <span class="btn-xs pull-right">
                <?php if (!empty($can_edit) && !empty($edited)) { ?>
                    <a href="<?= base_url() ?>admin/opportunities/create/<?= $opportunity_details->opportunities_id ?>"><?= lang('edit') . ' ' . lang('opportunities') ?></a>
                <?php } ?>
            </span>
        </h3>
    </div>
    <div class="panel-body row form-horizontal task_details">
        <div class="form-group col-sm-12">
            <div class="col-sm-6">
                <label class="control-label col-sm-6"><strong><?= lang('opportunity_name') ?>
                        :</strong>
                </label>
                <p class="form-control-static">
                    <?php
                    if (!empty($opportunity_details->opportunity_name)) {
                        echo $opportunity_details->opportunity_name;
                    }
                    ?>
                </p>
            </div>
            <div class="col-sm-6">
                <label class="control-label col-sm-6"><strong><?= lang('stages') ?>
                        :</strong></label>
                <p class="form-control-static">
                    <?php
                    if (!empty($opportunity_details->stages)) {
                        echo $opportunity_details->stages;
                    }
                    ?>
                </p>
            </div>

        </div>
        <div class="form-group col-sm-12">
            <div class="col-sm-6">
                <label class="control-label col-sm-6"><strong><?= lang('probability') ?></strong>
                </label>
                <p class="form-control-static">
                    <?php
                    if (!empty($opportunity_details->probability)) {
                        echo $opportunity_details->probability . ' %';
                    }
                    ?>
                </p>
            </div>
            <?php
            if (strtotime($opportunity_details->close_date) < strtotime(date('Y-m-d')) && $opportunity_details->probability < 100) {
                $danger = 'text-danger';
            } else {
                $danger = null;
            } ?>
            <div class="col-sm-6">
                <label class="control-label col-sm-6 <?= $danger ?>"><strong><?= lang('close_date') ?>
                        :</strong></label>
                <p class="form-control-static <?= $danger ?>">
                    <?= strftime(config_item('date_format'), strtotime($opportunity_details->close_date)) ?>
                </p>
            </div>

        </div>
        <?php
        $opportunities_state_info = $this->db->where('opportunities_state_reason_id', $opportunity_details->opportunities_state_reason_id)->get('tbl_opportunities_state_reason')->row();
        if ($opportunities_state_info->opportunities_state == 'open') {
            $label = 'primary';
        } elseif ($opportunities_state_info->opportunities_state == 'won') {
            $label = 'success';
        } elseif ($opportunities_state_info->opportunities_state == 'suspended') {
            $label = 'info';
        } else {
            $label = 'danger';
        }
        ?>
        <div class="form-group col-sm-12">
            <div class="col-sm-6">
                <label class="control-label col-sm-6"><strong><?= lang('state') ?>
                        : </strong></label>
                <p class="form-control-static">
                    <span class="label label-<?= $label ?>"><?= lang($opportunities_state_info->opportunities_state) ?></span>
                    <?= $opportunities_state_info->opportunities_state_reason ?>
                </p>
            </div>
            <div class="col-sm-6">
                <label class="control-label col-sm-6"><strong><?= lang('expected_revenue') ?>
                        : </strong></label>
                <p class="form-control-static">
                    <strong>
                        <?php
                        $currency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
                        if (!empty($opportunity_details->expected_revenue)) {
                            echo display_money($opportunity_details->expected_revenue, $currency->symbol);
                        }
                        ?>
                    </strong>
                </p>
            </div>
        </div>
        <div class="form-group col-sm-12">
            <div class="col-sm-6">
                <label class="control-label col-sm-6"><strong><?= lang('new_link') ?> :</strong>
                </label>
                <p class="form-control-static">
                    <?php
                    if (!empty($opportunity_details->new_link)) {
                        echo $opportunity_details->new_link;
                    }
                    ?>
                </p>
            </div>
            <div class="col-sm-6">
                <label class="control-label col-sm-6"><strong><?= lang('next_action') ?>
                        :</strong></label>
                <p class="form-control-static">
                    <?php
                    if (!empty($opportunity_details->next_action)) {
                        echo $opportunity_details->next_action;
                    }
                    ?>
                </p>
            </div>
        </div>
        <div class="form-group col-sm-12">
            <div class="col-sm-6">
                <label class="control-label col-sm-6"><strong><?= lang('next_action_date') ?>
                        : </strong></label>
                <p class="form-control-static">
                    <?= strftime(config_item('date_format'), strtotime($opportunity_details->next_action_date)) ?>
                </p>
            </div>
            <?php if ($opportunity_details->permission != '-') { ?>
                <div class="col-sm-6">
                    <label class="control-label col-sm-6"><strong><?= lang('participants') ?>
                            : </strong></label>
                    <?php
                    if ($opportunity_details->permission != 'all') {
                        $get_permission = json_decode($opportunity_details->permission);
                        if (!empty($get_permission)) :
                            foreach ($get_permission as $permission => $v_permission) :
                                $user_info = $this->db->where(array('user_id' => $permission))->get('tbl_users')->row();
                                if ($user_info->role_id == 1) {
                                    $label = 'circle-danger';
                                } else {
                                    $label = 'circle-success';
                                }
                                $profile_info = $this->db->where(array('user_id' => $permission))->get('tbl_account_details')->row();
                    ?>


                                <a href="#" data-toggle="tooltip" data-placement="top" title="<?= $profile_info->fullname ?>"><img src="<?= base_url() . $profile_info->avatar ?>" class="img-circle img-xs" alt="">
                                    <span class="custom-permission circle <?= $label ?>  circle-lg"></span>
                                </a>
                        <?php
                            endforeach;
                        endif;
                    } else { ?>
                        <p class="form-control-static"><strong><?= lang('everyone') ?></strong>
                            <i title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle" data-toggle="tooltip" data-placement="top"></i>

                        <?php
                    }
                        ?>
                        <?php if (!empty($can_edit) && !empty($edited)) { ?>
                            <span data-placement="top" data-toggle="tooltip" title="<?= lang('add_more') ?>">
                                <a data-toggle="modal" data-target="#myModal" href="<?= base_url() ?>admin/opportunities/update_users/<?= $opportunity_details->opportunities_id ?>" class="text-default ml"><i class="fa fa-plus"></i></a>
                            </span>
                        </p>
                    <?php
                        }
                    ?>
                </div>
            <?php } ?>

        </div>
        <?php $show_custom_fields = custom_form_label(8, $opportunity_details->opportunities_id);

        if (!empty($show_custom_fields)) {
            foreach ($show_custom_fields as $c_label => $v_fields) {
                if (!empty($v_fields)) {
                    if (count(array($v_fields)) == 1) {
                        $col = 'col-sm-12';
                        $sub_col = 'col-sm-3';
                        $style = null;
                    } else {
                        $col = 'col-sm-6';
                        $sub_col = 'col-sm-5';
                        $style = null;
                    }

        ?>
                    <div class="form-group  <?= $col ?>" style="<?= $style ?>">
                        <label class="control-label <?= $sub_col ?>"><strong><?= $c_label ?>
                                :</strong></label>
                        <div class="col-sm-7 ">
                            <p class="form-control-static">
                                <strong><?= $v_fields ?></strong>
                            </p>
                        </div>
                    </div>
        <?php }
            }
        }
        ?>
        <div class="form-group col-sm-12">
            <label class="col-sm-2 control-label"></label>
            <div class="col-sm-10">
                <blockquote style="font-size: 12px;"><?php
                                                        if (!empty($opportunity_details->notes)) {
                                                            echo $opportunity_details->notes;
                                                        }
                                                        ?></blockquote>
            </div>
        </div>
    </div>

</div>