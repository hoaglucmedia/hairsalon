<?php
class Book
{
    private $pdo;

    // Khởi tạo với đối tượng PDO
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Lấy danh sách sách
     * @param int|null $cat_id  Nếu truyền cat_id thì lọc theo loại, ngược lại lấy tất cả
     * @return array            Mảng kết quả
     */
    public function listByCat($cat_id = null)
    {
        if ($cat_id) {
            $sql = "SELECT book_id, book_name, price, img, description
                    FROM book
                    WHERE cat_id = :cat_id
                    ORDER BY book_name";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([":cat_id" => $cat_id]);
        } else {
            $sql = "SELECT book_id, book_name, price, img, description
                    FROM book
                    ORDER BY book_name";
            $stm = $this->pdo->query($sql);
        }
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    public function listByPub($pub_id = null)
    {
        if ($pub_id) {
            $sql = "SELECT book_id, book_name, price, img, description
                    FROM book
                    WHERE pub_id = :pub_id
                    ORDER BY book_name";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([":pub_id" => $pub_id]);
        } else {
            $sql = "SELECT book_id, book_name, price, img, description
                    FROM book
                    ORDER BY book_name";
            $stm = $this->pdo->query($sql);
        }
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
}
