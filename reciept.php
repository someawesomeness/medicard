<?php
    include ('sale.php');
    $sale_data = getSale($_GET['reciept_id']);

    $customer_data = $sale_data['customer'];
    $items = $sale_data['items'];
    $sale = $sale_data['sale'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicard Screen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://use.fontawesome.com/0c7a3095b5.js"></script>
    <!-- custom Style -->
    <link rel="stylesheet" href="screen2.css">
    <!--bootstrapping-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.4/js/bootstrap-dialog.min.js" integrity="sha512-LbO5ZwEjd9FPp4KVKsS6fBk2RRvKcXYcsHatEapmINf8bMe9pONiJbRWTG9CF/WDzUig99yvvpGb64dNQ27Y4g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</head>
<body>
    <div class="receiptContainer">
        <h3 style="font-size: 16px; color: #828282; text-align: right; border-bottom: 1px solid #cccccc; padding-bottom:10px; margin-top:10px;">Original Receipt</h3>
        <hr>
        <div> <!--2nd div -->
            <table>
                <tbody>
                    <tr>
                        <td><h3 style="font-size: 23px; text-transform: uppercase; color:aqua;">MediCard</h3></td>
                    </tr>
                    <tr>
                        <td><span  style="font-weight:bold; font-size:13px;">Address : </span> <span>Philippines</span></td>
                    </tr>
                    <tr> 
                        <td><span  style="font-weight:bold; font-size:13px;">City : </span><span>Manila</span></td>
                    </tr>
                    <tr>
                        <td><span  style="font-weight:bold; font-size:13px;">Postal : </span><span>9600</span></td>
                    </tr>
                        <tr>
                            <td style="height:20px;"></td>
                        </tr> 
                        <tr> 
                        <td width="50%" style="vertical-align:top;">
                        
                             <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            <h3 style="font-size: 15px; text-transform: uppercase; ">Customer Details</h3>
                                        </td>
                                    </tr>
                                        <tr><td><span style="font-weight:bold; margin-left:9px; font-size:13px;">Name : </span> <span><?= isset($customer_data['first_name']) ? $customer_data['first_name'] : 'N/A' ?> <?= isset($customer_data['last_name']) ? $customer_data['last_name'] : 'N/A' ?></span></td></tr>
                                        <tr><td><span style="font-weight:bold; margin-left:9px; font-size:13px;">Address : </span> <span><?= isset($customer_data['address']) ? $customer_data['address'] : 'N/A' ?></span></td></tr>
                                        <tr><td><span style="font-weight:bold; margin-left:9px; font-size:13px;">Contact : </span> <span><?= isset($customer_data['contact']) ? $customer_data['contact'] : 'N/A' ?></span></td></tr>
                                    <tr>
                                        <td style="height:20px;"></td>
                                    </tr>                              
                                </tbody>
                            </table>
                        </td>
                        </tr>
                        
                        <tr>
                        <td width="50%" style="vertical-align:top;"></td>
                             <table>
                                <tbody>
                                    <tr>
                                        <td><span style="font-size: 15px; text-transform: uppercase; font-weight: 600;">Details </span> </td>
                                    </tr>
                                    <tr> 
                                         <td><span  style="font-weight:bold; margin-left:9px; font-size:13px;">Receipt # : </span><span><?= $sale['id'] ?></span></td>
                                    </tr>
                                    <tr>
                                        <td><span  style="font-weight:bold; margin-left:9px; font-size:13px;"> Receipt Date : </span><span><?= date('M d,T h:i:s A', strtotime($sale['date_created'])) ?></span></td>
                                    </tr>
                                    <tr>
                                        <td style="height: 25px;"></td>
                                    </tr>
                                    <tr><td></td></tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>        
                </tbody>
            </table>

            <div>
                <h3 style="font-size:15px; text-transform: uppercase; margin: 0 auto; padding-top:20px;padding-bottom: 10px; ">Items</h3>
            </div>
                <table style="width: 100%; ">
                                <tbody>
                                    <tr>
                                        <td style="border: 2px solid #bbbbbb; border-collapse: collapse;width:10%;font-size:12px; font-weight: bold; text-align:center;background-color:aqua;color:black;">#</td>
                                        <td style="border: 2px solid #bbbbbb; border-collapse: collapse;width:30%;font-size:12px; font-weight: bold; text-align:center;background-color:aqua;color:black;">Product Name</td>
                                        <td style="border: 2px solid #bbbbbb; border-collapse: collapse;width:30%;font-size:12px; font-weight: bold; text-align:center;background-color:aqua;color:black;">Ordered Qty</td>
                                        <td style="border: 2px solid #bbbbbb; border-collapse: collapse; width:30%;font-size:12px; font-weight: bold; text-align:center;background-color:aqua;color:black;">Price</td>
                                        <td style="border: 2px solid #bbbbbb; border-collapse: collapse;width:30%;font-size:12px; font-weight: bold; text-align:center;background-color:aqua;color:black;">Amount</td>  
                                    </tr>
                                <?php $counter = 1;
                                    foreach ($items as $item) {
                                ?>
                                <tr>
                                    <td style="border: 2px solid #bbbbbb; border-collapse: collapse;font-size:13px;text-align:center;"><?= $counter ?></td>
                                    <td style="border: 2px solid #bbbbbb; border-collapse: collapse;font-size:13px;text-align:center;"><?= $item['product'] ?></td>
                                    <td style="border: 2px solid #bbbbbb; border-collapse: collapse;font-size:13px;text-align:center;"><?= number_format($item['quantity']) ?></td>
                                    <td style="border: 2px solid #bbbbbb; border-collapse: collapse;font-size:13px;text-align:center;"><?= isset($item['unit_price']) ? number_format($item['unit_price'], 2, '.', ',') : 'N/A' ?></td>
                                    <td style="border: 2px solid #bbbbbb; border-collapse: collapse;font-size:13px;text-align:center;"><?= number_format($item['sub_total'], 2, '.', ',') ?></td>
                                </tr>
                                <?php $counter++; } ?>
                                <tr>
                                    <td style="height:15px;"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="border: 2px solid #bbbbbb; border-collapse: collapse;font-size:14px;text-align:center; font-weight:bold;background-color:#b0b0b0;">TOTAL:</td>
                                    <td style="border: 2px solid #bbbbbb; border-collapse: collapse;font-size:14px;text-align:center;font-weight:bold;color:black;"><?= number_format($sale['total_amount'], 2, '.', ',') ?></td>
                                </tr>

                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="border: 2px solid #bbbbbb; border-collapse: collapse;font-size:14px;text-align:center;font-weight:bold; text-transform:uppercase;background-color:silver;">Amount Tendered:</td>
                                    <td style="border: 2px solid #bbbbbb; border-collapse: collapse;font-size:14px;text-align:center;font-weight:bold; text-transform: uppercase;color:black;"> <?= number_format($sale['amount_tendered'], 2, '.', ',') ?></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="border: 2px solid #bbbbbb; border-collapse: collapse;font-size:14px;text-align:center;font-weight:bold;background-color:#b0b0b0">change:</td>
                                    <td style="border: 2px solid #bbbbbb; border-collapse: collapse;font-size:14px;text-align:center;font-weight:bold;color:black;"> <?= number_format($sale['change_amt'], 2, '.', ',') ?></td>
                                </tr>
                                <td style="border: 2px solid #bbbbbb; border-collapse: collapse;font-size:14px;text-align:center;font-weight:bold;color:black;"><a href="index.php">Go back to screen</a></td>
                        </tbody>          
                    </table>
         </div> <!--2nd div -->
    </div> 
</body>
</html>