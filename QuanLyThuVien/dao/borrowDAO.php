<?php
// dao/BorrowDAO.php

class BorrowDAO
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Tạo phiếu mượn
    public function createBorrow(int $bookId, int $readerId, string $borrowDate, string $dueDate): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO borrow (book_id, reader_id, borrow_date, due_date, status)
            VALUES (:book_id, :reader_id, :borrow_date, :due_date, 'dang_muon')
        ");
        $stmt->execute([
            ':book_id'    => $bookId,
            ':reader_id'  => $readerId,
            ':borrow_date'=> $borrowDate,
            ':due_date'   => $dueDate,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    // Lấy 1 phiếu mượn
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM borrow WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    // Danh sách phiếu theo trạng thái
    public function listByStatus(string $status = 'dang_muon'): array
    {
        $sql = "
            SELECT 
                br.id,
                b.title    AS book_title,
                r.fullname AS reader_name,
                br.book_id,
                br.reader_id,
                br.borrow_date,
                br.due_date,
                br.return_date,
                br.status
            FROM borrow br
            JOIN books   b ON br.book_id   = b.id
            JOIN readers r ON br.reader_id = r.id
        ";

        $params = [];

        if ($status === 'dang_muon' || $status === 'qua_han' || $status === 'da_tra') {
            $sql .= " WHERE br.status = :st";
            $params[':st'] = $status;
        }

        $sql .= " ORDER BY br.borrow_date DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Trả sách
    public function returnBook(int $borrowId, string $returnDate): ?array
    {
        // Lấy phiếu mượn
        $stmt = $this->pdo->prepare("SELECT * FROM borrow WHERE id = ? AND status != 'da_tra'");
        $stmt->execute([$borrowId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        // Cập nhật trạng thái
        $stmt = $this->pdo->prepare("
            UPDATE borrow
            SET status = 'da_tra',
                return_date = :ret
            WHERE id = :id
        ");
        $stmt->execute([
            ':ret' => $returnDate,
            ':id'  => $borrowId,
        ]);

        return $row; // trả về để controller tăng available cho sách
    }
}
