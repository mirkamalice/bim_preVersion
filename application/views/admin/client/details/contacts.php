<?php
$add_contact = $this->uri->segment(6);
if (!empty($add_contact)) :
    $user_id = $this->uri->segment(7);
    $languages = $this->db->where('active', 1)->order_by('name', 'ASC')->get('tbl_languages')->result();
    $locales = $this->db->order_by('name', 'ASC')->get('tbl_locales')->result();
    ?>
    <?php include_once 'assets/admin-ajax.php'; ?>
    <?php
    $edited = can_action('4', 'edited');
    if (!empty($edited)) {
        $account_details = $this->client_model->check_by(array('user_id' => $user_id), 'tbl_account_details');    
        $user_info = $this->client_model->check_by(array('user_id' => $user_id), 'tbl_users');
        ?>
        <form role="form" data-parsley-validate="" novalidate="" enctype="multipart/form-data" id="form"
              action="<?php echo base_url(); ?>admin/client/save_contact/<?php
              if (!empty($account_details)) {
                  echo $account_details->user_id;
              }
              ?>" method="post" class="form-horizontal  ">
            
            <div class="panel panel-custom">
                <!-- Default panel contents -->
                <div class="panel-heading">
                    <div class="panel-title">
                        <?= lang('add_contact') ?>.
                        <a href="<?= base_url() ?>admin/client/details/<?= $client_details->client_id ?>/contacts"
                           class="btn-sm pull-right">Return to Details</a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-sm-8">
                        <input type="hidden" name="r_url"
                               value="<?= base_url() ?>admin/client/details/<?= $client_details->client_id ?>/contacts">
                        <input type="hidden" name="company" value="<?= $client_details->client_id ?>">
                        <input type="hidden" name="role_id" value="2">
                        <input type="hidden" id="user_id" value="<?php
                        if (!empty($account_details)) {
                            echo $account_details->user_id;
                        }
                        ?>">
                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?= lang('full_name') ?> <span
                                        class="text-danger"> *</span></label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" value="<?php
                                if (!empty($account_details)) {
                                    echo $account_details->fullname;
                                }
                                ?>" placeholder="E.g John Doe" name="fullname" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?= lang('email') ?><span
                                        class="text-danger">
                                                    *</span></label>
                            <div class="col-lg-7">
                                <input class="form-control" id="check_email_addrees" type="email"
                                       value="<?php
                                       if (!empty($user_info)) {
                                           echo $user_info->email;
                                       }
                                       ?>" placeholder="me@domin.com" name="email" required>
                                <span id="email_addrees_error" class="required"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?= lang('phone') ?> </label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" value="<?php
                                if (!empty($account_details)) {
                                    echo $account_details->phone;
                                }
                                ?>" name="phone" placeholder="+52 782 983 434">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?= lang('mobile') ?> <span
                                        class="text-danger"> *</span></label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" value="<?php
                                if (!empty($account_details)) {
                                    echo $account_details->mobile;
                                }
                                ?>" name="mobile" placeholder="+8801723611125">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?= lang('skype_id') ?> </label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" value="<?php
                                if (!empty($account_details)) {
                                    echo $account_details->skype;
                                }
                                ?>" name="skype" placeholder="john">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?= lang('language') ?></label>
                            <div class="col-lg-7">
                                <select name="language" class="form-control">
                                    <?php foreach ($languages as $lang) : ?>
                                        <option value="<?= $lang->name ?>" <?php
                                        if (!empty($account_details->language) && $account_details->language == $lang->name) {
                                            echo 'selected="selected"';
                                        } else {
                                            echo($this->config->item('language') == $lang->name ? ' selected="selected"' : '');
                                        }
                                        ?>>
                                            <?= ucfirst($lang->name) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?= lang('locale') ?></label>
                            <div class="col-lg-7">
                                <select class="  form-control" name="locale">
                                    <?php foreach ($locales as $loc) : ?>
                                        <option lang="<?= $loc->code ?>"
                                                value="<?= $loc->locale ?>" <?= ($this->config->item('locale') == $loc->locale ? ' selected="selected"' : '') ?>>
                                            <?= $loc->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?php
                        
                        if (!empty($account_details->direction)) {
                            $direction = $account_details->direction;
                        } else {
                            $RTL = config_item('RTL');
                            if (!empty($RTL)) {
                                $direction = 'rtl';
                            }
                        }
                        ?>
                        <div class="form-group">
                            <label for="direction" class="control-label col-sm-4"><?= lang('direction') ?></label>
                            <div class="col-sm-7">
                                <select name="direction" class="selectpicker" data-width="100%">
                                    <option <?php
                                    if (!empty($direction)) {
                                        echo $direction == 'ltr' ? 'selected' : '';
                                    }
                                    ?> value="ltr"><?= lang('ltr') ?></option>
                                    <option <?php
                                    if (!empty($direction)) {
                                        echo $direction == 'rtl' ? 'selected' : '';
                                    }
                                    ?> value="rtl"><?= lang('rtl') ?></option>
                                </select>
                            </div>
                        </div>
                        <?php if (empty($account_details)) : ?>
                            <div class="form-group">
                                <label class="col-lg-4 control-label"><?= lang('username') ?> <span class="text-danger">*</span></label>
                                <div class="col-lg-7">
                                    <input class="form-control" id="check_username" type="text"
                                           value="<?= set_value('username') ?>" placeholder="johndoe" name="username"
                                           required>
                                    <div class="required" id="check_username_error"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 control-label"><?= lang('password') ?> <span class="text-danger"> *</span></label>
                                <div class="col-lg-7">
                                    <input type="password" class="form-control" id="new_password"
                                           value="<?= set_value('password') ?>" name="password" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 control-label"><?= lang('confirm_password') ?>
                                    <span class="text-danger"> *</span></label>
                                <div class="col-lg-7">
                                    <input type="password" class="form-control" data-parsley-equalto="#new_password"
                                           value="<?= set_value('confirm_password') ?>" name="confirm_password"
                                           required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 control-label"><?= lang('send_email') . ' ' . lang('password') ?></label>
                                <div class="col-lg-6">
                                    <div class="checkbox c-checkbox">
                                        <label class="needsclick">
                                            <input type="checkbox" name="send_email_password">
                                            <span class="fa fa-check"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel ">
                            <div class="panel-title">
                                <strong><?= lang('permission') ?></strong>
                            </div>
                        </div>
                        <?php
                        $all_client_menu = $this->db->where('parent', 0)->order_by('sort')->get('tbl_client_menu')->result();
                        if (!empty($user_info)) {
                            $user_menu = $this->db->where('user_id', $user_info->user_id)->get('tbl_client_role')->result();
                        }
                        foreach ($all_client_menu as $key => $v_menu) {
                            ?>
                            <div class="form-group">
                                <label class="col-lg-6 control-label"><?= lang($v_menu->label) ?></label>
                                <div class="col-lg-5 checkbox">
                                    <input data-id="" data-toggle="toggle" name="<?= $v_menu->label ?>"
                                           value="<?= $v_menu->menu_id ?>" <?php
                                    $client_menu = unserialize(config_item('client_default_menu'));
                                    if (!empty($user_menu)) {
                                        foreach ($user_menu as $v_u_menu) {
                                            if ($v_u_menu->menu_id == $v_menu->menu_id) {
                                                echo 'checked';
                                            }
                                        }
                                    } else {
                                        if (!empty($client_menu['client_default_menu'])) {
                                            foreach ($client_menu['client_default_menu'] as $default_menu) {
                                                echo $v_menu->menu_id == $default_menu ? 'checked' : '';
                                            }
                                        }
                                    } ?> data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>"
                                           data-onstyle="success btn-xs" data-offstyle="danger btn-xs" type="checkbox">
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"></label>
                    <div class="col-lg-4">
                        <button type="submit" id="new_uses_btn"
                                class="btn btn-primary btn-block"><?= lang('save') . ' ' . lang('client_contact') ?></button>
                    </div>
                
                </div>
            </div>
        </form>
    <?php } ?>
<?php else : ?>
    <section class="panel panel-custom">
        <div class="panel-heading">
            <div class="panel-title">
                <strong><?= lang('contacts') ?></strong>
                <?php
                $edited = can_action('4', 'edited');
                if (!empty($edited)) {
                    ?>
                    <a href="<?= base_url() ?>admin/client/details/<?= $client_details->client_id ?>/contacts/add_contacts"
                       class="btn-sm pull-right"><?= lang('add_contact') ?></a>
                <?php } ?>
            </div>
        </div>
        <div class="panel-body">
            <table class="table table-striped" id="datatable_action" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th><?= lang('full_name') ?></th>
                    <th><?= lang('email') ?></th>
                    <th><?= lang('phone') ?> </th>
                    <th><?= lang('mobile') ?> </th>
                    <th><?= lang('skype_id') ?></th>
                    <th class="col-sm-2"><?= lang('last_login') ?> </th>
                    <th class="col-sm-3"><?= lang('action') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $client_contacts = $this->client_model->get_client_contacts($client_details->client_id);
                if (!empty($client_contacts)) {
                    foreach ($client_contacts as $key => $contact) {
                        ?>
                        <tr>
                            <td><?= $contact->fullname ?></td>
                            <td class="text-info"><?= $contact->email ?> </td>
                            <td><a href="tel:<?= $contact->phone ?>"><?= $contact->phone ?></a></td>
                            <td><a href="tel:<?= $contact->mobile ?>"><?= $contact->mobile ?></a>
                            </td>
                            <td>
                                <a href="skype:<?= $contact->skype ?>?call"><?= $contact->skype ?></a>
                            </td>
                            <?php
                            if ($contact->last_login == '0000-00-00 00:00:00' || empty($contact->last_login)) {
                                $login_time = "-";
                            } else {
                                $login_time = strftime(config_item('date_format'), strtotime($contact->last_login)) . ' ' . display_time($contact->last_login);
                            } ?>
                            <td><?= $login_time ?> </td>
                            <td class="col-sm-3">
                                <a href="<?= base_url() ?>admin/client/make_primary/<?= $contact->user_id ?>/<?= $client_details->client_id ?>"
                                   data-toggle="tooltip" class="btn <?php
                                if ($client_details->primary_contact == $contact->user_id) {
                                    echo "btn-success";
                                } else {
                                    echo "btn-default";
                                }
                                ?> btn-xs " title="<?= lang('primary_contact') ?>">
                                    <i class="fa fa-chain"></i> </a>
                                <a href="<?= base_url() ?>admin/client/details/<?= $client_details->client_id . '/contacts/add_contacts/' . $contact->user_id ?>"
                                   class="btn btn-primary btn-xs" title="<?= lang('edit') ?>">
                                    <i class="fa fa-edit"></i> </a>
                                <a href="<?= base_url() ?>admin/client/delete_contacts/<?= $client_details->client_id . '/' . $contact->user_id ?>"
                                   class="btn btn-danger btn-xs" title="<?= lang('delete') ?>">
                                    <i class="fa fa-trash-o"></i> </a>
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
<?php endif ?>