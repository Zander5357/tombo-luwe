<?php

session_start();
$ip_add = getenv("REMOTE_ADDR");
include "db.php";

if (isset($_POST["category"])) {
    $category_query = "SELECT * FROM categories";
    $run_query = pg_query($con, $category_query);// or die(pg_result_error($con));

    echo "<div class='aside'>
            <h3 class='aside-title'>Categories</h3>
            <div class='btn-group-vertical'>";

    if (pg_num_rows($run_query) > 0) {
        $i = 1;
        while ($row = pg_fetch_assoc($run_query)) {
            $cid = $row["cat_id"];
            $cat_name = $row["cat_title"];
            $sql = "SELECT COUNT(*) AS count_items FROM products WHERE product_cat=$i";
            $query = pg_query($con, $sql);
            $row = pg_fetch_assoc($query);
            $count = $row["count_items"];
            $i++;

            echo "<div type='button' class='btn navbar-btn category' cid='$cid'>
                    <a href='#'>
                        <span></span>
                        $cat_name
                        <small class='qty'>($count)</small>
                    </a>
                  </div>";
        }
        echo "</div></div>";
    }
}

if (isset($_POST["brand"])) {
    $brand_query = "SELECT * FROM brands";
    $run_query = pg_query($con, $brand_query);

    echo "<div class='aside'>
            <h3 class='aside-title'>Brand</h3>
            <div class='btn-group-vertical'>";

    if (pg_num_rows($run_query) > 0) {
        $i = 1;
        while ($row = pg_fetch_assoc($run_query)) {
            $bid = $row["brand_id"];
            $brand_name = $row["brand_title"];
            $sql = "SELECT COUNT(*) AS count_items FROM products WHERE product_brand=$i";
            $query = pg_query($con, $sql);
            $row = pg_fetch_assoc($query);
            $count = $row["count_items"];
            $i++;

            echo "<div type='button' class='btn navbar-btn selectBrand' bid='$bid'>
                    <a href='#'>
                        <span></span>
                        $brand_name
                        <small>($count)</small>
                    </a>
                  </div>";
        }
        echo "</div></div>";
    }
}

if (isset($_POST["page"])) {
    $sql = "SELECT * FROM products";
    $run_query = pg_query($con, $sql);
    $count = pg_num_rows($run_query);
    $pageno = ceil($count / 9);

    // Get the current page number from the request
    $currentPage = isset($_POST["pageNumber"]) ? (int)$_POST["pageNumber"] : 1;

    for ($i = 1; $i <= $pageno; $i++) {
        $activeClass = ($i == $currentPage) ? 'active' : ''; // Add 'active' class only if it's the current page
        echo "<li><a href='#product-row' page='$i' id='page' class='$activeClass'>$i</a></li>";
    }
}


if (isset($_POST["get_seleted_Category"]) || isset($_POST["selectBrand"]) || isset($_POST["search"])) {
    $sql = "";

    if (isset($_POST["get_seleted_Category"])) {
        $id = $_POST["cat_id"];
        $sql = "SELECT * FROM products, categories WHERE product_cat = '$id' AND product_cat=cat_id";
    } elseif (isset($_POST["selectBrand"])) {
        $id = $_POST["brand_id"];
        $sql = "SELECT * FROM products, categories WHERE product_brand = '$id' AND product_cat=cat_id";
    } elseif (isset($_POST["search"])) {
        $keyword = $_POST["keyword"];
        header('Location:store.php');
        $sql = "SELECT * FROM products, categories WHERE product_cat=cat_id AND product_keywords LIKE '%$keyword%'";
    }

    $run_query = pg_query($con, $sql);

    while ($row = pg_fetch_assoc($run_query)) {
        $pro_id = $row['product_id'];
        $pro_cat = $row['product_cat'];
        $pro_brand = $row['product_brand'];
        $pro_title = $row['product_title'];
        $pro_price = $row['product_price'];
        $pro_image = $row['product_image'];
        $cat_name = $row["cat_title"];

        echo "<div class='col-md-4 col-xs-6'>
                <a href='product.php?p=$pro_id'>
                    <div class='product'>
                        <div class='product-img'>
                            <img src='product_images/$pro_image' style='max-height: 170px;' alt=''>
                            <div class='product-label'>
                                <span class='sale'>-30%</span>
                                <span class='new'>NEW</span>
                            </div>
                        </div>
                    </a>
                    <div class='product-body'>
                        <p class='product-category'>$cat_name</p>
                        <h3 class='product-name header-cart-item-name'>
                            <a href='product.php?p=$pro_id'>$pro_title</a>
                        </h3>
                        <h4 class='product-price header-cart-item-info'>
                            $pro_price<del class='product-old-price'>RP20000</del>
                        </h4>
                        <div class='product-rating'>
                            <i class='fa fa-star'></i>
                            <i class='fa fa-star'></i>
                            <i class='fa fa-star'></i>
                            <i class='fa fa-star'></i>
                            <i class='fa fa-star'></i>
                        </div>
                        <div class='product-btns'>
                            <button class='add-to-wishlist' tabindex='0'>
                                <i class='fa fa-heart-o'></i>
                                <span class='tooltipp'>add to wishlist</span>
                            </button>
                            <button class='add-to-compare'>
                                <i class='fa fa-exchange'></i>
                                <span class='tooltipp'>add to compare</span>
                            </button>
                            <button class='quick-view'>
                                <i class='fa fa-eye'></i>
                                <span class='tooltipp'>quick view</span>
                            </button>
                        </div>
                    </div>
                    <div class='add-to-cart'>
                        <button pid='$pro_id' id='product' href='#' tabindex='0' class='add-to-cart-btn'>
                            <i class='fa fa-shopping-cart'></i> add to cart
                        </button>
                    </div>
                </div>
            </div>";
    }
}

if (isset($_POST["getProduct"])) {
    $limit = 9;
    $start = isset($_POST["setPage"]) ? ($_POST["pageNumber"] * $limit) - $limit : 0;

    $product_query = "SELECT * FROM products, categories WHERE product_cat=cat_id LIMIT $limit";
    $run_query = pg_query($con, $product_query);

    if (pg_num_rows($run_query) > 0) {
        while ($row = pg_fetch_assoc($run_query)) {
            $pro_id = $row['product_id'];
            $pro_cat = $row['product_cat'];
            $pro_brand = $row['product_brand'];
            $pro_title = $row['product_title'];
            $pro_price = $row['product_price'];
            $pro_image = $row['product_image'];
            $cat_name = $row["cat_title"];

            echo "<div class='col-md-4 col-xs-6'>
                    <a href='product.php?p=$pro_id'>
                        <div class='product'>
                            <div class='product-img'>
                                <img src='product_images/$pro_image' style='max-height: 170px;' alt=''>
                                <div class='product-label'>
                                    <span class='sale'>-30%</span>
                                    <span class='new'>NEW</span>
                                </div>
                            </div>
                        </a>
                        <div class='product-body'>
                            <p class='product-category'>$cat_name</p>
                            <h3 class='product-name header-cart-item-name'>
                                <a href='product.php?p=$pro_id'>$pro_title</a>
                            </h3>
                            <h4 class='product-price header-cart-item-info'>
                                $pro_price<del class='product-old-price'>20.000 RB</del>
                            </h4>
                            <div class='product-rating'>
                                <i class='fa fa-star'></i>
                                <i class='fa fa-star'></i>
                                <i class='fa fa-star'></i>
                                <i class='fa fa-star'></i>
                                <i class='fa fa-star'></i>
                            </div>
                            <div class='product-btns'>
                                <button class='add-to-wishlist'>
                                    <i class='fa fa-heart-o'></i>
                                    <span class='tooltipp'>add to wishlist</span>
                                </button>
                                <button class='add-to-compare'>
                                    <i class='fa fa-exchange'></i>
                                    <span class='tooltipp'>add to compare</span>
                                </button>
                                <button class='quick-view'>
                                    <i class='fa fa-eye'></i>
                                    <span class='tooltipp'>quick view</span>
                                </button>
                            </div>
                        </div>
                        <div class='add-to-cart'>
                            <button pid='$pro_id' id='product' class='add-to-cart-btn block2-btn-towishlist' href='#'>
                                <i class='fa fa-shopping-cart'></i> add to cart
                            </button>
                        </div>
                    </div>
                </div>";
        }
    }
}

if (isset($_POST["addToCart"])) {
    $p_id = $_POST["proId"];
    $ip_add = getenv("REMOTE_ADDR");

    if (isset($_SESSION["uid"])) {
        $user_id = $_SESSION["uid"];
        $sql = "SELECT * FROM cart WHERE p_id = '$p_id' AND user_id = '$user_id'";
        $run_query = pg_query($con, $sql);
        $count = pg_num_rows($run_query);

        if ($count > 0) {
            echo "<div class='alert alert-warning'>
                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <b>Product is already added into the cart. Continue Shopping..!</b>
                  </div>";
        } else {
            $sql = "INSERT INTO cart (p_id, ip_add, user_id, qty) VALUES ('$p_id', '$ip_add', '$user_id', '1')";
            if (pg_query($con, $sql)) {
                echo "<div class='alert alert-success'>
                        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                        <b>Product is Added..!</b>
                      </div>";
            }
        }
    } else {
        $sql = "SELECT id FROM cart WHERE ip_add = '$ip_add' AND p_id = '$p_id' AND user_id = -1";
        $query = pg_query($con, $sql);

        if (pg_num_rows($query) > 0) {
            echo "<div class='alert alert-warning'>
                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <b>Product is already added into the cart. Continue Shopping..!</b>
                  </div>";
            exit();
        }

        $sql = "INSERT INTO cart (p_id, ip_add, user_id, qty) VALUES ('$p_id', '$ip_add', '-1', '1')";
        if (pg_query($con, $sql)) {
            echo "<div class='alert alert-success'>
                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <b>Your product is Added Successfully..!</b>
                  </div>";
            exit();
        }
    }
}

// Count User cart item
if (isset($_POST["count_item"])) {
    if (isset($_SESSION["uid"])) {
        $sql = "SELECT COUNT(*) AS count_item FROM cart WHERE user_id = $_SESSION[uid]";
    } else {
        $sql = "SELECT COUNT(*) AS count_item FROM cart WHERE ip_add = '$ip_add' AND user_id < 0";
    }

    $query = pg_query($con, $sql);
    $row = pg_fetch_assoc($query);
    echo $row["count_item"];
    exit();
}

// Get Cart Item From Database to Dropdown menu
if (isset($_POST["Common"])) {
    if (isset($_SESSION["uid"])) {
        $sql = "SELECT a.product_id, a.product_title, a.product_price, a.product_image, b.id, b.qty 
                FROM products a, cart b 
                WHERE a.product_id = b.p_id AND b.user_id = '$_SESSION[uid]'";
    } else {
        $sql = "SELECT a.product_id, a.product_title, a.product_price, a.product_image, b.id, b.qty 
                FROM products a, cart b 
                WHERE a.product_id = b.p_id AND b.ip_add = '$ip_add' AND b.user_id < 0";
    }
    $query = pg_query($con, $sql);

    if (isset($_POST["getCartItem"])) {
        if (pg_num_rows($query) > 0) {
            $n = 0;
            $total_price = 0;
            echo '<form action="checkout.php" method="post">';

            while ($row = pg_fetch_assoc($query)) {
                $n++;
                $product_id = $row["product_id"];
                $product_title = $row["product_title"];
                $product_price = $row["product_price"];
                $product_image = $row["product_image"];
                $qty = $row["qty"];
                $total_price += $product_price * $qty;

                // Pass product details and quantity directly
                echo '<input type="hidden" name="product_id_' . $n . '" value="' . $product_id . '">';
                echo '<input type="hidden" name="product_title_' . $n . '" value="' . $product_title . '">';
                echo '<input type="hidden" name="product_price_' . $n . '" value="' . $product_price . '">';
                echo '<input type="hidden" name="product_image_' . $n . '" value="' . $product_image . '">';
                echo '<input type="hidden" name="qty_' . $n . '" value="' . $qty . '">'; // <-- Pass quantity directly

                echo '<div class="product-widget">
                        <div class="product-img">
                            <img src="product_images/' . $product_image . '" alt="">
                        </div>
                        <div class="product-body">
                            <h3 class="product-name"><a href="#">' . $product_title . '</a></h3>
                            <h4 class="product-price"><span class="qty">' . $qty . 'x</span>$' . $product_price . '</h4>
                        </div>
                    </div>';
            }

            echo '<input type="hidden" name="total_price" value="' . $total_price . '">';
            echo '<button type="submit" class="btn btn-success">Ready to Checkout</button>';
            echo '</form>';
        } else {
            echo '<div class="alert alert-warning">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <b>Your cart is empty.</b>
              </div>';
        }
    }
}

if (isset($_POST["checkOutDetails"])) {
    if (pg_num_rows($query) > 0) {
        echo '<div class="main">
                <div class="table-responsive">
                    <table id="cart" class="table table-hover table-condensed">
                        <thead>
                            <tr>
                                <th style="width:50%">Product</th>
                                <th style="width:10%">Price</th>
                                <th style="width:8%">Quantity</th>
                                <th style="width:7%" class="text-center">Subtotal</th>
                                <th style="width:10%"></th>
                            </tr>
                        </thead>
                        <tbody>';

        while ($row = pg_fetch_assoc($query)) {
            $product_id = $row["product_id"];
            $product_title = $row["product_title"];
            $product_price = $row["product_price"];
            $product_image = $row["product_image"];
            $cart_item_id = $row["id"];
            $qty = $row["qty"];

            echo '<tr>
                    <td data-th="Product">
                        <div class="row">
                            <div class="col-sm-4">
                                <img src="product_images/' . $product_image . '" style="height: 70px; width: 75px;">
                                <h4 class="nomargin product-name header-cart-item-name">
                                    <a href="product.php?p=' . $product_id . '">' . $product_title . '</a>
                                </h4>
                            </div>
                            <div class="col-sm-6">
                                <div style="max-width=50px;">
                                    <p>Barang Ready gan</p>
                                </div>
                            </div>
                        </div>
                    </td>
                    <input type="hidden" name="product_ids[]" value="' . $product_id . '"/>
                    <input type="hidden" name="cart_item_id[]" value="' . $cart_item_id . '"/>
                    <td data-th="Price">
                        <input type="text" class="form-control price" value="' . $product_price . '" readonly="readonly">
                    </td>
                    <td data-th="Quantity">
                        <input type="text" class="form-control qty" name="prod_count[]" value="' . $qty . '">
                    </td>
                    <td data-th="Subtotal" class="text-center">
                        <input type="text" class="form-control total" name="total_amt[]" value="' . $product_price * $qty . '" readonly>
                    </td>
                    <td class="actions" data-th="">
                        <div class="btn-group">
                            <a href="#" class="btn btn-info btn-sm update" update_id="' . $product_id . '">
                                <i class="fa fa-refresh"></i>
                            </a>
                            <a href="#" class="btn btn-danger btn-sm remove" remove_id="' . $product_id . '">
                                <i class="fa fa-trash-o"></i>
                            </a>      
                        </div>                          
                    </td>
                </tr>';
        }

        echo '</tbody>
            </table>
        </div>
    </div>';

        if (!isset($_SESSION["uid"])) {
            echo '<div class="text-center">
                    <a href="login_form.php" class="btn btn-success">Log In to Checkout</a>
                  </div>';
        } else {
            echo '<form action="checkout.php" method="post">
                    <input type="submit" name="checkout" class="btn btn-success" value="Proceed to Checkout">
                  </form>';
        }
    }
}

// Remove Item From cart
if (isset($_POST["removeItemFromCart"])) {
    $remove_id = $_POST["rid"];
    if (isset($_SESSION["uid"])) {
        $sql = "DELETE FROM cart WHERE p_id = '$remove_id' AND user_id = '$_SESSION[uid]'";
    } else {
        $sql = "DELETE FROM cart WHERE p_id = '$remove_id' AND ip_add = '$ip_add'";
    }
    if (pg_query($con, $sql)) {
        echo "<div class='alert alert-danger'>
                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                <b>Product is removed from cart</b>
            </div>";
        exit();
    }
}

// Update Item From cart
if (isset($_POST["updateCartItem"])) {
    $update_id = $_POST["update_id"];
    $qty = $_POST["qty"];
    if (isset($_SESSION["uid"])) {
        $sql = "UPDATE cart SET qty='$qty' WHERE p_id = '$update_id' AND user_id = '$_SESSION[uid]'";
    } else {
        $sql = "UPDATE cart SET qty='$qty' WHERE p_id = '$update_id' AND ip_add = '$ip_add'";
    }
    if (pg_query($con, $sql)) {
        echo "<div class='alert alert-info'>
                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                <b>Product is updated</b>
            </div>";
        exit();
    }
}
