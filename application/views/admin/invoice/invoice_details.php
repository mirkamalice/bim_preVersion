<?= message_box('success') ?>
<?= message_box('error');
$edited = can_action('13', 'edited');
$deleted = can_action('13', 'deleted');
?>
<?php if ($payment_status != lang('cancelled') && $payment_status != lang('fully_paid') && !empty($total_available_credit)) { ?>
    <div class="alert text-success btn-outline-success bg-white">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <i class="fa fa-info text-warning"></i>
        <?= lang('total_credit_available', display_money($total_available_credit, client_currency($sales_info->client_id))); ?>
        
        <a data-toggle="modal" data-target="#myModal_lg"
           href="<?= base_url() ?>admin/invoice/invoices_credit/<?= $sales_info->invoices_id ?>"
           title="<?= lang('apply_credits') ?>" class="text-info"><?= lang('apply_credits') ?></a>
    </div>
<?php } ?>
<div class="row mb">
    <div class="col-sm-12 mb">
        <div class="pull-left">
            <?= lang('copy_unique_url') ?>
        </div>
        <div class="col-sm-10">
            <input style="width: 100%" class="form-control"
                   value="<?= base_url() ?>frontend/view_invoice/<?= url_encode($sales_info->invoices_id); ?>"
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
    <div class="col-sm-10">
        <?php
        $where = array('user_id' => $this->session->userdata('user_id'), 'module_id' => $sales_info->invoices_id, 'module_name' => 'invoice');
        $check_existing = $this->invoice_model->check_by($where, 'tbl_pinaction');
        if (!empty($check_existing)) {
            $url = 'remove_todo/' . $check_existing->pinaction_id;
            $btn = 'danger';
            $title = lang('remove_todo');
        } else {
            $url = 'add_todo_list/invoice/' . $sales_info->invoices_id;
            $btn = 'warning';
            $title = lang('add_todo_list');
        }
        
        ?>
        <?php $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $sales_info->invoices_id));
        if (!empty($can_edit) && !empty($edited)) { ?>
            <span data-toggle="tooltip" data-placement="top" title="<?= lang('from_items') ?>">
            <a data-toggle="modal" data-target="#myModal_lg"
               href="<?= base_url() ?>admin/invoice/insert_items/<?= $sales_info->invoices_id ?>"
               title="<?= lang('item_quick_add') ?>" class="btn btn-xs btn-primary">
                <i class="fa fa-pencil text-white"></i> <?= lang('add_items') ?></a>
        </span>
        <?php }
        ?>
        <?php
        if (!empty($can_edit) && !empty($edited)) { ?>
            <?php if ($sales_info->show_client == 'Yes') { ?>
            <a class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top"
               href="<?= base_url() ?>admin/invoice/change_status/hide/<?= $sales_info->invoices_id ?>"
               title="<?= lang('hide_to_client') ?>"><i class="fa fa-eye-slash"></i> <?= lang('hide_to_client') ?>
                </a><?php } else { ?>
            <a class="btn btn-xs btn-warning" data-toggle="tooltip" data-placement="top"
               href="<?= base_url() ?>admin/invoice/change_status/show/<?= $sales_info->invoices_id ?>"
               title="<?= lang('show_to_client') ?>"><i class="fa fa-eye"></i> <?= lang('show_to_client') ?>
                </a><?php }
        } ?>
        
        <?php
        if (!empty($can_edit) && !empty($edited)) { ?>
            <?php if ($this->invoice_model->get_invoice_cost($sales_info->invoices_id) > 0) {
                ?>
                <?php if ($sales_info->status == 'Cancelled') {
                    $disable = 'disabled';
                    $p_url = '';
                } else {
                    $disable = false;
                    $p_url = base_url() . 'admin/invoice/manage_invoice/payment/' . $sales_info->invoices_id;
                } ?>
                <a class="btn btn-xs btn-danger <?= $disable ?>" data-toggle="tooltip" data-placement="top"
                   href="<?= $p_url ?>"
                   title="<?= lang('add_payment') ?>"><i class="fa fa-credit-card"></i> <?= lang('pay_invoice') ?>
                </a>
                <?php
            }
            if (!empty($all_payments_history)) {
                ?>
                <a class="btn btn-xs btn-info" data-toggle="tooltip" data-placement="top"
                   href="<?= base_url('admin/invoice/manage_invoice/payment_history/' . $sales_info->invoices_id) ?>"
                   title="<?= lang('payment_history_for_this_invoice') ?>"><i class="fa fa fa-money"></i>
                    <?= lang('histories') ?>
                </a>
            <?php }
        }
        ?>
        <?php
        if (!empty($can_edit) && !empty($edited)) { ?>
            <span data-toggle="tooltip" data-placement="top" title="<?= lang('clone') . ' ' . lang('invoice') ?>">
            <a data-toggle="modal" data-target="#myModal" title="<?= lang('clone') . ' ' . lang('invoice') ?>"
               href="<?= base_url() ?>admin/invoice/clone_invoice/<?= $sales_info->invoices_id ?>"
               class="btn btn-xs btn-purple">
                <i class="fa fa-copy"></i> <?= lang('clone') ?></a>
        </span>
            <?php
        }
        ?>
        
        
        <div class="btn-group">
            <button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
                <?= lang('more_actions') ?>
                <span class="caret"></span></button>
            <ul class="dropdown-menu animated zoomIn">
                <?php if ($this->invoice_model->get_invoice_cost($sales_info->invoices_id) > 0) { ?>
                    <li>
                        <a href="<?= base_url() ?>admin/invoice/manage_invoice/email_invoice/<?= $sales_info->invoices_id ?>"
                           title="<?= lang('email_invoice') ?>"><?= lang('email_invoice') ?></a>
                    </li>
                    
                    <li>
                        <a href="<?= base_url() ?>admin/invoice/manage_invoice/send_reminder/<?= $sales_info->invoices_id ?>"
                           title="<?= lang('send_reminder') ?>"><?= lang('send_reminder') ?></a>
                    </li>
                    <?php if (!empty($sales_info->overdue_days)) { ?>
                        <li>
                            <a href="<?= base_url() ?>admin/invoice/manage_invoice/send_overdue/<?= $sales_info->invoices_id ?>"
                               title="<?= lang('send_invoice_overdue') ?>"><?= lang('send_invoice_overdue') ?></a>
                        </li>
                    <?php } ?>
                    <?php if ($sales_info->emailed != 'Yes') { ?>
                        <li>
                            <a href="<?= base_url() ?>admin/invoice/change_invoice_status/mark_as_sent/<?= $sales_info->invoices_id ?>"
                               title="<?= lang('mark_as_sent') ?>"><?= lang('mark_as_sent') ?></a>
                        </li>
                    <?php }
                    if ($paid_amount <= 0) {
                        ?>
                        <?php if ($sales_info->status != 'Cancelled') { ?>
                            <li>
                                <a href="<?= base_url() ?>admin/invoice/change_invoice_status/mark_as_cancelled/<?= $sales_info->invoices_id ?>"
                                   title="<?= lang('mark_as_cancelled') ?>"><?= lang('mark_as_cancelled') ?></a>
                            </li>
                        <?php } ?>
                        <?php if ($sales_info->status == 'Cancelled') { ?>
                            <li>
                                <a href="<?= base_url() ?>admin/invoice/change_invoice_status/unmark_as_cancelled/<?= $sales_info->invoices_id ?>"
                                   title="<?= lang('unmark_as_cancelled') ?>"><?= lang('unmark_as_cancelled') ?></a>
                            </li>
                        <?php }
                    }
                    ?>
                    <li>
                        <a
                                href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_history/<?= $sales_info->invoices_id ?>"><?= lang('invoice_history') ?></a>
                    </li>
                <?php } ?>
                
                <?php
                if (!empty($can_edit) && !empty($edited)) { ?>
                    <li class="divider"></li>
                    <li>
                        <a
                                href="<?= base_url() ?>admin/invoice/createinvoice/create_invoice/<?= $sales_info->invoices_id ?>"><?= lang('edit_invoice') ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        
        <?php
        if (!empty($can_edit) && !empty($edited)) { ?>
            <?php if ($sales_info->recurring == 'Yes') { ?>
                <a onclick="return confirm('<?= lang('stop_recurring_alert') ?>')" class="btn btn-xs btn-warning"
                   href="<?= base_url() ?>admin/invoice/stop_recurring/<?= $sales_info->invoices_id ?>"
                   title="<?= lang('stop_recurring') ?>"><i class="fa fa-retweet"></i> <?= lang('stop_recurring') ?>
                </a>
            <?php }
        } ?>
        <?php
        if (!empty($sales_info->project_id)) {
            $project_info = $this->db->where('project_id', $sales_info->project_id)->get('tbl_project')->row();
            ?>
            <strong><?= lang('project') ?>:</strong>
            <a href="<?= base_url() ?>admin/projects/project_details/<?= $sales_info->project_id ?>" class="">
                <?= $project_info->project_name ?>
            </a>
        <?php }
        ?>
        
        <?php
        $notified_reminder = count($this->db->where(array('module' => 'invoice', 'module_id' => $sales_info->invoices_id, 'notified' => 'No'))->get('tbl_reminders')->result());
        ?>
        <a class="btn btn-xs btn-green" data-toggle="modal" data-target="#myModal_lg"
           href="<?= base_url() ?>admin/invoice/reminder/invoice/<?= $sales_info->invoices_id ?>"><?= lang('reminder') ?>
            <?= !empty($notified_reminder) ? '<span class="badge ml-sm" style="border-radius: 50%">' . $notified_reminder . '</span>' : '' ?>
        </a>
        <?php
        $credit_used = count(get_result('tbl_credit_used', array('invoices_id' => $sales_info->invoices_id)));
        ?>
        <a class="btn btn-xs btn-info" data-toggle="modal" data-target="#myModal_lg"
           href="<?= base_url() ?>admin/invoice/applied_credits/<?= $sales_info->invoices_id ?>"><?= lang('applied_credits') ?>
            <?= !empty($credit_used) ? '<span class="badge ml-sm" style="border-radius: 50%">' . $credit_used . '</span>' : '' ?>
        </a>
    </div>
    <div class="col-sm-2 pull-right">
        <a href="<?= base_url() ?>admin/invoice/send_invoice_email/<?= $sales_info->invoices_id . '/' . true ?>"
           data-toggle="tooltip" data-placement="top" title="<?= lang('send_email') ?>"
           class="btn btn-xs btn-primary pull-right">
            <i class="fa fa-envelope-o"></i>
        </a>
        <a onclick="print_sales_details('sales_details')" href="#" data-toggle="tooltip" data-placement="top" title=""
           data-original-title="Print" class="mr-sm btn btn-xs btn-danger pull-right">
            <i class="fa fa-print"></i>
        </a>
        <a href="<?= base_url() ?>admin/invoice/pdf_invoice/<?= $sales_info->invoices_id ?>" data-toggle="tooltip"
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

<?php $all_payment_info = $this->db->where('invoices_id', $sales_info->invoices_id)->get('tbl_payments')->result();

if (!empty($all_payment_info)) { ?>
    <div class="panel panel-custom">
        <div class="panel-heading">
            <div class="panel-title"> <?= lang('payment') . ' ' . lang('details') ?></div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped " cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th><?= lang('transaction_id') ?></th>
                    <th><?= lang('payment_date') ?></th>
                    <th><?= lang('amount') ?></th>
                    <th><?= lang('payment_mode') ?></th>
                    <th><?= lang('action') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $client_currency = false;
                if (!empty($sales_info->client_currency_on_invoice)) {
                    $client_currency = true;
                }
                
                foreach ($all_payment_info as $v_payments_info) {
                    if ($client_currency == true) {
                        $currency = $v_payments_info->client_currency;
                    } else {
                        $currency = $v_payments_info->currency;
                    }
                    if (is_numeric($v_payments_info->payment_method)) {
                        $v_payments_info->method_name = get_any_field('tbl_payment_methods', array('payment_methods_id' => $v_payments_info->payment_method), 'method_name');
                    } else {
                        $v_payments_info->method_name = $v_payments_info->payment_method;
                    }
                    ?>
                    <tr>
                        <td>
                            <a
                                    href="<?= base_url() ?>admin/invoice/manage_invoice/payments_details/<?= $v_payments_info->payments_id ?>">
                                <?= $v_payments_info->trans_id; ?></a>
                        </td>
                        <td>
                            <a
                                    href="<?= base_url() ?>admin/invoice/manage_invoice/payments_details/<?= $v_payments_info->payments_id ?>"><?= strftime(config_item('date_format'), strtotime($v_payments_info->payment_date)); ?></a>
                        </td>
                        <td><?= display_money($v_payments_info->amount, $currency) ?> (
                            <?php
                            do_action('multicurrency_in_invoice_payment', $v_payments_info);
                            ?>)
                        </td>
                        <td><?= !empty($v_payments_info->method_name) ? $v_payments_info->method_name : '-'; ?></td>
                        <?php if (!empty($edited) || !empty($deleted)) { ?>
                            <td>
                                <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                    <?= btn_edit('admin/invoice/all_payments/' . $v_payments_info->payments_id) ?>
                                <?php }
                                if (!empty($can_delete) && !empty($deleted)) {
                                    ?>
                                    <?= btn_delete('admin/invoice/delete/delete_payment/' . $v_payments_info->payments_id) ?>
                                <?php } ?>
                                <a data-toggle="tooltip" data-placement="top"
                                   href="<?= base_url() ?>admin/invoice/send_payment/<?= $v_payments_info->payments_id . '/' . $v_payments_info->amount ?>"
                                   title="<?= lang('send_email') ?>" class="btn btn-xs btn-success">
                                    <i class="fa fa-envelope"></i> </a>
                                <a data-toggle="tooltip" data-placement="top"
                                   href="<?= base_url() ?>admin/invoice/payments_pdf/<?= $v_payments_info->payments_id ?>"
                                   title="<?= lang('pdf') ?>" class="btn btn-xs btn-warning">
                                    <i class="fa fa-file-pdf-o"></i></a>
                            </td>
                        <?php } ?>
                    </tr>
                    <?php
                } ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>
<script type="text/javascript">
    if (get('posItems')) {
        remove('posItems');
    }

</script>