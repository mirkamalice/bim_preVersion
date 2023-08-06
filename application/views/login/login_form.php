<p class="text-center pv"><?= lang('sing_in_to_continue') ?></p>
<form data-parsley-validate="" novalidate="" action="<?php echo base_url() ?>login" method="post">
    <?php do_action('before_login_form') ?>
    <div class="form-group has-feedback">
        <input type="text" name="user_name" value="" required="true" class="form-control"
               placeholder="<?= lang('username') ?>"/>
        <span class="fa fa-envelope form-control-feedback text-muted"></span>
    </div>
    <div class="form-group has-feedback">
        <input type="password" value="" name="password" required="true" class="form-control"
               placeholder="<?= lang('password') ?>"/>
        <span class="fa fa-lock form-control-feedback text-muted"></span>
    </div>
    <div class="clearfix">
        <div class="checkbox c-checkbox pull-left mt0">
            <label>
                <input type="checkbox" value="" name="remember">
                <span class="fa fa-check"></span><?= lang('remember_me') ?></label>
        </div>
        <div class="pull-right"><a href="<?= base_url() ?>login/forgot_password"
                                   class="text-muted"><?= lang('forgot_password') ?></a>
        </div>
    </div>
    <?php if (config_item('recaptcha_secret_key') != '' && config_item('recaptcha_site_key') != '') { ?>
        <div class="g-recaptcha mb-lg mt-lg" data-sitekey="<?php echo config_item('recaptcha_site_key'); ?>"></div>
    <?php }
    $mark_attendance_from_login = config_item('mark_attendance_from_login');
    if (!empty($mark_attendance_from_login) && $mark_attendance_from_login == 'Yes') {
        $class = null;
    } else {
        $class = 'btn-block';
    }
    $mark_attendance = '';
    $allow_geo_clock_in = config_item('allow_geo_clock_in');
    if (!empty($allow_geo_clock_in) && $allow_geo_clock_in == 'TRUE') {
        $mark_attendance = 'mark_attendance';
    }
    ?>
    <button type="submit" class="btn btn-primary <?= $class ?> btn-flat"><?= lang('sign_in') ?> <i
                class="fa fa-arrow-right"></i></button>
    <?php if (empty($class)) { ?>
        <button type="submit" name="mark_attendance" id="<?= $mark_attendance ?>" value="mark_attendance"
                class="btn btn-purple btn-flat pull-right">
            <i class="fa fa-clock-o"></i> <?= lang('mark_attendance') ?> </button>
    <?php } ?>
</form>
<?php if (config_item('allow_client_registration') == 'TRUE') { ?>
    <p class="pt-lg text-center"><?= lang('do_not_have_an_account') ?></p><a href="<?= base_url() ?>login/register"
                                                                             class="btn btn-block btn-default"><i
                class="fa fa-sign-in"></i> <?= lang('get_your_account') ?></a>
<?php }
do_action('login_form_oauth'); ?>


<script>
    $("#mark_attendance").on("click", function (e) {
        e.preventDefault();
        get_geo_data(success_action);
    });

    function get_geo_data(success_action) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(success_action, handle_errors);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function success_action(position) {
        var input = $("<input/>")
            .attr("type", "hidden")
            .attr("name", "lat")
            .attr("value", position.coords.latitude);

        var input2 = $("<input>")
            .attr("type", "hidden")
            .attr("name", "long")
            .attr("value", position.coords.longitude);


        $('form').append(input);
        $('form').append(input2);
        $('form').append('<input type="hidden" name="mark_attendance" value="1" /> ');

        $('form').submit();
    }

    function handle_errors(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                alert("To mark attendence, you must allow location access also Only secure origins are allowed by default. You can modify your configuration.");
                break;

            case error.POSITION_UNAVAILABLE:
                alert("could not detect current position");
                break;

            case error.TIMEOUT:
                alert("retrieving position timedout");
                break;

            default:
                alert("unknown error");
                break;
        }
        //window.location.replace(window.location.href);
    }
</script>