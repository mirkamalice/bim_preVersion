<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Account extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('account_model');
    }

    public function manage_account()
    {
        $data['active'] = 1;
        $data['title'] = lang('manage_account');
        $data['subview'] = $this->load->view('admin/account/manage_account', $data, true);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function create_account($id = null)
    {
        $data['title'] = lang('manage_account');
        if ($id) {
            $data['active'] = 2;
            $can_edit = $this->account_model->can_action('tbl_accounts', 'edit', array('account_id' => $id));
            $edited = can_action('36', 'edited');
            if (!empty($can_edit) && !empty($edited)) {
                $data['account_info'] = $this->account_model->check_by(array('account_id' => $id), 'tbl_accounts');
            }
        }
        $data['active'] = 2;
        $data['permission_user'] = $this->account_model->all_permission_user('36');
        $data['subview'] = $this->load->view('admin/account/create_account', $data, true);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function save_account($id = null)
    {
        $created = can_action('36', 'created');
        $edited = can_action('36', 'edited');
        if (!empty($created) || !empty($edited) && !empty($id)) {
            $this->account_model->_table_name = 'tbl_accounts';
            $this->account_model->_primary_key = 'account_id';
            $data = $this->account_model->array_from_post(array('account_name', 'description', 'balance', 'account_number', 'contact_person', 'contact_phone', 'bank_details'));
            // update root category
            $where = array('account_name' => $data['account_name']);
            // duplicate value check in DB
            if (!empty($id)) { // if id exist in db update data
                $account_id = array('account_id !=' => $id);
            } else { // if id is not exist then set id as null
                $account_id = null;
            }
            // check whether this input data already exist or not
            $check_account = $this->account_model->check_update('tbl_accounts', $where, $account_id);
            if (!empty($check_account)) { // if input data already exist show error alert
                // massage for user
                $type = 'error';
                $msg = "<strong style='color:#000'>" . $data['account_name'] . '</strong>  ' . lang('already_exist');
            } else { // save and update query
                $permission = $this->input->post('permission', true);
                if (!empty($permission)) {
                    if ($permission == 'everyone') {
                        $assigned = 'all';
                    } else {
                        $assigned_to = $this->account_model->array_from_post(array('assigned_to'));
                        if (!empty($assigned_to['assigned_to'])) {
                            foreach ($assigned_to['assigned_to'] as $assign_user) {
                                $assigned[$assign_user] = $this->input->post('action_' . $assign_user, true);
                            }
                        }
                    }
                    if (!empty($assigned)) {
                        if ($assigned != 'all') {
                            $assigned = json_encode($assigned);
                        }
                    } else {
                        $assigned = 'all';
                    }
                    $data['permission'] = $assigned;
                } else {
                    set_message('error', lang('assigned_to') . ' Field is required');
                    if (empty($_SERVER['HTTP_REFERER'])) {
                        redirect('admin/account/manage_account');
                    } else {
                        redirect($_SERVER['HTTP_REFERER']);
                    }
                }
                if (!empty($id)) {
                    $can_edit = $this->account_model->can_action('tbl_accounts', 'edit', array('account_id' => $id));
                    if (!empty($can_edit)) {
                        $return_id = $this->account_model->save($data, $id);
                    } else {
                        set_message('error', lang('there_in_no_value'));
                        redirect('admin/account/manage_account');
                    }
                } else {
                    $return_id = $this->account_model->save($data);
                }
                if (!empty($id)) {
                    $id = $id;
                    $action = 'activity_update_account';
                    $msg = lang('update_account');
                } else {
                    $id = $return_id;
                    $action = 'activity_save_account';
                    $msg = lang('save_account');
                }
                save_custom_field(21, $id);
                $activity = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'account',
                    'module_field_id' => $id,
                    'activity' => $action,
                    'icon' => 'fa-circle-o',
                    'value1' => $data['account_name']
                );
                $this->account_model->_table_name = 'tbl_activities';
                $this->account_model->_primary_key = 'activities_id';
                $this->account_model->save($activity);
                // messages for user
                $type = "success";
            }
            $message = $msg;
            set_message($type, $message);
        }
        redirect('admin/account/manage_account');
    }

    public function new_account()
    {
        $data['title'] = lang('new_account');
        $data['permission_user'] = $this->account_model->all_permission_user('36');
        $data['subview'] = $this->load->view('admin/account/new_account', $data, false);
        $this->load->view('admin/_layout_modal', $data);
    }

    public function accountList()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_accounts';
            $this->datatables->column_order = array('account_name', 'balance', 'description', 'account_number', 'phone');
            $this->datatables->column_search = array('account_name', 'balance', 'description', 'account_number', 'phone');
            $this->datatables->order = array('account_id' => 'desc');

            $fetch_data = $this->datatables->get_datatable_permission();

            $data = array();

            $edited = can_action('36', 'edited');
            $deleted = can_action('36', 'deleted');
            foreach ($fetch_data as $key => $row) {
                $action = null;
                $can_edit = $this->account_model->can_action('tbl_accounts', 'edit', array('account_id' => $row->account_id));
                $can_delete = $this->account_model->can_action('tbl_accounts', 'delete', array('account_id' => $row->account_id));
                $sub_array = array();
                $sub_array[] = $row->account_name;
                $sub_array[] = $row->description;
                $sub_array[] = $row->account_number;
                $sub_array[] = $row->contact_phone;
                $sub_array[] = display_money($row->balance, default_currency());

                $custom_form_table = custom_form_table(21, $row->account_id);

                if (!empty($custom_form_table)) {
                    foreach ($custom_form_table as $c_label => $v_fields) {
                        $sub_array[] = $v_fields;
                    }
                }

                if (!empty($can_edit) && !empty($edited)) {
                    $action .= btn_edit('admin/account/create_account/' . $row->account_id) . ' ';
                }
                if (!empty($can_delete) && !empty($deleted)) {
                    $action .= ajax_anchor(base_url("admin/account/delete_account/$row->account_id"), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_" . $key));
                }

                //            echo $action;
                $sub_array[] = $action;
                $data[] = $sub_array;
            }
            render_table($data);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function saved_account($id = null)
    {
        if (!$this->input->is_ajax_request()) {
            redirect('admin/dashboard');
        }
        $created = can_action('36', 'created');
        $edited = can_action('36', 'edited');
        if (!empty($created) || !empty($edited) && !empty($id)) {
            $this->account_model->_table_name = 'tbl_accounts';
            $this->account_model->_primary_key = 'account_id';

            $data = $this->account_model->array_from_post(array('account_name', 'description', 'balance', 'account_number', 'contact_person', 'contact_phone', 'bank_details'));

            // update root category
            $where = array('account_name' => $data['account_name']);
            // duplicate value check in DB
            if (!empty($id)) { // if id exist in db update data
                $account_id = array('account_id !=' => $id);
            } else { // if id is not exist then set id as null
                $account_id = null;
            }
            // check whether this input data already exist or not
            $check_account = $this->account_model->check_update('tbl_accounts', $where, $account_id);
            if (!empty($check_account)) { // if input data already exist show error alert
                // massage for user
                $type = 'error';
                $msg = "<strong style='color:#000'>" . $data['account_name'] . '</strong>  ' . lang('already_exist');
            } else { // save and update query
                $data['permission'] = 'all';

                if (!empty($id)) {
                    $can_edit = $this->account_model->can_action('tbl_accounts', 'edit', array('account_id' => $id));
                    if (!empty($can_edit)) {
                        $return_id = $this->account_model->save($data, $id);
                    } else {
                        set_message('error', lang('there_in_no_value'));
                        redirect('admin/account/manage_account');
                    }
                } else {
                    $return_id = $this->account_model->save($data);
                }

                if (!empty($id)) {
                    $id = $id;
                    $action = 'activity_update_account';
                    $msg = lang('update_account');
                } else {
                    $id = $return_id;
                    $action = 'activity_save_account';
                    $msg = lang('save_account');
                }
                $activity = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'account',
                    'module_field_id' => $id,
                    'activity' => $action,
                    'icon' => 'fa-circle-o',
                    'value1' => $data['account_name']
                );
                $this->account_model->_table_name = 'tbl_activities';
                $this->account_model->_primary_key = 'activities_id';
                $this->account_model->save($activity);
                // messages for user
                $type = "success";
            }
        }
        if (!empty($id)) {
            $result = array(
                'id' => $id,
                'account_name' => $data['account_name'],
                'status' => $type,
                'message' => $msg,
            );
        } else {
            $result = array(
                'status' => $type,
                'message' => $msg,
            );
        }
        echo json_encode($result);
        exit();
    }

    public function delete_account($id)
    {
        $deleted = can_action('36', 'deleted');
        $can_delete = $this->account_model->can_action('tbl_accounts', 'delete', array('account_id' => $id));
        if (!empty($deleted) && !empty($can_delete)) {
            $all_files_info = $this->db->where(array('account_id' => $id))->get('tbl_transactions')->result();
            if (!empty($all_files_info)) {
                foreach ($all_files_info as $v_files) {
                    $attachment = json_decode($v_files->attachment);
                    foreach ($attachment as $v_file) {
                        remove_files($v_file->fileName);
                    }
                }
            }
            $action = 'activity_delete_account';
            $msg = lang('delete_account');
            $acc_info = $this->account_model->check_by(array('account_id' => $id), 'tbl_accounts');
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'account',
                'module_field_id' => $id,
                'activity' => $action,
                'icon' => 'fa-circle-o',
                'value1' => $acc_info->account_name
            );
            $this->account_model->_table_name = 'tbl_activities';
            $this->account_model->_primary_key = 'activities_id';
            $this->account_model->save($activity);

            $this->account_model->_table_name = "tbl_transactions"; //table name
            $this->account_model->delete_multiple(array('account_id' => $id));

            $this->account_model->_table_name = "tbl_transfer"; //table name
            $this->account_model->delete_multiple(array('to_account_id' => $id));
            $this->account_model->delete_multiple(array('from_account_id' => $id));

            $this->account_model->_table_name = 'tbl_accounts';
            $this->account_model->_primary_key = 'account_id';
            $this->account_model->delete($id);

            $type = "success";
            //            $message = $msg;
            //            set_message($type, $message);
            echo json_encode(array("status" => $type, 'message' => $msg));
            exit();
        }
        //        redirect('admin/account/manage_account');
    }

    public function account_balance()
    {
        $data['title'] = lang('account_balance');
        $data['subview'] = $this->load->view('admin/account/account_balance', $data, true);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function trigger_event()
    {
        $user = array(1);
        show_notification($user);
        $data['title'] = lang('account_balance');
        $data['subview'] = $this->load->view('admin/account/account_balance', $data, true);
        $this->load->view('admin/_layout_main', $data); //page load
    }


    public function getExpenses()
    {
        $detail_link = anchor('admin/purchases/expense_note/$1', '<i class="fa fa-file-text-o"></i> ' . lang('expense_note'), 'data-toggle="modal" data-target="#myModal2"');
        $edit_link = anchor('admin/purchases/edit_expense/$1', '<i class="fa fa-edit"></i> ' . lang('edit_expense'), 'data-toggle="modal" data-target="#myModal"');
        //$attachment_link = '<a href="'.base_url('assets/uploads/$1').'" target="_blank"><i class="fa fa-chain"></i></a>';
        $delete_link = "<a href='#' class='po' title='<b>" . $this->lang->line("delete_expense") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . base_url('purchases/delete_expense/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('delete') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li>' . $detail_link . '</li>
            <li>' . $edit_link . '</li>
            <li>' . $delete_link . '</li>
        </ul>
    </div></div>';

        $this->load->library('datatables');

        $this->datatables
            ->select("tbl_project.project_name,tbl_client.name,tbl_project.start_date,end_date,project_status", false)
            ->from('tbl_project')
            ->join('tbl_client', 'tbl_client.client_id=tbl_project.client_id', 'left')
            //            ->join('tbl_departments', 'tbl_departments.departments_id=tbl_tickets.departments_id', 'left')
            ->group_by('tbl_project.project_id');
        //$this->datatables->edit_column("attachment", $attachment_link, "attachment");
        $this->datatables->add_column(lang('actions'), $action, "id");
        echo $this->datatables->generate();
    }

    public function oauth()
    {
        $data['title'] = lang('account_balance');
        $data['clientid'] = 'newtest-ewunuvn0';
        $fields_string = '';
        $errors = array();
        if (isset($_GET['code'])) {
            $clientsecret = 'kDhfbDtuLE1bQh8wAywLvMb3yu7e2wKI';
            $initialurl = 'https://api.envato.com/token';

            $fields = array(
                'grant_type' => urlencode('authorization_code'),
                'code' => urlencode($_GET['code']),
                'client_id' => urlencode($data['clientid']),
                'client_secret' => urlencode($clientsecret)
            );

            //url-ify the data for the POST
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            $fields_string = rtrim($fields_string, '&');

            $ch = curl_init($initialurl);

            //curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, count($fields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_USERAGENT, $data['clientid']);

            $cinit_data = curl_exec($ch);

            $initialdata = json_decode($cinit_data, true);
            curl_close($ch);

            if (empty($initialdata['access_token'])) {
                $errors[] = 'Could not get bearer, please contact admin';
            }

            //now lets get the username from envato
            if (!$errors) {

                // echo '<pre>';
                // print_r($fields_string);
                // exit();
                // print_r($initialdata);

                //setting the header for the rest of the api
                $bearer = 'bearer ' . $initialdata['access_token'];
                $header = array();
                $header[] = 'Content-length: 0';
                $header[] = 'Content-type: application/json';
                $header[] = 'Authorization: ' . $bearer;


                $usernameurl = 'https://api.envato.com/v1/market/private/user/account.json';
                // $usernameurl = 'https://api.envato.com/v1/market/private/user/username.json';

                $ch_username = curl_init($usernameurl);

                curl_setopt($ch_username, CURLOPT_HTTPHEADER, $header);
                curl_setopt($ch_username, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch_username, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_username, CURLOPT_CONNECTTIMEOUT, 5);
                curl_setopt($ch_username, CURLOPT_USERAGENT, $data['clientid']);

                $cinit_username_data = curl_exec($ch_username);
                // echo $cinit_username_data;
                $username_data = json_decode($cinit_username_data, true);

                curl_close($ch_username);
                echo '<pre>';
                print_r($username_data);
                exit();
                if (empty($username_data['username'])) {
                    $errors[] = 'Could not get username, please contact admin';
                } else {
                    $username = $username_data['username'];
                    //do what you wish with the username
                    echo "Hello $username";
                }
            }
            print_r($errors);
        }
        $data['subview'] = $this->load->view('admin/account/new_test', $data, true);
        $this->load->view('admin/_layout_main', $data); //page load
    }


    public function new_test()
    {

        // $data['title'] = lang('account_balance');
        $this->load->view('admin/account/new_test');
        // $this->load->view('admin/_layout_main', $data); //page load
    }

    public function newTest()
    {
        $dd = '[{"columns":[[{"widget":"ticket_tasks_report","title":""}],[{"widget":"announcements","title":""}]],"ratio":"6-6"},{"columns":[[{"widget":"my_tasks","title":""}],[{"widget":"income_expense","title":""}],[{"widget":"recently_paid_invoices","title":""}]],"ratio":"4-4-4"}]';
        $data = json_decode($dd);
        echo '<pre>';
        print_r($data);
        exit();

    }

    public function basket($id)
    {
        $token = $this->input->post('token'); // Ödeme sonrası dönen token

        if (!empty($token)) {
            IyzipayBootstrap::init();
            $option = new \Iyzipay\Options();
            $option->setApiKey("sandbox-dNkkrXV6vpHQ2rb1pCOoH3Li5W8s7gMh");
            $option->setSecretKey("sandbox-RyAiG87yEKi0xm6r5OgpfbsPIRYIpnp5");
            $option->setBaseUrl("https://sandbox-api.iyzipay.com");

            $return = new \Iyzipay\Request\RetrieveCheckoutFormRequest(); //Token derlemesi yapılıp işlemin onaylanıp onaylanmadığını bildirir
            $return->setLocale(\Iyzipay\Model\Locale::TR);
            $return->setToken($token);

            $checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($return, $option);

            $request = $checkoutForm;

            if ($request->getPaymentStatus() === "SUCCESS") {
                /**
                 * Olumlu sonuç alınmış ödeme aktarılmıştır.
                 * Gerekli işlemlerinizi yapabilirsiniz.
                 * Ürünleri teslimatı vs. veritabanı işlemlerinizi
                 * print_r($request) 'i basarak üretilen benzersiz ürün koduna falan erişebilirsiniz.
                 */
            } else {
                /**
                 * Uyarı vermek isterseniz burayı kullanabilirsiniz.
                 */
            }
        } else {
            $get_item = $this->db->get_where('products', array('product_id' => $id))->row();
            if (!empty($get_item)) {
                $data['get_item'] = $get_item;
                $data['iyzico'] = $this->iyzico_trigger($get_item);
                echo '<pre>';
                print_r($data);
                exit();
                $this->load->view('admin/account/basket', $data);
            } else {
                redirect('products');
            }
        }
    }

    public function iyzico_trigger($products)
    {
        IyzipayBootstrap::init();
        if (!empty($products)) {
            $iyzico = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest(); // İyziPay Form tetiklemesi için gerekli bilgiler
            $iyzico->setLocale(\Iyzipay\Model\Locale::TR);
            $iyzico->setConversationId($products->product_code); //Benzersiz oluşturulması gereken ürün kodu
            $iyzico->setPrice($products->product_amount); // Ürün fiyatı
            $iyzico->setPaidPrice($products->product_amount); // Ödenecek ürün fiyatı (burası çekim işleminde tetiklenecek alan)
            $iyzico->setCurrency(\Iyzipay\Model\Currency::TL); // Ödeme şeklini belirtmek için kullanılır
            $iyzico->setBasketId($products->product_code); // Sipariş kodu, ürün kodu geri dönüş olarak gelmektedir.
            $iyzico->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT); // Ürün bilgilerini tetiklemesi
            $iyzico->setCallbackUrl("http://localhost/project/iyzipayCi/products/basket/" . $products->product_id); // Formun oluşturması için kullanılan geri dönüş URL adresi

            $buyer = new \Iyzipay\Model\Buyer(); // Müşteri bilgilerinin oluşturulması
            $buyer->setId("1"); // Müşteri bazındaki ID
            $buyer->setName("Müşteri Adı"); // Müşteri Adı
            $buyer->setSurname("Müşteri Soyadı"); // Müşteri Soyadı
            $buyer->setGsmNumber("Müşteri Telefon Numarası"); // Müşteri Telefon Numarası
            $buyer->setEmail("test@123.com"); // test@123.com
            $buyer->setIdentityNumber("00000000000"); // Müşteri TC Kimlik Numarası (zorunluluk sistem sahibine ait.)
            $buyer->setLastLoginDate(date('Y-m-d H:i:s')); // Müşteri Son giriş
            $buyer->setRegistrationDate(date('Y-m-d H:i:s')); // Müşteri Sipariş (Kayıt) Tarihi
            $buyer->setRegistrationAddress("Bursa"); // Müşteri Sipariş (Kayıt) Adresi
            $buyer->setIp($this->input->ip_address()); // Müşteri IP Adresi
            $buyer->setCity("Bursa"); // Müşteri İl
            $buyer->setCountry("Bursa"); // Müşteri İlçe
            $buyer->setZipCode("16000"); // Müşteri Posta Kodu
            $iyzico->setBuyer($buyer); // Müşteri sipariş (Sepet, ürün) bilgileri tetikletme

            $shippingAddress = new \Iyzipay\Model\Address(); // Müşteri kargo bilgilerinin oluşturulması
            $shippingAddress->setContactName("Müşteri Adı"); // Müşteri Adı
            $shippingAddress->setCity("Bursa"); // Müşteri İl
            $shippingAddress->setCountry("Bursa"); // Müşteri İlçe
            $shippingAddress->setAddress("Bursa"); // Müşteri Adresi
            $shippingAddress->setZipCode("16000"); // Müşteri Posta Kodu
            $iyzico->setShippingAddress($shippingAddress); // Sipariş kargo bilgileri tetikletme

            $billingAddress = new \Iyzipay\Model\Address(); //Fatura bilgileri için istenilen bilgiler
            $billingAddress->setContactName("Müşteri Adı"); // Müşteri Adı
            $billingAddress->setCity("Bursa"); // Müşteri İl
            $billingAddress->setCountry("Bursa"); // Müşteri İlçe
            $billingAddress->setAddress("Bursa"); // Müşteri Adresi
            $billingAddress->setZipCode("16000"); // Müşteri Posta Kodu
            $iyzico->setBillingAddress($billingAddress); // Gerekli tetikleme


            /**
             * Burada $firstBasketItem kendimizin tanımladığı bir değişken tetiklemesidir.
             * 1 den fazla ürün ekleyebilirsiniz tek dikkat edilmesi gerekilen nokta
             * basketItems[] arrayını düzgün oluşturarak ürün1,ürün2,ürün3 vb. set yapmanız
             */
            $basketItems = array();
            $firstBasketItem = new \Iyzipay\Model\BasketItem(); // Ürün listesi için gerekli tetiklemeler
            $firstBasketItem->setId($products->product_code); // Benzersiz oluşturulan ürün kodu
            $firstBasketItem->setName($products->product_name); // Ürün adı
            $firstBasketItem->setCategory1($products->product_name); // Ürün Kategorisi
            $firstBasketItem->setCategory2($products->product_name); // Ürün Kategorisi 2
            $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
            $firstBasketItem->setPrice($products->product_amount); // Ürün fiyatı (indirim vs işlemlerde güncel olarak)
            $basketItems[0] = $firstBasketItem;
            $iyzico->setBasketItems($basketItems);

            $option = new \Iyzipay\Options(); // Api bilgleri için gerekli ayarları tetikletme Api Key, Api Secret.
            $option->setApiKey("sandbox-dNkkrXV6vpHQ2rb1pCOoH3Li5W8s7gMh"); // Api Key
            $option->setSecretKey("sandbox-RyAiG87yEKi0xm6r5OgpfbsPIRYIpnp5"); // Api Secret Key
            $option->setBaseUrl("https://sandbox-api.iyzipay.com"); // Apinin istek atacağı URL değiştirmeyin

            $checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($iyzico, $option); // gerekli ürün bilgileri ve ayarlar ile api tetikleme.

            return $checkoutFormInitialize; // Api tarafından yapılan isteğin dönüş bilgileri
        }
    }

    public function paddle()
    {
        require_once(APPPATH . 'third_party/Paddle/autoload.php');
        $api = new \Paddle\API();
        $vendorID = '132278';
        $vendorAuthCode = '190dc3c66215ce13310d0e28eab969e33098ef818c220009c0';
        $api->init($vendorID, $vendorAuthCode);
        $product = $api->product();
//        redirect($payLink['url']);
//        echo '<pre>';
//        print_r($payLink);
//        exit();
        $uu = $product->listProducts();
        echo '<pre>';
        print_r($uu);
        exit();

    }

    public function paddleC()
    {

        require_once(APPPATH . 'third_party/paddle_client/autoload.php');
        $client = new \Paddle\Client();
        $client->setVendorId('132278');
        $client->setVendorAuthCode('190dc3c66215ce13310d0e28eab969e33098ef818c220009c0');
        $amount = '100.10';
        $price = new \Paddle\Price($amount, new \Paddle\Currency('USD'));
        $invoice = new \Paddle\Invoice();

        $invoice->addPrice($price)
            ->setPassthrough('order-id-2464')
            ->setReturnUrl('https://yourdomain.com/return')
            ->setQuantity(1, FALSE)
            ->setExpires(time())
            ->setTitle('Test invoice')
            ->setCustomerEmail('user@mail.com');


        $invoice->setProductId(659066);
        $url = $client->createPaymentUrl($invoice);

        $data['url'] = $url;
        $data['subview'] = $this->load->view('admin/account/paddle', $data, true);
        $this->load->view('admin/_layout_main', $data);
//
//        echo $url;
//        exit();


    }

    public function formatInvoiceForQuickBooks($invoices)
    {
        $data = array();
        foreach ($invoices as $invoice) {
            $allInvoiceItems = $this->invoice_model->get_invoice_items($invoice->invoices_id);
            $invoiceItems = array();
            foreach ($allInvoiceItems as $item) {
                $invoiceItems[] = array(
                    'Description' => $item->item_name,
                    'Amount' => $item->unit_cost,
                    'DetailType' => 'SalesItemLineDetail',
                    'SalesItemLineDetail' => array(
                        'ItemRef' => array(
                            'value' => $item->item_id,
                            'name' => $item->item_name
                        ),
                        'UnitPrice' => $item->unit_cost,
                        'Qty' => $item->quantity,
                        'TaxCodeRef' => array(
                            'value' => 'NON'
                        )
                    )
                );
            }

            $data[] = array(
                'DocNumber' => $invoice->reference_no,
                'TxnDate' => $invoice->due_date,
                'Line' => $invoiceItems,
                'CustomerRef' => array(
                    'value' => $invoice->client_id,
                    'name' => $invoice->name
                ),
                'BillAddr' => array(
                    'Line1' => $invoice->address,
                    'City' => $invoice->city,
                    'CountrySubDivisionCode' => $invoice->state,
                    'PostalCode' => $invoice->zipcode,
                    'Country' => $invoice->country
                ),
                'ShipAddr' => array(
                    'Line1' => $invoice->address,
                    'City' => $invoice->city,
                    'CountrySubDivisionCode' => $invoice->state,
                    'PostalCode' => $invoice->zipcode,
                    'Country' => $invoice->country
                ),
                'SalesTermRef' => array(
                    'value' => '1'
                ),
                'DueDate' => $invoice->due_date,
                'TotalAmt' => $invoice->amount,
                'ApplyTaxAfterDiscount' => false,
                'PrintStatus' => 'NotSet',
                'EmailStatus' => 'NotSet',
                'BillEmail' => array(
                    'Address' => $invoice->email
                ),
            );
        }

        // send data to QuickBooks
        $theResourceObj = QInvoice::create($data);
        $resultObj = $dataService->Add($theResourceObj);
        $error = "Trying to construct IPPReferenceType based on Array. The array should contain at most two fields. name and value";
        // how to solve this error ??
        if ($resultObj) {
            $error = null;
        }
        $data['error'] = $error;
        $data['result'] = $resultObj;


        return $data;


    }
    public function CreateExpenseForQuickBooks($expenses){
        foreach ($expenses as $expense) {




        }
    }

}
