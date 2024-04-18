<?php
    include("db_connection.php");
    include("User.php");
    include("Role.php");
    session_start();
    $message = "";
    $success_message = "";

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

    //Create a new Role object
    $role = new Role($connection);
    $roles = $role->getRoles();

    if (isset($_POST['create'])) {
        //Add user
        $response = json_decode($user->createUser($_POST));

        if($response->status){
            $userCreated = $response->result;

            //Create role for user
            $response = json_decode($role->setRole($_POST['role'], $userCreated));

            if(!$response->status){
                $message = $response->message;
            }
        }
        else{
            $message = $response->message;
        }

        $success_message = "User created successfully!";
    } 
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
                <div class="w-100 py-4">
                    <h4 class="mb-4 border-bottom">Create New User</h4>
                    <form action="" method="POST" name="create_user" id="create_user">
                        <?php 
                            if(!empty($success_message)){
                                echo "<div class='alert alert-success' role='alert'>".$success_message."</div>";
                            }
                        ?>
                        <?php echo "<p class='text-danger'>".$message."</p>" ?>
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <label for="firstName" class="form-label required">First name</label>
                                <input type="text" class="form-control" id="firstName" name="first_name"
                                    placeholder="First Name" value="">
                            </div>

                            <div class="col-sm-6">
                                <label for="lastName" class="form-label required">Last name</label>
                                <input type="text" class="form-control" id="lastName" name="last_name"
                                    placeholder="Last Name" value="">
                            </div>

                            <div class="col-12">
                                <label for="email" class="form-label required">Email</label>
                                <input type="email" class="form-control" name="email" id="email"
                                    placeholder="you@example.com">
                            </div>

                            <div class="col-12">
                                <label for="role" class="form-label required">Role</label>
                                <select class="form-select" id="role" name="role">
                                    <option value="">Select</option>
                                    <?php
                                        foreach($roles as $role){
                                            echo "<option value='".$role['id']."'>".$role['label']."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <button class="w-100 btn btn-primary btn-lg" type="submit" name="create">Create</button>
                    </form>
                </div>
            </main>
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
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}
</script>