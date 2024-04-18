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

    // Fetch users
    $users = $user->getUsers();

    if(isset($_SESSION['delete_error']) && !empty($_SESSION['delete_error'])){
        $failure_message = $_SESSION['delete_error'];
    }
    else if(isset($_SESSION['success_message']) && !empty($_SESSION['success_message'])){
        $success_message = $_SESSION['success_message'];
    }
    else if(isset($_SESSION['authorize_error']) && !empty($_SESSION['authorize_error'])){
        $authorize_error = $_SESSION['authorize_error'];
    }

    unset($_SESSION['delete_error']);
    unset($_SESSION['success_message']);
    unset($_SESSION['authorize_error']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css" />
</head>

<body>
    <?php
        include('navbar.php');
    ?>

    <div class="container-fluid">
        <div class="row">
            <?php
                include('sidebar.php');
            ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-end align-items-center pt-3 pb-2 mb-3">
                    <a href="create_user.php" class="btn btn-primary rounded-pill px-3" type="button">Create User</a>
                </div>
                <?php 
                    if(!empty($success_message)){
                        echo "<div class='alert alert-success' role='alert'>".$success_message."</div>";
                    }
                ?>
                <?php 
                    if(!empty($failure_message)){
                        echo "<div class='alert alert-danger' role='alert'>".$failure_message."</div>";
                    }
                ?>
                <?php 
                    if(!empty($authorize_error)){
                        echo "<div class='alert alert-warning' role='alert'>".$authorize_error."</div>";
                    }
                ?>
                <div class="table-responsive medium">
                    <table class="table table-striped table-md">
                        <thead>
                            <tr>
                                <th scope="col">User ID</th>
                                <th scope="col">First Name</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if($users) {
                                    if (count($users)>0)
                                    {
                                        foreach($users as $user) {
                                            if($_SESSION['user']['id'] == $user['id']){
                                                $edit_delete = "<div class='d-flex justify-content-around align-items-center'><a href='update_user.php?id=".$user['id']."' class='btn btn-success rounded-pill px-3' type='button'>Edit</a><button data-user-id=".$user['id']." class='delete-user btn btn-danger rounded-pill px-3 disabled' data-toggle='modal' data-target='#deleteModal' type='button' disabled>Delete</button></div>";
                                            }
                                            else{
                                                $edit_delete = "<div class='d-flex justify-content-around align-items-center'><a href='update_user.php?id=".$user['id']."' class='btn btn-success rounded-pill px-3' type='button'>Edit</a><button data-user-id=".$user['id']." class='delete-user btn btn-danger rounded-pill px-3' data-toggle='modal' data-target='#deleteModal' type='button'>Delete</button></div>";
                                            }
                                            
                                            echo "<tr>
                                            <td>".$user['id']."</td>
                                            <td>".$user['first_name']."</td>
                                            <td>".$user['last_name']."</td>
                                            <td>".$user['email']."</td>
                                            <td>".$user['role']."</td>
                                            <td>".$edit_delete."</td>
                                            </tr>";
                                        }      
                                    }
                                    else
                                    {
                                        echo "<tr colspan='6'><td>No user present.</td></tr>";
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="DeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this user?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a id="delete-user-button" href="" type="button" class="btn btn-primary">Delete</a>
                </div>
            </div>
        </div>
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
$(".delete-user").on("click", function() {
    var user_id = $(this).data("user-id");
    $("#delete-user-button").attr("href", "delete_user.php?id=" + user_id);
});
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}
</script>