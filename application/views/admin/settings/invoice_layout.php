<?php echo message_box('success') ?>
<?php
$all_invoice_layout = $this->invoice_model->get_sale_layout();
$view = config_item('invoice_layout_' . $type);
if (!empty($view)) {
    $layout = $view;
} else {
    $layout = 'default';
}
$current_layout = $layout;
?>
<style>
    /* show two columns in a row  from .layoutUl and will be same height */
    .layoutUl {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        /*align-items: center;*/

    }

    .layout:first-child {
        margin-bottom: 10px;
        margin-left: -10px !important;
    }

    .layout {
        border: 1px solid #ddd;
        width: 48%;
        /*height: 150px;*/
        /*padding: 10px;*/
        margin-bottom: 10px;
        /*background: #fff;*/
        /*margin-right: 5px;*/

    }

    .layoutUl>.active {
        border: 1px solid #ddd;
        /*    !*padding: 10px;*!*/
        /*    margin-bottom: 10px;*/
        /*    background: #fff;*/
        /*    margin-right: 10px;*/
    }

    .layoutUl>.active>a {
        /*border: 1px solid #ddd;*/
        /*padding: 12px !important;*/
        /*margin-bottom: 10px;*/
    }
</style>
<div class="row">
    <!-- Start Form -->
    <div class="col-lg-12">
        <form action="<?php echo base_url() ?>admin/settings/save_invoice" enctype="multipart/form-data" class="form-horizontal" method="post">
            <div class="panel panel-custom">
                <header class="panel-heading  "><?= lang('invoice_layout_' . $type) ?></header>
                <div class="panel-body row">
                    <div class="col-lg-2">
                        <ul class="nav nav-pills nav-stacked layoutUl">
                            <?php foreach ($all_invoice_layout as $key => $layout) : ?>
                                <li class="layout <?php echo $key == $current_layout ? 'active' : '' ?>">
                                    <a id="layout_view" href="#" data-type="<?= $key ?>">
                                        <span class="text-center"><?php echo lang($layout) ?></span>
                                    </a>
                                </li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                    <div class="col-lg-9 bl" id="layout_preview">
                        <?php
                        $this->load->view('admin/common/sales/' . $current_layout);
                        ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- End Form -->
</div>
<script type="text/javascript">
    // click on layout_view to change layout and show preview into layout_preview
    $(document).on('click', '#layout_view', function(e) {
        e.preventDefault();
        var layout = $(this).attr('data-type');
        // change active class
        $('.layout').removeClass('active');
        $(this).parent().addClass('active');
        $.ajax({
            type: "POST",
            url: '<?= base_url() ?>admin/settings/invoice_layout',
            data: {
                layout
            },
            dataType: "json",
            success: function(data) {
                // show preview
                $('#layout_preview').html(data.subview);
            }
        });
    });
</script>