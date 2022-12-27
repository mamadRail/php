<?php
include './models/db.php';
class admin extends DB
{
    private $UserDataArray;
    private $allUsers;
    private $allUsernames;
    private $id;
    public function __construct($table)
    {
        parent::__construct($table);
        $statm = $this->pdo->prepare("SELECT id,username,role from users");
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
                }
                echo "<td>$value</td>";
            }
            echo "<td><a href='./admin.php?id=$this->id&action=edit' >ویرایش</a></td>";
            echo "<td><a href='./admin.php?id=$this->id&action=delete' >حذف</a></td>";
            echo "<tr>";
        }
        echo "<td><a href='./admin.php?action=create' >ایجاد کاربر</a></td>";
        echo "</table>";
    }
    public function createUser(array $data)
    {
        $fields = join(",", array_keys($data));
        $params = join(",", array_map(fn ($item) => ":$item", array_keys($data)));

        $statm = $this->pdo->prepare("insert into {$this->table} ({$fields}) values ({$params})");
        return $statm->execute($data);
    }
    public function updateUser($newUserData)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET username = :username, password = :password, role = :role WHERE id = :id");
        $stmt->execute($newUserData);
    }
    public function deleteUser($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute($id);
    }
    public function showProfessor()
    {
        $checkbox_Professor = [];
        $statm = $this->pdo->prepare("SELECT id,username from users WHERE role = 'Professor'");
        $statm->execute();
        $result = $statm->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $uslesskey => $key) {
            foreach ($key as $key => $value) {
                if (is_int($value)) {
                    $id = $value;
                }
                if (is_string($value)) {
                    echo "<p><input type='checkbox' name='checkbox_Professor[]' id='$value' value='$id'>";
                    echo "<label for='$value'>$value</label></p>";
                }
            }
        }
    }
}
class saveCourse extends DB
{

    public function createCourse($course, $Professor)
    {
        if ($Professor && $course["course_text"]) {
            echo 'hi';
            $statm = $this->pdo->prepare("INSERT INTO {$this->table} (course_text) VALUES (:course_text)");
            $statm->execute($course);
            $statm = $this->pdo->prepare("SELECT course_id from {$this->table} ORDER BY course_id DESC LIMIT 1");
            $statm->execute();
            $result = $statm->fetchAll(PDO::FETCH_ASSOC);
            $C_id = array_shift($result);
            parent::__construct('users_course');
            foreach ($Professor as $unlesskey => $value) {
                $statm = $this->pdo->prepare("INSERT INTO {$this->table} (id,course_id) VALUES (?,?)");
                $statm->execute([$value, $C_id["course_id"]]);
            }
        }
    }
}
