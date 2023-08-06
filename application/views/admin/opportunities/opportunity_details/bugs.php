<?php
$edited = can_action('56', 'edited');

?>

<div class="box" style="border: none; padding-top: 15px;" data-collapsed="0">
    <div class="nav-tabs-custom">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs">
            <li class="active"><a href="#manage_bugs" data-toggle="tab"><?= lang('all_bugs') ?></a>
            </li>
            <?php if (!empty($edited)) { ?>
                <li class=""><a href="<?= base_url() ?>admin/bugs/create/opportunities/<?= $opportunity_details->opportunities_id ?>"><?= lang('new_bugs') ?></a>
                </li>
            <?php } ?>
        </ul>
        <div class="tab-content bg-white">
            <!-- ************** general *************-->
            <div class="tab-pane <?= $active == 'bugs' ? 'active' : ''; ?>" id="manage_bugs">
                <div class="table-responsive">
                    <table id="table-milestones" class="table table-striped">
                        <thead>
                            <tr>
                                <th><?= lang('bug_title') ?></th>
                                <th><?= lang('status') ?></th>
                                <th><?= lang('priority') ?></th>
                                <th><?= lang('reporter') ?></th>
                                <th><?= lang('action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // $all_bugs_info = $this->db->where('opportunities_id', $opportunity_details->opportunities_id)->order_by('bug_id', 'DESC')->get('tbl_bug')->result();
                            $all_bugs_info = get_result('tbl_bug', array('opportunities_id' => $opportunity_details->opportunities_id));
                            if (!empty($all_bugs_info)) : foreach ($all_bugs_info as $key => $v_bugs) :
                                    $reporter = $this->db->where('user_id', $v_bugs->reporter)->get('tbl_users')->row();
                                    if ($reporter->role_id == '1') {
                                        $badge = 'danger';
                                    } elseif ($reporter->role_id == '2') {
                                        $badge = 'info';
                                    } else {
                                        $badge = 'primary';
                                    }
                            ?>
                                    <tr id="table-bugs-<?= $v_bugs->bug_id ?>">
                                        <td><a class="text-info" style="<?php
                                                                        if ($v_bugs->bug_status == 'resolve') {
                                                                            echo 'text-decoration: line-through;';
                                                                        }
                                                                        ?>" href="<?= base_url() ?>admin/bugs/details/<?= $v_bugs->bug_id ?>"><?php echo $v_bugs->bug_title; ?></a>
                                        </td>
                                        </td>
                                        <td><?php
                                            if ($v_bugs->bug_status == 'unconfirmed') {
                                                $label = 'warning';
                                            } elseif ($v_bugs->bug_status == 'confirmed') {
                                                $label = 'info';
                                            } elseif ($v_bugs->bug_status == 'in_progress') {
                                                $label = 'primary';
                                            } else {
                                                $label = 'success';
                                            }
                                            ?>
                                            <span class="label label-<?= $label ?>"><?= lang("$v_bugs->bug_status") ?></span>
                                        </td>
                                        <td><?= ucfirst($v_bugs->priority) ?></td>
                                        <td>

                                            <span class="badge btn-<?= $badge ?> "><?= $reporter->username ?></span>
                                        </td>
                                        <td>
                                            <?php echo btn_edit('admin/bugs/create/' . $v_bugs->bug_id) ?>
                                            <?php echo ajax_anchor(base_url("admin/bugs/delete_bug/" . $v_bugs->bug_id), "<i class='btn btn-danger btn-xs fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table-bugs-" . $v_bugs->bug_id)); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- End Tasks Management-->

        </div>
    </div>
</div>