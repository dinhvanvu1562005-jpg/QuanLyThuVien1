<?php
// dao/StatisticsDAO.php
class StatisticsDAO
{
    private PDO $pdo;
    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    public function getBookTotals(): array
    {
        $sql = "SELECT COUNT(*) AS titles, SUM(total) AS copies FROM books";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['titles' => 0, 'copies' => 0];
    }

    public function getTopBorrowedBooks(int $limit = 10): array
    {
        $sql = "
            SELECT 
                b.id, b.title,
                COUNT(br.id) AS times_borrowed
            FROM books b
            LEFT JOIN borrow br ON b.id = br.book_id
            GROUP BY b.id
            ORDER BY times_borrowed DESC
            LIMIT ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTopReaders(int $limit = 10): array
    {
        $sql = "
            SELECT 
                r.id, r.fullname,
                COUNT(br.id) AS times_borrow
            FROM readers r
            LEFT JOIN borrow br ON r.id = br.reader_id
            GROUP BY r.id
            ORDER BY times_borrow DESC
            LIMIT ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
