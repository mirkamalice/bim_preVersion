<?php
if (!empty($global)) {
    echo $global;
}
?>
<div class="row mt-lg">
    <div class="col-sm-2">
        <ul class="nav nav-pills nav-stacked navbar-custom-nav">
            <?php
            
            if (!empty($all_tabs)) {
                foreach ($all_tabs as $key => $v_tab) {
                    ?>
                    <li class="<?php
                    if ($active == $key) {
                        echo 'active';
                    }
                    ?>">
                        <a href="<?= base_url($v_tab['url']) ?>">
                            <?php if (!empty($v_tab['icon'])) { ?>
                                <i class="<?= $v_tab['icon'] ?>"></i>
                            <?php } ?>
                            <?= lang($v_tab['name']) ?>
                            <strong class="pull-right">
                                <?php
                                if (!empty($v_tab['count'])) {
                                    echo '<span class="label label-inverse">' . $v_tab['count'] . '</span>';
                                }
                                ?>
                            </strong>
                        </a>
                    </li>
                    <?php
                }
            }
            ?>
        
        </ul>
    </div>
    
    <div class="col-sm-10">
        <div class="tab-content" style="border: 0;padding:0;">
            <!-- Task Details tab Starts -->
            <?php
            $view = tab_load_view($all_tabs, $active);
            $this->load->view($view);
            ?>
        
        </div>
    </div>
</div>