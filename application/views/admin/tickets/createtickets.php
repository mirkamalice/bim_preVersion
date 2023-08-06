<?= message_box('success'); ?>
<?= message_box('error'); ?>
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-tagsinput/fm.tagator.jquery.js"></script>


<div id="tickets_state_report_div">
    <?php //$this->load->view("admin/tickets/tickets_state_report"); 
    ?>
</div>

<?php
$created = can_action(6, 'created');
$edited = can_action(6, 'edited');
$deleted = can_action(6, 'deleted');

if (!empty($created) || !empty($edited)) {
?>
<div class="row">
    <div class="col-sm-12">

        <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs">
                <li class="<?= $active == 1 ? 'active' : ''; ?>"><a
                        href="<?= base_url('admin/tickets') ?>"><?= lang('tickets') ?></a>
                </li>
                <li class="<?= $active == 2 ? 'active' : ''; ?>"><a href="<?= base_url('admin/tickets/create') ?>"
                        id="form_tab"><?= lang('new_ticket') ?></a>
                </li>
            </ul>
            <style type="text/css">
            .custom-bulk-button {
                display: initial;
            }
            </style>
            <div class="tab-content bg-white">
                <!-- ************** general *************-->

                <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="new">
                    <?php $this->load->view("admin/tickets/new_ticket"); ?>
                </div>
                <?php } else { ?>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
</div>


<script>
$(document).ready(function() {
    ins_data(base_url + 'admin/tickets/tickets_state_report');
});
</script>

<script>
$(document).ready(function() {
    $("#form_tab").on("click", function() {
        if ($('#new_form').length) {}
        // else{ins_data(base_url+'admin/tickets/new_ticket_form');}
    });
});
</script>