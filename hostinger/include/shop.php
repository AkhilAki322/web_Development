<?php
session_start();
require_once("dbcontroller.php");
$db_handle = new DBController();
if(!empty($_GET["p1"])) {
    $table = $_GET["p1"];
}
if(!empty($_GET["action"])) {
switch($_GET["action"]) {
	case "add":
		if(!empty($_POST["quantity"])) {
			$productByCode = $db_handle->runQuery("SELECT * FROM $table WHERE item_name='" . $_GET["code"] . "'");
			$itemArray = array($productByCode[0]["item_name"]=>array('item_name'=>$productByCode[0]["item_name"], 'Current_Stock'=>$productByCode[0]["Current_Stock"], 'quantity'=>$_POST["quantity"], 'Act_Price'=>$productByCode[0]["Act_Price"],'Final_Price'=>$productByCode[0]["Final_Price"], 'image'=>$productByCode[0]["image"], 'Discount'=>$productByCode[0]["Discount"]));
			
			if(!empty($_SESSION["cart_item"])) {
				if(in_array($productByCode[0]["item_name"],array_keys($_SESSION["cart_item"]))) {
					foreach($_SESSION["cart_item"] as $k => $v) {
							if($productByCode[0]["item_name"] == $k) {
								if(empty($_SESSION["cart_item"][$k]["quantity"])) {
									$_SESSION["cart_item"][$k]["quantity"] = 0;
								}
								$_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
							}
					}
                } 
                else {
                    $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
				}
            } 
            else {
                $_SESSION["cart_item"] = $itemArray;
			}
		}
	break;
	case "remove":
		if(!empty($_SESSION["cart_item"])) {
			foreach($_SESSION["cart_item"] as $k => $v) {
					if($_GET["code"] == $k)
						unset($_SESSION["cart_item"][$k]);				
					if(empty($_SESSION["cart_item"]))
						unset($_SESSION["cart_item"]);
			}
		}
	break;
	case "empty":
		unset($_SESSION["cart_item"]);
	break;	
}
}
?>




<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Nadar || Superstores</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="images/icons/icon_logo.png">
        <!-- Place favicon.ico in the root directory -->

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/ionicons.min.css">
        <link rel="stylesheet" href="css/css-plugins-call.css">
        <link rel="stylesheet" href="css/bundle.css">
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/responsive.css">
        <link rel="stylesheet" href="css/colors.css">
        <!-- <link href="assets/css/stylig.css" rel="stylesheet"/> -->
        <style>
            .shopBtn {min-height: 34px;font-size: 14px;font-weight: bold;line-height: 34px;text-align: center;color: #fff;border-radius: 2px;background: #f89406;display:inline-block;padding:0 12px;border:0;}
            .defaultBtn {min-height: 34px;font-size: 14px;font-weight: bold;line-height: 34px;text-align: center;border-radius: 2px;display:inline-block;padding:0 12px;color: #666;text-decoration: none;background: #e6e6e6;border:0;}
            .shopBtn:hover {color: #fff; text-decoration:none; background:#F86706}
            .gotop {background-color: #000000;display: none;position: fixed;bottom: 30px;right: 30px;padding: 0px 10px 5px;color: #fff;text-decoration: none;font-size: 30px;line-height: 34px;}
            .gotop:hover {color: #fff;text-decoration: none;-moz-border-radius: 10px;-webkit-border-radius: 10px;border-radius: 10px;}
            #gototop{margin-top:1px;}
            .thumbnail .btn{ border-radius: 0 0 0 0;}
            .thumbnail h4{line-height:42px}
            .thumbnail img{
                min-height: 100px;
                max-height: 171px;
            }
            .thumbnail:hover {border: 1px solid #CACACA;}
            .thumbnail>a{display:block; text-align:center}
            .thumbnail h5,.thumbnail p{text-align:center}
            .thumbnail{position:relative;}
            .thumbnail>a.overlay, .thumbnail>a.zoomTool {display:none;}
            
            .thumbnails {
                margin-left: -20px;
                list-style: none;
                *zoom: 1;
            }
            
            .thumbnails:before,
            .thumbnails:after {
                display: table;
                line-height: 0;
                content: "";
            }
            
            .thumbnails:after {
                clear: both;
            }
            
            .row-fluid .thumbnails {
                margin-left: 0;
            }
            
            .thumbnails > li {
                float: left;
                margin-bottom: 20px;
                margin-left: 20px;
            }
            
            .thumbnail {
                display: block;
                padding: 4px;
                line-height: 20px;
                border: 1px solid #ddd;
                -webkit-border-radius: 4px;
                -moz-border-radius: 4px;
                        border-radius: 4px;
                -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.055);
                -moz-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.055);
                        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.055);
                -webkit-transition: all 0.2s ease-in-out;
                -moz-transition: all 0.2s ease-in-out;
                    -o-transition: all 0.2s ease-in-out;
                        transition: all 0.2s ease-in-out;
            }
            
            a.thumbnail:hover,
            a.thumbnail:focus {
                border-color: #0088cc;
                -webkit-box-shadow: 0 1px 4px rgba(0, 105, 214, 0.25);
                -moz-box-shadow: 0 1px 4px rgba(0, 105, 214, 0.25);
                        box-shadow: 0 1px 4px rgba(0, 105, 214, 0.25);
            }
            
            .thumbnail > img {
                display: block;
                margin-right: auto;
                margin-left: auto;
            }
        </style>

    </head>
    <body>

        <!-- Body main wrapper start -->
        <div class="wrapper home-one">
            <!-- HEADER AREA START -->
            <?php include "include/nav.php";?>

            <div class="breadcrumbs-container">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <nav class="woocommerce-breadcrumb">
                                <a href="index.php">Home</a>
                                <span class="separator">/</span> Shop
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <div class="shop-page-wraper">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12 col-md-2 sidebar-shop">
                                <div class="sidebar-product-categori">
                                    <div class="widget-title">
                                        <h3>PRODUCT CATEGORIES</h3>
                                    </div>
                                    <div class="widget-content">
                                        <?php include "include/sidebar.php" ; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-md-10 shop-content">
                                <div id="shopping-cart">
                                    <div class="txt-heading">Shopping Cart</div>

                                    <a id="btnEmpty" href="shop.php?action=empty&p1=<?php echo $table;?>">Empty Cart</a>
                                    
                                    <?php
                                    if(isset($_SESSION["cart_item"])){
                                        $total_quantity = 0;
                                        $total_price = 0;
                                    ?>	
                                    <table class="tbl-cart" cellpadding="10" cellspacing="1">
                                        <tbody>
                                            <tr>
                                            <th style="text-align:left;">Name</th>
                                            <th style="text-align:left;">Code</th>
                                            <th style="text-align:right;" width="5%">Quantity</th>
                                            <th style="text-align:right;" width="10%">Unit Price</th>
                                            <th style="text-align:right;" width="10%">Price</th>
                                            <th style="text-align:center;" width="5%">Remove</th>
                                            </tr>	
                                            <?php		
                                                foreach ($_SESSION["cart_item"] as $item){
                                                    $item_price = $item["quantity"]*$item["Final_Price"];
                                            ?>
                                            <tr>
                                                <td>	
                                                    <img src="upload/<?php echo $item["image"]; ?>" class="cart-item-image" /> 
                                                    <?php echo $item["item_name"]; ?> 
                                                </td>                         
                                                <td>	
                                                    <?php echo $item["item_name"]; ?>	
                                                </td>
                                                <td style="text-align:right;">	
                                                    <?php echo $item["quantity"]; ?>	
                                                </td>
                                                <td  style="text-align:right;">	
                                                    <?php echo "₹ ".$item["Final_Price"]; ?>	
                                                </td>
                                                <td  style="text-align:right;">	
                                                    <?php echo "₹ ". number_format($item_price,2); ?>	
                                                </td>
                                                <td style="text-align:center;">
                                                    <a href="shop.php?action=remove&p1=<?php echo $table;?>&code=<?php echo $item["item_name"]; ?>" class="btnRemoveAction">
                                                        <img src="icon-delete.png" alt="Remove Item" />
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                                $total_quantity += $item["quantity"];
                                                $total_price += ($item["Final_Price"]*$item["quantity"]);
                                                }
                                            ?>

                                            <tr>
                                                <td colspan="2" align="right">Total:</td>
                                                <td align="right"><?php echo $total_quantity; ?></td>
                                                <td align="right" colspan="2"><strong><?php echo "₹ ".number_format($total_price, 2); ?></strong></td>
                                                <td></td>
                                            </tr>
                                            
                                        </tbody>
                                        <a id="btnEmpty" href="cart.php">Proceed to Checkout</a>
                                    </table>		
                                    <?php
                                        } else {
                                    ?>
                                    <div class="no-records">Your Cart is Empty</div>
                                    <?php 
                                        }
                                    ?>
                                </div>

                                <div id="product-grid">
                                    <div class="txt-heading">Products</div>
                                    <?php
                                    $product_array = $db_handle->runQuery("SELECT * FROM $table");
                                    if (!empty($product_array)) { 
                                        foreach($product_array as $key=>$value){
                                    ?>
                                        <div class="product-item">
                                            <form method="post" action="shop.php?action=add&p1=<?php echo $table ?>&code=<?php echo $product_array[$key]["item_name"]; ?>">
                                            <div class="product-image"><img src="upload/<?php echo $product_array[$key]["image"]; ?>" style="height: 140px; width: 140px;margin-left:10px" ></div>
                                            <div class="product-tile-footer">
                                            <div class="product-title"><?php echo $product_array[$key]["item_name"]; ?></div>
                                            <div class="product-price act-price"><?php echo "₹".$product_array[$key]["Act_Price"]; ?></div>
                                            <div class="product-price" style="padding-left: 4px;"><?php echo "₹".$product_array[$key]["Final_Price"]; ?></div>
                                            <div class="product-price disc"><?php echo $product_array[$key]["Discount"]."%"; ?></div>
                                            </div>
                                            <div class="product-tile-footer">
                                            <div class="cart-action">
                                                <div class="product-title"><input type="texty" class="product-quantity" name="quantity" value="1" size="2" />
                                                <input type="submit" value="Add to Cart" class="btnAddAction" style="height: 24px;background:linear-gradient(to right, #51128a, #cc00ff);" /></div>
                                            </div>
                                            </div>
                                            </form>
                                        </div>
                                        <?php
                                            }
                                        }
                                        ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
            
























            <?php include "include/footer.php";?>
            <!-- QUICKVIEW PRODUCT START -->
            
            <!-- QUICKVIEW PRODUCT END -->
        </div>
        <!-- Body main wrapper end -->


        <!-- jQuery CDN -->
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <!-- jQuery Local -->
        <script>window.jQuery || document.write('<script src="js/jquery-3.2.1.min.js"><\/script>')</script>

        <!-- Popper min js -->
        <script src="js/popper.min.js"></script>
        <!-- Bootstrap min js  -->
        <script src="js/bootstrap.min.js"></script>
		<!-- nivo slider pack js  -->
        <script src="js/jquery.nivo.slider.pack.js"></script>
        <!-- All plugins here -->
        <script src="js/plugins.js"></script>
        <!-- Main js  -->
        <script src="js/main.js"></script>



        <!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
        <script>
            window.ga=function(){ga.q.push(arguments)};ga.q=[];ga.l=+new Date;
            ga('create','UA-XXXXX-Y','auto');ga('send','pageview')
        </script>
        <script src="https://www.google-analytics.com/analytics.js" async defer></script>
    </body>
</html>
