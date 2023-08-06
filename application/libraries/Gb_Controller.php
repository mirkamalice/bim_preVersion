<?php

/**
 * Description of Admin_Controller
 *
 * @author pc mart ltd
 */
class Gb_Controller extends MY_Controller
{
    private $_current_version;
    
    function __construct()
    {
        parent::__construct();
        do_action('appended_to_gb_controller');
    }
}
