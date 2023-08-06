<?= message_box('success'); ?>
<?= message_box('error');
$created = can_action('39', 'created');
$edited = can_action('39', 'edited');
$deleted = can_action('39', 'deleted');
if (!empty($created) || !empty($edited)) {
?>
    <div class="nav-tabs-custom">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs">

            <li><a href="<?= base_url('admin/termination/') ?>"><?= lang('all_termination') ?></a>
            </li>
            <li>
                <a href="<?= base_url() ?>admin/termination/new_termination" data-toggle="modal" data-placement="top" data-target="#myModal_lg">
                    <?= ' ' . lang('new') . ' ' . lang('termination') ?></a>

            </li>
            <li class="active">
                <a href="<?= base_url() ?>admin/termination/termination_type">
                    </i> <?= ' ' . lang('termination_type') ?></a>
            </li>
        </ul>

        <div class="tab-content bg-white">
            <div class="tab-pane active" id="group">

                <div class="table-responsive">
                    <table class="table table-striped ">
                        <thead>
                            <tr>
                                <th><?= lang('termination_type') ?></th>
                                <th><?= lang('description') ?></th>
                                <?php if (!empty($edited) || !empty($deleted)) { ?>
                                    <th><?= lang('action') ?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $all_customer_group = $this->db->where('type', 'termination')->get('tbl_customer_group')->result();

                            if (!empty($all_customer_group)) {
                                foreach ($all_customer_group as $customer_group) {
                            ?>
                                    <tr id="table_items_group_<?= $customer_group->customer_group_id ?>">
                                        <td><?php
                                            $id = $this->uri->segment(5);
                                            if (!empty($id) && $id == $customer_group->customer_group_id) { ?>
                                                <form method="post" action="<?= base_url() ?>admin/termination/saved_termination_type/<?php
                                                                                                                                        if (!empty($termination)) {
                                                                                                                                            echo $termination->customer_group_id;
                                                                                                                                        }
                                                                                                                                        ?>" class="form-horizontal">
                                                    <input type="text" name="customer_group" value="<?php
                                                                                                    if (!empty($customer_group)) {
                                                                                                        echo $customer_group->customer_group;
                                                                                                    }
                                                                                                    ?>" class="form-control" placeholder="<?= lang('termination_type') ?>" required>
                                                <?php } else {
                                                echo $customer_group->customer_group;
                                            }
                                                ?>
                                        </td>
                                        <td><?php
                                            $id = $this->uri->segment(5);
                                            if (!empty($id) && $id == $customer_group->customer_group_id) { ?>
                                                <textarea name="description" rows="1" class="form-control"><?php
                                                                                                            if (!empty($customer_group)) {
                                                                                                                echo $customer_group->description;
                                                                                                            }
                                                                                                            ?></textarea>
                                            <?php } else {
                                                echo $customer_group->description;
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $id = $this->uri->segment(5);
                                            if (!empty($id) && $id == $customer_group->customer_group_id) { ?>
                                                <?= btn_update() ?>
                                                </form>
                                                <?= btn_cancel('admin/termination/termination_type/termination/') ?>
                                            <?php } else { ?>
                                                <?php if (!empty($edited)) { ?>
                                                    <?= btn_edit('admin/termination/termination_type/termination/' . $customer_group->customer_group_id) ?>
                                                    <?php if (!empty($deleted)) { ?>
                                                        <?php echo ajax_anchor(base_url("admin/termination/delete_termination_type/" . $customer_group->customer_group_id), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_items_group_" . $customer_group->customer_group_id)); ?>
                                                <?php }
                                                } ?>
                                        </td>
                                    <?php } ?>
                                    </tr>
                            <?php }
                            } ?>
                            <form role="form" enctype="multipart/form-data" id="form" action="<?php echo base_url(); ?>admin/termination/saved_termination_type/<?php
                                                                                                                                                                if (!empty($termination)) {
                                                                                                                                                                    echo $termination->customer_group_id;
                                                                                                                                                                }
                                                                                                                                                                ?>" method="post" class="form-horizontal  ">
                                <tr>
                                    <td><input required type="text" name="customer_group" class="form-control" placeholder="<?= lang('termination_type') ?>">
                                    </td>
                                    <td>
                                        <textarea name="description" rows="1" class="form-control"></textarea>
                                    </td>
                                    <td><?= btn_add() ?></td>
                                </tr>
                            </form>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>
        </div>