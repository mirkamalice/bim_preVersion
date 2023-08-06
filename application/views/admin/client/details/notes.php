<section class="panel panel-custom">
    <div class="panel-heading">
        <div class="panel-title">
            <strong>
                <?= lang('notes') ?>
            </strong>
            <button id="new_notes" class="btn btn-xs pull-right b0"><?= lang('new') . ' ' . lang('notes') ?></button>
        </div>
    </div>
    <?php
    $client_notes = get_result('tbl_notes', array('user_id' => $client_details->client_id, 'is_client' => 'Yes'));
    $url = $this->uri->segment(5);
    if ($url == 'notes') {
        $notes_id = $this->uri->segment(6);
        $notes_info = $this->db->where('notes_id', $notes_id)->get('tbl_notes')->row();
    } else {
        $notes_id = null;
    }
    
    ?>
    <div class="panel-body">
        <div class="new_notes mb-lg" style="display: <?= !empty($notes_info) ? 'block' : 'none' ?>">
            <form action="<?php echo base_url() ?>admin/client/new_notes/<?= $notes_id ?>" method="post"
                  class="form-horizontal">
                                <textarea name="notes" class="form-control textarea-md"><?php if (!empty($notes_info)) {
                                        echo $notes_info->notes;
                                    } ?></textarea>
                <input type="hidden" name="client_id" value="<?= $client_details->client_id ?>">
                
                <div class="">
                    <button class="btn btn-primary pull-right mt-lg mb-lg " type="submit"><?= lang('save') ?></button>
                </div>
            
            </form>
        </div>
        <script>
            $(document).ready(function () {
                $('#new_notes').click(function () {
                    $('.new_notes').toggle('slow');
                });
            });
        </script>
        <table class="table table-striped " cellspacing="0" width="100%">
            <thead>
            <tr>
                <th><?= lang('description') ?></th>
                <th><?= lang('added_by') ?></th>
                <th class="col-sm-3"><?= lang('date') ?> </th>
                <th class="col-sm-2"><?= lang('action') ?></th>
            </tr>
            </thead>
            
            <tbody>
            <?php
            if (!empty($client_notes)) {
                foreach ($client_notes as $v_notes) {
                    $n_user = $this->db->where('user_id', $v_notes->added_by)->get('tbl_users')->row();
                    if (empty($n_user)) {
                        $n_user->fullname = '-';
                        $n_url = '#';
                    } else {
                        $n_url = base_url() . 'admin/user/user_details/' . $n_user->user_id;
                    }
                    ?>
                    <tr>
                        <td><a class="text-info"
                               href="<?= base_url() ?>admin/client/details/<?= $client_details->client_id . '/notes/' . $v_notes->notes_id ?>"><?= $v_notes->notes ?></a>
                        </td>
                        <td>
                            
                            <a href="<?= $n_url ?>"> <?= $n_user->username ?></a>
                        </td>
                        <td><?= strftime(config_item('date_format'), strtotime($v_notes->added_date)) . ' ' . display_time($v_notes->added_date); ?>
                        </td>
                        <td>
                            <?= btn_edit('admin/client/details/' . $client_details->client_id . '/notes/' . $v_notes->notes_id) ?>
                            <?php echo btn_delete('admin/client/delete_notes/' . $v_notes->notes_id . '/' . $client_details->client_id); ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
            </tbody>
        </table>
    </div>
</section>