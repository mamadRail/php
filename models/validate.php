<?php
class ValidateRegisrer
{
    public function Recive_post()
    {
        $username = isset($_POST['username']) && ($_POST['username'] != '') ? $_POST['username'] : null;
        $password = isset($_POST['password']) && ($_POST['password'] != '') ? $_POST['password'] : null;
        if (is_null($username) || is_null($password) || ctype_space($username) || ctype_space($password)) {
            return false;
        } else {

            return $user_data = ['username' => strtolower($username), 'password' => $password];
        }
    }
    public function Recive_get($value)
    {
        $result = isset($value) && ($value != '') ? $value : null;
        if (is_null($result) || is_null($result)) {
            return false;
        } else {

            return strtolower($result);
        }
    }
}
