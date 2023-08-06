<?php

defined('BASEPATH') or exit('No direct script access allowed');

class My_module extends Admin_Controller
{
    private $tmp_update_dir;
    private $tmp_dir;
    private $purchase_code;
    private $envato_username;
    private $latest_version;

    public function __construct()
    {
        parent::__construct();
        if (!admin()) {
            redirect('admin/dashboard');
        }
    }

    public function index()
    {
        $data['modules'] = $this->module->get();
        $data['title']   = lang('modules');
        $data['subview'] = $this->load->view('admin/module/module_list', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load        
    }

    public function activate($name)
    {
        $this->module->activate($name);
        $this->to_modules();
    }

    public function deactivate($name)
    {
        $this->module->deactivate($name);
        $this->to_modules();
    }

    public function uninstall($name)
    {
        $this->module->uninstall($name);
        $this->to_modules();
    }

    public function upload()
    {

        if ($_POST['envato_username'] == '') {
            $response['type'] = 'error';
            $response['message'] = 'We need your envato username to verify The purchase';
        } elseif ($_POST['purchase_key'] == '') {
            $response['type'] = 'error';
            $response['message'] = 'Enter your envato purchase code here';
        } elseif ($_FILES['module']['name'] == '') {
            $response['type'] = 'error';
            $response['message']  = 'you need to upload the module zip file to install it.';
        }
        if (!empty($_FILES['module']['name'])) {
            $file_name = $_FILES['module']['name'];
            $valid = $this->fileValidation($file_name);
            if ($valid === true) {
                $config['upload_path'] = 'uploads/temp/';
                $config['allowed_types'] = 'zip';
                $config['overwrite'] = TRUE;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('module')) {
                    $error = $this->upload->display_errors();
                    $response['type'] = 'error';
                    $response['message']  = $error;
                } else {
    
                    $target_path = getcwd() . "/uploads/temp/";
                    $zip = new ZipArchive;
                    $res = $zip->open($target_path . $file_name);
                    if ($res === TRUE) { // Unzip path   
                        if (!$zip->extractTo($target_path)) {
                            $response['type'] = 'error';
                            $response['message'] = 'Failed to extract downloaded zip file';
                        }
                        $zip->extractTo($target_path);
                        $moduleID = $this->check_module($target_path);
                        if ($moduleID === false) {
                            $response['type'] = 'error';
                            $response['message']  = 'No valid module is found.';
                        } else {
                            $pdata['envato_username'] = $_POST['envato_username'];
                            $pdata['purchase_code'] = $_POST['purchase_key'];
                            $pdata['item_id'] = $moduleID;
                            $pdata['ip_address'] = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : $_SERVER["HTTP_HOST"];
                            $pdata['url'] = base_url();
                            $pdata['path'] = MODULES_PATH . explode('.', $file_name)[0];
                            $this->latest_version = 'module_' . explode('.', $file_name)[1];
                            $this->purchase_code = $_POST['purchase_key'];
                            $env_data = $this->remote_get_contents($pdata);
                            if (!empty($env_data) && $env_data === true) {
                                $response['type'] = 'success';
                                $response['message'] = 'Upload & Extract successfully.';
                            } else {
                                $response['type'] = 'success';
                                $response['message'] = 'Upload & Extract successfully.';
                                $this->clean_up_dir($target_path);
                            }
                        }
                        $this->clean_up_dir($target_path);
                        $zip->close();
                    } else {
                        $response['type'] = 'error';
                        $response['message'] = 'Failed to extract downloaded zip file';
                    }
                }
            } else {
                $response['type'] = 'error';
                $response['message'] = 'you have to upload only zip file to install module';
            }
        }
        if (!empty($response['type'])) {
            $this->session->set_userdata($response);
        }
        $this->to_modules();
    }
    
    public function remove_old_files($target_path)
    {
        $files = glob($target_path . '*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file))
                unlink($file); // delete file
        }
    }
    
    
    public function remote_get_contents($post, $getDB = null)
    {
        if (function_exists('curl_init')) {
            return self::curl_get_contents($post, $getDB);
        } else {
            return 'Please enable the curl function';
        }
    }
    
    public function curl_get_contents($post)
    {
        $tmp_dir = get_temp_dir();
        if (!$tmp_dir || !is_writable($tmp_dir)) {
            $tmp_dir = app_temp_dir();
        }
        if (!is_writable($tmp_dir)) {
            header('HTTP/1.0 400');
            echo "Temporary directory not writable - <b>$tmp_dir</b><br />Please contact your hosting provider make this directory writable. The directory needs to be writable for the update files.";
            exit();
        }

        $this->tmp_dir = $tmp_dir;
        $tmp_dir = $tmp_dir . 'v' . $this->latest_version . '/';
        $this->tmp_update_dir = $tmp_dir;

        if (!is_dir($tmp_dir)) {
            mkdir($tmp_dir);
            fopen($tmp_dir . 'index.html', 'w');
        }
        $zipFile = $tmp_dir . $this->latest_version . '.zip'; // Local Zip File Path
        $zipResource = fopen($zipFile, "w+");
        $this->load->library('user_agent');
        // Get The Zip File From Server        
        $url = UPDATE_URL . 'api/installModule';
        $curl_handle = curl_init();

        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, $this->agent->agent_string());
        curl_setopt($curl_handle, CURLOPT_FAILONERROR, true);
        curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl_handle, CURLOPT_AUTOREFERER, true);
        curl_setopt($curl_handle, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl_handle, CURLOPT_TIMEOUT, 600);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl_handle, CURLOPT_FILE, $zipResource);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $post);
        $success = curl_exec($curl_handle);
        if (!$success) {
            $this->clean_tmp_files();
            header('HTTP/1.0 400 Bad error');
            $error = $this->getErrorByStatusCode(curl_getinfo($curl_handle, CURLINFO_HTTP_CODE));
            if ($error == '') {
                // Uknown error
                $error = curl_error($curl_handle);
            }
            $this->clean_tmp_files();
            return  $error;
        }
        curl_close($curl_handle);
        $zip = new ZipArchive;
        if ($zip->open($zipFile) === true) {
            if (!$zip->extractTo(MODULES_PATH)) {
                header('HTTP/1.0 400 Bad error');
                $upgradeCopyLocation = $this->copyUpgrade($zipFile);
                $message = 'Failed to extract downloaded zip file';
                if ($upgradeCopyLocation) {
                    $message .= '<hr /><p>The update files are copied to <b>' . $upgradeCopyLocation . '</b> so you can try to <b>extract them manually</b> e.q. via cPanel or command line, use the best method that is suitable for you. <br /><br /><b>Don\'t forget that you must extract the contents of the ' . basename($upgradeCopyLocation) . ' file in ' . FCPATH . '</b> <br/> <br/> after that please remove after extract the files from ' . $upgradeCopyLocation . '</p>';
                }
                $zip->close();
                return $message;
            }
            $zip->close();
            return true;
        } else {
            header('HTTP/1.0 400 Bad error');
            $message = 'Failed to open downloaded zip file';
            return $message;
        }
        $this->clean_tmp_files();
    }


    public function check_module($source)
    {
        // Check the folder contains at least 1 valid module.
        $modules_found = false;

        $files = get_dir_contents($source);
        if ($files) {
            foreach ($files as $file) {
                if (endsWith($file, '.php')) {
                    $info = $this->module->get_headers($file);
                    if (isset($info['item_id']) && !empty($info['item_id'])) {
                        $modules_found = $info['item_id'];
                        break;
                    }
                }
            }
        }
    
        if (!$modules_found) {
            return false;
        }
        return $modules_found;
    }
    
    private function clean_up_dir($target_path)
    {
        $files = glob($target_path . '/*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file))
                unlink($file); // delete file`
        }
        
        $target_path = getcwd() . "/uploads/temp/*";
        array_map('unlink', array_filter((array)glob($target_path, GLOB_BRACE)));
        $dirFile = $target_path . '__MACOSX';
        delete_files($dirFile, true);
    }

    public function fileValidation($file_name)
    {
        $file_ext1 = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $file_ext2 = explode(".", $file_name);
        if ($file_ext1 === 'zip' && end($file_ext2) === 'zip') {
            return true;
        }
        return false;
    }

    public function upgrade_database($name)
    {

        $latest_version = $this->input->post('latest_version', true);
        $result = $this->module->upgrade_database($name);
        $module = $this->module->get($name);
        $auto_update = $this->input->post('auto_update', true);
        if (!empty($auto_update)) {
            if (is_string($result)) {
                echo $result;
                exit();
            }
            echo '<div class="bold">
            <h4 class="bold">Hi! Thanks for updating ' . $module['headers']['module_name'] . ' - You are using version ' . wordwrap($latest_version, 1, '.', true) . '</h4>
            <p>
                This window will reload automatically in 5 seconds and will try to clear your browser cache, however its recommended to clear your browser cache manually.
            </p></div>';
            set_message('success', lang('using_latest_version'));
            exit();
        } else {
            // Possible error
            if (is_string($result)) {
                $type = 'error';
                $msg = $result;
            } else {
                $type = 'success';
                $msg = 'Database Upgraded Successfully';
            }
            set_message($type, $msg);
            $this->to_modules();
        }
    }

    public function update_version($name)
    {
        $version_available = $this->module->new_version_available($name);
        if (!empty($version_available['new_version'])) {
            $this->module->update_to_new_version($name);
        }
        $this->to_modules();
    }

    private function to_modules()
    {
        redirect(base_url('admin/my_module'));
    }

    private
    function clean_tmp_files()
    {
        if (is_dir($this->tmp_update_dir)) {
            @delete_files($this->tmp_update_dir);
            if (@!delete_dir($this->tmp_update_dir)) {
                @rename($this->tmp_update_dir, $this->tmp_dir . 'delete_this_' . uniqid());
            }
        }
    }

    protected function getErrorByStatusCode($statusCode)
    {
        $error = '';
        if ($statusCode == 499) {
            $mailBody = 'Hello. I tried to upgrade to the latest version but for some reason the upgrade failed. Please remove the key from the upgrade log so i can try again. My installation URL is: ' . base_url() . '. Regards.';

            $mailSubject = 'Purchase code Removal Request - [' . $this->purchase_code . ']';
            $error = 'The Purchase code already used to download upgrade files for version ' . wordwrap($this->latest_version, 1, '.', true) . '. Performing multiple auto updates to the latest version with one/regular version purchase code is not allowed. If you have multiple installations you must buy another license.<br /><br /> If you have staging/testing installation and auto upgrade is performed there, <b>you should perform manually upgrade</b> in your production area<br /><br /> <h4 class="bold">Upgrade failed?</h4> The error can be shown also if the update failed for some reason, but because the purchase code is already used to download the files, you won’t be able to re-download the files again.<br /><br />Click <a href="mailto:uniquecoder007@gmail.com?subject=' . $mailSubject . '&body=' . $mailBody . '"><b>here</b></a> to send an mail and get your purchase code removed from the upgrade log.';
        } elseif ($statusCode == 498) {
            $error = 'Your username or purchase code is Invalid.Please enter the valid information.';
        } elseif ($statusCode == 497) {
            $error = 'Your Download Item ID and the software ID does not match.Please download it from your . <a href="https://help.market.envato.com/hc/en-us/articles/202501014-How-To-Download-Your-Items"> download </a> drop-down menu';;
        } elseif ($statusCode == 496) {
            $error = 'Your Purchase code is Empty.';
        } elseif ($statusCode == 495) {
            $error = 'Your envato username does not match the buyer username';
        } else {
            $error = $statusCode;
        }
        return $error;
    }

    protected function copyUpgrade($zipFile)
    {
        if (!file_exists($zipFile)) {
            return false;
        }
        $tmp_dir = get_temp_dir();
        if (!$tmp_dir || !is_writable($tmp_dir)) {
            $tmp_dir = app_temp_dir();
        }

        $copyFileLocation = $tmp_dir . time() . '-upgrade-version-' . basename($zipFile);

        $upgradeCopied = false;

        if (@copy($zipFile, $copyFileLocation)) {

            // Delete old upgrade stored data
            $oldUpgradeData = $this->get_last_upgrade_copy_data();

            if ($oldUpgradeData) {
                @unlink($oldUpgradeData->path);
            }

            $optionData = ['path' => $copyFileLocation, 'version' => $this->latest_version, 'time' => time()];
            $data = array('value' => json_encode($optionData));
            $this->db->where('config_key', 'last_update_data')->update('tbl_config', $data);
            $exists = $this->db->where('config_key', 'last_update_data')->get('tbl_config');
            if ($exists->num_rows() == 0) {
                $this->db->insert('tbl_config', array("config_key" => 'last_update_data', "value" => json_encode($optionData)));
            }
            $upgradeCopied = true;
        }

        return $upgradeCopied ? $copyFileLocation : false;
    }

    function get_last_upgrade_copy_data()
    {
        $lastUpgradeCopyData = config_item('last_update_data');
        if ($lastUpgradeCopyData !== '') {
            $lastUpgradeCopyData = json_decode($lastUpgradeCopyData);

            return is_object($lastUpgradeCopyData) ? $lastUpgradeCopyData : false;
        }

        return false;
    }

    public function __destruct()
    {
        $this->clean_tmp_files();
    }


    public function available_modules()
    {
        $data['title'] = lang('available_modules');
        $data['available_modules'] = $this->get_available_modules();        
        $data['subview'] = $this->load->view('admin/module/available_modules', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load        
    }

    public
    function get_available_modules()
    {
        $curl = curl_init();
        $itemID = PurchaseitemID;
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_URL => UPDATE_URL . 'api/available_modules',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                'item_id' => $itemID,
            )
        ));

        $result = curl_exec($curl);
        $error = '';

        if (!$curl || !$result) {
            $error = 'Curl Error - Contact your hosting provider with the following error as reference: Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl);
        }

        curl_close($curl);

        if ($error != '') {
            return $error;
        }
        return $result;
    }
}
