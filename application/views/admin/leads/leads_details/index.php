<?php
$where = array('user_id' => $this->session->userdata('user_id'), 'module_id' => $leads_details->leads_id, 'module_name' => 'leads');
$check_existing = $this->items_model->check_by($where, 'tbl_pinaction');
if (!empty($check_existing)) {
    $url = 'remove_todo/' . $check_existing->pinaction_id;
    $btn = 'danger';
    $title = lang('remove_todo');
} else {
    $url = 'add_todo_list/leads/' . $leads_details->leads_id;
    $btn = 'warning';
    $title = lang('add_todo_list');
}
$can_edit = $this->items_model->can_action('tbl_leads', 'edit', array('leads_id' => $leads_details->leads_id));
$edited = can_action('55', 'edited');
?>
<div class="panel panel-custom">
    <div class="panel-heading">
        <h3 class="panel-title"><?php
            if (!empty($leads_details->lead_name)) {
                echo $leads_details->lead_name;
            }
            ?>
            <div class="pull-right ml-sm " style="margin-top: -6px">
                <a data-toggle="tooltip" data-placement="top" title="<?= $title ?>"
                   href="<?= base_url() ?>admin/projects/<?= $url ?>" class="btn-xs btn btn-<?= $btn ?>"><i
                            class="fa fa-thumb-tack"></i></a>
            </div>
            <span class="btn-xs pull-right">
                <?php
                if ($leads_details->converted_client_id == 0) {
                    if (!empty($can_edit) && !empty($edited)) { ?>
                        <a href="<?= base_url() ?>admin/leads/create/<?= $leads_details->leads_id ?>"><?= lang('edit') . ' ' . lang('leads') ?></a>
                    <?php }
                } else {
                    $c_edited = can_action('4', 'edited');
                    if (!empty($c_edited)) {
                        ?>
                        <a href="<?php echo base_url() ?>admin/client/manage_client/<?= $leads_details->converted_client_id ?>"
                           class="btn-xs pull-right"><i class="fa fa-edit"></i>
                            <?= lang('edit') . ' ' . lang('client') ?></a>
                    <?php }
                } ?>
            </span>
        </h3>
    </div>
    <div class="panel-body row form-horizontal task_details">
        <div class="form-group col-sm-12">
            <div class="col-sm-6">
                <label class="control-label col-sm-5"><strong><?= lang('lead_name') ?> :</strong>
                </label>
                <p class="form-control-static"><?php
                    if (!empty($leads_details->lead_name)) {
                        echo $leads_details->lead_name;
                    }
                    ?></p>

            </div>
            <div class="col-sm-6">
                <label class="control-label col-sm-5"><strong><?= lang('lead_source') ?> :</strong>
                </label>
                <?php
                if (!empty($leads_details->lead_source_id)) {
                    $lead_source = $this->db->where('lead_source_id', $leads_details->lead_source_id)->get('tbl_lead_source')->row();
                    if (!empty($lead_source->lead_source)) {
                        ?>
                        <div class="mt">
                            <p class="label label-info form-control-static">
                                <?php echo $lead_source->lead_source; ?></p>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>

        </div>
        <div class="form-group col-sm-12">
            <div class="col-sm-6">
                <label class="control-label col-sm-5"><strong><?= lang('lead_status') ?>
                        :</strong></label>
                <div class="pull-left">
                    <?php
                    if (!empty($leads_details->lead_status_id)) {
                        $lead_status = $this->db->where('lead_status_id', $leads_details->lead_status_id)->get('tbl_lead_status')->row();

                        if ($lead_status->lead_type == 'open') {
                            $status = "<span class='label label-success'>" . lang($lead_status->lead_type) . "</span>";
                        } else {
                            $status = "<span class='label label-warning'>" . lang($lead_status->lead_type) . "</span>";
                        } ?>
                        <p class="form-control-static"><?= $status . ' ' . $lead_status->lead_status ?></p>
                    <?php }
                    ?>
                </div>
                <?php
                if ($leads_details->converted_client_id == 0) {
                    if (!empty($can_edit) && !empty($edited)) {
                        ?>
                        <div class="col-sm-1 pull-right mt">
                            <div class="btn-group">
                                <button class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown">
                                    <?= lang('change') ?>
                                    <span class="caret"></span></button>
                                <ul class="dropdown-menu animated zoomIn">
                                    <?php
                                    $status_info = $this->db->get('tbl_lead_status')->result();
                                    if (!empty($status_info)) {
                                        foreach ($status_info as $v_status) {
                                            ?>
                                            <li>
                                                <a href="<?= base_url() ?>admin/leads/change_status/<?= $leads_details->leads_id ?>/<?= $v_status->lead_status_id ?>"><?= lang($v_status->lead_type) . '-' . $v_status->lead_status ?></a>
                                            </li>
                                            <?php
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    <?php }
                }
                ?>
            </div>
            <div class="col-sm-6">
                <label class="control-label col-sm-5"><strong><?= lang('tags') ?> : </strong></label>
                <p class="form-control-static">
                    <?php
                    if (!empty($leads_details->tags)) {
                        echo get_tags($leads_details->tags, true);
                    }
                    ?>
                </p>
            </div>
        </div>
        <div class="form-group col-sm-12">
            <div class="col-sm-6">
                <label class="control-label col-sm-5"><strong><?= lang('contact_name') ?>
                        : </strong></label>
                <p class="form-control-static">
                    <?php
                    if (!empty($leads_details->contact_name)) {
                        echo $leads_details->contact_name;
                    }
                    ?>
                </p>
            </div>
            <div class="col-sm-6">
                <label class="control-label col-sm-5"><strong><?= lang('email') ?> :</strong> </label>
                <p class="form-control-static">
                    <?php
                    if (!empty($leads_details->email)) {
                        echo $leads_details->email;
                    }
                    ?>
                </p>
            </div>
        </div>


        <div class="form-group col-sm-12">
            <div class="col-sm-6">
                <label class="control-label col-sm-5"><strong><?= lang('phone') ?> : </strong></label>
                <p class="form-control-static">
                    <?php
                    if (!empty($leads_details->phone)) {
                        echo $leads_details->phone;
                    }
                    ?>
                </p>
            </div>
            <div class="col-sm-6">
                <label class="control-label col-sm-5"><strong><?= lang('organization') ?> :</strong>
                </label>
                <p class="form-control-static">
                    <?php
                    if (!empty($leads_details->organization)) {
                        echo $leads_details->organization;
                    }
                    ?>
                </p>
            </div>
        </div>
        <div class="form-group col-sm-12">
            <div class="col-sm-6">
                <label class="control-label col-sm-5"><strong><?= lang('mobile') ?> :</strong></label>
                <p class="form-control-static">
                    <?php
                    if (!empty($leads_details->mobile)) {
                        echo $leads_details->mobile;
                    }
                    ?>
                </p>
            </div>
            <div class="col-sm-6">
                <label class="control-label col-sm-5"><strong><?= lang('address') ?>: </strong></label>
                <p class="form-control-static">
                    <?php
                    if (!empty($leads_details->address)) {
                        echo $leads_details->address;
                    }
                    ?>
                </p>
            </div>
        </div>
        <div class="form-group col-sm-12">
            <div class="col-sm-6">
                <label class="control-label col-sm-5"><strong><?= lang('city') ?>: </strong></label>
                <p class="form-control-static">
                    <?php
                    if (!empty($leads_details->city)) {
                        echo $leads_details->city;
                    }
                    ?>
                </p>
            </div>
            <div class="col-sm-6">
                <label class="control-label col-sm-5"><strong><?= lang('country') ?>: </strong></label>
                <p class="form-control-static">
                    <?php
                    if (!empty($leads_details->country)) {
                        echo $leads_details->country;
                    }
                    ?>
                </p>
            </div>
        </div>
        <div class="form-group col-sm-12">
            <div class="col-sm-6">
                <label class="control-label col-sm-5"><strong><?= lang('language') ?>: </strong></label>
                <p class="form-control-static">
                    <?php
                    if (!empty($leads_details->language)) {
                        echo $leads_details->language;
                    }
                    ?>
                </p>
            </div>
            <div class="col-sm-6">
                <label class="control-label col-sm-5"><strong><?= lang('state') ?>
                        : </strong></label>
                <p class="form-control-static"><?php
                    if (!empty($leads_details->state)) {
                        echo $leads_details->state;
                    }
                    ?></p>
            </div>

        </div>
        <div class="form-group col-sm-12">
            <div class="col-sm-6">
                <label class="control-label col-sm-5"><strong><?= lang('facebook_profile_link') ?>
                        : </strong></label>
                <a target="_blank" href="//<?php
                if (!empty($leads_details->facebook)) {
                    echo $leads_details->facebook;
                }
                ?>">
                    <p class="form-control-static"><?php
                        if (!empty($leads_details->facebook)) {
                            echo $leads_details->facebook;
                        }
                        ?></p>
                </a>
            </div>
            <div class="col-sm-6">
                <label class="control-label col-sm-5"><strong><?= lang('skype_id') ?>: </strong></label>
                <a href="skype:'<?php
                if (!empty($leads_details->skype)) {
                    echo $leads_details->skype;
                }
                ?>'">
                    <p class="form-control-static"><?php
                        if (!empty($leads_details->skype)) {
                            echo $leads_details->skype;
                        }
                        ?></p>
                </a>
            </div>
        </div>

        <div class="form-group col-sm-12">
            <div class="col-sm-6">
                <label class="control-label col-sm-5"><strong><?= lang('created') ?>
                        : </strong></label>
                <p class="form-control-static"><?= time_ago($leads_details->created_time) ?></p>
            </div>
            <div class="col-sm-6">
                <label class="control-label col-sm-5"><strong><?= lang('last_contact') ?>
                        : </strong></label>
                <p class="form-control-static"><?php
                    if (!empty($leads_details->last_contact)) {
                        echo time_ago($leads_details->last_contact);
                    } else {
                        echo '-';
                    }
                    ?></p>
            </div>
        </div>
        <?php $show_custom_fields = custom_form_label(5, $leads_details->leads_id);
        if (!empty($show_custom_fields)) {
            foreach ($show_custom_fields as $c_label => $v_fields) {
                if (!empty($v_fields)) {
                    if (count(array($v_fields)) == 1) {
                        $col = 'col-sm-10';
                        $sub_col = 'col-sm-3';
                        $style = 'padding-left:21px';
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

            <?php if ($leads_details->permission != '-') { ?>
                <div class="form-group  col-sm-6">
                    <label class="control-label col-sm-5"><strong><?= lang('participants') ?>
                            :</strong></label>
                    <div class="col-sm-7 ">
                        <?php
                        if ($leads_details->permission != 'all') {
                            $get_permission = json_decode($leads_details->permission);
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


                                    <a href="#" data-toggle="tooltip" data-placement="top"
                                       title="<?= $profile_info->fullname ?>"><img
                                                src="<?= base_url() . $profile_info->avatar ?>"
                                                class="img-circle img-xs" alt="">
                                        <span class="custom-permission circle <?= $label ?>  circle-lg"></span>
                                    </a>
                                <?php
                                endforeach;
                            endif;
                        } else { ?>
                        <p class="form-control-static"><strong><?= lang('everyone') ?></strong>
                            <i title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle"
                               data-toggle="tooltip" data-placement="top"></i>

                            <?php
                            }
                            ?>
                            <?php
                            if ($leads_details->converted_client_id == 0) { ?>
                            <?php
                            if (!empty($can_edit) && !empty($edited)) {
                            ?>
                            <span data-placement="top" data-toggle="tooltip" title="<?= lang('add_more') ?>">
                                        <a data-toggle="modal" data-target="#myModal"
                                           href="<?= base_url() ?>admin/leads/update_users/<?= $leads_details->leads_id ?>"
                                           class="text-default ml"><i class="fa fa-plus"></i></a>
                                    </span>
                        </p>
                    <?php
                    }
                    }
                    ?>

                    </div>
                </div>
            <?php } ?>
            <div class="col-sm-6"><label class="control-label col-sm-5"><strong><?= lang('twitter_profile_link') ?>
                        : </strong></label>
                <a target="_blank" href="//<?php
                if (!empty($leads_details->twitter)) {
                    echo $leads_details->twitter;
                }
                ?>">
                    <p class="form-control-static"><?php
                        if (!empty($leads_details->twitter)) {
                            echo $leads_details->twitter;
                        }
                        ?></p>
                </a>
            </div>
        </div>
        <div class="col-sm-12">
            <blockquote style="font-size: 12px;"><?php
                if (!empty($leads_details->notes)) {
                    echo $leads_details->notes;
                }
                ?> </blockquote>
        </div>
    </div>

</div>