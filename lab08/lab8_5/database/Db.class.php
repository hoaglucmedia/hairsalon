<?php
// Hàm tiện ích
class DB
{
    private $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Hàm select trả về mảng kết quả
    public function select($sql, $params = [])
    {
        $stm = $this->pdo->prepare($sql);
        $stm->execute($params);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    // Hàm thực thi insert/update/delete
    public function execute($sql, $params = [])
    {
        $stm = $this->pdo->prepare($sql);
        $stm->execute($params);
        return $stm->rowCount();
    }
}
