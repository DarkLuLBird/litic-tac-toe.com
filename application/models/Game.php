<?php

namespace application\models;

use application\core\Model;

class Game extends Model
{
    private $rows = 3;
    private $cols = 3;

    public function createGame()
    {
        $firstPlayerId = $_SESSION['authorize']['id'];
        $hash = md5($firstPlayerId . time());

        $params = [
            'firstPlayerId' => $firstPlayerId,
            'hash' => $hash,
            'id' => $firstPlayerId,
        ];
        $sql = "INSERT INTO games (`hash`, `first_player_id`, `active_player_id`) VALUES (:hash, :firstPlayerId, :id)";
        $this->db->query($sql, $params);

        $_SESSION['player'] = 1;

        return $hash;
    }

    public function joinGame($post)
    {
        $hash = $post['identifier'];
        $secondPlayerId = $_SESSION['authorize']['id'];

        $params = [
            'hash' => $hash,
        ];

        $sql = "SELECT `second_player_id` FROM games WHERE hash = :hash";
        $data = $this->db->select($sql, $params);
        if(empty($data['second_player_id'])){
            $_SESSION['player'] = 2;

            $params = [
                'hash' => $hash,
                'secondPlayerId' => $secondPlayerId,
            ];
            $sql = "UPDATE games SET `second_player_id` = :secondPlayerId WHERE `hash` = :hash";
            $this->db->query($sql, $params);

            return $hash;
        }
        return false;
    }

    public function getOpponent($get)
    {
        $opponentId = "";
        $hash = $get['hash'];
        $params = [
            'hash' => $hash,
        ];
        $sql = "SELECT `first_player_id`, `second_player_id` FROM games WHERE hash = :hash";
        $data = $this->db->select($sql, $params);

        if($_SESSION['authorize']['id'] == $data['first_player_id']){
            $opponentId = $data['second_player_id'];
        }
        else if($_SESSION['authorize']['id'] == $data['second_player_id']){
            $opponentId = $data['first_player_id'];
        }

        if(empty($opponentId)){
            return "your opponent is not ready";
        }
        
        $params = [
            'opponentId' => $opponentId,
        ];
        $sql = "SELECT `id`, `username` FROM users WHERE id = :opponentId";
        $data = $this->db->select($sql, $params);
        $_SESSION['opponentId'] = $data['id'];
        return $data['username'];
    }

    public function turn($post)
    {
        $turn = $post['turn'];
        $hash = $post['hash'];

        $field = '';
        
        $params = [
            'hash' => $hash,
        ];
        $sql = "SELECT `field`, `active_player_id` FROM games WHERE hash = :hash";
        $data = $this->db->select($sql, $params);
        $field = $data['field'];
        $activePlayerId = $data['active_player_id'];

        if($_SESSION['authorize']['id'] != $activePlayerId){
            return;
        }

        $winner = $this->checkWinner($field);
        if(!empty($winner)){
            $gameOver = '-1';
            $params = [
                'field' => $field,
                'hash' => $hash,
                'winnerId' => $winner,
                'gameOver' => $gameOver,
            ];
            $sql = "UPDATE games SET `field` = :field, `winner_id` = :winnerId, `active_player_id` = :gameOver WHERE `hash` = :hash";
            $this->db->query($sql, $params);
        }else{
            $turnValue = $_SESSION['player'];
            $field[$turn] = $turnValue;
    
            $params = [
                'field' => $field,
                'hash' => $hash,
                'opponentId' => $_SESSION['opponentId'],
            ];
            $sql = "UPDATE games SET `field` = :field, `active_player_id` = :opponentId WHERE `hash` = :hash";
            $this->db->query($sql, $params);

        }
        
    }

    public function getField($hash)
    {
        $params = [
            'hash' => $hash,
        ];
        $sql = "SELECT `field` FROM games WHERE hash = :hash";
        $data = $this->db->select($sql, $params);

        $field = $data['field'];
        return $this->fieldToArray($field);
    }

    private function fieldToArray($field)
    {
        $arrayField = [
            [0, 0, 0],
            [0, 0, 0],
            [0, 0, 0],
        ];

        $pos = 0;
        for($i = 0; $i < $this->rows; $i++){
            for($j = 0; $j < $this->cols; $j++){
                $arrayField[$i][$j] = $field[$pos];
                $pos++; 
            }
        }

        return $arrayField;
    }

    private function checkWinner($field){

        $field = $this->fieldToArray($field);

        $winner = '1';

        for($i = 0; $i < $this->rows; $i++){
            if($this->compareThree($field[$i][0], $field[$i][1], $field[$i][2])){
                $winner = $field[$i][0];
            }
        }

        for($i = 0; $i < $this->cols; $i++){
            if($this->compareThree($field[0][$i], $field[1][$i], $field[2][$i])){
                $winner = $field[0][$i];
            }
        }

        if($this->compareThree($field[0][0], $field[1][1], $field[2][2])){
            $winner = $field[0][0];
        }

        if($this->compareThree($field[0][2], $field[1][1], $field[2][0])){
            $winner = $field[0][2];
        }

        return $winner;
    }

    private function compareThree($a, $b, $c){
        if(($a == $b) and ($b == $c)){
            return true;
        }
        return false;
    }
}