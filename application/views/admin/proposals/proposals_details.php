<?= message_box('success') ?>
<?= message_box('error');
$edited = can_action('140', 'edited');
$deleted = can_action('140', 'deleted');
?>
    <div class="row mb">
        <div class="col-sm-12 mb">
            <div class="pull-left">
                <?= lang('copy_unique_url') ?>
            </div>
            <div class="col-sm-10">
                <input style="width: 100%" class="form-control"
                       value="<?= base_url() ?>frontend/proposals/<?= url_encode($sales_info->proposals_id); ?>"
                       type="text" id="foo"/>
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
            $client_id = null;
            $can_edit = $this->proposal_model->can_action('tbl_proposals', 'edit', array('proposals_id' => $sales_info->proposals_id));
            $can_delete = $this->proposal_model->can_action('tbl_proposals', 'delete', array('proposals_id' => $sales_info->proposals_id));
            ?>
            
            <?php if (!empty($can_edit) && !empty($edited)) { ?>
                
                <a data-toggle="modal" data-target="#myModal_lg"
                   href="<?= base_url() ?>admin/proposals/insert_items/<?= $sales_info->proposals_id ?>"
                   title="<?= lang('item_quick_add') ?>" class="btn btn-xs btn-primary">
                    <i class="fa fa-pencil text-white"></i> <?= lang('add_items') ?></a>
                
                <?php if ($sales_info->show_client == 'Yes') { ?>
                <a class="btn btn-xs btn-success"
                   href="<?= base_url() ?>admin/proposals/change_status/hide/<?= $sales_info->proposals_id ?>"
                   title="<?= lang('hide_to_client') ?>"><i class="fa fa-eye-slash"></i> <?= lang('hide_to_client') ?>
                    </a><?php } else { ?>
                <a class="btn btn-xs btn-warning"
                   href="<?= base_url() ?>admin/proposals/change_status/show/<?= $sales_info->proposals_id ?>"
                   title="<?= lang('show_to_client') ?>"><i class="fa fa-eye"></i> <?= lang('show_to_client') ?>
                    </a><?php }
                if ($sales_info->convert != 'Yes') {
                    ?>
                    <div class="btn-group">
                        <button class="btn btn-xs btn-purple dropdown-toggle" data-toggle="dropdown">
                            <?= lang('convert') . ' ' . lang('TO') ?>
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu animated zoomIn">
                            <li>
                                <a data-toggle="modal" data-target="#myModal_large"
                                   href="<?= base_url() ?>admin/proposals/convert_to/invoice/<?= $sales_info->proposals_id ?>"
                                   title="<?= lang('invoice') ?>"><?= lang('invoice') ?></a>
                            </li>
                            <li>
                                <a data-toggle="modal" data-target="#myModal_large"
                                   href="<?= base_url() ?>admin/proposals/convert_to/estimate/<?= $sales_info->proposals_id ?>"><?= lang('estimate') ?></a>
                            </li>
                        </ul>
                    </div>
                <?php } else {
                    if ($sales_info->convert_module == 'invoice') {
                        $convert_info = $this->proposal_model->check_by(array('invoices_id' => $sales_info->convert_module_id), 'tbl_invoices');
                        $c_url = base_url() . 'admin/invoice/manage_invoice/invoice_details/' . $sales_info->convert_module_id;
                    } else {
                        $convert_info = $this->proposal_model->check_by(array('estimates_id' => $sales_info->convert_module_id), 'tbl_estimates');
                        $c_url = base_url() . 'admin/estimates/create/estimates_details/' . $sales_info->convert_module_id;
                    } ?>
                <?php } ?>
                <span data-toggle="tooltip" data-placement="top" title="<?= lang('clone') . ' ' . lang('proposal') ?>">
            <a data-toggle="modal" data-target="#myModal" title="<?= lang('clone') . ' ' . lang('proposal') ?>"
               href="<?= base_url() ?>admin/proposals/clone_proposal/<?= $sales_info->proposals_id ?>"
               class="btn btn-xs btn-green">
                <i class="fa fa-copy"></i> <?= lang('clone') ?></a>
        </span>
                
                <div class="btn-group">
                    <button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
                        <?= lang('more_actions') ?>
                        <span class="caret"></span></button>
                    <ul class="dropdown-menu animated zoomIn">
                        <li>
                            <a href="<?= base_url() ?>admin/proposals/index/email_proposals/<?= $sales_info->proposals_id ?>"
                               data-toggle="ajaxModal"><?= lang('email_proposal') ?></a>
                        </li>
                        <li>
                            <a
                                    href="<?= base_url() ?>admin/proposals/index/proposals_history/<?= $sales_info->proposals_id ?>"><?= lang('proposal_history') ?></a>
                        </li>
                        <li>
                            <a href="<?= base_url() ?>admin/proposals/change_status/draft/<?= $sales_info->proposals_id ?>"
                               title="<?= lang('unmark_as_draft') ?>"><?= lang('mark_as_draft') ?></a>
                        </li>
                        <li>
                            <a href="<?= base_url() ?>admin/proposals/change_status/sent/<?= $sales_info->proposals_id ?>"
                               title="<?= lang('mark_as_sent') ?>"><?= lang('mark_as_sent') ?></a>
                        </li>
                        
                        <li>
                            <a href="<?= base_url() ?>admin/proposals/change_status/revised/<?= $sales_info->proposals_id ?>"
                               title="<?= lang('mark_as_revised') ?>"><?= lang('mark_as_revised') ?></a>
                        </li>
                        
                        <li>
                            <a href="<?= base_url() ?>admin/proposals/change_status/open/<?= $sales_info->proposals_id ?>"
                               title="<?= lang('mark_as_open') ?>"><?= lang('mark_as_open') ?></a>
                        </li>
                        <li>
                            <a
                                    href="<?= base_url() ?>admin/proposals/change_status/declined/<?= $sales_info->proposals_id ?>"><?= lang('declined') ?></a>
                        </li>
                        <li>
                            <a
                                    href="<?= base_url() ?>admin/proposals/change_status/accepted/<?= $sales_info->proposals_id ?>"><?= lang('accepted') ?></a>
                        </li>
                        <?php if (!empty($can_edit) && !empty($edited)) { ?>
                            <li class="divider"></li>
                            <li>
                                <a
                                        href="<?= base_url() ?>admin/proposals/createproposal/edit_proposals/<?= $sales_info->proposals_id ?>"><?= lang('edit') . ' ' . lang('proposals') ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <?php if (!empty($c_url)) { ?>
                    <a class="btn btn-purple btn-xs" href="<?= $c_url ?>"><i class="fa fa-hand-o-right"></i>
                        <?= $convert_info->reference_no ?></a>
                <?php } ?>
            <?php } ?>
            <?php
            $notified_reminder = count($this->db->where(array('module' => 'proposal', 'module_id' => $sales_info->proposals_id, 'notified' => 'No'))->get('tbl_reminders')->result());
            ?>
            <a class="btn btn-xs btn-green" data-toggle="modal" data-target="#myModal_lg"
               href="<?= base_url() ?>admin/invoice/reminder/proposal/<?= $sales_info->proposals_id ?>"><?= lang('reminder') ?>
                <?= !empty($notified_reminder) ? '<span class="badge ml-sm" style="border-radius: 50%">' . $notified_reminder . '</span>' : '' ?>
            </a>
        
        
        </div>
        <div class="col-sm-4 pull-right">
            <a href="<?= base_url() ?>admin/proposals/send_proposals_email/<?= $sales_info->proposals_id . '/' . true ?>"
               data-toggle="tooltip" data-placement="top" title="<?= lang('send_email') ?>"
               class="btn btn-xs btn-primary pull-right">
                <i class="fa fa-envelope-o"></i>
            </a>
            <a onclick="print_sales_details('sales_details')" href="#" data-toggle="tooltip" data-placement="top"
               title=""
               data-original-title="Print" class="mr-sm btn btn-xs btn-danger pull-right">
                <i class="fa fa-print"></i>
            </a>
            
            <a href="<?= base_url() ?>admin/proposals/pdf_proposals/<?= $sales_info->proposals_id ?>"
               data-toggle="tooltip" data-placement="top" title="" data-original-title="PDF"
               class="btn btn-xs btn-success pull-right mr-sm">
                <i class="fa fa-file-pdf-o"></i>
            </a>
        
        </div>
    </div>
    <!-- Main content -->
<?php
$this->view('admin/common/sales_details', $sales_info);
?>