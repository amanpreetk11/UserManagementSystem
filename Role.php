<?php

class Role extends User {
	
    private $db;
    private $user;

    public function __construct($db, $user=null) {
        $this->db = $db;
        $this->user = $user;
    }

    public function getRoles() {
        $query = "select * from roles";
        $result = $this->db->query($query);

        $roles = array();
        while ($row = $result->fetch_assoc()) {
            $roles[] = $row;
        }

        return $roles;
    }

    public function setRole($role_id,$user){
        $user_id = $user->id;
        $query = "insert into user_role (user_id, role_id) VALUES ('$user_id', '$role_id')";
        $result = $this->db->query($query);
        
        if (!$result) {
            return json_encode(["status" => false, "message" => "Error creating role for user!"]);
        }

        return json_encode(["status" => true]);
    }

    public function getUserRole($user){
        $user_email = $user['email'];
        $query = "select r.name AS role FROM roles r INNER JOIN user_role ur ON r.id = ur.role_id INNER JOIN users u ON ur.user_id = u.id where u.email='$user_email'";
        $result = $this->db->query($query);

        while ($row = $result->fetch_assoc()) {
            $user_role = $row['role'];
        }

        return $user_role;
    }
}

?>