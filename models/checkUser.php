<?php
// include './models/db.php';
class CheckUser extends DB
{
    protected $table = 'users';
    private $usernames;
    public function __construct()
    {
        parent::__construct($this->table);
    }
    private function joinUsername($valu)
    {
        foreach ($valu as $keys => $values) {

            $this->usernames[] .= strtolower($values);
        }
    }
    public function check($username)
    {
        $statm = $this->pdo->prepare("SELECT `username` FROM {$this->table}");
        $statm->execute();
        $stmtResult = $statm->fetchAll(PDO::FETCH_ASSOC);
        foreach ($stmtResult as $key => $value) {
            $this->joinUsername($value);
        }
        return in_array(strtolower($username), $this->usernames);
    }
    public function CheckLoged(array $storageData, $name, $pass)
    {
        if (in_array($name, $storageData)) {
            if (in_array($pass, $storageData)) {
                $_SESSION['userData'] = $storageData['username'];
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function checkPermission()
    {
        session_start();
        if (!empty($_SESSION)) {
            $userData = parent::get('username', $_SESSION);
            $userData = array_shift($userData);
            return $userData['roule'];
        } else {
            header('location: ../auth/register.php');
        }
    }
}
