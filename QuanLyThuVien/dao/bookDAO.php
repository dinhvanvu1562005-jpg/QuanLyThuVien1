<?php
// dao/BookDAO.php

class BookDAO
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // 1. Lấy 1 sách theo id
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM books WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    // 2. Tìm kiếm sách theo từ khóa (tiêu đề / tác giả / mã sách nếu có)
    public function search(string $keyword = ''): array
    {
        if ($keyword === '') {
            $stmt = $this->pdo->query("
                SELECT b.*, c.name AS category_name
                FROM books b
                LEFT JOIN categories c ON b.category_id = c.id
                ORDER BY b.title ASC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $kw = "%$keyword%";
        $stmt = $this->pdo->prepare("
            SELECT b.*, c.name AS category_name
            FROM books b
            LEFT JOIN categories c ON b.category_id = c.id
            WHERE b.title  LIKE :kw
               OR b.author LIKE :kw
            ORDER BY b.title ASC
        ");
        $stmt->execute([':kw' => $kw]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. Thêm sách mới
    public function insert(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO books (title, author, category_id, total, available, cover)
            VALUES (:title, :author, :category_id, :total, :available, :cover)
        ");

        $stmt->execute([
            ':title'       => $data['title'],
            ':author'      => $data['author'],
            ':category_id' => $data['category_id'] ?: null,
            ':total'       => $data['total'],
            ':available'   => $data['available'],
            ':cover'       => $data['cover'] ?? null, // đường dẫn ảnh bìa (nếu có)
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    // 4. Cập nhật sách
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE books
            SET title = :title,
                author = :author,
                category_id = :category_id,
                total = :total,
                available = :available,
                cover = :cover
            WHERE id = :id
        ");

        return $stmt->execute([
            ':title'       => $data['title'],
            ':author'      => $data['author'],
            ':category_id' => $data['category_id'] ?: null,
            ':total'       => $data['total'],
            ':available'   => $data['available'],
            ':cover'       => $data['cover'] ?? null,
            ':id'          => $id,
        ]);
    }

    // 5. Xóa sách (nếu không bị mượn)
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM books WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
