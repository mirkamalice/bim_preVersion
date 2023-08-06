<?= message_box('success') ?>
<?= message_box('error');
$edited = can_action('156', 'edited');
$deleted = can_action('156', 'deleted');
?>
<div class="row mb">
    <div class="col-sm-8">
        <?php
        $where = array('user_id' => $this->session->userdata('user_id'), 'module_id' => $sales_info->credit_note_id, 'module_name' => 'credit_note');
        $check_existing = $this->credit_note_model->check_by($where, 'tbl_pinaction');
        if (!empty($check_existing)) {
            $url = 'remove_todo/' . $check_existing->pinaction_id;
            $btn = 'danger';
            $title = lang('remove_todo');
        } else {
            $url = 'add_todo_list/credit_note/' . $sales_info->credit_note_id;
            $btn = 'warning';
            $title = lang('add_todo_list');
        }

        $can_edit = $this->credit_note_model->can_action('tbl_credit_note', 'edit', array('credit_note_id' => $sales_info->credit_note_id));
        $can_delete = $this->credit_note_model->can_action('tbl_credit_note', 'delete', array('credit_note_id' => $sales_info->credit_note_id));
        $client_info = $this->credit_note_model->check_by(array('client_id' => $sales_info->client_id), 'tbl_client');
        if (!empty($client_info)) {
            $currency = $this->credit_note_model->client_currency_symbol($sales_info->client_id);
            $client_lang = $client_info->language;
        } else {
            $client_lang = 'english';
            $currency = $this->credit_note_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
        }
        unset($this->lang->is_loaded[5]);
        $language_info = $this->lang->load('sales_lang', $client_lang, TRUE, FALSE, '', TRUE);
        ?>

        <?php if (!empty($can_edit) && !empty($edited)) { ?>

            <a data-toggle="modal" data-target="#myModal_lg" href="<?= base_url() ?>admin/credit_note/insert_items/<?= $sales_info->credit_note_id ?>" title="<?= lang('item_quick_add') ?>" class="btn btn-xs btn-primary">
                <i class="fa fa-pencil text-white"></i> <?= lang('add_items') ?></a>

            <span data-toggle="tooltip" data-placement="top" title="<?= lang('clone') . ' ' . lang('credit_note') ?>">
                <a data-toggle="modal" data-target="#myModal" title="<?= lang('clone') . ' ' . lang('credit_note') ?>" href="<?= base_url() ?>admin/credit_note/clone_credit_note/<?= $sales_info->credit_note_id ?>" class="btn btn-xs btn-purple">
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
                        <a href="<?= base_url() ?>admin/credit_note/index/email_credit_note/<?= $sales_info->credit_note_id ?>" data-toggle="ajaxModal"><?= lang('email_credit_note') ?></a>
                    </li>
                    <li>
                        <a href="<?= base_url() ?>admin/credit_note/index/credit_note_history/<?= $sales_info->credit_note_id ?>"><?= lang('credit_note_history') ?></a>
                    </li>
                    <li class="<?= $sales_info->status == 'open' ? 'hide' : '' ?>">
                        <a href="<?= base_url() ?>admin/credit_note/change_status/open/<?= $sales_info->credit_note_id ?>"><?= lang('open') ?></a>
                    </li>
                    <li class="<?= $sales_info->status == 'refund' ? 'hide' : '' ?>">
                        <a href="<?= base_url() ?>admin/credit_note/change_status/refund/<?= $sales_info->credit_note_id ?>"><?= lang('refund') ?></a>
                    </li>
                    <li class="<?= $sales_info->status == 'void' ? 'hide' : '' ?>">
                        <a href="<?= base_url() ?>admin/credit_note/change_status/void/<?= $sales_info->credit_note_id ?>"><?= lang('void') ?></a>
                    </li>
                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                        <li class="divider"></li>
                        <li>
                            <a href="<?= base_url() ?>admin/credit_note/createcreditnote/edit_credit_note/<?= $sales_info->credit_note_id ?>"><?= lang('edit_credit_note') ?></a>
                        </li>
                    <?php } ?>

                </ul>
            </div>
        <?php } ?>
        <?php
        $notified_reminder = count(get_result('tbl_reminders', array('module' => 'credit_note', 'module_id' => $sales_info->credit_note_id, 'notified' => 'No')));
        ?>
        <a class="btn btn-xs btn-green" data-toggle="modal" data-target="#myModal_lg" href="<?= base_url() ?>admin/invoice/reminder/credit_note/<?= $sales_info->credit_note_id ?>"><?= lang('reminder') ?>
            <?= !empty($notified_reminder) ? '<span class="badge ml-sm" style="border-radius: 50%">' . $notified_reminder . '</span>' : '' ?>
        </a>
        <?php
        $credit_used = count(get_result('tbl_credit_used', array('credit_note_id' => $sales_info->credit_note_id)));
        ?>
        <a class="btn btn-xs btn-info" data-toggle="modal" data-target="#myModal_lg" href="<?= base_url() ?>admin/credit_note/invoice_credited/<?= $sales_info->credit_note_id ?>"><?= lang('invoice_credited') ?>
            <?= !empty($credit_used) ? '<span class="badge ml-sm" style="border-radius: 50%">' . $credit_used . '</span>' : '' ?>
        </a>
        <a class="btn btn-xs btn-success" data-toggle="modal" data-target="#myModal_lg" href="<?= base_url() ?>admin/credit_note/credit_invoices/<?= $sales_info->credit_note_id ?>"><?= lang('apply') . ' ' . lang('TO') . ' ' . lang('invoice') ?>
        </a>

    </div>
    <div class="col-sm-4 pull-right">
        <a href="<?= base_url() ?>admin/credit_note/send_credit_note_email/<?= $sales_info->credit_note_id . '/' . true ?>" data-toggle="tooltip" data-placement="top" title="<?= lang('send_email') ?>" class="btn btn-xs btn-primary pull-right">
            <i class="fa fa-envelope-o"></i>
        </a>
        <a onclick="print_sales_details('sales_details')" href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print" class="mr-sm btn btn-xs btn-danger pull-right">
            <i class="fa fa-print"></i>
        </a>

        <a href="<?= base_url() ?>admin/credit_note/pdf_credit_note/<?= $sales_info->credit_note_id ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="PDF" class="btn btn-xs btn-success pull-right mr-sm">
            <i class="fa fa-file-pdf-o"></i>
        </a>
        <a data-toggle="tooltip" data-placement="top" title="<?= $title ?>" href="<?= base_url() ?>admin/projects/<?= $url ?>" class="mr-sm btn pull-right  btn-xs  btn-<?= $btn ?>"><i class="fa fa-thumb-tack"></i></a>
    </div>
</div>

<?php
$this->view('admin/common/sales_details', $sales_info);
?>