<?php
session_start();
include("../db.php");

error_reporting(0);
if (isset($_GET['action']) && $_GET['action'] != "" && $_GET['action'] == 'delete') {
  $order_id = $_GET['order_id'];

  /*this is delet query*/
  pg_query($con, "delete from orders where order_id='$order_id'") or die("delete query is incorrect...");
}

///pagination
$page = $_GET['page'];

if ($page == "" || $page == "1") {
  $page1 = 0;
} else {
  $page1 = ($page * 10) - 10;
}

include "sidenav.php";
include "topheader.php";
?>
<!-- End Navbar -->
<div class="content">
  <div class="container-fluid">
    <!-- your content here -->
    <div class="col-md-14">
      <div class="card ">
        <div class="card-header card-header-primary">
          <h4 class="card-title">Orders / Page <?php echo $page; ?> </h4>
        </div>
        <div class="card-body">
          <div class="table-responsive ps">
            <table class="table table-hover tablesorter " id="">
              <thead class=" text-primary">
                <tr>
                  <th>Nama Pelanggan</th>
                  <th>Products</th>
                  <th>Contact | Email</th>
                  <th>Alamat</th>
                  <th>Harga</th>
                  <th>Jumlah</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php
                $limit = 12; // Jumlah data per halaman
                $page1 = max(1, $page1); // Pastikan nilai $page1 tidak kurang dari 1
                $offset = ($page1 - 1) * $limit; // Menghitung offset

                $query = "SELECT * FROM orders, products, user_info WHERE orders.product_id = products.product_id AND user_info.user_id = orders.user_id";

                $result = pg_query($con, $query);

                while ($row = pg_fetch_array($result)) {
                  $order_id = $row['order_id'];
                  $p_names = $row['product_title'];
                  $cus_name = $row['first_name'];
                  $contact_no = $row['mobile'];
                  $email = $row['email'];
                  $address = $row['address1'];
                  $country = $row['address2'];
                  $details = $row['product_price'];
                  $zip_code = $row['address2'];
                  $time = $row['qty'];

                  echo "<tr>
    <td>$cus_name</td>
    <td>$p_names</td>
    <td>$email<br>$contact_no</td>
    <td>$address<br>$zip_code
    <br>$country</td>
    <td>$details</td>
    <td>$time</td>
    <td>
      <a class='btn btn-danger' href='orders.php?order_id=$order_id&action=delete'>Delete</a>
    </td>
  </tr>";
                }
                ?>
              </tbody>
            </table>
            <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
              <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
            </div>
            <div class="ps__rail-y" style="top: 0px; right: 0px;">
              <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
<?php
include "footer.php";
?>