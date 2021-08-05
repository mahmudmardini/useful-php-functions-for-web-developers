<?php

// check user email
function check_email($email, $con){
  $read = $con->query("SELECT email FROM user_table");
    while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
      if(strtolower($email) == strtolower($row["email"])) return true;
    }
    return false;
}

// check user password
function check_pass($email, $pass, $con){
  $SALT_VALUE = 'YOUR_SALT';
  $hashed_password = hash('md5', $SALT_VALUE . $pass);
  $read = $con->prepare("SELECT password FROM user_table WHERE email = :email");
  $read->execute(array(':email'=>$email));

  $row = $read->fetch(PDO::FETCH_ASSOC);
      if($hashed_password === $row["Password"]) return true;

      return false;
}

function getIpAddress() {
  //get user ip address from share internet
    if (!empty($_SERVER['HTTP_CLIENT_IP'])){
      $IP = $_SERVER['HTTP_CLIENT_IP'];
    }
    //to check ip is pass from proxy
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
      $IP = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else{
      $IP = $_SERVER['REMOTE_ADDR'];
    }
    return $IP;
}

// user logout
function logout(){
    session_start();
    session_destroy();
    redirect("index");
  }

/* you can use this in pages those you want to limit access to them by specific user types
for example: when you write role('admin') in manage_products.php page,
just admins will be able to access this page.
be sure to store user role info in SESSION['role'] when they log in succesfully.
*/
function role($role){
      if(isset($_SESSION["role"]))
      if($_SESSION["role"] !== $role) die("ACCESS DENIED.");
}

// check if a specific user is admin or not
function isAdmin($email, $pdo){
    $read = $pdo->prepare("SELECT role FROM user_table WHERE email = :email");
    $read->execute(array(':email'=>$email));
    $row = $read->fetch(PDO::FETCH_ASSOC);

        if(strtolower($row["role"]) == 'admin') return true;
        return false;
  }

// check if a specific user is active or not
function isActive($email, $pdo){
  $read = $pdo->prepare("SELECT active FROM user_table WHERE email = :email");
  $read->execute(array(':email' => $email));
    while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
      if($row["active"]) return true;
    }
  return false;
}
?>
