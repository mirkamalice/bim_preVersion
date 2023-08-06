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
$edited = can_action('4', 'edited');
?>
<div class="panel panel-custom">
    <div class="panel-heading">
        <div class="panel-title"><strong><?= $client_details->name ?> - <?= lang('details') ?> </strong>
            <div class="pull-right">
                <?php
                if ($client_details->leads_id != 0) {
                    echo lang('converted_from')
                    ?>
                    <a href="<?= base_url() ?>admin/leads/leads_details/<?= $client_details->leads_id ?>"><?= lang('leads') ?></a>
                <?php }
                if (!empty($edited)) {
                    ?>
                    <a href="<?php echo base_url() ?>admin/client/create_client/<?= $client_details->client_id ?>"
                       class="btn-xs "><i class="fa fa-edit"></i> <?= lang('edit') ?></a>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <!-- Details START -->
        <div class="col-md-6">
            <div class="group">
                <h4 class="subdiv text-muted"><?= lang('contact_details') ?></h4>
                <div class="row inline-fields">
                    <div class="col-md-4"><?= lang('name') ?></div>
                    <div class="col-md-6"><?= $client_details->name ?></div>
                </div>
                <div class="row inline-fields">
                    <div class="col-md-4"><?= lang('contact_person') ?></div>
                    <div class="col-md-6">
                        <?php
                        if ($client_details->primary_contact != 0) {
                            $contacts = $client_details->primary_contact;
                        } else {
                            $contacts = NULL;
                        }
                        $primary_contact = $this->client_model->check_by(array('user_id' => $contacts), 'tbl_account_details');
                        if ($primary_contact) {
                            echo $primary_contact->fullname;
                        }
                        ?>
                    </div>
                </div>
                <div class="row inline-fields">
                    <div class="col-md-4"><?= lang('email') ?></div>
                    <div class="col-md-6"><?= $client_details->email ?></div>
                </div>
            </div>
            
            <div class="row inline-fields">
                <div class="col-md-4"><?= lang('city') ?></div>
                <div class="col-md-6"><?= $client_details->city ?></div>
            </div>
            <div class="row inline-fields">
                <div class="col-md-4"><?= lang('zipcode') ?></div>
                <div class="col-md-6"><?= $client_details->zipcode ?></div>
            </div>
            
            <?php $show_custom_fields = custom_form_label(12, $client_details->client_id);
            
            if (!empty($show_custom_fields)) {
                foreach ($show_custom_fields as $c_label => $v_fields) {
                    if (!empty($v_fields)) {
                        ?>
                        <div class="row inline-fields">
                            <div class="col-md-4"><?= $c_label ?></div>
                            <div class="col-md-6"><?= $v_fields ?></div>
                        </div>
                    <?php }
                }
            }
            ?>
            <div class=" mt">
                <?php if (!empty($client_details->website)) { ?>
                    <div class="row inline-fields">
                        <div class="col-md-4"><?= lang('website') ?></div>
                        <div class="col-md-6"><?= $client_details->website ?></div>
                    </div>
                <?php } ?>
                <?php if (!empty($client_details->skype_id)) { ?>
                    <div class="row inline-fields">
                        <div class="col-md-4"><?= lang('skype_id') ?></div>
                        <div class="col-md-6"><?= $client_details->skype_id ?></div>
                    </div>
                <?php } ?>
                <?php if (!empty($client_details->facebook)) { ?>
                    <div class="row inline-fields">
                        <div class="col-md-4"><?= lang('facebook_profile_link') ?></div>
                        <div class="col-md-6"><?= $client_details->facebook ?></div>
                    </div>
                <?php } ?>
                <?php if (!empty($client_details->twitter)) { ?>
                    <div class="row inline-fields">
                        <div class="col-md-4"><?= lang('twitter_profile_link') ?></div>
                        <div class="col-md-6"><?= $client_details->twitter ?></div>
                    </div>
                <?php } ?>
                <?php if (!empty($client_details->linkedin)) { ?>
                    <div class="row inline-fields">
                        <div class="col-md-4"><?= lang('linkedin_profile_link') ?></div>
                        <div class="col-md-6"><?= $client_details->linkedin ?></div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="col-md-6 mb-lg">
            <div class="group">
                <div class="row" style="margin-top: 5px">
                    <div class="rec-pay col-md-12">
                        
                        <div class="row inline-fields">
                            <div class="col-md-4"><?= lang('address') ?></div>
                            <div class="col-md-6"><?= $client_details->address ?></div>
                        </div>
                        <div class="row inline-fields">
                            <div class="col-md-4"><?= lang('phone') ?></div>
                            <div class="col-md-6"><a
                                        href="tel:<?= $client_details->phone ?>"><?= $client_details->phone ?></a>
                            </div>
                        </div>
                        <?php if (!empty($client_details->mobile)) { ?>
                            <div class="row inline-fields">
                                <div class="col-md-4"><?= lang('mobile') ?></div>
                                <div class="col-md-6"><a
                                            href="tel:<?= $client_details->mobile ?>"><?= $client_details->mobile ?></a>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="row inline-fields">
                            <div class="col-md-4"><?= lang('fax') ?></div>
                            <div class="col-md-6"><?= $client_details->fax ?>
                            </div>
                        </div>
                        
                        <div class=" mt">
                            <?php if (!empty($client_details->hosting_company)) { ?>
                                <div class="row inline-fields">
                                    <div class="col-md-4"><?= lang('hosting_company') ?></div>
                                    <div class="col-md-6"><?= $client_details->hosting_company ?></div>
                                </div>
                            <?php } ?>
                            <?php if (!empty($client_details->hostname)) { ?>
                                <div class="row inline-fields">
                                    <div class="col-md-4"><?= lang('hostname') ?></div>
                                    <div class="col-md-6"><?= $client_details->hostname ?></div>
                                </div>
                            <?php } ?>
                            <?php if (!empty($client_details->username)) { ?>
                                <div class="row inline-fields">
                                    <div class="col-md-4"><?= lang('username') ?></div>
                                    <div class="col-md-6"><?= $client_details->username ?></div>
                                </div>
                            <?php } ?>
                            <?php if (!empty($client_details->password)) {
                                $hosting_password = strlen(decrypt($client_details->password));
                                ?>
                                <div class="row inline-fields">
                                    <div class="col-md-4"><?= lang('password') ?></div>
                                    <div class="col-md-6">
                                                        <span id="show_password">
                                                            <?php
                                                            if (!empty($hosting_password)) {
                                                                for ($p = 1; $p <= $hosting_password; $p++) {
                                                                    echo '*';
                                                                }
                                                            } ?>
                                                        </span>
                                        <a data-toggle="modal" data-target="#myModal"
                                           href="<?= base_url('admin/client/see_password/c_' . $client_details->client_id) ?>"
                                           id="see_password"><?= lang('see_password') ?></a>
                                        <strong id="hosting_password" class="required"></strong>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (!empty($client_details->port)) { ?>
                                <div class="row inline-fields">
                                    <div class="col-md-4"><?= lang('port') ?></div>
                                    <div class="col-md-6"><?= $client_details->port ?></div>
                                </div>
                            <?php } ?>
                        </div>
                    
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center block mt">
            <h4 class="subdiv text-muted"><?= lang('received_amount') ?></h4>
            <h3 class="amount text-danger cursor-pointer"><strong>
                    <?php
                    ?><?= display_money($this->client_model->client_paid($client_details->client_id), client_currency($client_details->client_id)); ?>
                </strong></h3>
            <div style="display: inline-block">
                <div id="easypie3" data-percent="<?= $perc_paid ?>" class="easypie-chart">
                    <span class="h2"><?= $perc_paid ?>%</span>
                    <div class="easypie-text"><?= lang('paid') ?></div>
                </div>
            </div>
        </div>
        
        <!-- Details END -->
    </div>
    <div class="panel-footer">
                        <span><?= lang('invoice_amount') ?>: <strong class="label label-primary">
                                <?= display_money($client_payable, client_currency($client_details->client_id)); ?>
                            </strong></span>
        <span class="text-danger pull-right">
                            <?= lang('outstanding') ?>
                            :<strong class="label label-danger">
                                <?= display_money($client_outstanding, client_currency($client_details->client_id)) ?></strong>
                        </span>
    </div>
</div>

