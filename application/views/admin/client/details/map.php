<section class="panel panel-custom">
    <div class="panel-heading">
        <div class="panel-title">
            <?= lang('map') ?>
            <div class="pull-right" style="margin-top: -20px">
                <?php echo form_open(base_url('admin/client/update_latitude/' . $client_details->client_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                <div class="col-md-5">
                    <label class="text-sm">
                        <a href="#" onclick="fetch_lat_long_from_google_cprofile(); return false;" data-toggle="tooltip"
                           data-title="<?php echo lang('fetch_from_google') . ' - ' . lang('customer_fetch_lat_lng_usage'); ?>"><i
                                    id="gmaps-search-icon" class="fa fa-google" aria-hidden="true"></i></a>
                        <?= lang('latitude') . '( ' . lang('google_map') . ' )' ?>
                    </label>
                    <div class="">
                        <input type="text" style="height: 20px" class="form-control text-sm " value="<?php
                        if (!empty($client_details->latitude)) {
                            echo $client_details->latitude;
                        }
                        ?>" name="latitude">
                    </div>
                </div>
                <div class="col-md-5">
                    <label class="text-sm"><?= lang('longitude') . '( ' . lang('google_map') . ' )' ?></label>
                    <div class="">
                        <input type="text" style="height: 20px" class="form-control input-sm text-sm" value="<?php
                        if (!empty($client_details->longitude)) {
                            echo $client_details->longitude;
                        }
                        ?>" name="longitude">
                    </div>
                </div>
                
                
                <textarea class="form-control hidden" name="address"><?php
                    if (!empty($client_details->address)) {
                        echo $client_details->address;
                    }
                    ?></textarea>
                <input type="hidden" class="form-control" value="<?php
                if (!empty($client_details->city)) {
                    echo $client_details->city;
                }
                ?>" name="city">
                <select name="country" class="form-control hidden" style="width: 100%">
                    <option selected value="<?= $client_details->country ?>">
                        <?= $client_details->country ?></option>
                </select>
                <div class="col-md-2" style="margin-top: -6px">
                    <label class="" style="visibility: hidden"><?= lang('longitude') ?></label>
                    <button type="submit" class="btn btn-sm btn-primary btn-xs"><?= lang('update') ?></button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
    <div class="panel-body">
        
        
        <style type="text/css">
            .client_map {
                height: 500px;
            }
        </style>
        
        <?php
        $google_api_key = config_item('google_api_key');
        if ($google_api_key !== '') {
            if ($client_details->longitude == '' && $client_details->latitude == '') {
                echo lang('map_notice');
            } else {
                echo '<div id="map" class="client_map"></div>';
            } ?>
            <script>
                var latitude = '<?= $client_details->latitude ?>';
                var longitude = '<?= $client_details->longitude ?>';
                var marker = '<?= $client_details->name ?>';
            </script>
            <script src="<?= base_url() ?>assets/plugins/map/map.js"></script>
            <script async defer
                    src="https://maps.googleapis.com/maps/api/js?key=<?= $google_api_key ?>&callback=initMap">
            </script>
        
        <?php } else {
            echo lang('setup_google_api_key_map');
        }
        ?>
    </div>
</section>

<script type="text/javascript">
    function fetch_lat_long_from_google_cprofile() {
        var data = {};
        data.address = $('textarea[name="address"]').val();
        data.city = $('input[name="city"]').val();
        data.country = $('select[name="country"] option:selected').text();
        console.log(data);
        $('#gmaps-search-icon').removeClass('fa-google').addClass('fa-spinner fa-spin');
        $.post('<?= base_url() ?>admin/common/fetch_address_info_gmaps', data).done(function (data) {
            data = JSON.parse(data);
            $('#gmaps-search-icon').removeClass('fa-spinner fa-spin').addClass('fa-google');
            if (data.response.status == 'OK') {
                $('input[name="latitude"]').val(data.lat);
                $('input[name="longitude"]').val(data.lng);
            } else {
                if (data.response.status == 'ZERO_RESULTS') {
                    toastr.warning("<?php echo lang('g_search_address_not_found'); ?>");
                } else {
                    toastr.warning(data.response.status);
                }
            }
        });
    }
</script>