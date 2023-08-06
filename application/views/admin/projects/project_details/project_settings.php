 <?php 
 $edited = can_action('57', 'edited');
$deleted = can_action('57', 'deleted');
 ?>
 <div class="panel panel-custom">
     <div class="panel-heading">
         <h3 class="panel-title"><?= lang('project_settings') ?></h3>
     </div>
     <div class="panel-body">

         <form action="<?= base_url() ?>admin/projects/update_settings/<?php
                                                                        if (!empty($project_details)) {
                                                                            echo $project_details->project_id;
                                                                        }
                                                                        ?>" enctype="multipart/form-data" method="post" id="form" class="form-horizontal">
             <?php
                $project_permissions = $this->db->get('tbl_project_settings')->result();
                if (!empty($project_details->project_settings)) {
                    $current_permissions = $project_details->project_settings;
                    if ($current_permissions == NULL) {
                        $current_permissions = '{"settings":"on"}';
                    }
                    $get_permissions = json_decode($current_permissions);
                }

                foreach ($project_permissions as $v_permissions) {
                ?>
                 <div class="checkbox c-checkbox">
                     <label class="needsclick">
                         <input name="<?= $v_permissions->settings_id ?>" value="<?= $v_permissions->settings ?>" <?php
                                                                                                                    if (!empty($project_details->project_settings)) {
                                                                                                                        if (in_array($v_permissions->settings, $get_permissions)) {
                                                                                                                            echo "checked=\"checked\"";
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo "checked=\"checked\"";
                                                                                                                    }
                                                                                                                    ?> type="checkbox">
                         <span class="fa fa-check"></span>
                         <?= lang($v_permissions->settings) ?>
                     </label>
                 </div>
                 <hr class="mt-sm mb-sm" />
             <?php } ?>

             <div class="form-group">
                 <div class="col-sm-2">
                     <button type="submit" id="sbtn" class="btn btn-primary"><?= lang('updates') ?></button>
                 </div>
             </div>
         </form>
     </div>
 </div>