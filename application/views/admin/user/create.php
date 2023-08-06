<?php include_once 'assets/admin-ajax.php'; ?>

<?= message_box('success'); ?>
<?= message_box('error');
$created = can_action('24', 'created');
$edited = can_action('24', 'edited');
$deleted = can_action('24', 'deleted');


if (!empty($created) || !empty($edited)) {
?>

    <div class="nav-tabs-custom">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs">
            <li class="<?= $active == 1 ? 'active' : ''; ?>"><a href="<?= base_url('admin/user/user_list') ?>"><?= lang('all_users') ?></a></li>
            <li class="<?= $active == 2 ? 'active' : ''; ?>"><a href="<?= base_url('admin/user/create') ?>"><?= lang('new_user') ?></a>
            </li>
        </ul>
        <div class="tab-content bg-white">

            <?php
            $user_id = null;
            if (!empty($login_info->user_id)) {
                $profile_info = $this->user_model->check_by(array('user_id' => $login_info->user_id), 'tbl_account_details');
                $user_id = $login_info->user_id;
            }
            ?>
            <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="new">
                <form role="form" data-parsley-validate="" novalidate="" id="userform" enctype="multipart/form-data" action="<?php echo base_url(); ?>admin/user/save_user/<?= $user_id ?>" method="post" class="form-horizontal form-groups-bordered">
                    <input type="hidden" id="username_flag" value="">
                    <input type="hidden" id="user_id" name="user_id" value="<?php
                                                                            if (!empty($login_info)) {
                                                                                echo $login_info->user_id;
                                                                            }
                                                                            ?>">
                    <input type="hidden" name="account_details_id" value="<?php
                                                                            if (!empty($profile_info)) {
                                                                                echo $profile_info->account_details_id;
                                                                            }
                                                                            ?>">

                    <div class="form-group">
                        <label class="col-sm-3 control-label"><strong><?= lang('full_name') ?> </strong><span class="text-danger">*</span></label>
                        <div class="col-sm-5">
                            <input type="text" class="input-sm form-control" value="<?php
                                                                                    if (!empty($profile_info)) {
                                                                                        echo $profile_info->fullname;
                                                                                    }
                                                                                    ?>" placeholder="<?= lang('eg') ?> <?= lang('enter_your') . ' ' . lang('full_name') ?>" name="fullname" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><strong><?= lang('employment_id') ?> </strong></label>
                        <div class="col-sm-5">
                            <input type="text" id="check_employment_id" class="input-sm form-control" value="<?php
                                                                                                                if (!empty($profile_info)) {
                                                                                                                    echo $profile_info->employment_id;
                                                                                                                }
                                                                                                                ?>" placeholder="<?= lang('eg') ?> 15351" name="employment_id">
                            <span class="required" id="employment_id_error"></span>
                        </div>
                    </div>
                    <?php if (empty($login_info->user_id)) { ?>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"><strong> <?= lang('username'); ?></strong><span class="text-danger">*</span></label>
                            <div class="col-sm-5">
                                <input type="text" name="username" id="check_username" placeholder="<?= lang('eg') ?> <?= lang('enter_your') . ' ' . lang('username') ?>" value="<?php
                                                                                                                                                                                    if (!empty($login_info)) {
                                                                                                                                                                                        echo $login_info->username;
                                                                                                                                                                                    }
                                                                                                                                                                                    ?>" class="input-sm form-control" required>
                                <span class="required" id="check_username_error"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label"><strong><?= lang('password') ?> </strong><span class="text-danger">*</span></label>
                            <div class="col-sm-5">
                                <input type="password" id="new_password" placeholder="<?= lang('password') ?>" name="password" class="input-sm form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"><strong><?= lang('confirm_password') ?> </strong><span class="text-danger">*</span></label>
                            <div class="col-sm-5">
                                <input type="password" data-parsley-equalto="#new_password" placeholder="<?= lang('confirm_password') ?>" name="confirm_password" class="input-sm form-control" required>
                            </div>
                        </div>
                    <?php } else { ?>
                        <input type="hidden" name="username" placeholder="<?= lang('eg') ?> <?= lang('user_placeholder_username') ?>" value="<?php
                                                                                                                                                if (!empty($login_info)) {
                                                                                                                                                    echo $login_info->username;
                                                                                                                                                }
                                                                                                                                                ?>" class="input-sm form-control" required>
                    <?php } ?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><strong><?= lang('email') ?> </strong><span class="text-danger">*</span></label>
                        <div class="col-sm-5">
                            <input type="email" id="check_email_addrees" placeholder="<?= lang('eg') ?> <?= lang('user_placeholder_email') ?>" name="email" value="<?php
                                                                                                                                                                    if (!empty($login_info)) {
                                                                                                                                                                        echo $login_info->email;
                                                                                                                                                                    }
                                                                                                                                                                    ?>" class="input-sm form-control" required>
                            <span class="required" id="email_addrees_error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><strong><?= lang('locale') ?></strong></label>
                        <div class="col-lg-5">
                            <select class="  form-control select_box" style="width: 100%" name="locale">

                                <?php
                                $locales = $this->db->get('tbl_locales')->result();
                                foreach ($locales as $loc) :
                                ?>
                                    <option lang="<?= $loc->code ?>" value="<?= $loc->locale ?>" <?php
                                                                                                    if (!empty($profile_info)) {
                                                                                                        if ($profile_info->locale == $loc->locale) {
                                                                                                            echo 'selected';
                                                                                                        }
                                                                                                    } else {
                                                                                                        echo ($this->config->item('locale') == $loc->locale ? 'selected="selected"' : '');
                                                                                                    }
                                                                                                    ?>>
                                        <?= $loc->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><strong><?= lang('language') ?></strong></label>
                        <div class="col-sm-5">
                            <select name="language" class="form-control select_box" style="width: 100%">
                                <?php foreach ($languages as $lang) : ?>
                                    <option value="<?= $lang->name ?>" <?php
                                                                        if (!empty($profile_info)) {
                                                                            if ($profile_info->language == $lang->name) {
                                                                                echo 'selected';
                                                                            }
                                                                        } else {
                                                                            echo ($this->config->item('language') == $lang->name ? ' selected="selected"' : '');
                                                                        }
                                                                        ?>><?= ucfirst($lang->name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><strong><?= lang('phone') ?> </strong></label>
                        <div class="col-sm-5">
                            <input type="text" class="input-sm form-control" value="<?php
                                                                                    if (!empty($profile_info)) {
                                                                                        echo $profile_info->phone;
                                                                                    }
                                                                                    ?>" name="phone" placeholder="<?= lang('eg') ?> <?= lang('user_placeholder_phone') ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><strong><?= lang('mobile') ?> </strong></label>
                        <div class="col-sm-5">
                            <input type="text" class="input-sm form-control" value="<?php
                                                                                    if (!empty($profile_info)) {
                                                                                        echo $profile_info->mobile;
                                                                                    }
                                                                                    ?>" name="mobile" placeholder="<?= lang('eg') ?> <?= lang('user_placeholder_mobile') ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><strong><?= lang('skype_id') ?> </strong></label>
                        <div class="col-sm-5">
                            <input type="text" class="input-sm form-control" value="<?php
                                                                                    if (!empty($profile_info)) {
                                                                                        echo $profile_info->skype;
                                                                                    }
                                                                                    ?>" name="skype" placeholder="<?= lang('eg') ?> <?= lang('user_placeholder_skype') ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><strong><?= lang('profile_photo') ?></strong><span class="text-danger">*</span></label>
                        <div class="col-lg-5">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 210px;">
                                    <?php
                                    if (!empty($profile_info)) :
                                    ?>
                                        <img src="<?php echo base_url() . $profile_info->avatar; ?>">
                                    <?php else : ?>
                                        <img src="<?= base_url('uploads/default_avatar.jpg') ?>" alt="Please Connect Your Internet">
                                    <?php endif; ?>
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="width: 210px;"></div>
                                <div>
                                    <span class="btn btn-default btn-file">
                                        <span class="fileinput-new">
                                            <input type="file" name="avatar" value="upload" data-buttonText="<?= lang('choose_file') ?>" id="myImg" />
                                            <span class="fileinput-exists"><?= lang('change') ?></span>
                                        </span>
                                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput"><?= lang('remove') ?></a>

                                        <div id="valid_msg" style="color: #e11221"></div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    $wData['itemType'] = 'staff';
                    if (!empty($profile_info)) {
                        $wData['warehouseID'] = $profile_info->warehouse_id;
                    }
                    $this->load->view('admin/items/warehouselist', $wData)
                    ?>

                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><strong><?= lang('user_type') ?></strong><span class="required">*</span></label>
                        <div class="col-sm-5">
                            <select id="user_type" name="role_id" class="form-control" required>
                                <option value=""><?= lang('select_user_type') ?></option>
                                <?php
                                $admin = admin();
                                if (!empty($admin)) {
                                ?>
                                    <option <?php
                                            if (!empty($login_info)) {
                                                echo $login_info->role_id == 1 ? 'selected' : '';
                                            }
                                            ?> value="1"><?= lang('admin') ?></option>
                                <?php } ?>
                                <option <?php
                                        if (!empty($login_info)) {
                                            echo $login_info->role_id == 3 ? 'selected' : '';
                                        }
                                        ?> value="3"><?= lang('staff') ?></option>
                                <option <?php
                                        if (!empty($login_info)) {
                                            echo $login_info->role_id == 2 ? 'selected' : '';
                                        }
                                        ?> value="2"><?= lang('client') ?></option>
                            </select>
                        </div>
                    </div>


                    <?php
                    if (!empty($profile_info->direction)) {
                        $direction = $profile_info->direction;
                    } else {
                        $RTL = config_item('RTL');
                        if (!empty($RTL)) {
                            $direction = 'rtl';
                        }
                    }
                    ?>


                    <div class="form-group">
                        <label for="direction" class="control-label col-sm-3"><?= lang('direction') ?></label>
                        <div class="col-sm-5">
                            <select name="direction" class="selectpicker" data-width="100%">
                                <option <?php
                                        if (!empty($direction)) {
                                            echo $direction == 'ltr' ? 'selected' : '';
                                        }
                                        ?> value="ltr"><?= lang('ltr') ?></option>
                                <option <?php
                                        if (!empty($direction)) {
                                            echo $direction == 'rtl' ? 'selected' : '';
                                        }
                                        ?> value="rtl"><?= lang('rtl') ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12" <?php
                                            if (!empty($login_info) && $login_info->role_id == 2) {
                                                echo 'style="display:block"';
                                            }
                                            ?>>
                        <div class="col-sm-3"></div>
                        <div class="col-sm-6 row">
                            <div id="client_permission" class="panel panel-custom ">
                                <div class="panel-heading">
                                    <h4 class="modal-title" id="myModalLabel">
                                        <?= lang('select') . ' ' . lang('client') . ' &  ' . lang('permission') ?></h4>
                                </div>
                                <style type="text/css">
                                    .toggle.btn-xs {
                                        min-width: 59px;
                                    }
                                </style>
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label"><strong><?= lang('companies') ?>
                                            </strong></label>
                                        <div class="col-sm-6">
                                            <select class="form-control select_box" style="width: 100%" name="company">
                                                <option value="-"><?= lang('select_client') ?></option>
                                                <?php
                                                if (!empty($all_client_info)) {
                                                    foreach ($all_client_info as $v_client) {
                                                ?>
                                                        <option value="<?= $v_client->client_id ?>" <?php
                                                                                                    if (!empty($profile_info)) {
                                                                                                        if ($profile_info->company == $v_client->client_id) {
                                                                                                            echo 'selected';
                                                                                                        }
                                                                                                    }
                                                                                                    ?>>
                                                            <?= $v_client->name ?></option>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <?php
                                    $all_client_menu = $this->db->where('parent', 0)->order_by('sort')->get('tbl_client_menu')->result();
                                    if (!empty($login_info)) {
                                        $user_menu = $this->db->where('user_id', $login_info->user_id)->get('tbl_client_role')->result();
                                    }

                                    foreach ($all_client_menu as $key => $v_menu) {
                                    ?>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label"><?= lang($v_menu->label) ?></label>
                                            <div class="col-lg-6 checkbox">
                                                <input class="client_permission" data-toggle="toggle" name="<?= $v_menu->label ?>" value="<?= $v_menu->menu_id ?>" <?php
                                                                                                                                                                    if (!empty($user_menu)) {
                                                                                                                                                                        foreach ($user_menu as $v_u_menu) {
                                                                                                                                                                            if ($v_u_menu->menu_id == $v_menu->menu_id) {
                                                                                                                                                                                echo 'checked';
                                                                                                                                                                            }
                                                                                                                                                                        }
                                                                                                                                                                    } else {
                                                                                                                                                                        $client_menu = unserialize(config_item('client_default_menu'));
                                                                                                                                                                        if (!empty($client_menu['client_default_menu'])) {
                                                                                                                                                                            foreach ($client_menu['client_default_menu'] as $default_menu) {
                                                                                                                                                                                echo $v_menu->menu_id == $default_menu ? 'checked' : '';
                                                                                                                                                                            }
                                                                                                                                                                        }
                                                                                                                                                                    } ?> data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" type="checkbox">
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3"></div>
                    </div>


                    <div class="form-group" id="department" <?php
                                                            if (!empty($login_info) && $login_info->role_id != 2) {
                                                                echo 'style="display:block"';
                                                            }
                                                            ?>>
                        <label class="col-sm-3 control-label"><strong><?= lang('designation') ?> </strong><span class="text-danger">*</span></label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <select class="form-control select_box department" required style="width: 100%" name="designations_id">
                                    <option value=""><?= lang('select') . ' ' . lang('designation'); ?></option>
                                    <?php
                                    if (!empty($all_designation_info)) {
                                        foreach ($all_designation_info as $dept_name => $v_designation_info) {
                                    ?>
                                            <optgroup label="<?= $dept_name ?>">
                                                <?php if (!empty($v_designation_info)) {
                                                    foreach ($v_designation_info as $v_designation) { ?>
                                                        <option value="<?= $v_designation->designations_id ?>" <?php
                                                                                                                if (!empty($profile_info)) {
                                                                                                                    if ($profile_info->designations_id == $v_designation->designations_id) {
                                                                                                                        echo 'selected';
                                                                                                                    }
                                                                                                                }
                                                                                                                ?>>
                                                            <?= $v_designation->designations; ?></option>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </optgroup>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <?php
                                $acreated = can_action('70', 'created');
                                if (!empty($acreated)) { ?>
                                    <div class="input-group-addon" title="<?= lang('new') . ' ' . lang('designation') ?>" data-toggle="tooltip" data-placement="top">
                                        <a data-toggle="modal" data-target="#myModal_extra_lg" href="<?= base_url() ?>admin/departments/new_designation"><i class="fa fa-plus"></i></a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php
                        if (!empty($profile_info->designations_id)) {
                            $designation_info = $this->db->where('designations_id', $profile_info->designations_id)->get('tbl_designations')->row();

                            if (!empty($designation_info)) {
                                $departments_info = $this->db->where('departments_id', $designation_info->departments_id)->get('tbl_departments')->row();
                            }
                        }
                        ?>
                        <div class="col-sm-4">
                            <div class="checkbox-inline c-checkbox">
                                <label class="needsclick">
                                    <input <?php if (!empty($departments_info) && $profile_info->user_id == $departments_info->department_head_id) {
                                                echo 'checked';
                                            } ?> name="department_head_id" value="1" type="checkbox" style="margin-right: 8px;" class="">
                                    <span class="fa fa-check"></span>
                                    <?= lang('is_he_department_head') ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <?php
                    if (!empty($profile_info->user_id)) {
                        $user_id = $profile_info->user_id;
                    } else {
                        $user_id = null;
                    }
                    ?>
                    <?= custom_form_Fields(13, $user_id); ?>

                    <?php
                    $permissionL = null;
                    if (!empty($login_info->permission)) {
                        $permissionL = $login_info->permission;
                    }
                    ?>
                    <?= get_permission(3, 9, $permission_user, $permissionL, ''); ?>
                    <div class="btn-bottom-toolbar text-right">
                        <?php
                        if (!empty($user_id)) { ?>
                            <button type="submit" class="btn btn-sm btn-primary"><?= lang('update_user') ?></button>
                            <button type="button" onclick="goBack()" class="btn btn-sm btn-danger"><?= lang('cancel') ?></button>
                        <?php } else {
                        ?>
                            <button type="submit" class="btn btn-sm btn-primary"><?= lang('create_user') ?></button>
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
    <script>
        <?php if (!empty($edited)) { ?>
            $(document).ready(function() {
                $('#department').hide();
                $('#client_permission').hide();
                var user_flag = document.getElementById("user_type").value;
                // on change user type select action
                $('#user_type').on('change', function() {
                    if (this.value == '3' || this.value == '1') {
                        $("#department").show();
                        $(".department").removeAttr('disabled');
                        $('#client_permission').hide();
                        $(".client_permission").attr('disabled', 'disabled');
                        $(".department").attr('required', true);
                    } else if (this.value == '2') {
                        $('#client_permission').show();
                        $(".client_permission").removeAttr('disabled');
                        $("#department").hide();
                        $(".department").attr('disabled', 'disabled');
                        $(".department").removeAttr('required');

                    } else {
                        $('#client_permission').hide();
                        $(".client_permission").attr('disabled', 'disabled');
                        $("#department").hide();
                        $(".department").attr('disabled', 'disabled');
                    }
                });
            });
        <?php } ?>
    </script>
    <?php
    if (!empty($login_info) && $login_info->role_id != 2) { ?>
        <script>
            $(document).ready(function() {
                $('#department').show();
                $(".department").removeAttr('disabled');
                $('#client_permission').hide();
                $(".client_permission").attr('disabled', 'disabled');
            });
        </script>
        <?php }
        ?><?php
            if (!empty($login_info) && $login_info->role_id == 2) { ?>
        <script>
            $(document).ready(function() {
                $('#client_permission').show();
                $(".client_permission").removeAttr('disabled');
                $("#department").hide();
                $(".department").attr('disabled', 'disabled');
                $(".department").removeAttr('required');
            });
        </script>
    <?php }
    ?>
    <script type="text/javascript">
        function getItemByWarehouse(warehouseId) {            
            warehouse_id = warehouseId;
            $("[name='warehouse_id']").val(warehouse_id);
        }
    </script>