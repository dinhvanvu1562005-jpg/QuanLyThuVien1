<?php
// dao/CategoryDAO.php
class CategoryDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /** Lấy tất cả thể loại, sắp xếp theo tên */
    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Lấy 1 thể loại theo id */
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /** Thêm mới thể loại, trả về id */
    public function create(string $name): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$name]);
        return (int)$this->pdo->lastInsertId();
    }

    /** Cập nhật tên thể loại */
    public function update(int $id, string $name): bool
    {
        $stmt = $this->pdo->prepare("UPDATE categories SET name = ? WHERE id = ?");
        return $stmt->execute([$name, $id]);
    }

    /** Xóa thể loại */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
