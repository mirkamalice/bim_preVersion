<?php echo message_box('success'); ?>
<?php echo message_box('error');
$created = can_action('86', 'created');
$edited = can_action('86', 'edited');
if (!empty($created) || !empty($edited)) {
?>
<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs">
                <li class="<?= $active == 1 ? 'active' : '' ?>"><a
                        href="<?= base_url('admin/performance/performance_indicator') ?>"><?= lang('indicator_list') ?></a>
                </li>
                <li class="<?= $active == 2 ? 'active' : '' ?>"><a
                        href="<?= base_url('admin/performance/create_indicator') ?>"><?= lang('set_indicator') ?></a>
                </li>
            </ul>
            <div class="tab-content bg-white">
                <!-- Indicator List tab Starts -->
                <div class="tab-pane <?= $active == 1 ? 'active' : '' ?>" id="indicator_list"
                    style="position: relative;">
                    <?php } else { ?>
                    <div class="panel panel-custom">
                        <header class="panel-heading ">
                            <div class="panel-title"><strong><?= lang('indicator_list') ?></strong></div>
                        </header>
                        <?php } ?>
                        <div class="row">
                            <?php if (!empty($all_department_info)) : foreach ($all_department_info as $akey => $v_department_info) : ?>
                            <?php if (!empty($v_department_info)) :
                                        if (!empty($all_dept_info[$akey]->deptname)) {
                                            $deptname = $all_dept_info[$akey]->deptname;
                                        } else {
                                            $deptname = lang('undefined_department');
                                        }
                                    ?>
                            <div class="col-sm-6">
                                <div class="box-heading">
                                    <div class="box-title">
                                        <h4><?php echo $deptname ?>
                                        </h4>
                                    </div>
                                </div>

                                <!-- Table -->
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <td class="text-bold col-sm-1">#</td>
                                            <td class="text-bold"><?= lang('designation') ?></td>
                                            <td class="text-bold col-sm-1"><?= lang('action') ?></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($v_department_info as $key => $v_department) :
                                                        if (!empty($v_department->designations)) {
                                                    ?>

                                        <tr>
                                            <td><?php echo $key + 1 ?></td>
                                            <td>
                                                <a data-toggle="modal" data-target="#myModal_lg"
                                                    href="<?= base_url() ?>admin/performance/indicator_details/<?= $v_department->designations_id ?>">
                                                    <?php echo $v_department->designations ?></a>
                                            </td>
                                            <td>
                                                <?php echo btn_view_modal('admin/performance/indicator_details/' . $v_department->designations_id); ?>
                                            </td>

                                        </tr>
                                        <?php
                                                        } else {
                                                        ?>
                                        <tr>
                                            <td colspan="3"><?= lang('no_designation_create_yet') ?></td>
                                        <tr></tr>
                                        <?php }
                                                    endforeach;
                                                    ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <?php endforeach; ?>
                            <?php endif; ?>


                        </div>
                    </div>
                    <!-- Indicator List tab Ends -->

                </div>
                <!-- Add Indicator Values Ends --->
            </div>
        </div>
    </div>