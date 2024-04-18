<?php

class User {
	
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Data validation
    private function validateUserData($userData) {
        if (empty($userData['first_name']) || empty($userData['last_name']) || empty($userData['email']) || empty($userData['role'])) {
            return false;
        }

        return true;
    }

    // Data sanitization
    private function sanitizeUserData($userData) {
        $first_name = mysqli_real_escape_string($this->db, $userData['first_name']);
        $last_name = mysqli_real_escape_string($this->db, $userData['last_name']);
        $email = mysqli_real_escape_string($this->db, $userData['email']);
        $role = mysqli_real_escape_string($this->db, $userData['role']);

        return array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'role' => $role
        );
    }

    // Authentication checks
    public function authenticateUser($userId) {
        $query = "select r.name AS role FROM roles r INNER JOIN user_role ur ON r.id = ur.role_id INNER JOIN users u ON ur.user_id = u.id where u.id='$userId'";
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $user_role = $row['role'];
        }
        
        if ($user_role !== 'admin') {
            return json_encode(["status" => false, "message" => "Unauthorized access!"]);
        }

        return json_encode(["status" => true]);
    }

    //User create
    public function createUser($userData) {        
        $validatedData = $this->validateUserData($userData);

        if(!$validatedData){
            return json_encode(["status" => false, "message" => "Please fill required fields"]);
        }
        
        $sanitizedData = $this->sanitizeUserData($userData);

        $first_name = $sanitizedData['first_name'];
        $last_name = $sanitizedData['last_name'];
        $email = $sanitizedData['email'];
        $role = $sanitizedData['role'];

        $password = base64_encode(rand());
        
        $query = "insert into users (first_name, last_name, email, password) VALUES ('$first_name', '$last_name', '$email', '$password')";
        $result = $this->db->query($query);

        if (!$result) {
            return json_encode(["status" => false, "message" => "Error creating user!"]);
        }

        $insertedUserId = mysqli_insert_id($this->db);
        $newUser = $this->getUserWithoutRole($insertedUserId);

        //Implement email to send login details to newly created user
        //$this->sendEmail($newUser);

        return json_encode(["status" => true, "result" => $newUser]);
    }

    //Send email
    public function sendEmail($user){
        // Include PHPMailer autoload file path
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';

        // Create a new PHPMailer instance
        $mail = new PHPMailer\PHPMailer\PHPMailer();

        // SMTP Configuration (if sending via SMTP)
        // $mail->isSMTP();
        // $mail->Host = 'smtp.example.com';
        // $mail->SMTPAuth = true;
        // $mail->Username = 'your_smtp_username';
        // $mail->Password = 'your_smtp_password';
        // $mail->Port = 587; // Change port if necessary

        // Email Configuration
        $mail->setFrom('noreply@email.com', 'Admin');
        $mail->addAddress($user->email, $user->first_name." ".$user->last_name);
        $mail->Subject = 'Login Details';
        $mail->isHTML(true);
        $mail->Body = '<h4>Login Details</h4><br>
        <p>Email: '.$user->email.'</p><br>
        <p>Password: '.base64_decode($user->password).'</p>';

        // Send email
        if ($mail->send()) {
            return true;
        } else {
            return false;
        }
    }

    //Get User with Role
    public function getUser($userId) {       
        $query = "select u.*, r.id as role_id, r.name as role from users u INNER JOIN user_role ur on u.id = ur.user_id INNER JOIN roles r on ur.role_id = r.id where u.id='$userId'";
        $result = $this->db->query($query);

        while ($row = $result->fetch_assoc()) {
            $user = $row;
        }

        return $user;
    }

    //Get User without Role
    public function getUserWithoutRole($userId) {       
        $query = "select * from users where id='$userId'";
        $result = $this->db->query($query);

        while ($row = $result->fetch_assoc()) {
            $user = $row;
        }

        return $user;
    }

    //User update
    public function updateUser($userData, $userId) {
        $validatedData = $this->validateUserData($userData);

        if(!$validatedData){
            return json_encode(["status" => false, "message" => "Please fill required fields"]);
        }
        
        $sanitizedData = $this->sanitizeUserData($userData);
        
        $first_name = $sanitizedData['first_name'];
        $last_name = $sanitizedData['last_name'];
        $email = $sanitizedData['email'];
        $role = $sanitizedData['role'];
        
        $query = "update users set first_name='$first_name',last_name='$last_name',email='$email' where id='$userId'";
        $result = $this->db->query($query);
        
        if (!$result) {
            return json_encode(["status" => false, "message" => "Error updating user!"]);
        }

        $updatedUser = $this->getUserWithoutRole($userId);
        return json_encode(["status" => true, "result" => $updatedUser]);
    }

    //User delete
    public function deleteUser($userId) {
        $query = "delete from users where id='$userId'";
        $result = $this->db->query($query);
        if (!$result) {
            return json_encode(["status" => false, "message" => "Error deleting user!"]);
        }

        return json_encode(["status" => true]);
    }

    //Retrieve all users
    public function getUsers() {       
        $query = "select u.id, u.first_name, u.last_name, u.email, r.name AS role FROM users u INNER JOIN user_role ur ON u.id = ur.user_id INNER JOIN roles r ON ur.role_id = r.id ORDER BY u.id DESC";
        $result = $this->db->query($query);

        $users = array();
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        return $users;
    }
}

?>