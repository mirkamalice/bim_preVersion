<?= message_box('success'); ?>
<?= message_box('error');

$eeror_message = $this->session->userdata('error');
if (!empty($eeror_message)) : foreach ($eeror_message as $key => $message) :
?>
        <div class="alert alert-danger">
            <?php echo $message; ?>
        </div>
<?php
    endforeach;
endif;
$this->session->unset_userdata('error');

?>
<?php
$all_bug_info = $this->client_model->get_permission('tbl_bug');
$total_bugs = 0;
if (!empty($all_bug_info)) {
    foreach ($all_bug_info as $v_bugs) {
        if (!empty($v_bugs)) {
            $profile = $this->db->where(array('user_id' => $v_bugs->reporter))->get('tbl_account_details')->row();
            if (!empty($profile->company)) {
                if ($profile->company == $client_details->client_id) {
                    $total_bugs += count($v_bugs->bug_id);
                }
            }
        }
    }
}
$recently_paid = $this->db
    ->where('paid_by', $client_details->client_id)
    ->order_by('created_date', 'desc')
    ->get('tbl_payments')
    ->result();
$all_tickets_info = $this->client_model->get_permission('tbl_tickets');
$total_tickets = 0;
if (!empty($all_tickets_info)) {
    foreach ($all_tickets_info as $v_tickets_info) {
        if (!empty($v_tickets_info)) {
            $profile_info = $this->db->where(array('user_id' => $v_tickets_info->reporter))->get('tbl_account_details')->row();
            if (!empty($profile_info->company))
                if ($profile_info->company == $client_details->client_id) {
                    $total_tickets += count($v_tickets_info->tickets_id);
                }
        }
    }
}
$all_project = $this->db->where('client_id', $client_details->client_id)->get('tbl_project')->result();
$client_notes = $this->db->where(array('user_id' => $client_details->client_id, 'is_client' => 'Yes'))->get('tbl_notes')->result();

$client_outstanding = $this->invoice_model->client_outstanding($client_details->client_id);
$client_payments = $this->invoice_model->get_sum('tbl_payments', 'amount', $array = array('paid_by' => $client_details->client_id));
$client_payable = $client_payments + $client_outstanding;
$client_currency = $this->invoice_model->client_currency_symbol($client_details->client_id);
if (!empty($client_currency)) {
    $cur = $client_currency->symbol;
} else {
    $currency = get_row('tbl_currencies', array('code' => config_item('default_currency')));
    $cur = $currency->symbol;
}
if ($client_payable > 0 and $client_payments > 0) {
    $perc_paid = round(($client_payments / $client_payable) * 100, 1);
    if ($perc_paid > 100) {
        $perc_paid = '100';
    }
} else {
    $perc_paid = 0;
}
$client_transactions = $this->db->where('paid_by', $client_details->client_id)->get('tbl_transactions')->result();
$all_proposals_info = $this->db->where(array('module' => 'client', 'module_id' => $client_details->client_id))->order_by('proposals_id', 'DESC')->get('tbl_proposals')->result();
$edited = can_action('4', 'edited');
$notified_reminder = count($this->db->where(array('module' => 'client', 'module_id' => $client_details->client_id, 'notified' => 'No'))->get('tbl_reminders')->result());
?>
<div class="row">
    <div class="col-md-3">
        <div class="panel widget mb0 b0">
            <div class="row-table row-flush">
                <div class="col-xs-4 bg-info text-center">
                    <em class="fa fa-money fa-2x"></em>
                </div>
                <div class="col-xs-8">
                    <div class="text-center">
                        <h4 class="mb-sm"><?php
                                            if (!empty($client_payments)) {
                                                echo display_money($client_payments, client_currency($client_details->client_id));
                                            } else {
                                                echo '0.00';
                                            }
                                            ?></h4>
                        <p class="mb0 text-muted"><?= lang('paid_amount') ?></p>
                        <a href="<?= base_url() ?>admin/invoice/all_payments" class="small-box-footer"><?= lang('more_info') ?> <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel widget mb0 b0">
            <div class="row-table row-flush">
                <div class="col-xs-4 bg-danger text-center">
                    <em class="fa fa-usd fa-2x"></em>
                </div>
                <div class="col-xs-8">
                    <div class="text-center">
                        <h4 class="mb-sm"><?php
                                            if ($client_outstanding > 0) {
                                                echo display_money($client_outstanding, client_currency($client_details->client_id));
                                            } else {
                                                echo '0.00';
                                            }
                                            ?></h4>
                        <p class="mb0 text-muted"><?= lang('due_amount') ?></p>
                        <a href="<?= base_url() ?>admin/invoice/manage_invoice" class="small-box-footer"><?= lang('more_info') ?>
                            <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel widget mb0 b0">
            <div class="row-table row-flush">
                <div class="col-xs-4 bg-inverse text-center">
                    <em class="fa fa-usd fa-2x"></em>
                </div>
                <div class="col-xs-8">
                    <div class="text-center">
                        <h4 class="mb-sm">
                            <?php
                            if ($client_payable > 0) {
                                echo display_money($client_payable, client_currency($client_details->client_id));
                            } else {
                                echo '0.00';
                            }
                            ?></h4>
                        <p class="mb0 text-muted"><?= lang('invoice_amount') ?></p>
                        <a href="<?= base_url() ?>admin/invoice/manage_invoice" class="small-box-footer"><?= lang('more_info') ?>
                            <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel widget mb0 b0">
            <div class="row-table row-flush">
                <div class="col-xs-4 bg-purple text-center">
                    <em class="fa fa-usd fa-2x"></em>
                </div>
                <div class="col-xs-8">
                    <div class="text-center">
                        <h4 class="mb-sm">
                            <?= $perc_paid ?>%</h4>
                        <p class="mb0 text-muted"><?= lang('paid') . ' ' . lang('percentage') ?></p>
                        <a href="<?= base_url() ?>admin/invoice/all_payments"
                           class="small-box-footer"><?= lang('more_info') ?>
                            <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->load->view('admin/common/tabs');
?>