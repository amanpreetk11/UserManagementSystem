<?php
    include("db_connection.php");
    include("User.php");
    session_start();

    if(!isset($_SESSION['user'])){
        header("Location: index.php");
        exit();
    }

    // Create a new User object
    $user = new User($connection);

    //Check Authorization
    $result = json_decode($user->authenticateUser($_SESSION['user']['id']));
    if(!$result->status){
        $_SESSION['authorize_error'] = $result->message;
        header("Location: users.php");
        exit();
    }

    //Delete user data
    $response = json_decode($user->deleteUser($_GET['id']));
    
    if(!$response->status){
        $_SESSION['delete_error'] = $response->message;
    }

    $_SESSION['success_message'] = "User deleted successfully!";
    header("Location: users.php");
    exit();
?>

<script>
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}
</script>