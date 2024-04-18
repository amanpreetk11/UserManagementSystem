<?php

session_start(); 

include('db_connection.php');
$message = "";

if(isset($_SESSION['user'])){
  header("Location: dashboard.php");
  exit();
}

class Login {
    private $email;
    private $password;

    public function __construct($email, $password) {
        $this->email = $email; 
        $this->password = $password; 
    } 
    public function authenticate($enteredEmail, $enteredPassword) { 
      // Simulated authentication logic 
      if ($enteredEmail === $this->email && $enteredPassword === $this->password) { 
        return true; 
      } else { 
        return false; 
      } 
    }
} 

// Check if form is submitted 
if (isset($_POST['login'])) {
  $username = $_POST["email"];
  $password = $_POST["password"];

  //Get user
  $query=mysqli_query($connection, "select u.email, u.password, r.name AS role FROM users u INNER JOIN user_role ur ON u.id = ur.user_id INNER JOIN roles r ON ur.role_id = r.id where u.email='$username'");

  if($query){
    if (mysqli_num_rows($query)>0)
		{
      while ($rows = mysqli_fetch_array($query)) {
        $login_email = $rows['email'];
        $login_password = base64_decode($rows['password']);
        $login_role = $rows['role'];
      }

      // Create a user login instance
      $userLogin = new Login($login_email, $login_password); 
      // Authenticate user 
      if($userLogin->authenticate($username, $password)) {
        
        //Get user
        $query=mysqli_query($connection, "select * from users where email='$username'");
        while ($rows = mysqli_fetch_assoc($query)) {
          $user = $rows;
        }

        $_SESSION['user']=$user;
        $_SESSION['role']=$login_role;
        header("Location: dashboard.php");
        exit();
      } else { 
        $message = "Please check email & password.";
      }      
    }
		else
		{
			$message = "Email not registered";
		}
  }
  else{
    die("Query failed: " . mysqli_error($query));
  } 
} 

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
    <link rel="stylesheet" href="styles.css" />
</head>

<body class="d-flex align-items-center py-4" style="height: 100vh">
    <div class="form-signin w-100 m-auto">
        <form action="" method="POST" name="login_form" id="login_form">
            <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
            <?php echo "<p id='message' class='text-danger'>".$message."</p>"; ?>
            <div class="form-floating">
                <input type="email" class="form-control" id="email" placeholder="name@example.com" name="email" />
                <label for="email">Email address</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="password" placeholder="Password" name="password" />
                <label for="password">Password</label>
            </div>
            <button class="btn btn-primary w-100 py-2" type="submit" name="login">
                Sign in
            </button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
</body>

</html>

<script>
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}
</script>