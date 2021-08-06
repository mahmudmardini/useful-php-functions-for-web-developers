<?php

// returns true if the row exists in database, and returns false if don't.
function rowExists($column, $table, $rowId, $pdo){
  // sql statement
  $sql = "SELECT $column FROM $table WHERE $column = ?";
  // prepare statement
  $stmt = $pdo->prepare($sql);

  // execute query
  $stmt->execute([$rowId]);
  // fetch data from database
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  // if row is exists return true, else return false
  if($rowId === $row[$column]) return true;
  else  return false;
}

// returns the number of the rows in a table
function rowsCount($table, $condition, $pdo){
  // sql statement
  $sql = "SELECT COUNT(*) FROM $table $condition";

  // execute query
  $counter_result = $pdo->query($sql);

  return $counter_result->fetchColumn();
}


// backup all database tables
function backup_tables($connection) {
    $tables = '*';
      $link = $connection;

      // Check connection
      // if (mysqli_connect_errno())
      // {
      //     $_SESSION['message'] = "Failed to connect to MySQL: " . mysqli_connect_error();
      //     exit;
      //     redirect(current_url());
      //     return;
      // }

      mysqli_query($link, "SET NAMES 'utf8'");

      //get all of the tables
      if($tables == '*')
      {
          $tables = array();
          $result = mysqli_query($link, 'SHOW TABLES');
          while($row = mysqli_fetch_row($result))
          {
              $tables[] = $row[0];
          }
      }
      else
      {
          $tables = is_array($tables) ? $tables : explode(',',$tables);
      }

      $return = '';
      //cycle through
      foreach($tables as $table)
      {
          $result = mysqli_query($link, 'SELECT * FROM '.$table);
          $num_fields = mysqli_num_fields($result);
          $num_rows = mysqli_num_rows($result);

          $return.= 'DROP TABLE IF EXISTS '.$table.';';
          $row2 = mysqli_fetch_row(mysqli_query($link, 'SHOW CREATE TABLE '.$table));
          $return.= "\n\n".$row2[1].";\n\n";
          $counter = 1;

          //Over tables
          for ($i = 0; $i < $num_fields; $i++)
          {   //Over rows
              while($row = mysqli_fetch_row($result))
              {
                  if($counter == 1){
                      $return.= 'INSERT INTO '.$table.' VALUES(';
                  } else{
                      $return.= '(';
                  }

                  //Over fields
                  for($j=0; $j<$num_fields; $j++)
                  {
                      $row[$j] = addslashes($row[$j]);
                      $row[$j] = str_replace("\n","\\n",$row[$j]);
                      if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
                      if ($j<($num_fields-1)) { $return.= ','; }
                  }

                  if($num_rows == $counter){
                      $return.= ");\n";
                  } else{
                      $return.= "),\n";
                  }
                  ++$counter;
              }
          }
          $return.="\n\n\n";
      }

      //save file
      $fileName = 'databaseBackUp';
      $date = date("d-m-Y");
      $extention = '.sql';
      $completeFileName = $fileName . $date . $extention;
      $handle = fopen( $completeFileName, 'w+');
      fwrite($handle,$return);
      if(fclose($handle)){
        return true;
      }
  }
 ?>
