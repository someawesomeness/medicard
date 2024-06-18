<?php 
    // Start the session.
    session_start();
    if (!isset($_SESSION["user"])) header('location: ../starting/login.php');
    $user = $_SESSION["user"];

    include('dashboard-back.php');
    $widgetData = getSaleWidgetData();
    $recentOrders = getRecentOrders();

    $end = date('Y-m-d');
    $start = date('Y-m-d', strtotime($end.'- 15 days'));
    $graph_data = getChartData($start, $end);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicard System Admin - Dashboard</title>
    <?php include("../fixed/app-header-scripts.php");?>
</head>
<body>
    <div id="dashboardMainCont">
        <?php include('../fixed/app-sidebar.php') ?>
        <div class="dashboard_content_container" id="dashboard_content_container">
        <?php include('../fixed/app-topnav.php') ?>
            <div class="dashboard_content">
                <div class="dashboard_content_main">
                    <div class="row widgetMainRow">
                        <div class="col-4">
                            <div class="widgetContainer widgetSale">
                                <p class="widgetHeader">Sale amount</p>
                                <p class="widgetValue"><?= number_format($widgetData['sale_amount']) ?></p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="widgetContainer widgetQty">
                                <p class="widgetHeader">Quantity</p>
                                <p class="widgetValue"><?= number_format($widgetData['qty']) ?></p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="widgetContainer widgetOrder">
                                <p class="widgetHeader">Total Order</p>
                                <p class="widgetValue"><?= number_format($widgetData['orders']) ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="row widgetMainRow">
                        <div class="col-md-5 widgetSecondRow">
                            <p class="header">last 5 orders</p>
                            <?php   
                                if (count($recentOrders) > 0) {
                                    echo '<table class="table">';
                                    echo '<tr>';
                                    echo '<td>Order #</td>';
                                    echo '<td>Total Amount</td>';
                                    echo '<td>Date</td>';
                                    echo '</tr>';
                                    foreach ($recentOrders as $order) {
                                        echo '<tr>';
                                        echo '<td>' . $order['id'] . '</td>';
                                        echo '<td>' . number_format($order['total_amount']) . '</td>';
                                        echo '<td>' . $order['date_created'] . '</td>';
                                        echo '</tr>';
                                    }
                                    echo '</table>';
                                } else {
                                    echo 'No recent orders.';
                                }
                            ?>

                        </div>
                        <div class="col-md-7">
                            <p class="header">Dispensed Tracker</p>
                            <div class="alignRight">
                                <button class="btn btn-sm btn-primary" id="daterange">
                                    Select Range
                                </button>
                            </div>
                            <figure class="highcharts-figure">
                                <div id="container"></div>
                                    <p class="highcharts-description">
                                        This chart. 
                                    </p>
                            </figure>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    
    <script>
        function toDateRange() {
            $('#daterange').daterangepicker({
                maxDate: moment(),
            }, function(start, end, label) {
                console.log(start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            });
        }
        
        // $(document).ready(function() {
        //     toDateRange();
        // });
        function visualize($graphData) {
            let graphData = <?= $graph_data ?>;
            console.log(graphData);
            Highcharts.chart('container', {
                chart: {
                    type: 'spline'
                },
                title: {
                    text: 'Total Dispensed'
                },
                xAxis: {
                    categories: graphData['categories'],
                    labels: {
                        style: {
                            color: 'aquamarine',
                            fontSize: '15px'
                        },
                    },
                },
                yAxis: {
                    text: 'Total Dispensed',
                    labels: {
                        format: '{value}',
                    },
                },
                accessibility: {
                    description: 'Total Dispensed'
                },
                plotOptions: {
                    series: {
                        text: 'Bioflu',
                    },
                    spline: {
                        marker: {
                            radius: 3,
                            lineColor: '#666666',
                            lineWidth: 1,
                            fillColor: 'white',
                            lineWidth: 2
                        }
                    }
                },
                series: [{
                    name: 'Total Dispensed',
                    data: graphData['series'],
                }]
            });
        }
        // call graph
        visualize();
        // call date
        toDateRange();

    </script>

    <script src="../scripts/sidebaropen.js"></script>
</body>
</html>