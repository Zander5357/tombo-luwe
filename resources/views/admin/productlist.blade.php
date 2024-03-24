 <?php
  session_start();
  include("../db.php");
  error_reporting(0);
  if (isset($_GET['action']) && $_GET['action'] != "" && $_GET['action'] == 'delete') {
    $product_id = $_GET['product_id'];
    ///////picture delete/////////
    $result = pg_query($con, "select product_image from products where product_id='$product_id'")
      or die("query is incorrect...");

    list($picture) = pg_fetch_array($result);
    $path = "../product_images/$picture";

    if (file_exists($path) == true) {
      unlink($path);
    } else {
    }
    /*this is delet query*/
    pg_query($con, "DELETE from products where product_id='$product_id'") or die("query is incorrect...");
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


     <div class="col-md-14">
       <div class="card ">
         <div class="card-header card-header-primary">
           <h4 class="card-title"> Products List</h4>

         </div>
         <div class="card-body">
           <div class="table-responsive ps">
             <table class="table tablesorter " id="page1">
               <thead class=" text-primary">
                 <tr>
                   <th>Image</th>
                   <th>Name</th>
                   <th>Price</th>
                   <th>
                     <a class=" btn btn-primary" href="addproduct.php">Add New</a>
                   </th>
                 </tr>
               </thead>
               <tbody>
                 <?php
                  // Query untuk mengambil data produk dengan kategori 2, 3, atau 4
                  $query = "SELECT product_id, product_image, product_title, product_price FROM products WHERE product_cat IN (2, 3, 4) OFFSET $page1 LIMIT 100";
                  $result = pg_query($con, $query) or die("Query failed: " . pg_last_error());

                  while ($row = pg_fetch_array($result)) {
                    $product_id = $row['product_id'];
                    $image = $row['product_image'];
                    $product_name = $row['product_title'];
                    $price = $row['product_price'];

                    echo "<tr>
    <td><img src='../product_images/$image' style='width:50px; height:50px; border:groove #000'></td>
    <td>$product_name</td>
    <td>$price</td>
    <td>
      <a class='btn btn-success' href='clothes_list.php?product_id=$product_id&action=delete'>Delete</a>
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
       <nav aria-label="Page navigation example">
         <ul class="pagination">
           <li class="page-item">
             <a class="page-link" href="#" aria-label="Previous">
               <span aria-hidden="true">&laquo;</span>
               <span class="sr-only">Previous</span>
             </a>
           </li>
           <?php
            //counting paging

            $paging = pg_query($con, "select product_id,product_image, product_title,product_price from products");
            $count = pg_num_rows($paging);

            $a = $count / 10;
            $a = ceil($a);

            for ($b = 1; $b <= $a; $b++) {
            ?>
             <li class="page-item"><a class="page-link" href="productlist.php?page=<?php echo $b; ?>"><?php echo $b . " "; ?></a></li>
           <?php
            }
            ?>
           <li class="page-item">
             <a class="page-link" href="#" aria-label="Next">
               <span aria-hidden="true">&raquo;</span>
               <span class="sr-only">Next</span>
             </a>
           </li>
         </ul>
       </nav>



     </div>


   </div>
 </div>
 <?php
  include "footer.php";
  ?>