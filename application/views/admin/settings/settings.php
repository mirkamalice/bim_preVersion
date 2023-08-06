<div class="row">
    <div class="col-md-2">
        <ul class="nav nav-pills nav-stacked navbar-custom-nav">
            <?php
            $settings_menu = $this->db->where('status', 2)->order_by('sort', 'time')->get('tbl_menu')->result();
            // get last segment of url
            $last_segment = end($this->uri->segments);
            foreach ($settings_menu as $key => $menu) {
                $can_do = can_do($menu->menu_id);
                $link = $menu->link;
                // get last word of link
                $link_array = explode('/', $link);
                $last_word = end($link_array);
                if ($last_word == $last_segment) {
                    $active = 'active';
                } else {
                    $active = '';
                }
                if (!empty($can_do)) {
                    ?>
                    <li class="<?php echo $active; ?>">
                        <a href="<?= base_url($menu->link) ?>">
                            <i class="<?= $menu->icon ?>"></i>
                            <?= lang($menu->label) ?>
                        </a>
                    </li>
                <?php }
            }
            ?>
        </ul>
    </div>
    
    <div class="col-sm-10">
        <?php
        $load = explode('/', $load_setting);
        if (!empty($load[1])) {
            $this->load->view($load_setting);
        } else {
            $this->load->view('admin/settings/' . $load_setting);
        }
        ?>
    </div>
</div>