<?php
session_start();
require './database.php';
if(!isset($_SESSION['user'])){
    header('location: /');
}
$username = $_SESSION['user'];
$results = mysqli_query($conn, "SELECT * FROM `userlist` WHERE username='$username'");
$rows = mysqli_fetch_assoc($results)
?>
<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://kit.fontawesome.com/612f542d54.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Allan&family=Anton&family=Bebas+Neue&family=Courgette&family=Imbue&family=Kaushan+Script&family=Lobster&family=Nova+Square&family=Oswald:wght@300;400&family=PT+Sans+Narrow&family=Pathway+Gothic+One&family=Poppins&family=Potta+One&family=Righteous&family=Roboto:wght@300;400&family=Squada+One&family=Teko:wght@300;400&family=Trade+Winds&family=Yanone+Kaffeesatz:wght@400;500&family=Yellowtail&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <title>Dashboard | <?php echo $rows["name"]; ?></title>
</head>
<body>
    <section class="main_container">
        <div class="left_parts">
            <div class="intro_parts">
                <div class="proimage">
                    <img src="../assets/imgs/def_profile.png" alt="">
                </div>
                <div class="userdetails">
                    <div class="username">Sachin Acharya</div>
                    <div class="userpost">Super Admin</div>
                </div>
            </div>
            <div class="links">
                <li class="options optmenu" data-class='of_dashboard' onclick='menu_this(this)'><i class="fas fa-tachometer-alt"></i> Dashboard</li>
                <li class='options optmenu' data-class="of_transactions" onclick='menu_this(this)'><i class="fas fa-money-check-alt"></i> Transactions</li>
                <li class="options optmenu" data-class="of_inventory" onclick='menu_this(this)'><i class="fas fa-warehouse"></i> Inventory</li>
                <li class="options optmenu" data-class="of_orders" onclick='menu_this(this)'><i class="fas fa-sort-numeric-up-alt"></i> Orders</li>
                <li class="options optmenu sectionactive" data-class="of_customers" onclick='menu_this(this)'><i class="fas fa-sort-numeric-up-alt"></i> Customers</li>
            </div>
        </div>
        <div class="right_parts">
            <div class="top_menus">
                <div class="hamburgers">
                    <div class="ham ham1"></div>
                    <div class="ham ham2"></div>
                    <div class="ham ham3"></div>
                </div>
                <h2 class="heading mainer_heading">Dashboard</h2>
                <div class="userprofiles">
                    <div class="profileimage">
                        <img src="../assets/imgs/def_profile.png" alt="">
                    </div>
                    <div class="userdetails">
                        <div class="username">Sachin Acharya</div>
                        <div class="userpost">Super Administrator</div>
                    </div>
                </div>
            </div>
            <div class="sidesections">
                <div class="of_dashboard allboards">
                    <div class="analytics">
                        <div class="container">
                            <h1>Graph</h1>
                            <div id="curve_chart">
                            </div>
                            <h1>Statistics</h1>
                            <div id="stat" class="stat">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="of_transactions allboards">
                    <div class="history">
                        
                    </div>
                    <div class="options">
                        <select>
                            <option value="null">Choose Options</option>
                            <option value="all">Show All</option>
                            <option value="debit">Debit</option>
                            <option value="credit">Credit</option>
                        </select>
                        <div class="output">
                        </div>
                    </div>
                </div>
                <div class="of_orders allboards opendiv">
                    <div class="topmemenus">
                        <h1>Choose: </h1>
                        <button class="create-orders" id='create-orders' onclick="opening_windows('creating_orders')">Create Order</button>
                        <button class="view-orders" id="view-orders" onclick="opening_windows('viewing-orders')">View Orders</button>
                    </div>
                    <div class="creating_orders" id="creating_orders">
                        <h1><span>Order Placement</span> <button class="closethis" onclick="closing_windows('creating_orders')"><i class="fas fa-times"></i></button></h1>
                        <div class="customers-name" id="customers_name">
                            
                            <label for="customersName"><i class="fas fa-user"></i> Customers Name</label>
                            <input type="text" name="customersName" id="customersName" class="customersName customersNameLive" autocomplete="geda_bhyo_jindagi" required>
                            <p class="all_the_customer">
                            </p>
                            <label for="customersaddress"><i class="fas fa-map-marker-alt"></i> Address</label>
                            <input type="text" name="customersaddress" id="customersaddress" class="customersaddress" value='Bharatpur-2, Chitwan' autocomplete="false" required>
                            
                            <label for="asking_amount">Amount Received</label>
                            <input type="text" name="asking_amount" id="asking_amount" class="asking_amount" value='0' autocomplete="false" required>
                            
                            <button id="add_prod">Add More Product</button>
                        </div>
                        <div class="items-details" id="items-details">
                            <div class="items" id="1">
                                <select name="itemname" class="itemname">
                                </select>
                                <input type="text" data-parent='1' name="itemquantity" id="itemquantity" class="itemquantity" required="required" placeholder="Quantity" onkeyup="calculateTotal('1')">
                                <input type="text" data-parent='1' name="peramount" id="peramount" class="peramount" required="required" placeholder="Rate" onkeyup="calculateTotal('1')">
                                <span class="total" data-total='0'>Total: 0/-</span>
                            </div>
                        </div>
                        <div class="submitButton">
                            <input type="submit" value="Place Order" id="placeorder" class="placeorder" name="placeorder">
                        </div>
                    </div>
                    <!--Showing Order data-->
                    <div class="viewing-orders" id="viewing-orders">
                        <div class="search-div">
                            <input type="text" name="search-bar" id="search-bar" class="search-bar" placeholder="Search By Reference Number or Cutomer Name" autocomplete="off">
                        </div>
                        <div class="details">
                            
                        </div>
                    </div>
                    <div class="showing_output_of_orders" id="showing_output_of_orders">
                        <div class="contents">
                            <h1><span>Order List</span> <button class="closethis" onclick="closing_windows('showing_output_of_orders')"><i class="fas fa-times"></i></button></h1>
                            <div class="details" id="details">
                            </div>
                            <div class="itemlist">
                            </div>
                        </div>
                        <div class="buttons">
                            <button class="download">Download</button>
                            <button class="print-out" onclick="printout()">Print Out</button>
                            <button class="editit" onclick="editthusout()">Edit</button>
                        </div>
                    </div>
                    <!--activate -->
                    <div class="showing_output" id='showing_output'>
                        <div class="options_menus">
                            <button class="downloadthis">Download</button>
                            <button class="closethis" onclick="closing_windows('showing_output')"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="contents tobedownload">
                            <div class="introduction">
                                <h2>Sachin Acharya Production</h2>
                                <h3>Bharatpur-2, Chitwan, Aanpatari</h3>
                                <h3>Regd No. 121231</h3>
                            </div>
                            <div class="basics">
                                <table>
                                    <tr>
                                        <td>REF. No</td>
                                        <td class='res-refno'></td>
                                    </tr>
                                    <tr>
                                        <td>Order By</td>
                                        <td class="res-customername"></td>
                                    </tr>
                                    <tr>
                                        <td>Date (Order)</td>
                                        <td class="res-order"></td>
                                    </tr>
                                    <tr>
                                        <td>Checked Date</td>
                                        <td class="res-checkdate"></td>
                                    </tr>
                                    <tr>
                                        <td>Total Received Amount</td>
                                        <td class="res-total"></td>
                                    </tr>
                                    <tr>
                                        <td>Total Cost</td>
                                        <td class="res-actual-cost"></td>
                                    </tr>
                                    <tr>
                                        <td>Debit/Credit</td>
                                        <td class="res-debit-credit"></td>
                                    </tr>
                                </table>

                            </div>
                            <div class="allitems">
                                <table>
                                    <tr>
                                        <td>Items</td>
                                        <td>Quantity</td>
                                        <td>Rate</td>
                                        <td>Cost</td>
                                        <td>Status</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="editing_content" id="editing_content">
                        <div class="innerDiv">
                            
                        </div>
                    </div>
                </div>
                <div class="of_inventory allboards">
                    <div class="controllers">
                        <select>
                            <option value="making_purchase">Show Options</option>
                            <option value="making_purchase">Make Purchase</option>
                            <option value="viewing_purchases">Show Purchase</option>
                            <option value="adding_products">Add Products</option>
                            <option value="showing_products">Show Products</option>
                        <select>
                        <!-- <p><button class="make_purchase" onclick="closer_look('making_purchase')">Make Purchase</button> <button class="show_purchases" onclick="closer_look('viewing_purchases')">Show Purchases</button> <button class='add_products' onclick="closer_look('adding_products')">Add Product</button></p> -->
                    </div>
                    <div class="allcontainers">
                        <div class="making-purchase" id="making_purchase">
                            <h1>Make Purchase</h1>
                            <div class="shopkeeperdetails">
                                <p>
                                    <input type="text" name="shopname" id="shopname" class="shopname" autocomplete="off" required="required" placeholder="Shopkeeper Name">
                                </p>
                                <p>
                                    <input type="text" name="shopaddr" id="shopaddr" class="shopaddr" autocomplete="off" required="required" placeholder="Shop Address"> 
                                </p>
                                <p>
                                    <select class="payment_type" id="payment_type">
                                        <option value="Cheque">Choose Payment Method</option>
                                        <option value="Cheque">Cheque</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </p>
                                <p>
                                    <select class="ispaid">
                                        <option value="yes">Have you paid, Already? (yes)</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </p>
                                <p>
                                    <select class="isDelivered">
                                        <option value="yes">Have you got delivered? (yes)</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </p>
                                <p>
                                    <input type="text" class="remark" placeholder="Amount Paid" required="required" autocomplete="off">
                                </p>
                            </div>
                            <div class="button">
                                <button onclick="create_new_tab()">ADD PRODUCT</button>
                            </div>
                            <div class="purchasing-items">
                                <div class="items" data-index="1">
                                    <input type="text" name="item-name" id="item-name" class="item-name" placeholder="Product Name" required="required" autocomplete="off">
                                    <select name="unit" id="unit" class="unit">
                                        <option value="regular">General (eg. 1 Biscuit)</option>
                                        <option value="kilo">Kilo</option>
                                        <option value="litre">Litre</option>
                                        <option value="dozan">Dozan</option>
                                    </select>
                                    <input type="text" name="quantity" id="quantity" class="quantity" placeholder="Quantity" required="required" autocomplete="off" onkeyup="update_total('1')">
                                    <input type="text" name="rate" id="item-rate" class="rate" placeholder="Rate per unit" required="required" autocomplete="off" onkeyup="update_total('1')">
                                    <p class="total">
                                        Total: 0/-
                                    </p>
                                </div>
                            </div>
                            <div class="purchase-btn">
                                <input type="submit" value="Make Purchase" class="send" id="send" name="send" onclick="make_purchase_please()">
                            </div>
                        </div>
                        <div class="viewing_purchases" id="viewing_purchases">
                            <h1>Viewing Purchased</h1>
                            <div class="buttons">
                                <select id='filtering_this_item'>
                                    <option value="all">Filter</option>
                                    <option value="all">Show All</option>
                                    <option value="incompleted">InCompleted Only</option>
                                    <option value="completed">Completed Only</option>
                                    <option value="deu">Left to Pay</option>
                                    <option value="delivery">Left to be Delivered</option>
                                </select>
                                <button onclick="data_view()">Show!</button>
                            </div>
                            <div class="containers">
                                
                            </div>
                        </div>
                        <div class="adding_products" id="adding_products">
                            <div class="innerHtml">
                                <h1>Add Product</h1>
                                <div class="details">
                                    <div class="items items_one">
                                        <p>
                                            <input type="text" class="prodname" placeholder="Product Name" required="required" autocomplete="none">
                                        </p>
                                        <p>
                                            <input type="text" class="instock" placeholder="Quantity InStock" required="required" autocomplete="none">
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="buttons">
                                <button onclick='create_add_product()'>Add Product</button>
                                <button onclick='save_product("saving")'>Save Product</button>
                            </div>
                        </div>
                        <div class="showing_products" id='showing_products'>
                            <h1>Product In Stock</h1>
                            <div class="details">
    
                            </div>
                        </div>
                    </div>
                    <div class="showing-container" id="data_lockdown">
                    </div>
                </div>
                <div class="of_customers allboards">
                    Hello World
                </div>
            </div>
        </div>
    </section>
    <?php
        // getting all the years in databases
        $query = mysqli_query($conn, "SELECT `year` FROM `transactions` ORDER BY `year` ASC");
        $final_array = [["Year", "Sales", "Expenses", "Profit"]];
        $on_current_year = '';
        $count = 0;
        while($date_rows = mysqli_fetch_array($query)){
            if($on_current_year != $date_rows[0]){
                $nowyear = $date_rows[0];
                // Sales: Orders
                $qone = mysqli_query($conn, "SELECT `ref`,`comtotal` FROM `orders` WHERE `year`='$nowyear'");
                $totalSales = 0;
                $now_ref = '';
                while($fortotal = mysqli_fetch_array($qone)){
                    if($now_ref != $fortotal[0]){
                        $totalSales += intval($fortotal[1]);
                        $now_ref = $fortotal[0];
                    }
                }
                // Expenses
                $qtwo = mysqli_query($conn, "SELECT `ref`,`total` FROM `purchases` WHERE `year`='$nowyear'");
                $totalexpenses = 0;
                $thisref = '';
                while($forexpenses = mysqli_fetch_array($qtwo)){
                    if($thisref != $forexpenses[0]){
                        $totalexpenses += intval($forexpenses[0]);
                        $thisref = $forexpenses[0];
                    }
                }
                $profit = intval($totalSales)-intval($forexpenses);
                $data_array = [$date_rows[0], $totalSales, $totalexpenses, $profit];
                array_push($final_array, $data_array);
                $on_current_year = $date_rows[0];
                $count++;
            }
        }
        $out = json_encode($final_array);
        echo $count;
        echo "<script>const coord = $out</script>";
    ?>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="/assets/js/dashboard.js"></script>
    <script src="/assets/js/createOrder.js"></script>
    <script type="module" src="/assets/js/chart.js"></script>
</body>
</html>