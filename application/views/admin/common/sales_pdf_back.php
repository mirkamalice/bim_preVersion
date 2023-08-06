<html>

<head>
    
    <style>

        /*
        
        PDF library using PHP have some limitations and all CSS properties may not support. Before Editing this file, Please create a backup, so that You can restore this.
        
        The location of this file is here- system/lib/invoices/pdf-x2.php
        
        */

        * {
            margin: 0;
            padding: 0;
        }

        body {
            /*

            Important: Do not Edit Font Name, Unless you are sure. It's required for PDF Rendering Properly

            */


            font: 14px/1.4 dejavusanscondensed;
            width: 100%;

            /*

            Font Name End

            */
        }


        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        #page-wrap {
            width: 95%;
            margin: 0 auto;
        }

        table {
            border-collapse: collapse;
        }

        table td, table th {
            border: 1px solid #c4cacf;
            /*border: 1px solid #e8ecef;*/
            padding: 5px;
        }


        #customer {

            clear: both;
            width: 100%;

            /*width: 100%;*/
            /*overflow: hidden;*/
        }

        .top_header {
            border-bottom: 1px solid #dde6e9;
            margin-bottom: 15px;
        }

        #logo {
            /*text-align: right;*/
            /*float: right;*/
            /*position: relative;*/
            margin-top: 15px;
            margin-bottom: 10px;
            /*border: 1px solid #fff;*/
            /*max-width: 540px;*/
            /*overflow: hidden;*/
        }

        #meta {
            clear: both;
            margin-top: 1px;
            width: 100%;
            float: right;
        }

        #meta td {
            text-align: right;
        }

        #meta td.meta-head {
            text-align: left;
            background: #eee;
        }

        #meta td textarea {
            width: 100%;
            height: 20px;
            text-align: right;
        }

        #items {
            clear: both;
            width: 100%;
            margin: 30px 0 0 0;
            /*border: 1px solid #dde6e9;*/
        }

        #items th {
            background: #eee;
        }

        #items textarea {
            width: 80px;
            height: 50px;
        }

        #items tr.item-row td {
            vertical-align: top;
        }

        #items td.description {
            width: 300px;
        }

        #items td.item-name {
            width: 175px;
        }

        #items td.description textarea, #items td.item-name textarea {
            width: 100%;
        }

        #items td.total-line {
            border-right: 0;
            text-align: right;
        }

        #items td.total-value {
            border-left: 0;
            padding: 10px;
            text-align: right;
            border-left: 1px solid #c4cacf;
        }

        #items td.total-value textarea {
            height: 20px;
            background: none;
        }

        #items td.balance {
            background: #eee;
        }

        #items td.blank {
            border: 0;
        }

        #terms {
            text-align: left;
            margin: 20px 0 0 0;
        }

        #terms h5 {
            text-transform: uppercase;
            font: 13px dejavusanscondensed;
            letter-spacing: 10px;
            border-bottom: 1px solid #dde6e9;
            padding: 0 0 8px 0;
            margin: 0 0 15px 0 !important;
        }

        #terms textarea {
            width: 100%;
            text-align: center;
        }

        #items td.blank {
            border: 0;
        }

        .price {
            margin-right: 5px;
        }
    
    </style>

</head>

<body style="font-family:dejavusanscondensed">

<div id="page-wrap">
    <?php
    $d = array();
    $d['id'] = 1;
    $d['userid'] = 1;
    $d['type'] = 'Invoice';
    $d['related_to'] = '';
    $d['relation_id'] = 0;
    $d['account'] = 'Ehinmisan Olawale';
    $d['title'] = '';
    $d['cn'] = '00035';
    $d['invoicenum'] = 'INV-';
    $d['date'] = '2022-03-05';
    $d['duedate'] = '2022-03-08';
    $d['datepaid'] = '2022-10-05 08:40:23';
    $d['subtotal'] = '400.00';
    $d['discount_type'] = 'f';
    $d['discount_value'] = '0.00';
    $d['discount'] = '0.00';
    $d['credit'] = '0.00';
    $d['taxname'] = '';
    $d['tax'] = '0.00';
    $d['tax2'] = '0.00';
    $d['tax_total'] = '0.0000';
    $d['total'] = '400.00';
    $d['taxrate'] = '0.00';
    $d['taxrate2'] = '0.00';
    $d['status'] = 'Paid';
    $d['paymentmethod'] = '';
    $d['notes'] = '1. For lifetime support after the final product, the Codexcube shall provide the Client attention to answer any questions or assist solving any problems with regard to the to the software free of charge and billed to the Client at a rate discussed and agreed later for any assistance thereafter. The Codexcube agrees to respond to any reasonable request for assistance made by the Client regarding the software within time frame of the request.';
    //    $d['notes'] .= '2. The Codexcube shall provide to the software after the Delivery Date, a cumulative one week of training with respect to the operation of the software platform if requested by the Client.'
    $d['vtoken'] = '1234567890';
    $d['ptoken'] = '6945460774';
    $d['r'] = '1';
    $d['nd'] = '2022-03-05';
    $d['eid'] = 0;
    $d['ename'] = 'John Doe';
    $d['receipt_number'] = '00035';
    
    
    $a = array();
    $a['id'] = 1;
    $a['account'] = 'Ehinmisan Olawale';
    $a['fname'] = '';
    $a['lname'] = '';
    $a['company'] = '';
    $a['business_number'] = '';
    $a['jobtitle'] = '';
    $a['cid'] = 0;
    $a['o'] = 1;
    $a['phone'] = '+2349017292738';
    $a['fax'] = '';
    $a['email'] = '';
    $a['is_primary_contact'] = 1;
    $a['username'] = '';
    $a['address'] = 'Suite 101, GCL Plaza, Aminu Kano Crescent, Wuse 2';
    $a['city'] = 'Abuja';
    $a['state'] = '';
    $a['zip'] = '';
    $a['country'] = 'Nigeria';
    $a['balance'] = '0.00';
    $a['status'] = 'Active';
    $a['notes'] = '';
    $a['options'] = '';
    $a['tags'] = '';
    $a['password'] = '$2y$10$vQ.U4Jscx13C1m8orNHpnu6SEqT9KV3Veh0.t2C.aG9px/AURt6wa';
    $a['token'] = '';
    $a['ts'] = '';
    $a['img'] = '';
    $a['web'] = '';
    $a['facebook'] = '';
    $a['google'] = '';
    $a['linkedin'] = '';
    $a['twitter'] = '';
    $a['skype'] = '';
    $a['tax_number'] = '';
    $a['entity_number'] = '';
    $a['currency'] = 1;
    $a['pmethod'] = '';
    $a['autologin'] = '';
    $a['lastlogin'] = '';
    $a['lastloginip'] = '';
    $a['stage'] = '';
    $a['timezone'] = '';
    
    $a['isp'] = '';
    $a['lat'] = '';
    $a['lon'] = '';
    $a['gname'] = '';
    $a['gid'] = 0;
    $a['sid'] = '';
    $a['role'] = '';
    $a['country_code'] = '';
    $a['country_idd'] = '';
    $a['signed_up_by'] = '';
    $a['signed_up_ip'] = '';
    $a['dob'] = '';
    $a['ct'] = '';
    $a['assistant'] = '';
    $a['asst_phone'] = '';
    $a['second_email'] = '';
    $a['second_phone'] = '';
    $a['taxexempt'] = '';
    $a['latefeeoveride'] = '';
    $a['overideduenotices'] = '';
    $a['separateinvoices'] = '';
    $a['disableautocc'] = '';
    $a['billingcid'] = 0;
    $a['securityqid'] = 0;
    $a['securityqans'] = '';
    $a['cardtype'] = '';
    $a['cardlastfour'] = '';
    $a['cardnum'] = '';
    $a['startdate'] = '';
    $a['expdate'] = '';
    $a['issuenumber'] = '';
    $a['bankname'] = '';
    $a['banktype'] = '';
    $a['bankcode'] = '';
    $a['bankacct'] = '';
    $a['gatewayid'] = 0;
    $a['language'] = '';
    $a['pwresetkey'] = '';
    $a['emailoptout'] = '';
    $a['created_at'] = '2022-10-05 08:26:24';
    
    $a['updated_at'] = '2022-10-05 08:26:28';
    $a['pwresetexpiry'] = '';
    $a['is_email_verified'] = 0;
    $a['is_phone_veirifed'] = 0;
    $a['photo_id_type'] = '';
    $a['photo_id'] = '';
    $a['type'] = 'Customer,Supplier';
    $a['drive'] = '1664972784i0tl1ggsl866';
    $a['workspace_id'] = 0;
    $a['parent_id'] = 0;
    $a['code'] = 'CUS-000299';
    $a['display_name'] = '';
    $a['secondary_email'] = '';
    $a['secondary_phone'] = '';
    ?>
    <table class="clearfix top_header">
        <tr>
            <td style="border: 0;  text-align: left;width: 62%;">
                <span style="font-size: 18px; color: #2f4f4f"><strong><?php echo 'INVOICE'; ?> # <?php
                        if ($d['cn'] != '') {
                            $dispid = $d['cn'];
                        } else {
                            $dispid = $d['id'];
                        }
                        echo $d['invoicenum'] . $dispid;
                        ?></strong></span>
            </td>
            <td style="border: 0;  text-align: right" width="62%">
                <div id="logo" style="font-size:18px">
                    <img id="image" src="<?php echo base_url(); ?>/storage/system/logo_6826576337.jpeg"
                         alt="logo"/> <br> <br>
                    <?php echo 'UniqueCoder'; ?> <br>
                    <?php echo '10 Block KA,PC Culture Housing Society
 Rizia Villa,Ring Road Shyamoli
Dhaka-1207
 Bangladesh' ?>
                </div>
            </td>
        </tr>
    
    
    </table>
    <div class="clearfix" style="clear:both"></div>
    
    <div class="clearfix" id="customer">
        
        <table class="clearfix" id="meta">
            <tr>
                <td rowspan="5" style="border: 1px solid white; border-right: 1px solid #dde6e9; text-align: left"
                    width="62%">
                    
                    <?php if (isset($d['title']) && $d['title'] != '') {
                        echo '<h4>' .
                            $d['title'] .
                            '</h4>
                    <br>';
                    } ?>
                    
                    <?php
                    echo '<h4>' .
                        'Receipt Number' .
                        ': ' .
                        $d['receipt_number'] .
                        '</h4>
                    <br>';
                    ?>
                    
                    <strong><?php echo 'Invoiced To'; ?></strong> <br>
                    
                    <?php if ($a['company'] != '') { ?>
                        <?php echo $a['company']; ?> <br>
                        
                        <?php
                        echo
                            'Business Number' .
                            ': ' .
                            'dddd' .
                            ' <br>';
                        
                        ?>
                        <?php echo ['ATTN']; ?>: <?php echo $a['account']; ?> <br>
                    <?php } else { ?>
                        <?php echo $d['account']; ?> <br>
                    <?php } ?>
                    
                    <?php
                    if ($a['phone'] != '') {
                        echo 'Phone' . ': ' . $a['phone'] . ' <br>';
                    }
                    echo 'Fax' . ': ' . $a['fax'] . ' <br>';
                    
                    
                    if ($a['email'] != '') {
                        echo 'Email: ' . $a['email'] . ' <br>';
                    }
                    ?></td>
                <td class="meta-head"><?php echo 'INVOICE'; ?> #</td>
                <td><?php echo $d['invoicenum'] . $dispid; ?></td>
            </tr>
            <tr>
                
                <td class="meta-head"><?php echo 'Status'; ?></td>
                <td><?php echo($d['status']); ?></td>
            </tr>
            <tr>
                
                <td class="meta-head"><?php echo 'Invoice Date'; ?></td>
                <td><?php echo $d['date']; ?></td>
            </tr>
            <tr>
                
                <td class="meta-head"><?php echo 'Due Date'; ?></td>
                <td><?php echo $d['duedate']; ?></td>
            </tr>
            
            <?php if ($d['credit'] != '0.00') { ?>
                
                <tr>
                    
                    <td class="meta-head"><?php echo 'Amount Due'; ?></td>
                    <td>
                        <div class="due"><?php echo '$200'; ?></div>
                    </td>
                </tr>
            
            <?php } else { ?>
                <tr>
                    
                    <td class="meta-head"><?php echo 'Amount Due'; ?></td>
                    <td>
                        <div class="due"><?php echo '$200'; ?></div>
                    </td>
                </tr>
            <?php } ?>
        
        </table>
    
    </div>
    
    
    <table id="items" class="clearfix">
        
        <tr>
            <th align="right"><?php echo 'Item'; ?></th>
            <?php
            $col_span = 2;
            ?>
            <th align="right"><?php echo 'Price'; ?></th>
            <th align="right"><?php echo 'Qty' ?></th>
            <th align="right"><?php echo 'Total'; ?></th>
        </tr>
        
        
        <?php
        $cols = '';
        
        $item_total = 200;
        
        echo '<tr class="item-row">
            <td class="description">' .
            'Sasd asdhk adlsj advance_salary_reject_email' .
            '</td>
            <td align="right">' .
            '$5354' .
            '</td>
            <td align="right">' .
            '$5354' .
            '</td>
            <td align="right"><span class="price">' .
            '$5354' .
            '</span></td>
        </tr>';
        echo '<tr class="item-row">
            <td class="description">' .
            'Sasd asdhk adlsj advance_salary_reject_email' .
            '</td>
            <td align="right">' .
            '$5354' .
            '</td>
            <td align="right">' .
            '$5354' .
            '</td>
            <td align="right"><span class="price">' .
            '$5354' .
            '</span></td>
        </tr>' ?>
        <tr>
            <td class="blank"></td>
            <td colspan="<?php echo $col_span; ?>" class="total-line"><?php echo 'Sub Total'; ?></td>
            <td class="total-value" align="right">
                <div id="subtotal"><?php echo '$5354'; ?></div>
            </td>
        </tr>
        
        <tr>
            <td class="blank"></td>
            <td colspan="<?php echo $col_span; ?>" class="total-line"><?php echo 'Discount'; ?></td>
            <td class="total-value">
                <div id="subtotal"><?php echo '$5354' ?></div>
            </td>
        </tr>
        
        <tr>
            <td class="blank"></td>
            <td colspan="<?php echo $col_span; ?>" class="total-line"><?php echo 'Tax'; ?></td>
            <td class="total-value">
                <div id="total"><?= '$5354' ?></div>
            </td>
        </tr>
        
        
        <?php if ($d['credit'] != '0.00') { ?>
            <tr>
                <td class="blank"></td>
                <td colspan="<?php echo $col_span; ?>" class="total-line"><?php echo 'Invoice Total'; ?></td>
                <td class="total-value">
                    <div class="due"><?php echo '$5354' ?></div>
                </td>
            </tr>
            <tr>
                <td class="blank"></td>
                <td colspan="<?php echo $col_span; ?>" class="total-line"><?php echo 'Total Paid'; ?></td>
                <td class="total-value">
                    <div class="due"><?php echo '$5354'; ?></div>
                </td>
            </tr>
            <tr>
                <td class="blank"></td>
                <td colspan="<?php echo $col_span; ?>" class="total-line balance"><?php echo 'Amount Due'; ?></td>
                <td class="total-value balance">
                    <div class="due"><?php echo '$5354'; ?></div>
                </td>
            </tr>
        <?php } else { ?>
            <tr>
                <td class="blank"></td>
                <td colspan="<?php echo $col_span; ?>" class="total-line balance"><?php echo 'Grand Total'; ?></td>
                <td class="total-value balance">
                    <div class="due"><?php echo '$5354'; ?></div>
                </td>
            </tr>
        <?php } ?>
    
    </table>
    
    <!--    related transactions -->
    <br>
    <br>
    <h4><?php echo 'Related Transactions'; ?>: </h4>
    
    <table id="related_transactions" style="width: 100%;margin-top: 10px">
        
        <tr>
            <th align="left" width="20%"><?php echo 'Date'; ?></th>
            <th align="left"><?php echo 'Account'; ?></th>
            <th width="50%" align="left"><?php echo 'Description'; ?></th>
            <th align="right"><?php echo 'Amount'; ?></th>
        </tr>
        
        <?php
        echo '  <tr class="item-row">
            <td align="left">' .
            '2025545' .
            '</td>
            <td align="left">' .
            'THe account
                ' .
            '</td>
            <td align="left">' .
            'this is designation' .
            '</td>
            <td align="right"><span class="price">' .
            '$5354' .
            '</span></td>
        </tr>';
        ?>
    </table>
    
    <!--    end related transactions -->
    
    
    <div id="terms">
        <h5><?php echo 'Terms'; ?></h5>
        <?php echo $d['notes']; ?>
    </div>


</div>

</body>

</html>
