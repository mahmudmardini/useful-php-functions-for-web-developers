<?php

//upload product images function
function uploadProductImages($productId, $targetDir,$time, $pdoConnection){
  $counter = 0;
  foreach($_FILES['images']['name'] as $key=>$val){

    //file extention
    $ext = pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION);

      // File upload path
      $fileName = ('img'.$counter.$time.'.'.$ext);
      $targetFilePath = $targetDir . $fileName;

      // Upload files to server
      if(move_uploaded_file($_FILES["images"]["tmp_name"][$key], $targetFilePath)){
        $insert = $pdoConnection->prepare("INSERT INTO product_images (ProductID, Image) VALUES ( :productId,:fileName ) ");
        $insert->execute(array( ':productId'=>$productId, ':fileName'=>$fileName));
      }else{
        // if some files not uploaded, show a message with not uploaded files
          $_SESSION["message"] .= $_FILES['images']['name'][$key].' | ';
      }
      $counter++;
  }
}


//change product images direction function
function changeImagesDirection($oldDirection, $newDirection, $productId, $pdoConnection){
  $oldDir = "img/".html_entity_decode($oldDirection)."/";
  $newDir = "img/".html_entity_decode($newDirection)."/";

  // sql statement
  $sql = "SELECT * FROM productImagesTable WHERE ProductID = :productId";

  // prepare statement
  $images_stmt = $pdoConnection->prepare($sql);
  // execute query
  $images_stmt->execute(array(":productId" => $productId));

  // change images direction on the server
  while ($images_row = $images_stmt->fetch(PDO::FETCH_ASSOC)) {
  rename($oldDir.$images_row['Image'], $newDir.$images_row['Image']);
  }
}

// delete a folder with its files
function delete_directory($dirname) {
     if (is_dir($dirname)){
      $dir_handle = opendir($dirname);
     }

     // return false if directory is not found
     if (!$dir_handle){
          return false;
     }

     // delete files included in mentioned directory
     while($file = readdir($dir_handle)) {
           if ($file != "." && $file != "..") {
                if (!is_dir($dirname."/".$file))
                     unlink($dirname."/".$file);
                else
                     delete_directory($dirname.'/'.$file);
           }
     }
     closedir($dir_handle);
     // remove the empty directory
     rmdir($dirname);
     return true;
}

// redirect to a specific file
function redirect($url){
  echo "<script type='text/javascript'> document.location = '".$url."'; </script>";
  return;
}

// get current url
function current_url(){
    $current_file = explode('/', $_SERVER["REQUEST_URI"]);
    $current_file = end($current_file);

    return $current_file;
  }

// compare current url to another
function compare_current_url($url){
  // get current file name
    $current_file = explode('/', $_SERVER["REQUEST_URI"]);
    $current_file = end($current_file);

  // compare current url to given one
    if( $current_file == $url) return true;
    return false;
  }
?>
