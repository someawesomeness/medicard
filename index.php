<?php 
    include('product.php');
    $products = getProducts();
?>
<?php
	$Write="<?php $" . "UIDresult=''; " . "echo $" . "UIDresult;" . " ?>";
	file_put_contents('rfid/UIDContainer.php',$Write);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicard Screen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://use.fontawesome.com/0c7a3095b5.js"></script>
    <link href="rfid/css/bootstrap.min.css" rel="stylesheet">
		<script src="rfid/js/bootstrap.min.js"></script>
		<script src="rfid/jquery.min.js"></script>
    <link rel="stylesheet" href="css/screen2.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.4/js/bootstrap-dialog.min.js" integrity="sha512-LbO5ZwEjd9FPp4KVKsS6fBk2RRvKcXYcsHatEapmINf8bMe9pONiJbRWTG9CF/WDzUig99yvvpGb64dNQ27Y4g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
			$(document).ready(function(){
				 $("#getUID").load("rfid/UIDContainer.php");
				setInterval(function() {
					$("#getUID").load("rfid/UIDContainer.php");
				}, 500);
			});
		</script>
        <style>
            textarea {
			resize: none;
            }
            form {
                display: block;
                margin-top: 0em;
                unicode-bidi: isolate;
                margin-block-end: 1em;
            }
            .row h3 {
			margin-right: 10px;
			margin-left: 5px;
		    }
        </style>
</head>


<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-8">
                <div class="searchInputContainer">
                    <input type="text" placeholde="search your meds here">
                    <!--
                        1. Create container of search results
                        2. Design the search result entry
                        3. Implement the js file
                    -->
                        
                        <div id="searchResultContainerMain">
                            
                        </div>
                    
                </div>                
                    <div class="productsContainer">
                        <div class="row">
                        <?php foreach($products as $product) { ?>
                            <div class="col-4 productColContainer" data-pid="<?= isset($product['id']) ? $product['id'] : 'ID not set' ?>">
                                <div class="productResultContainer">
                                    <img src="uploads/products/<?= isset($product['img']) ? $product['img'] : '' ?>" class="productImage" alt="Bioflu" >
                                    <div class="productInfoContainer">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <p class="productName"><?= isset($product['product_name']) ? $product['product_name'] : '' ?></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="productPrice"><?= isset($product['price']) ? $product['price'] : '' ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php }?>                        
                        </div>
                    </div>
                </div>

            <div class="col-4 screenOrderContainer">
                <div class="screenHeader">
                    <div class="setting alignRight">
                        <a href="homepage.php"><i class="fa fa-gear"></i></a>
                    </div>
                    <p class="logo">Medicard</p>
                    <p class="timeAndDate">XXX XX, XXXX, XX:XX XX</p>
                </div>


                <div class="screenItemsContainer">
                    <div class="screenItems" id="noData">
                        <div class="itemNoData">NO DATA</div>
                    </div>
                    <div class="itemTotalContainer">
                        <p class="itemTotal">
                            <span class="itemTotal-label">TOTAL </span>
                            <span class="itemTotal-value">0.00 </span>
                        </p>
                    </div>
                    <div class="proceedBtnContainer">
                    <a href="javascript:void(0);" class="proceedBtn">PROCEED</a>
                    </div>
                </div>
            </div>
            </div>
            </div>
        </div>
    </div>
    
    <!-- Create a global js variable to hold products -->
    <script>
        let productsJson = <?= json_encode($products) ?>;
        var products = {};

        // loop through productsJson and push to products array
        productsJson.forEach((product) => {
            products[product.id] = {
                name: product.product_name,
                stock: product.stock,
                price: product.price,
            }
        });

        // Live search feature
        var typingTimer; // timer identifier
        var doneTypingInterval = 500;

        // Add event listener, once user click keyboard key, this will be run
        document.addEventListener("keyup", function (ev) {
        let el = ev.target;

        // If searchInput is the element
        if (el.id === "searchInput") {
            // Get the value
            let searchTerm = el.value;

            // Use clearTimeout to stop running setTimeout
            // This will clear the timeout, to avoid calling / searching database everytime we type a key
            clearTimeout(typingTimer);

            // Set timeout
            // This is the function that calls the searchDb, which pulls the search in database
            // After 500 milliseconds, it will be triggered
            typingTimer = setTimeout(function () {
            // Call the funstion, and pass the searchTerm as parameter
            searchDb(searchTerm);
            }, doneTypingInterval);
        }
        });

        function searchDb(searchTerm) {
        console.log(searchTerm);
        return;
        let searchResult = document.getElementById("searchResultContainerMain");

        // Check if searchterm is not empty
        // If not empty, trigger this function
        if (searchTerm.length) {
            // Set container of result to block
            searchResult.style.display = "block";
            $.ajax({
            type: "GET",
            data: {search_term: searchTerm},
            url: "search.php",
            success: function (response) {
                // If there is no length, we show no data found
                if (response.length === 0) {
                searchResult.innerHTML = "no data";
                } else {
                let html = "";
                searchResult.innerHTML = html;
                }
            },
            dataType: "json",
            });
        } else {
            searchResult.style.display = "none";
        }
        }
        console.log(products);
    </script>
    
<script defer src="scripts/clock.js?v=c?="></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- <script src="screen.js"></script> -->
<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>