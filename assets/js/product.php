<script src="<?= base_url() ?>assets/plugins/jquery-ui/jquery-u.min.js"></script>
<script type="text/javascript">
    var qty_alert = '<?= config_item('item_total_qty_alert') ?>';
    let forID = $('.prodcutTable').attr('data-terget');
    let pStore = forID + 'Items'
    pStore = {};
    let intStore = forID + 'Items'
    let productTable = forID + 'Table'
    let Warehouses = forID + 'Warehouse' // wharehouse 

    let forItem = forID + '_item'
    var warehouse_id;

    $('.warehouse').change(function() {
        var to_warehouse_id = $("[name=to_warehouse_id]").val();
        var from_warehouse_id = $("[name=from_warehouse_id]").val();
        if (from_warehouse_id == to_warehouse_id) {
            $(this).val('');
            alert('<?= lang('please_select_different_warehouse') ?>');
        }
    });


    function getItemByWarehouse(warehouseId) {
        warehouse_id = warehouseId;
        store(Warehouses, warehouse_id);
        $('.WarehouseValue').val(warehouse_id);
        $.ajax({
            type: "get",
            url: "<?= base_url() ?>admin/common/getItemByWarehouse/" + warehouseId,
            dataType: "json",
            success: function(data) {
                $('#product_result').html(data);
            }
        });
    }


    $(document).ready(function() {
        setWarehouse();
        load_data();

        function load_data(query) {
            $.ajax({
                url: "<?php echo base_url(); ?>admin/common/items_suggestions",
                method: "POST",
                data: {
                    term: query,
                    warehouse_id: warehouse_id,
                },
                success: function(data) {
                    $('#product_result').html(data);
                }
            })
        }

        $('#search_text').keyup(function() {
            var search = $(this).val();
            if (search != '' && warehouse_id != '') {
                load_data(search, warehouse_id);
            } else {
                load_data();
            }
        });

        $("#" + forItem).autocomplete({
            source: function(request, response) {
                if (warehouse_id == undefined || warehouse_id == 0 || warehouse_id == '') {
                    $('html,body').animate({
                            scrollTop: 80
                        },
                        'slow');
                    $('label[for="warehouse"]').addClass('text-danger');
                    setTimeout(function() {
                        $('label[for="warehouse"]').removeClass('text-danger');
                    }, 4000);
                    alert('<?= lang('please_select_warehouse') ?>');
                    $("#" + forItem).val('');
                    return false;
                }
                $.ajax({
                    url: '<?= base_url('admin/common/suggestions') ?>',
                    dataType: "json",
                    data: {
                        term: request.term,
                        for: forItem,
                        warehouse_id: warehouse_id
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 1,
            autoFocus: false,
            delay: 200,
            response: function(event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].saved_items_id == 0) {
                    $(this).val('');
                } else if (ui.content.length == 1 && ui.content[0].saved_items_id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                    $(this).val('');
                } else if (ui.content.length == 1 && ui.content[0].saved_items_id == 0) {
                    $(this).val('');
                }
            },
            select: function(event, ui) {
                event.preventDefault();
                if (ui.item.saved_items_id !== 0) {
                    var row = add_order_item(ui.item.row);
                    if (row) {
                        $(this).val('');
                    }
                } else {
                    alert('<?php echo lang('no_result_found'); ?>');
                    $(this).val('');
                }
            }
        });
        $("#" + forItem).bind('keypress', function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).autocomplete("search");
            }
        });

        loadItems();
        calculate_total();

        $(document).on("change", '.Rqty', function() {
            var row = $(this).closest('tr');
            var new_qty = parseFloat($(this).val()),
                item_id = row.attr('data-key');
            var old_qty = pStore[item_id].qty;

            var error = false;

            pStore[item_id].qty = new_qty;
            if (forID != 'purchase' && pStore[item_id].total_qty) {
                // round total_qty to 2 decimal places
                var total_qty = parseFloat(pStore[item_id].total_qty).toFixed(2);

                if (qty_alert == 'Yes' && new_qty > total_qty) {
                    error = true;
                    $('#' + productTable).find('.item').each(function() {
                        if ($(this).attr('data-key') === item_id) {
                            $(this).addClass('danger');
                            // change input value
                            $(this).find('.Rqty').val(old_qty);
                            // add error message if not already added

                            if (!$(this).find('.Rqty').next().hasClass('text-danger')) {
                                $(this).find('.Rqty').after('<span class="text-danger text-sm">' + '<?= lang('only') ?>' + ' ' + total_qty + ' ' + '<?= lang('left') ?>' + ' ' + '</span>');
                            }
                        }
                    });
                    pStore[item_id].qty = old_qty;
                }
            }
            if (error == false) {
                store(intStore, JSON.stringify(pStore));
                loadItems();
            }

        });

        $(document).on("change", '.Rrate', function() {
            var row = $(this).closest('tr');
            var new_cost = $(this).val(),
                item_id = row.attr('data-key');
            pStore[item_id].rate = new_cost;
            store(intStore, JSON.stringify(pStore));
            loadItems();
        });
        $(document).on("change", '.RitemDesc', function() {
            var row = $(this).closest('tr');
            var item_desc = $(this).val(),
                item_id = row.attr('data-key');
            pStore[item_id].item_desc = item_desc;
            store(intStore, JSON.stringify(pStore));
            loadItems();
        });
        $(document).on("change", '.RitemName', function() {
            var row = $(this).closest('tr');
            var item_name = $(this).val(),
                item_id = row.attr('data-key');
            pStore[item_id].item_name = item_name;
            store(intStore, JSON.stringify(pStore));
            loadItems();
        });
        $(document).on("change", '.Rtax', function() {
            var taxname = $(this).find('select.Rtax').selectpicker('val');
            var row = $(this).closest('tr');
            var item_id = row.attr('data-key');
            pStore[item_id].taxname = taxname;
            store(intStore, JSON.stringify(pStore));
            loadItems();
        });
        $('#Preset').click(function(e) {
            if (confirm("<?= lang('r_u_sure') ?>")) {
                if (get(intStore)) {
                    remove(intStore);
                }
                window.location.reload();
            } else {
                return false;
            }
        });
    });

    $("body").on('click', '.itemManualy', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        var data = form.serialize();
        $.ajax({
            type: 'post',
            url: '<?= base_url() ?>admin/common/itemAddedManualy',
            data: data,
            dataType: "json",
            success: function(data) {
                if (data !== null) {
                    var row = add_order_item(data.row, true);
                    if (row) {
                        form[0].reset();
                    }
                } else {
                    alert('<?php echo lang('no_result_found'); ?>');
                }
                $('#myModal_lg').modal('hide');
            }
        });
    });

    $(document).on('click', '.select_pos_item', function(e) {
        var saved_items_id = $(this).data('item-id');
        $.ajax({
            type: "get",
            url: "<?= base_url() ?>admin/common/suggestions/" + saved_items_id,
            dataType: "json",
            success: function(data) {
                if (data !== null) {
                    add_order_item(data.row);
                } else {
                    alert('<?php echo lang('no_result_found'); ?>');
                }
            }
        });
    });

    function get(name) {
        if (typeof(Storage) !== "undefined") {
            return localStorage.getItem(name);
        } else {
            alert('Please use a modern browser as this site needs localstroage!');
        }
    }

    function store(name, val) {
        if (typeof(Storage) !== "undefined") {
            localStorage.setItem(name, val);
        } else {
            alert('Please use a modern browser as this site needs localstroage!');
        }
    }

    function remove(name) {
        if (typeof(Storage) !== "undefined") {
            localStorage.removeItem(name);
        } else {
            alert('Please use a modern browser as this site needs localstroage!');
        }
    }

    function add_order_item(data, manual = false) {
        var item_id = data.saved_items_id;

        if (manual) {
            if (get(intStore)) {
                pStore = JSON.parse(get(intStore));
                item_id = pStore.length;
            } else {
                // make a new array if no data
                pStore = [];
                item_id = 1;
            }
        }
        if (pStore[item_id]) {
            pStore[item_id].qty = parseFloat(pStore[item_id].qty) + 1;
        } else {
            pStore[item_id] = data;
        }
        if (forID != 'purchase' && pStore[item_id].total_qty) {
            if (qty_alert == 'Yes' && pStore[item_id].qty > pStore[item_id].total_qty) {
                // show message into child id in parent id in header
                $('#' + productTable).find('.item').each(function() {
                    if ($(this).attr('data-key') === item_id) {
                        $(this).addClass('danger');
                        if (!$(this).find('.Rqty').next().hasClass('text-danger')) {
                            $(this).find('.Rqty').after('<span class="text-danger text-sm">' + '<?= lang('only') ?>' + ' ' + total_qty + ' ' + '<?= lang('left') ?>' + ' ' + '</span>');
                        }
                    }
                });
                // remove item from localStorage
                pStore[item_id].qty = parseFloat(pStore[item_id].qty) - 1;
                return false;
            }
        }

        store(intStore, JSON.stringify(pStore));
        loadItems();
        return true;
    }

    function setWarehouse() {
        var getWarehouse = $('.mwarehouse');
        if (get(Warehouses)) {
            warehouse_id = get(Warehouses);
        } else {
            warehouse_id = getWarehouse.val();
        }
        getWarehouse.val(warehouse_id);
        $('.WarehouseValue').val(warehouse_id);
        // alert(warehouse_id);
        if (warehouse_id != '' && warehouse_id != 0 && get(intStore)) {
            getWarehouse.attr('disabled', true);
        }
    }

    function validate_qty(item_id) {
        if (pStore[item_id].total_qty) {
            if (qty_alert == 'Yes' && pStore[item_id].qty > pStore[item_id].total_qty) {                
                // show message into child id in parent id in header
                $('#' + productTable).find('.item').each(function() {
                    if ($(this).attr('data-key') === item_id) {
                        $(this).addClass('danger');
                    }
                });
                // remove item from localStorage
                pStore[item_id].qty = parseFloat(pStore[item_id].qty) - 1;
                return false;
            }
        }
    }


    function loadItems() {
        var merge_invoice = null;
        if (get(intStore)) {
            setWarehouse();
            $("#" + productTable).empty();
            pStore = JSON.parse(get(intStore));
            $.each(pStore, function(key, data) {
                if (data) {
                    var item_id = data.saved_items_id;
                    var product_id = data.items_id;
                    item_cost = data.rate,
                        item_qty = data.qty,
                        item_code = data.code,
                        total_qty = data.total_qty,
                        item_name = data.item_name;
                    var table_row = '';
                    var unit_placeholder = '';

                    var item_key = $('body').find('tbody .item').length + 1;
                    table_row += '<tr class="sortable item" data-key="' + key + '" data-item-id="' + product_id + '" data-merge-invoice="' +
                        merge_invoice + '">';
                    table_row += '<td class="dragger">';

                    // Check if quantity is number
                    if (isNaN(data.qty)) {
                        data.qty = 1;
                    }
                    // Check if rate is number
                    if (data.rate == '' || isNaN(data.rate)) {
                        data.rate = 0;
                    }
                    var amount = data.rate * data.qty;
                    var tax_name = 'items[' + item_key + '][taxname][]';
                    $('body').append('<div class="dt-loader"></div>');
                    var regex = /<br[^>]*>/gi;
                    get_taxes_dropdown_template(tax_name, data.taxname).done(function(tax_dropdown) {
                        // order input
                        table_row += '<input type="hidden" class="order" name="items[' + item_key +
                            '][order]"><input type="hidden" name="items[' + item_key +
                            '][saved_items_id]" value="' + data.new_itmes_id +
                            '"><input type="hidden" name="new_itmes_id[]" value="' + data.new_itmes_id +
                            '">';
                        if (data.items_id) {
                            table_row += '<input type="hidden" name="items[' + item_key +
                                '][items_id]" value="' + data.items_id + '">';
                        }
                        table_row += '</td>';
                        table_row += '<td class="bold item_name"><textarea  name="items[' + item_key +
                            '][item_name]" class="form-control RitemName">' + data.item_name +
                            '</textarea></td>';
                        table_row += '<td><textarea  name="items[' + item_key +
                            '][item_desc]" class="form-control item_item_desc RitemDesc" >' + ((data
                                .item_desc) ? data.item_desc.replace(regex, "\n") : '') +
                            '</textarea></td>';
                        <?php $invoice_view = config_item('invoice_view');
                        if (!empty($invoice_view) && $invoice_view == '2') { ?>
                            table_row += '<td><input type="text" name="items[' + item_key +
                                '][hsn_code]" class="form-control" value="' + data.hsn_code + '"></td>';
                        <?php } ?>
                        table_row +=
                            '<td class="qtytd"><input type="text" data-parsley-type="number" min="0" onblur="calculate_total();" onchange="calculate_total();" data-quantity id="qty_' +
                            item_key + '" name="items[' + item_key + '][quantity]" value="' + data.qty +
                            '" class="form-control Rqty">';

                        unit_placeholder = '';
                        if (!data.unit || typeof(data.unit) == 'undefined') {
                            data.unit = '';
                        }

                        table_row += '<input type="text" placeholder="' + unit_placeholder +
                            '" name="items[' + item_key +
                            '][unit]" class="form-control input-transparent" value="' + data.unit + '">';

                        table_row += '</td>';
                        table_row +=
                            '<td class="rate"><input type="text" min=0 data-parsley-type="number"  onblur="calculate_total();" onchange="calculate_total();" name="items[' +
                            item_key + '][unit_cost]" value="' + data.rate +
                            '" class="form-control Rrate"></td>';
                        table_row += '<td class="taxrate">' + tax_dropdown + '</td>';
                        table_row += '<td class="amount">' + amount + '</td>';
                        table_row +=
                            '<td><a href="#" class="btn-xs btn btn-danger pull-left" onclick="delete_item(this,' +
                            key + '); return false;"><i class="fa fa-trash"></i></a></td>';
                        table_row += '</tr>';

                        $('table.items tbody').append(table_row);

                        setTimeout(function() {
                            calculate_total();
                        }, 10);

                        init_selectpicker();
                        clear_main_values();
                        reorder_items();

                        $('body').find('.dt-loader').remove();
                        $('#item_select').selectpicker('val', '');
                        return true;
                    });
                }
            });
        }
        return true;
    }

    // Invoices to merge
    $('body').on('change', 'input[name="invoices_to_merge[]"]', function() {
        var checked = $(this).prop('checked');
        var _id = $(this).val();
        if ($('.row').hasClass('estimate-template')) {
            var mtype = 'estimate';
        } else {
            var mtype = null;
        }
        if (mtype == 'estimate') {
            var post = 'estimates/';
        } else {
            var post = 'invoice/';
        }
        if (checked == true) {
            $.get('<?= base_url() ?>admin/' + post + 'get_merge_data/' + _id, function(response) {
                $.each(response, function(i, obj) {
                    add_item_to_table(obj, 'undefined', _id);
                });
            }, 'json');
        } else {
            // Remove the appended invoice to merge
            $('body').find('[data-merge-invoice="' + _id + '"]').remove();
        }
    });

    $('body').on('change', '.f_client_id select[name="client_id"]', function() {
        var val = $(this).val();
        if (val == '' || val == '-') {
            $('#merge').empty();
            return false;
        }
        var current_invoice = $('body').find('input[name="merge_current_invoice"]').val();
        if ($('.row').hasClass('estimate-template')) {
            var mtype = 'estimate';
        } else {
            var mtype = null;
        }
        if (mtype == 'estimate') {
            var post = 'estimates/';
        } else {
            var post = 'invoice/';
        }
        $.get('<?= base_url() ?>admin/' + post + 'client_change_data/' + val + '/' + current_invoice, function(
            response) {
            $('#merge').html(response.merge_info);
            if (response.merge_info != '') {
                $('#invoice_top_info').removeClass('hide');
            } else {
                $('#invoice_top_info').addClass('hide');
            }
        }, 'json');
    });

    // Fix for reordering the items the tables to show the full width
    function fixHelperTableHelperSortable(e, ui) {
        ui.children().each(function() {
            $(this).width($(this).width());
        });
        return ui;
    }

    function init_items_sortable(preview_table) {
        var _items_sortable = $("body").find('.items tbody');
        if (_items_sortable.length == 0) {
            return;
        }
        _items_sortable.sortable({
            helper: fixHelperTableHelperSortable,
            handle: '.dragger',
            placeholder: 'ui-placeholder',
            itemPath: '> tbody',
            itemSelector: 'tr.sortable',
            items: "tr.sortable",
            update: function() {
                if (typeof(preview_table) == 'undefined') {
                    reorder_items();
                } else {
                    // If passed from the admin preview there is other function for re-ordering
                    save_ei_items_order();
                }
            },
            sort: function(event, ui) {
                // Firefox fixer when dragging
                var $target = $(event.target);
                if (!/html|body/i.test($target.offsetParent()[0].tagName)) {
                    var top = event.pageY - $target.offsetParent().offset().top - (ui.helper.outerHeight(true) /
                        2);
                    ui.helper.css({
                        'top': top + 'px'
                    });
                }
            }
        });
    }

    // Save the items from order from the admin preview
    function save_ei_items_order() {
        var rows = $(
            '.table.invoice-items-preview.items tbody tr,.table.estimate-items-preview.items tbody tr,.table.proposal-items-preview.items tbody tr,.table.todo-preview.items tbody tr'
        );
        var i = 1;
        var order = [];
        var _order_id, type;
        var item_id;
        if ($('.table.items').hasClass('invoice-items-preview')) {
            type = 'invoice';
        } else if ($('.table.items').hasClass('estimate-items-preview')) {
            type = 'estimate';
        } else if ($('.table.items').hasClass('proposal-items-preview')) {
            type = 'proposal';
        } else if ($('.table.items').hasClass('purchase-items-preview')) {
            type = 'purchase';
        } else if ($('.table.items').hasClass('todo-preview')) {
            type = 'todo';
        } else {
            return false;
        }
        $.each(rows, function() {
            order.push([$(this).data('item-id'), i]);
            // update item number when reordering
            $(this).find('td.item_no').html(i);
            i++;
        });
        setTimeout(function() {
            $.post('<?= base_url() ?>admin/common/update_ei_items_order/' + type, {
                items_id: order
            });
        }, 200);
    }

    // Reoder the items in table edit for estimate and invoices
    function reorder_items() {
        var rows = $('.table.invoice-items-table tbody tr.item,.table.table-main-estimate-edit tbody tr.item');
        var i = 1;
        $.each(rows, function() {
            $(this).find('input.order').val(i);
            i++;
        });
    }

    // Show quantity as change we need to change on the table QTY heading for better user experience
    $('body').on('change', 'input[name="show_quantity_as"]', function() {
        $('body').find('th.qty').html($(this).attr('id'));
    });

    // Add item to preview
    function add_item_to_preview(itemid) {
        $.get('<?= base_url() ?>admin/common/get_item_by_id/' + itemid, function(response) {

            $('.main textarea[name="item_name"]').val(response.item_name);
            $('.main textarea[name="item_desc"]').val(response.item_desc);
            <?php $invoice_view = config_item('invoice_view');
            if (!empty($invoice_view) && $invoice_view == '2') { ?>
                $('.main input[name="hsn_code"]').val(response.hsn_code);
            <?php } ?>
            $('.main input[name="new_itmes_id"]').val(itemid);
            $('.main input[name="quantity"]').val(1);

            var taxname = $.parseJSON(response.taxname);
            var taxrate = $.parseJSON(response.taxrate);
            if (taxname != null) {
                var tax = [];
                for (var i = 0; i < taxname.length; i++) {
                    tax.push(taxname[i] + '|' + taxrate[i]);
                }
            }
            $('.main select.tax').selectpicker('val', tax);
            $('.main input[name="unit"]').val(response.unit_type);
            $('.main input[name="unit_cost"]').val(response.unit_cost);
        }, 'json');
    }

    // Add item to preview from the dropdown for invoices estimates
    $('body').on('change', 'select[name="item_select"]', function() {
        var itemid = $(this).selectpicker('val');
        if (itemid != '' && itemid !== 'newitem') {
            add_item_to_preview(itemid);
        } else if (itemid == 'newitem') {
            // New item
            $('#item_modal').modal('show');
        }
    });


    // Recaulciate total on these changes
    $('body').on('change', 'input[name="adjustment"],select.tax', function() {
        calculate_total();
    });
    // Discount type for estimate/invoice
    $('body').on('change', 'select[name="discount_type"]', function() {
        // if discount_type == ''
        if ($(this).val() == '') {
            $('input[name="discount_percent"]').val(0);
        }
        // Recalculate the total
        calculate_total();
    });
    //retention_type
    $('body').on('change', 'select[name="retention_type"]', function() {
        // if discount_type == ''
        if ($(this).val() == '') {
            $('input[name="retention_percent"]').val(0);
        }
        // Recalculate the total
        calculate_total();
    });
    // In case user enter discount percent but there is no discount type set
    $('body').on('change', 'input[name="discount_percent"]', function() {
        if ($('select[name="discount_type"]').val() == '' && $(this).val() != 0) {
            alert('You need to select discount type');
            $('html,body').animate({
                    scrollTop: 50
                },
                'slow');
            $('label[for="discount_type"]').addClass('text-danger');
            setTimeout(function() {
                $('label[for="discount_type"]').removeClass('text-danger');
            }, 3000);
            return false;
        }
        if ($(this).valid() == true) {
            calculate_total();
        }
    });
    // In case user enter discount percent but there is no discount type set
    $('body').on('change', 'input[name="retention_percent"]', function() {
        if ($('select[name="retention_type"]').val() == '' && $(this).val() != 0) {
            alert('You need to select Retention Withheld');
            $('html,body').animate({
                    scrollTop: 100
                },
                'slow');
            $('label[for="retention_type"]').addClass('text-danger');
            setTimeout(function() {
                $('label[for="retention_type"]').removeClass('text-danger');
            }, 3000);
            return false;
        }
        if ($(this).valid() == true) {
            calculate_total();
        }
    });

    // Init bootstrap select picker
    function init_selectpicker() {
        $('body').find('select.selectpicker').not('.ajax-search').selectpicker({
            showSubtext: true,
        });
    }

    // Function to slug string
    function slugify(string) {
        return string
            .toString()
            .trim()
            .toLowerCase()
            .replace(/\s+/g, "-")
            .replace(/[^\w\-]+/g, "")
            .replace(/\-\-+/g, "-")
            .replace(/^-+/, "")
            .replace(/-+$/, "");
    }

    // Generate hidden input field
    function hidden_input(name, val) {
        return '<input type="hidden" name="' + name + '" value="' + val + '">';
    }

    // Calculate invoice total - NOT RECOMENDING EDIT THIS FUNCTION BECUASE IS VERY SENSITIVE
    function calculate_total() {

        var calculated_tax,
            taxrate,
            item_taxes,
            row,
            _amount,
            _tax_name,
            taxes = {},
            taxes_rows = [],
            subtotal = 0,
            total = 0,
            quantity = 1;
        total_discount_calculated = 0,
            total_retention_calculated = 0,
            rows = $('.table.invoice-items-table tbody tr.item,.table.table-main-estimate-edit tbody tr.item'),
            adjustment = $('input[name="adjustment"]').val(),
            discount_area = $('tr#discount_percent'),
            discount_percent = $('input[name="discount_percent"]').val();
        discount_type = $('select[name="discount_type"]').val();
        retention_area = $('tr#retention_percent');
        retention_percent = $('input[name="retention_percent"]').val();
        retention_type = $('select[name="retention_type"]').val();
        $('.tax-area').remove();
        var qty_alert = '<?= config_item('item_total_qty_alert') ?>';


        $.each(rows, function() {
            var total_qty = Math.round($(this).find('[data-total-qty]').val());
            var saved_items_id = Math.round($(this).find('[data-saved-items-id]').val());
            quantity = $(this).find('[data-quantity]').val();
            if (quantity == '') {
                quantity = 1;
                $(this).find('[data-quantity]').val(1);
            }
            _amount = parseFloat($(this).find('td.rate input').val()) * quantity;
            $(this).find('td.amount').html(_amount);
            subtotal += _amount;
            row = $(this);
            item_taxes = $(this).find('select.tax').selectpicker('val');
            if (item_taxes) {
                $.each(item_taxes, function(i, taxname) {
                    taxrate = row.find('select.tax [value="' + taxname + '"]').data('taxrate');
                    calculated_tax = (_amount / 100 * taxrate);

                    if (!taxes.hasOwnProperty(taxname)) {
                        if (taxrate != 0) {
                            _tax_name = taxname.split('|');
                            tax_row = '<tr class="tax-area"><td>' + _tax_name[0] + '(' + taxrate +
                                '%)</td><td id="tax_id_' + slugify(taxname) + '"></td></tr>';
                            $(discount_area).after(tax_row);
                            taxes[taxname] = calculated_tax;
                        }
                    } else {
                        // Increment total from this tax
                        taxes[taxname] = taxes[taxname] += calculated_tax;
                    }
                });
            }
        });

        if (discount_percent != '' && discount_type == 'before_tax') {
            // Calculate the discount total
            total_discount_calculated = (subtotal * discount_percent) / 100;
        }

        if (retention_percent != '' && retention_type == 'before_tax') {
            // Calculate the discount total
            total_retention_calculated = (subtotal * retention_percent) / 100;
        }


        $.each(taxes, function(taxname, total_tax) {
            if (discount_percent != '' && discount_type == 'before_tax') {
                total_tax_calculated = (total_tax * discount_percent) / 100;
                total_tax = (total_tax - total_tax_calculated);
            }
            if (retention_percent != '' && retention_type == 'before_tax') {
                total_tax_calculated = (total_tax * retention_percent) / 100;
                total_tax = (total_tax - total_tax_calculated);
            }
            total += total_tax;
            $('#tax_id_' + slugify(taxname)).html(total_tax + hidden_input('total_tax_name[]', taxname) +
                hidden_input('total_tax[]', total_tax.toFixed(2)));
        });
        total = (total + subtotal);
        if (discount_percent != '' && discount_type == 'after_tax') {
            total_discount_calculated = (total * discount_percent) / 100;
        }
        if (retention_percent != '' && retention_type == 'after_tax') {
            total_retention_calculated = (total * retention_percent) / 100;
        }
        total = total - total_discount_calculated - total_retention_calculated;
        adjustment = parseFloat(adjustment);

        // Check if adjustment not empty
        if (!isNaN(adjustment)) {
            total = total + adjustment;
        }
        // Append, format to html and display
        $('.discount_percent').html('-' + total_discount_calculated.toFixed(2) + hidden_input('discount_percent',
            discount_percent) + hidden_input('discount_total', total_discount_calculated.toFixed(2)));
        $('.retention_percent').html('-' + total_retention_calculated.toFixed(2) + hidden_input('retention_percent',
            retention_percent) + hidden_input('retention_total', total_retention_calculated.toFixed(2)));

        $('.adjustment').html(adjustment.toFixed(2) + hidden_input('adjustment', adjustment.toFixed(2)))
        $('.subtotal').html(subtotal = subtotal.toFixed(2) + hidden_input('subtotal', subtotal.toFixed(2)));
        $('.total').html(total.toFixed(2) + hidden_input('total', total.toFixed(2)));
    }

    // Deletes invoice items
    function delete_item(row, itemid) {
        var parent = $(row).parents('tr');
        var item_id = parent.attr('data-item-id');
        setTimeout(function() {
            parent.remove();
            // delete items from localStorage and reload localStorage
            if (typeof(Storage) !== "undefined") {
                if (pStore[itemid]) {
                    delete pStore[itemid];
                    store(intStore, JSON.stringify(pStore));
                }
            }
            loadItems();
            calculate_total();
        }, 50)
        // If is edit we need to add to input removed_items to track activity
        if ($('input[name="isedit"]').length > 0) {
            $('#removed-items').append(hidden_input('removed_items[]', item_id));
        }
    }

    // Clear the items added to preview
    function clear_main_values(default_taxes) {
        // Get the last taxes applied to be available for the next item
        var last_taxes_applied = $('table.items tbody').find('tr:last-child').find('select').selectpicker('val');

        $('.main textarea[name="item_name"]').val('');
        $('.main textarea[name="item_desc"]').val('');
        <?php $invoice_view = config_item('invoice_view');
        if (!empty($invoice_view) && $invoice_view == '2') { ?>
            $('.main input[name="hsn_code"]').val('');
        <?php } ?>
        $('.main input[name="quantity"]').val(1);
        $('.main select.tax').selectpicker('val', last_taxes_applied);
        $('.main input[name="unit_cost"]').val('');
        $('.main input[name="unit"]').val('');
        $('.main input[name="new_itmes_id"]').val('');
    }

    // Reoder the items in table edit for estimate and invoices
    function reorder_items() {
        var rows = $('.table.invoice-items-table tbody tr.item,.table.table-main-estimate-edit tbody tr.item');
        var i = 1;
        $.each(rows, function() {
            $(this).find('input.order').val(i);
            i++;
        });
    }

    // Append the added items to the preview to the table as items
    function add_item_to_table(data, itemid, merge_invoice) {
        // If not custom data passed get from the preview
        if (typeof(data) == 'undefined' || data == 'undefined') {
            data = get_main_values();
        }
        var table_row = '';
        var unit_placeholder = '';
        var item_key = $('body').find('tbody .item').length + 1;
        table_row += '<tr class="sortable item" data-merge-invoice="' + merge_invoice + '">';
        table_row += '<td class="dragger">';

        // Check if quantity is number
        if (isNaN(data.qty)) {
            data.qty = 1;
        }
        // Check if rate is number
        if (data.rate == '' || isNaN(data.rate)) {
            data.rate = 0;
        }
        var amount = data.rate * data.qty;
        amount = amount;
        var tax_name = 'items[' + item_key + '][taxname][]';
        $('body').append('<div class="dt-loader"></div>');
        var regex = /<br[^>]*>/gi;
        get_taxes_dropdown_template(tax_name, data.taxname).done(function(tax_dropdown) {
            // order input
            table_row += '<input type="hidden" class="order" name="items[' + item_key +
                '][order]"><input type="hidden" name="items[' + item_key + '][saved_items_id]" value="' + data
                .new_itmes_id + '"><input type="hidden" name="new_itmes_id[]" value="' + data.new_itmes_id + '">';
            if (data.item_id) {
                table_row += '<input type="hidden" value="' + data.item_id + '" name="items[' + item_key +
                    '][item_id]">';
            }
            table_row += '</td>';
            table_row += '<td class="bold item_name"><textarea  name="items[' + item_key +
                '][item_name]" class="form-control">' + data.item_name + '</textarea></td>';
            table_row += '<td><textarea  name="items[' + item_key +
                '][item_desc]" class="form-control item_item_desc" >' + ((data.item_desc) ? data.item_desc.replace(
                    regex, "\n") : '') + '</textarea></td>';
            <?php $invoice_view = config_item('invoice_view');
            if (!empty($invoice_view) && $invoice_view == '2') { ?>
                table_row += '<td><input type="text" name="items[' + item_key +
                    '][hsn_code]" class="form-control" value="' + data.hsn_code + '"></td>';
            <?php } ?>
            table_row +=
                '<td><input type="text" data-parsley-type="number" min="0" onblur="calculate_total();" onchange="calculate_total();" data-quantity name="items[' +
                item_key + '][quantity]" value="' + data.qty + '" class="form-control">';

            unit_placeholder = '';
            if (!data.unit || typeof(data.unit) == 'undefined') {
                data.unit = '';
            }

            table_row += '<input type="text" placeholder="' + unit_placeholder + '" name="items[' + item_key +
                '][unit]" class="form-control input-transparent" value="' + data.unit + '">';

            table_row += '</td>';
            table_row +=
                '<td class="rate"><input type="text" data-parsley-type="number" min=0 onblur="calculate_total();" onchange="calculate_total();" name="items[' +
                item_key + '][unit_cost]" value="' + data.rate + '" class="form-control"></td>';
            table_row += '<td class="taxrate">' + tax_dropdown + '</td>';
            table_row += '<td class="amount">' + amount + '</td>';
            table_row += '<td><a href="#" class="btn-xs btn btn-danger pull-left" onclick="delete_item(this,' +
                itemid + '); return false;"><i class="fa fa-trash"></i></a></td>';
            table_row += '</tr>';

            $('table.items tbody').append(table_row);

            setTimeout(function() {
                calculate_total();
            }, 10);

            init_selectpicker();
            clear_main_values();
            reorder_items();

            $('body').find('.dt-loader').remove();
            $('#item_select').selectpicker('val', '');
            return true;
        });
        return true;
    }

    // Get taxes dropdown selectpicker template / Causing problems with ajax becuase is fetching from server
    function get_taxes_dropdown_template(name, taxname) {
        jQuery.ajaxSetup({
            async: false
        });
        var d = $.post('<?= base_url() ?>admin/common/get_taxes_dropdown', {
            name: name,
            taxname: taxname
        });

        jQuery.ajaxSetup({
            async: true
        });

        return d;
    }

    // Get the preview main values
    function get_main_values() {
        var response = {};
        response.item_name = $('.main textarea[name="item_name"]').val();
        response.item_desc = $('.main textarea[name="item_desc"]').val();
        <?php $invoice_view = config_item('invoice_view');
        if (!empty($invoice_view) && $invoice_view == '2') { ?>
            response.hsn_code = $('.main input[name="hsn_code"]').val();
        <?php } ?>
        response.qty = $('.main input[name="quantity"]').val();
        response.taxname = $('.main select.tax').selectpicker('val');
        response.rate = $('.main input[name="unit_cost"]').val();
        response.unit = $('.main input[name="unit"]').val();
        response.new_itmes_id = $('.main input[name="new_itmes_id"]').val();
        return response;
    }
</script>