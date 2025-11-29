<?php
// dao/ReaderDAO.php

class ReaderDAO
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM readers WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function search(string $keyword = ''): array
    {
        if ($keyword === '') {
            $stmt = $this->pdo->query("
                SELECT * FROM readers
                ORDER BY fullname ASC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $kw = "%$keyword%";
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM readers
            WHERE fullname LIKE :kw
               OR email    LIKE :kw
               OR phone    LIKE :kw
            ORDER BY fullname ASC
        ");
        $stmt->execute([':kw' => $kw]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO readers (fullname, email, phone)
            VALUES (:fullname, :email, :phone)
        ");
        $stmt->execute([
            ':fullname' => $data['fullname'],
            ':email'    => $data['email'],
            ':phone'    => $data['phone'],
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE readers
            SET fullname = :fullname,
                email    = :email,
                phone    = :phone
            WHERE id = :id
        ");
        return $stmt->execute([
            ':fullname' => $data['fullname'],
            ':email'    => $data['email'],
            ':phone'    => $data['phone'],
            ':id'       => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        // Có thể kiểm tra nếu bạn đọc đang có phiếu mượn chưa trả thì không cho xóa
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM borrow
            WHERE reader_id = ? AND status IN ('dang_muon','qua_han')
        ");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) {
            return false;
        }

        $stmt = $this->pdo->prepare("DELETE FROM readers WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
