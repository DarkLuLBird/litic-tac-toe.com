<?php

namespace application\models;

use application\core\Model;

class Profile extends Model
{
    public function getRating()
    {
        $id = $_SESSION['authorize']['id'];
        
        $params = [
            'id' => $id,
        ];
        $sql = "SELECT `rating` FROM users WHERE id = :id";
        $data = $this->db->select($sql, $params);
        return $data['rating'];
    }

    public function getStatistics()
    {
        # code...
    }
}