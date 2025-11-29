<?php
// dao/CardDAO.php

class CardDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /** Lấy danh sách thẻ (kèm tên bạn đọc) */
    public function getAll(): array
    {
        $sql = "
            SELECT c.*, r.fullname AS reader_name, r.email, r.phone
            FROM cards c
            JOIN readers r ON c.reader_id = r.id
            ORDER BY c.created_at DESC
        ";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT c.*, r.fullname AS reader_name, r.email, r.phone
            FROM cards c
            JOIN readers r ON c.reader_id = r.id
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /** Danh sách bạn đọc chưa có thẻ (để cấp thẻ) */
    public function getReadersWithoutCard(): array
    {
        $sql = "
            SELECT r.*
            FROM readers r
            LEFT JOIN cards c ON c.reader_id = r.id
            WHERE c.id IS NULL
            ORDER BY r.fullname ASC
        ";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Cấp thẻ mới */
    public function create(
        int    $reader_id,
        string $card_code,
        ?string $issue_date,
        ?string $expire_date
    ): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO cards (reader_id, card_code, issue_date, expire_date, status, created_at)
            VALUES (?, ?, ?, ?, 'active', NOW())
        ");
        $stmt->execute([$reader_id, $card_code, $issue_date, $expire_date]);
        return (int)$this->pdo->lastInsertId();
    }

    /** Cập nhật thông tin thẻ */
    public function update(
        int    $id,
        string $card_code,
        ?string $issue_date,
        ?string $expire_date
    ): bool {
        $stmt = $this->pdo->prepare("
            UPDATE cards
            SET card_code = ?, issue_date = ?, expire_date = ?
            WHERE id = ?
        ");
        return $stmt->execute([$card_code, $issue_date, $expire_date, $id]);
    }

    /** Khóa / mở thẻ */
    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->pdo->prepare("UPDATE cards SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    /** Thống kê đơn giản */
    public function getStats(): array
    {
        $sql = "
            SELECT 
                COUNT(*) AS total_cards,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) AS active_cards,
                SUM(CASE WHEN status = 'locked' THEN 1 ELSE 0 END) AS locked_cards
            FROM cards
        ";
        return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC) ?: [
            'total_cards'  => 0,
            'active_cards' => 0,
            'locked_cards' => 0,
        ];
    }
}
