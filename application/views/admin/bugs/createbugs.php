<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<?php include_once 'assets/admin-ajax.php'; ?>

<div id="bugs_state_report_div">
</div>


<?php
$created = can_action('58', 'created');
$edited = can_action('58', 'edited');
$deleted = can_action('58', 'deleted');
if (!empty($created) || !empty($edited)) {
?>
<div class="row">
    <div class="col-sm-12">

        <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs">
                <li class="<?= $active == 1 ? 'active' : '' ?>"><a
                            href="<?= base_url('admin/bugs') ?>"><?= lang('all_bugs') ?></a></li>
                <li class="<?= $active == 2 ? 'active' : '' ?>"><a
                            href="<?= base_url('admin/bugs/create') ?>"><?= lang('new_bugs') ?></a></li>

            </ul>
            <div class="tab-content bg-white">
                <!-- Stock Category List tab Starts -->


                <!-- Add Stock Category tab Starts -->
                <div class="tab-pane <?= $active == 2 ? 'active' : '' ?>" id="assign_task" style="position: relative;">
                    <div class="box" style="border: none; padding-top: 15px;" data-collapsed="0">
                        <div class="panel-body row">
                            <form data-parsley-validate="" novalidate=""
                                  action="<?php echo base_url() ?>admin/bugs/save_bug/<?php if (!empty($bug_info->bug_id)) echo $bug_info->bug_id; ?>"
                                  method="post" class="form-horizontal">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label"><?= lang('issue_#') ?><span
                                                    class="required">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" style="width:260px" value="<?php
                                            $this->load->helper('string');
                                            if (!empty($bug_info)) {
                                                echo $bug_info->issue_no;
                                            } else {
                                                echo strtoupper(random_string('alnum', 7));
                                            }
                                            ?>"
                                                   name="issue_no">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label"><?= lang('bug_title') ?><span
                                                    class="required">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="bug_title" required class="form-control"
                                                   value="<?php if (!empty($bug_info->bug_title)) echo $bug_info->bug_title; ?>"/>
                                        </div>
                                    </div>
                                    <?php
                                    if (!empty($bug_info->project_id)) {
                                        $project_id = $bug_info->project_id;
                                    } elseif (!empty($project_id)) {
                                        $project_id = $project_id; ?>
                                        <input type="hidden" name="un_project_id" required class="form-control"
                                               value="<?php echo $project_id ?>"/>
                                    <?php }
                                    if (!empty($bug_info->opportunities_id)) {
                                        $opportunities_id = $bug_info->opportunities_id;
                                    } elseif (!empty($opportunities_id)) {
                                        $opportunities_id = $opportunities_id; ?>
                                        <input type="hidden" name="un_opportunities_id" required class="form-control"
                                               value="<?php echo $opportunities_id ?>"/>
                                    <?php }
                                    ?>
                                    <div class="form-group" id="border-none">
                                        <label for="field-1" class="col-sm-3 control-label"><?= lang('related_to') ?>
                                        </label>
                                        <div class="col-sm-8">
                                            <select name="related_to" class="form-control" id="check_related"
                                                    onchange="get_related_moduleName(this.value)">
                                                <option value="0"> <?= lang('none') ?> </option>
                                                <option value="project" <?= (!empty($project_id) ? 'selected' : '') ?>>
                                                    <?= lang('project') ?> </option>
                                                <option value="opportunities"
                                                    <?= (!empty($opportunities_id) ? 'selected' : '') ?>>
                                                    <?= lang('opportunities') ?> </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="related_to">
                                    </div>
                                    <?php
                                    if (!empty($project_id)) : ?>
                                        <div class="form-group <?= !empty($project_id) ? '' : 'company' ?>">
                                            <label for="field-1" class="col-sm-3 control-label"><?= lang('project') ?>
                                                <span class="required">*</span></label>
                                            <div class="col-sm-8">
                                                <select name="project_id" style="width: 100%"
                                                        class="select_box <?= !empty($project_id) ? '' : 'company' ?>"
                                                        required="1">
                                                    <?php
                                                    $all_project = $this->bugs_model->get_permission('tbl_project');
                                                    if (!empty($all_project)) {
                                                        foreach ($all_project as $v_project) {
                                                            ?>
                                                            <option value="<?= $v_project->project_id ?>" <?php
                                                            if (!empty($project_id)) {
                                                                echo $v_project->project_id == $project_id ? 'selected' : '';
                                                            }
                                                            ?>>
                                                                <?= $v_project->project_name ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div id="milestone"></div>
                                        </div>
                                    <?php endif ?>
                                    <?php if (!empty($opportunities_id)) : ?>
                                        <div class="form-group <?= !empty($opportunities_id) ? '' : 'company' ?>">
                                            <label for="field-1"
                                                   class="col-sm-3 control-label"><?= lang('opportunities') ?>
                                                <span class="required">*</span></label>
                                            <div class="col-sm-8">
                                                <select name="opportunities_id" style="width: 100%"
                                                        class="select_box <?= !empty($opportunities_id) ? '' : 'company' ?>"
                                                        required="1">
                                                    <?php
                                                    if (!empty($all_opportunities_info)) {
                                                        foreach ($all_opportunities_info as $v_opportunities) {
                                                            ?>
                                                            <option value="<?= $v_opportunities->opportunities_id ?>"
                                                                <?php
                                                                if (!empty($opportunities_id)) {
                                                                    echo $v_opportunities->opportunities_id == $opportunities_id ? 'selected' : '';
                                                                }
                                                                ?>><?= $v_opportunities->opportunity_name ?>
                                                            </option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                    <div class="form-group" id="border-none">
                                        <label for="field-1" class="col-sm-3 control-label"><?= lang('reporter') ?>
                                            <span class="required">*</span></label>
                                        <div class="col-sm-8">
                                            <select name="reporter" style="width: 100%" class="select_box" required="">
                                                <?php
                                                $type = $this->uri->segment(4);
                                                if (!empty($type) && !is_numeric($type)) {
                                                    $ex = explode('_', $type);
                                                    if ($ex[0] == 'c') {
                                                        $primary_contact = $ex[1];
                                                    }
                                                }
                                                $reporter_info = $this->db->get('tbl_users')->result();
                                                if (!empty($reporter_info)) {
                                                    foreach ($reporter_info as $key => $v_reporter) {
                                                        $users_info = $this->db->where(array("user_id" => $v_reporter->user_id))->get('tbl_account_details')->row();
                                                        if (!empty($users_info)) {
                                                            if ($v_reporter->role_id == 1) {
                                                                $role = lang('admin');
                                                            } elseif ($v_reporter->role_id == 2) {
                                                                $role = lang('client');
                                                            } else {
                                                                $role = lang('staff');
                                                            }
                                                            ?>
                                                            <option value="<?= $users_info->user_id ?>" <?php
                                                            if (!empty($bug_info->reporter)) {
                                                                echo $v_reporter->user_id == $bug_info->reporter ? 'selected' : '';
                                                            } else if (!empty($primary_contact) && $primary_contact == $users_info->user_id) {
                                                                echo 'selected';
                                                            }
                                                            ?>>
                                                                <?= $users_info->fullname . ' (' . $role . ')'; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('priority') ?> <span
                                                    class="text-danger">*</span> </label>
                                        <div class="col-lg-8">
                                            <div class=" ">
                                                <select name="priority" class="form-control">
                                                    <?php
                                                    $priorities = $this->db->get('tbl_priority')->result();
                                                    // print('<pre>'.print_r($priorities,true).'</pre>'); exit;
                                                    if (!empty($priorities)) {
                                                        foreach ($priorities as $v_priorities) :
                                                            ?>
                                                            <option value="<?= $v_priorities->priority ?>" <?php
                                                            if (!empty($bug_info) && $bug_info->priority == $v_priorities->priority) {
                                                                echo 'selected';
                                                            }
                                                            ?>>
                                                                <?= ($v_priorities->priority) ?></option>
                                                        <?php
                                                        endforeach;
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <?= get_permission(3, 8, $assign_user, $permissionL, lang('assined_to')); ?>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('severity') ?> <span
                                                    class="text-danger">*</span> </label>
                                        <div class="col-lg-8">
                                            <div class=" ">
                                                <select name="severity" class="form-control">
                                                    <?php
                                                    $severity = array('minor', 'major', 'show_stopper', 'must_be_fixed');
                                                    if (!empty($severity)) {
                                                        foreach ($severity as $v_severity) :
                                                            ?>
                                                            <option value="<?= $v_severity ?>" <?php
                                                            if (!empty($bug_info) && $bug_info->severity == $v_severity) {
                                                                echo 'selected';
                                                            }
                                                            ?>>
                                                                <?= lang($v_severity) ?></option>
                                                        <?php
                                                        endforeach;
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group" id="border-none">
                                        <label for="field-1" class="col-sm-3 control-label"><?= lang('bug_status') ?>
                                            <span class="required">*</span></label>
                                        <div class="col-sm-8">

                                            <select name="bug_status" class="form-control" required>
                                                <option value="unconfirmed"
                                                    <?php if (!empty($bug_info->bug_status)) echo $bug_info->bug_status == 'unconfirmed' ? 'selected' : '' ?>>
                                                    <?= lang('unconfirmed') ?> </option>
                                                <option value="confirmed"
                                                    <?php if (!empty($bug_info->bug_status)) echo $bug_info->bug_status == 'confirmed' ? 'selected' : '' ?>>
                                                    <?= lang('confirmed') ?> </option>
                                                <option value="in_progress"
                                                    <?php if (!empty($bug_info->bug_status)) echo $bug_info->bug_status == 'in_progress' ? 'selected' : '' ?>>
                                                    <?= lang('in_progress') ?> </option>
                                                <option value="resolved"
                                                    <?php if (!empty($bug_info->bug_status)) echo $bug_info->bug_status == 'resolved' ? 'selected' : '' ?>>
                                                    <?= lang('resolved') ?> </option>
                                                <option value="verified"
                                                    <?php if (!empty($bug_info->bug_status)) echo $bug_info->bug_status == 'verified' ? 'selected' : '' ?>>
                                                    <?= lang('verified') ?> </option>
                                            </select>
                                        </div>
                                    </div>
                                    <?php if (!empty($project_id)) : ?>
                                        <div class="form-group">
                                            <label for="field-1"
                                                   class="col-sm-3 control-label"><?= lang('visible_to_client') ?>
                                                <span class="required">*</span></label>
                                            <div class="col-sm-8">
                                                <input data-toggle="toggle" name="client_visible" value="Yes" <?php
                                                if (!empty($bug_info) && $bug_info->client_visible == 'Yes') {
                                                    echo 'checked';
                                                }
                                                ?>
                                                       data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>"
                                                       data-onstyle="success" data-offstyle="danger" type="checkbox">
                                            </div>
                                        </div>
                                    <?php endif ?>
                                    <div class="form-group">
                                        <label for="field-1"
                                               class="col-sm-3 control-label"><?= lang('reproducibility') ?>
                                        </label>
                                        <div class="col-sm-7">
                                        <textarea class="form-control textarea"
                                                  name="reproducibility"><?php if (!empty($bug_info->reproducibility)) echo $bug_info->reproducibility; ?></textarea>
                                        </div>
                                    </div>
                                    <?php
                                    if (!empty($bug_info)) {
                                        $bug_id = $bug_info->bug_id;
                                        $permissionL = $bug_info->permission;
                                    } else {
                                        $bug_id = null;
                                        $permissionL = null;
                                    }
                                    ?>
                                    <?= custom_form_Fields(6, $bug_id); ?>

                                </div>
                                <div class="form-group col-md-12">
                                    <label for="field-1" class="col-sm-1 control-label"><?= lang('description') ?>
                                    </label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control textarea_"
                                                  name="bug_description"><?php if (!empty($bug_info->bug_description)) echo $bug_info->bug_description; ?></textarea>
                                    </div>
                                </div>

                                <div class="btn-bottom-toolbar text-right">
                                    <?php
                                    if (!empty($bug_info)) { ?>
                                        <button type="submit"
                                                class="btn btn-sm btn-primary"><?= lang('updates') ?></button>
                                        <button type="button" onclick="goBack()"
                                                class="btn btn-sm btn-danger"><?= lang('cancel') ?></button>
                                    <?php } else {
                                        ?>
                                        <button type="submit"
                                                class="btn btn-sm btn-primary"><?= lang('save') ?></button>
                                    <?php }
                                    ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php } else { ?>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
</div>


<script>
    $(document).ready(function () {
        ins_data(base_url + 'admin/bugs/bugs_state_report')
    });
</script>