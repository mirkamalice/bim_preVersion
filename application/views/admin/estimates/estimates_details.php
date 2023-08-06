<?= message_box('success') ?>
<?= message_box('error');
$edited = can_action('14', 'edited');
$deleted = can_action('14', 'deleted');
?>
    <div class="row mb">
        <div class="col-sm-12 mb">
            <div class="pull-left">
                <?= lang('copy_unique_url') ?>
            </div>
            <div class="col-sm-10">
                <input style="width: 100%" class="form-control"
                       value="<?= base_url() ?>frontend/estimates/<?= url_encode($sales_info->estimates_id); ?>"
                       type="text"
                       id="foo"/>
            </div>
        </div>
        <script type="text/javascript">
            var textBox = document.getElementById("foo");
            textBox.onfocus = function () {
                textBox.select();
                // Work around Chrome's little problem
                textBox.onmouseup = function () {
                    // Prevent further mouseup intervention
                    textBox.onmouseup = null;
                    return false;
                };
            };
        </script>
        <div class="col-sm-8">
            <?php
            $where = array('user_id' => $this->session->userdata('user_id'), 'module_id' => $sales_info->estimates_id, 'module_name' => 'estimates');
            $check_existing = $this->estimates_model->check_by($where, 'tbl_pinaction');
            if (!empty($check_existing)) {
                $url = 'remove_todo/' . $check_existing->pinaction_id;
                $btn = 'danger';
                $title = lang('remove_todo');
            } else {
                $url = 'add_todo_list/estimates/' . $sales_info->estimates_id;
                $btn = 'warning';
                $title = lang('add_todo_list');
            }
            
            $can_edit = $this->estimates_model->can_action('tbl_estimates', 'edit', array('estimates_id' => $sales_info->estimates_id));
            $can_delete = $this->estimates_model->can_action('tbl_estimates', 'delete', array('estimates_id' => $sales_info->estimates_id));
            ?>
            
            <?php if (!empty($can_edit) && !empty($edited)) { ?>
                
                <a data-toggle="modal" data-target="#myModal_lg"
                   href="<?= base_url() ?>admin/estimates/insert_items/<?= $sales_info->estimates_id ?>"
                   title="<?= lang('item_quick_add') ?>" class="btn btn-xs btn-primary">
                    <i class="fa fa-pencil text-white"></i> <?= lang('add_items') ?></a>
                
                <?php if ($sales_info->show_client == 'Yes') { ?>
                <a class="btn btn-xs btn-success"
                   href="<?= base_url() ?>admin/estimates/change_status/hide/<?= $sales_info->estimates_id ?>"
                   title="<?= lang('hide_to_client') ?>"><i class="fa fa-eye-slash"></i> <?= lang('hide_to_client') ?>
                    </a><?php } else { ?>
                <a class="btn btn-xs btn-warning"
                   href="<?= base_url() ?>admin/estimates/change_status/show/<?= $sales_info->estimates_id ?>"
                   title="<?= lang('show_to_client') ?>"><i class="fa fa-eye"></i> <?= lang('show_to_client') ?>
                    </a><?php } ?>
                
                <a data-toggle="modal" data-target="#myModal_large"
                   data-original-title="<?= lang('convert_to_invoice') ?>"
                   data-toggle="tooltip" data-placement="top" class="btn btn-xs btn-purple <?php
                if ($sales_info->invoiced == 'Yes' or $sales_info->client_id == '0') {
                    echo "disabled";
                }
                ?>" href="<?= base_url() ?>admin/estimates/convert_to_invoice/<?= $sales_info->estimates_id ?>"
                   title="<?= lang('convert_to_invoice') ?>">
                    <?= lang('convert_to_invoice') ?></a>
                <span data-toggle="tooltip" data-placement="top" title="<?= lang('clone') . ' ' . lang('estimate') ?>">
                <a data-toggle="modal" data-target="#myModal" title="<?= lang('clone') . ' ' . lang('estimate') ?>"
                   href="<?= base_url() ?>admin/estimates/clone_estimate/<?= $sales_info->estimates_id ?>"
                   class="btn btn-xs btn-green">
                    <i class="fa fa-copy"></i> <?= lang('clone') ?></a>
            </span>
                <?php
            }
            ?>
            
            <?php if (!empty($can_edit) && !empty($edited)) { ?>
                <div class="btn-group">
                    <button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
                        <?= lang('more_actions') ?>
                        <span class="caret"></span></button>
                    <ul class="dropdown-menu animated zoomIn">
                        <li>
                            <a href="<?= base_url() ?>admin/estimates/create/email_estimates/<?= $sales_info->estimates_id ?>"
                               data-toggle="ajaxModal"><?= lang('email_estimate') ?></a>
                        </li>
                        <li>
                            <a href="<?= base_url() ?>admin/estimates/create/estimates_history/<?= $sales_info->estimates_id ?>"><?= lang('estimate_history') ?></a>
                        </li>
                        
                        <?php if ($sales_info->status == 'expired' || $sales_info->status == 'sent' || $sales_info->status == 'cancelled' || $sales_info->status == 'draft') { ?>
                            <li>
                                <a href="<?= base_url() ?>admin/estimates/change_status/pending/<?= $sales_info->estimates_id ?>"
                                   title="<?= lang('mark_as_pending') ?>"><?= lang('mark_as_pending') ?></a>
                            </li>
                        <?php } ?>
                        <?php if ($sales_info->status == 'draft') { ?>
                            <li>
                                <a href="<?= base_url() ?>admin/estimates/change_status/draft/<?= $sales_info->estimates_id ?>"
                                   title="<?= lang('unmark_as_draft') ?>"><?= lang('mark_as_draft') ?></a>
                            </li>
                        <?php } ?>
                        <?php if ($sales_info->status != 'sent' || $sales_info->status == 'expired') { ?>
                            <li>
                                <a href="<?= base_url() ?>admin/estimates/change_status/sent/<?= $sales_info->estimates_id ?>"
                                   title="<?= lang('mark_as_sent') ?>"><?= lang('mark_as_sent') ?></a>
                            </li>
                        <?php } ?>
                        <?php if ($sales_info->status == 'pending' || $sales_info->status == 'sent') { ?>
                            <li>
                                <a href="<?= base_url() ?>admin/estimates/change_status/expired/<?= $sales_info->estimates_id ?>"
                                   title="<?= lang('mark_as_sent') ?>"><?= lang('mark_as_expired') ?></a>
                            </li>
                        <?php } ?>
                        <?php if ($sales_info->status != 'cancelled') { ?>
                            <li>
                                <a href="<?= base_url() ?>admin/estimates/change_status/cancelled/<?= $sales_info->estimates_id ?>"
                                   title="<?= lang('mark_as_cancelled') ?>"><?= lang('mark_as_cancelled') ?></a>
                            </li>
                        <?php } ?>
                        <?php if ($sales_info->status == 'cancelled') { ?>
                            <li>
                                <a href="<?= base_url() ?>admin/estimates/change_status/draft/<?= $sales_info->estimates_id ?>"
                                   title="<?= lang('unmark_as_cancelled') ?>"><?= lang('unmark_as_cancelled') ?></a>
                            </li>
                        <?php } ?>
                        <li>
                            <a href="<?= base_url() ?>admin/estimates/change_status/declined/<?= $sales_info->estimates_id ?>"><?= lang('declined') ?></a>
                        </li>
                        <li>
                            <a href="<?= base_url() ?>admin/estimates/change_status/accepted/<?= $sales_info->estimates_id ?>"><?= lang('accepted') ?></a>
                        </li>
                        <?php if (!empty($can_edit) && !empty($edited)) { ?>
                            <li class="divider"></li>
                            <li>
                                <a href="<?= base_url() ?>admin/estimates/create/edit_estimates/<?= $sales_info->estimates_id ?>"><?= lang('edit_estimate') ?></a>
                            </li>
                        <?php } ?>
                    
                    </ul>
                </div>
            <?php } ?>
            <?php if ($sales_info->invoiced == 'Yes') {
                $invoice_info = $this->db->where('invoices_id', $sales_info->invoices_id)->get('tbl_invoices')->row();
                if (!empty($invoice_info)) {
                    ?>
                    <a href="<?= base_url() ?>admin/invoice/invoice_details/<?= $sales_info->invoices_id ?>"
                       class="btn btn-xs btn-purple">
                        <i class="fa fa-hand-o-right"></i> <?= $invoice_info->reference_no ?></a>
                <?php }
            } ?>
            <?php
            $notified_reminder = count(get_result('tbl_reminders', array('module' => 'estimate', 'module_id' => $sales_info->estimates_id, 'notified' => 'No')));
            ?>
            <a class="btn btn-xs btn-green" data-toggle="modal" data-target="#myModal_lg"
               href="<?= base_url() ?>admin/invoice/reminder/estimate/<?= $sales_info->estimates_id ?>"><?= lang('reminder') ?>
                <?= !empty($notified_reminder) ? '<span class="badge ml-sm" style="border-radius: 50%">' . $notified_reminder . '</span>' : '' ?>
            </a>
            
            <?php
            if (!empty($sales_info->project_id)) {
                $project_info = $this->db->where('project_id', $sales_info->project_id)->get('tbl_project')->row();
                ?>
                <strong><?= lang('project') ?>:</strong>
                <a href="<?= base_url() ?>admin/projects/project_details/<?= $sales_info->project_id ?>" class="">
                    <?= $project_info->project_name ?>
                </a>
            <?php } ?>
        </div>
        <div class="col-sm-4 pull-right">
            <a href="<?= base_url() ?>admin/estimates/send_estimates_email/<?= $sales_info->estimates_id . '/' . true ?>"
               data-toggle="tooltip" data-placement="top" title="<?= lang('send_email') ?>"
               class="btn btn-xs btn-primary pull-right">
                <i class="fa fa-envelope-o"></i>
            </a>
            <a onclick="print_sales_details('sales_details')" href="#" data-toggle="tooltip" data-placement="top"
               title=""
               data-original-title="Print" class="mr-sm btn btn-xs btn-danger pull-right">
                <i class="fa fa-print"></i>
            </a>
            
            <a href="<?= base_url() ?>admin/estimates/pdf_estimates/<?= $sales_info->estimates_id ?>"
               data-toggle="tooltip"
               data-placement="top" title="" data-original-title="PDF" class="btn btn-xs btn-success pull-right mr-sm">
                <i class="fa fa-file-pdf-o"></i>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="<?= $title ?>"
               href="<?= base_url() ?>admin/projects/<?= $url ?>" class="mr-sm btn pull-right  btn-xs  btn-<?= $btn ?>"><i
                        class="fa fa-thumb-tack"></i></a>
        </div>
    </div>
<?php
$this->view('admin/common/sales_details', $sales_info);
?>