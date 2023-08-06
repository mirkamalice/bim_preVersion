<?php
$edited = can_action('24', 'edited');
$deleted = can_action('24', 'deleted');
?>

<div class="panel panel-custom">
    <div class="panel-heading">
        <h4 class="panel-title"><?= lang('user_documents') ?>
            <?php if (!empty($edited)) { ?>
                <div class="pull-right hidden-print">
                    <span data-placement="top" data-toggle="tooltip" title="<?= lang('update_conatct') ?>">
                        <a data-toggle="modal" data-target="#myModal"
                           href="<?= base_url() ?>admin/user/user_documents/<?= $profile_info->user_id ?>"
                           class="text-default text-sm ml"><?= lang('update') ?></a>
                    </span>
                </div>
            <?php } ?>
        </h4>
    </div>
    <div class="panel-body form-horizontal">
        <!-- CV Upload -->
        <?php
        $document_info = get_row('tbl_employee_document', array('user_id' => $profile_info->user_id));
        if (!empty($document_info->resume)) : ?>
            <div class="form-group">
                <label class="col-sm-4 control-label"><?= lang('resume') ?> : </label>
                <div class="col-sm-8">
                    <p class="form-control-static">
                        <a href="<?php echo base_url() . $document_info->resume; ?>" target="_blank"
                           style="text-decoration: underline;"><?= lang('view') . ' ' . lang('resume') ?></a>
                        <a href="<?= base_url('admin/user/delete_documents/resume/' . $document_info->document_id) ?>"
                           class="btn btn-xs" title="" data-toggle="tooltip" data-placement="top"
                           onclick="return confirm('You are about to delete a record. This cannot be undone. Are you sure?');"
                           data-original-title="Delete"><i class="fa fa-trash-o"></i></a>
                    </p>
                </div>
            </div>
        <?php endif; ?>
        <?php if (!empty($document_info->offer_letter)) : ?>
            <div class="form-group">
                <label class="col-sm-4 control-label"><?= lang('offer_latter') ?> : </label>
                <div class="col-sm-8">
                    <p class="form-control-static">
                        <a href="<?php echo base_url() . $document_info->offer_letter; ?>" target="_blank"
                           style="text-decoration: underline;"><?= lang('view') . ' ' . lang('offer_latter') ?></a>
                        <a href="<?= base_url('admin/user/delete_documents/offer_letter/' . $document_info->document_id) ?>"
                           class="btn btn-xs" title="" data-toggle="tooltip" data-placement="top"
                           onclick="return confirm('You are about to delete a record. This cannot be undone. Are you sure?');"
                           data-original-title="Delete"><i class="fa fa-trash-o"></i></a>
                    </p>
                </div>
            </div>
        <?php endif; ?>
        <?php if (!empty($document_info->joining_letter)) : ?>
            <div class="form-group">
                <label class="col-sm-4 control-label"><?= lang('joining_latter') ?>
                    : </label>
                <div class="col-sm-8">
                    <p class="form-control-static">
                        <a href="<?php echo base_url() . $document_info->joining_letter; ?>" target="_blank"
                           style="text-decoration: underline;"><?= lang('view') . ' ' . lang('joining_latter') ?></a>
                        <a href="<?= base_url('admin/user/delete_documents/joining_letter/' . $document_info->document_id) ?>"
                           class="btn btn-xs" title="" data-toggle="tooltip" data-placement="top"
                           onclick="return confirm('You are about to delete a record. This cannot be undone. Are you sure?');"
                           data-original-title="Delete"><i class="fa fa-trash-o"></i></a>
                    </p>
                </div>
            </div>
        <?php endif; ?>
        <?php if (!empty($document_info->contract_paper)) : ?>
            <div class="form-group">
                <label class="col-sm-4 control-label"><?= lang('contract_paper') ?>
                    : </label>
                <div class="col-sm-8">
                    <p class="form-control-static">
                        <a href="<?php echo base_url() . $document_info->contract_paper; ?>" target="_blank"
                           style="text-decoration: underline;"><?= lang('view') . ' ' . lang('contract_paper') ?></a>
                        <a href="<?= base_url('admin/user/delete_documents/contract_paper/' . $document_info->document_id) ?>"
                           class="btn btn-xs" title="" data-toggle="tooltip" data-placement="top"
                           onclick="return confirm('You are about to delete a record. This cannot be undone. Are you sure?');"
                           data-original-title="Delete"><i class="fa fa-trash-o"></i></a>
                    </p>
                </div>
            </div>
        <?php endif; ?>
        <?php if (!empty($document_info->id_proff)) : ?>
            <div class="form-group">
                <label class="col-sm-4 control-label"><?= lang('id_prof') ?> : </label>
                <div class="col-sm-8">
                    <p class="form-control-static">
                        <a href="<?php echo base_url() . $document_info->id_proff; ?>" target="_blank"
                           style="text-decoration: underline;"><?= lang('view') . ' ' . lang('id_prof') ?></a>
                        <a href="<?= base_url('admin/user/delete_documents/id_proff/' . $document_info->document_id) ?>"
                           class="btn btn-xs" title="" data-toggle="tooltip" data-placement="top"
                           onclick="return confirm('You are about to delete a record. This cannot be undone. Are you sure?');"
                           data-original-title="Delete"><i class="fa fa-trash-o"></i></a>
                    </p>
                </div>
            </div>
        <?php endif; ?>
        <?php if (!empty($document_info->other_document)) : ?>
            <div class="form-group">
                <label class="col-sm-4 control-label"><?= lang('other_document') ?>
                    : </label>
                <div class="col-sm-8">
                    <?php
                    $uploaded_file = json_decode($document_info->other_document);
                    
                    if (!empty($uploaded_file)) :
                        foreach ($uploaded_file as $sl => $v_files) :
                            
                            if (!empty($v_files)) :
                                ?>
                                <p class="form-control-static">
                                    <a href="<?php echo base_url() . 'uploads/' . $v_files->fileName; ?>"
                                       target="_blank"
                                       style="text-decoration: underline;"><?= $sl + 1 . '. ' . lang('view') . ' ' . lang('other_document') ?></a>
                                </p>
                            <?php
                            endif;
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>