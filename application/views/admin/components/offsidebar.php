<?php

$user_id = $this->session->userdata('user_id');
?>
<style type="text/css">
    .offsidebar {
        background-color: #1e1e2d
    }
</style>
<aside class="offsidebar hide">
    <!-- START Off Sidebar (right)-->
    <div class="tab-content">
        <!-- Home tab content -->
        <div class="tab-pane active" style="background:none;" id="control-sidebar-home-tab">
            <h2 style="color: #EFF3F4;font-weight: 100;text-align: center;">
                <?php echo jdate("l"); ?>
                <br/>
                <?php echo jdate("jS F, Y"); ?>
            </h2>
            <div id="idCalculadora"></div>

        </div><!-- /.tab-pane -->
    </div>
    <!-- END Off Sidebar (right)-->
</aside>
