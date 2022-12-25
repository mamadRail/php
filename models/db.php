<?php
class DB
{
    protected $pdo = null;
    protected $host = 'localhost';
    protected $db = 'train';
    protected $username = 'root';
    protected $password = '';
    protected $table;

    public function __construct($table_name)
    {
        $this->table = $table_name;
        $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->db}", $this->username, $this->password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function create(array $data)
    {
        $fields = join(",", array_keys($data));
        $params = join(",", array_map(fn ($item) => ":$item", array_keys($data)));

        $statm = $this->pdo->prepare("insert into {$this->table} ({$fields}) values ({$params})");
        return $statm->execute($data);
    }
    public function get($fieldName, array $data)
    {
        $param = join(",", array_map(fn ($item) => ":$item", array_keys($data)));
        $statm = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE {$fieldName} = {$param}");
        $statm->execute($data);
        return $statm->fetchAll(PDO::FETCH_ASSOC);
    }
}
