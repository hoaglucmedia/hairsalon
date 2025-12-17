<?php
class News
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Lấy danh sách tất cả tin tức
    public function list()
    {
        $sql = "SELECT id, title, content,short_content FROM news ";
        $stm = $this->pdo->query($sql);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết 1 tin theo ID
    public function detail($id)
    {
        $sql = "SELECT * FROM news WHERE id = :id";
        $stm = $this->pdo->prepare($sql);
        $stm->execute([":id" => $id]);
        return $stm->fetch(PDO::FETCH_ASSOC);
    }
}
