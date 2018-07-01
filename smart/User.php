<?php


namespace smart;


class User
{
    private $_username;
    private $_password;

    public function IsLoggedIn(){
        session_start();
        $this->_username = isset($_SESSION['login']);
        session_write_close();
        return !empty($this->_username);
    }

    public function login($username, $password){
        $this->_username = $username;
        $this->_password = $password;
        if (!empty(array_filter(Application::$instance->config['access'], [$this, 'findUser']))){
            session_start();
            $_SESSION['login'] = $username;
            session_write_close();
            return true;
        }
        return false;
    }

    public function findUser($row){
        return $row['login'] == $this->_username && $row['password'] == md5($this->_password);
    }
}