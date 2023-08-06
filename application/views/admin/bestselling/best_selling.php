<?php echo message_box('success') ?>

<div class="row">
    <!-- Start Form test -->
    <div class="col-lg-12">

        <div class="panel panel-custom">
            <header class="panel-heading ">
                <div class="panel-title"><strong><?= lang('best_selling') . ' ' . lang('product') ?></strong></div>

            </header>
            <div class="panel-body">
                <div class="panel-body">
                    <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th><?= lang('name') ?></th>
                                <th><?= lang('code') ?></th>
                                <th><?= lang('quantity') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <script type="text/javascript">
                            $(document).ready(function() {
                                list = base_url + "admin/best_selling/bestsellinglist";
                            });
                            </script>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>



        <script src="<?= base_url() ?>assets/plugins/raphael/raphael.min.js"></script>
        <script src="<?= base_url() ?>assets/plugins/morris/morris.min.js"></script>

        <div class="row">




            <div class="col-lg-12">
                <div class="panel panel-custom">
                    <div class="panel-heading">
                        <div class="panel-title"><strong><?= lang('best_selling') . ' ' . lang('product') ?></strong>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div id="morris-bar"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">




            <div class="col-lg-12">
                <div class="panel panel-custom">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <div class="panel-title">
                                <strong><?= lang('Worst_selling') . ' ' . lang('product') ?></strong></div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="chart-pie flot-chart"></div>
                    </div>
                </div>
            </div>
        </div>


        <script src="<?= base_url() ?>assets/plugins/Flot/jquery.flot.js"></script>
        <script src="<?= base_url() ?>assets/plugins/Flot/jquery.flot.tooltip.min.js"></script>
        <script src="<?= base_url() ?>assets/plugins/Flot/jquery.flot.resize.js"></script>
        <script src="<?= base_url() ?>assets/plugins/Flot/jquery.flot.pie.js"></script>
        <script src="<?= base_url() ?>assets/plugins/Flot/jquery.flot.time.js"></script>
        <script src="<?= base_url() ?>assets/plugins/Flot/jquery.flot.categories.js"></script>
        <script src="<?= base_url() ?>assets/plugins/Flot/jquery.flot.spline.min.js"></script>
        <script type="text/javascript">
        $(document).ready(function() {
            var chartdata = [
                <?php



                    if (!empty($bestselling)) : foreach ($bestselling as $user   => $v_bestselling) :




                    ?> {
                    y: "<?= $v_bestselling->item_name ?>",
                    <?php
                                $inparogress = $v_bestselling->quantity;
                                $notstarted = 0;
                                $sdeferred = 0;
                                // foreach ($v_task_user as $status => $value) {
                                //     if ($status == 'not_started') {
                                //         $notstarted = count($value);
                                //     } elseif ($status == 'in_progress') {
                                //         $inparogress = count($value);
                                //     } elseif ($status == 'deferred') {
                                //         $sdeferred = count($value);
                                //     }
                                // }
                                ?>
                    a: <?= $inparogress; ?>,


                },
                <?php




                        endforeach;
                    endif;

                    ?>
            ];
            new Morris.Bar({
                element: 'morris-bar',
                data: chartdata,
                xkey: 'y',
                ykeys: ["a"],
                labels: ["<?= lang('total_sales') ?>"],
                xLabelMargin: 2,
                barColors: ['#23b7e5'],
                resize: true,
                parseTime: false,
            });

            // CHART PIE
            // -----------------------------------
            (function(window, document, $, undefined) {

                $(function() {

                    var data = [
                        <?php
                            function random_color_part()
                            {
                                return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
                            }

                            function random_color()
                            {
                                return random_color_part() . random_color_part() . random_color_part();
                            }


                            if (!empty($bestselling)) :

                                foreach ($bestselling as $v_bestselling) {

                                    $color = random_color();

                                    if ($v_bestselling->quantity < 100) {
                                        $i = 'F2340C';
                                    } elseif ($v_bestselling->quantity < 200) {
                                        $i = '0CF247';
                                    } elseif ($v_bestselling->quantity > 300) {
                                        $i = '0CF2D6';
                                    } elseif ($v_bestselling->quantity < 500) {
                                        $i = 'AC0CF2';
                                    } else {
                                        $i = 'ff902b';
                                    }

                            ?> {
                            "label": "<?= $v_bestselling->item_name  ?>",
                            "color": "#<?= $color ?>",
                            "data": <?= $v_bestselling->quantity ?>

                        },
                        <?php };
                            endif; ?>
                    ];


                    var options = {
                        series: {
                            pie: {
                                show: true,
                                innerRadius: 0,
                                label: {
                                    show: true,
                                    radius: 0.8,
                                    formatter: function(label, series) {
                                        return '<div class="flot-pie-label">' +
                                            //label + ' : ' +
                                            Math.round(series.percent) +
                                            '%</div>';
                                    },
                                    background: {
                                        opacity: 0.8,
                                        color: '#222'
                                    }
                                }
                            }
                        }
                    };

                    var chart = $('.chart-pie');
                    if (chart.length)
                        $.plot(chart, data, options);

                });

            })(window, document, window.jQuery);


        });
        </script>