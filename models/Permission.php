<?php
include './models/db.php';
class admin extends DB
{
    private $UserDataArray;
    private $allUsers;
    private $allUsernames;
    public $id;
    public function __construct($table)
    {
        parent::__construct($table);
        $statm = $this->pdo->prepare("SELECT id,username from users");
        $statm->execute();
        $this->allUsers = $statm->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getUsers()
    {
        echo "<table class='userTable'><tr><th>نام کاربری</th></tr>";
        foreach ($this->allUsers as $uselesskey => $this->UserDataArray) {
            echo "<tr> ";
            foreach ($this->UserDataArray as $key => $value) {
                $this->allUsernames .= "$value ,";
                if (is_int($value)) {
                    $this->id = $value;
                    echo "<td>$value</td>";
                }
                if (!is_int($value)) {
                    echo "<td>$value</td>
                    <td><a href='./admin.php?id=$this->id&action=edit' >ویرایش</a></td>
                    <td><a href='./admin.php?id=$this->id&action=delete' >حذف</a></td>";
                    echo "<tr>";
                }
            }
        }
        echo "</table>";
    }
    public function updateUser($newUserData)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET username = :username, password = :password, roule = :roule WHERE id = :id");
        $stmt->execute($newUserData);
    }
    public function deleteUser($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute($id);
    }
}
