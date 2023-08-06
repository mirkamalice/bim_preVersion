<?php
$layout = config_item('invoice_layout_view');
if (empty($layout)) {
    $layout = 'default';
}
$this->load->view('admin/common/sales/pdf/' . $layout);
