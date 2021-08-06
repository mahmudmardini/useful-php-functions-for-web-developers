<?php

// check if a specific product has purchased or not
function hasPurchased($productId, $customerId, $pdoConnection){
  $sql = "SELECT * FROM sold_products_table WHERE ProductID = ? AND CustomerID = ?";

  // prepare statement
  $stmt = $pdoConnection->prepare($sql);

  // execute query
  $stmt->execute([$productId, $customerId]);

  // fetch data
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

// return true if the product was purchased from the mentioned customer
// else return false
if($productId === $row['ProductID']) return true;
  return false;
}


// delete product images
function deleteProductImages($productId, $targetDirectory, $pdoConnection){

  $images_stmt = $pdoConnection->prepare("SELECT * FROM product_images_table_name WHERE ProductID = :productId");
  $images_stmt->execute(array(":productId" => $productId));

  // delete images from server
  while ($images_row = $images_stmt->fetch(PDO::FETCH_ASSOC)) {
  unlink($targetDirectory.$images_row["ImageColumnName"]);
  }
}

// update product score
function updateProductScore($productId, $pdoConnection){
  // sql statement
  $sql = "SELECT * FROM ReviewsTable WHERE ProductID = ?";

  // prepare sql statement
  $reviews_stmt = $pdoConnection->prepare();
  // execute query
  $reviews_stmt->execute([$productId]);

  $score = 0;
  $ratesCount = 0;

  // set product score and rates count
  while ($reviews_row = $reviews_stmt->fetch(PDO::FETCH_ASSOC)) {
    $score += $reviews_row['Rate'];
    $ratesCount++;
  }

  // update product rating score 
  if ($score > 0){
    $score = $score / $ratesCount;
    $product_stmt = $pdoConnection->prepare("UPDATE productsTable SET ScoreColumn = ? WHERE ProductID = ?");
    $product_stmt->execute([$score, $productId]);
  }


 ?>
