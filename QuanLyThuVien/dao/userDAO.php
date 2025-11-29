<?php
// dao/UserDAO.php
class UserDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /** Tìm user theo username (nếu cần dùng riêng) */
    public function findByUsername(string $username): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    /**
     * Tìm user theo username / email / phone
     * → dùng cho đăng nhập
     */
    public function findByIdentity(string $identity): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM users
            WHERE username = ? OR phone = ? OR email = ?
            LIMIT 1
        ");
        $stmt->execute([$identity, $identity, $identity]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    /** Kiểm tra trùng email hoặc số điện thoại */
    public function existsEmailOrPhone(?string $email, ?string $phone): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT 1 FROM users
            WHERE (email IS NOT NULL AND email = ?)
               OR (phone IS NOT NULL AND phone = ?)
            LIMIT 1
        ");
        $stmt->execute([$email, $phone]);
        return (bool)$stmt->fetchColumn();
    }

    /** Tạo user mới (có fullname, email, phone) */
    public function createWithContact(
        string  $fullname,
        ?string $email,
        ?string $phone,
        string  $username,
        string  $password,
        string  $role = 'thuthu'    // mặc định là thủ thư
    ): int {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("
            INSERT INTO users (fullname, email, phone, username, password, role, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, 'active', NOW())
        ");
        $stmt->execute([$fullname, $email, $phone, $username, $hash, $role]);

        return (int)$this->pdo->lastInsertId();
    }

    /** Đổi mật khẩu (nếu sau này cần) */
    public function updatePassword(int $id, string $newPassword): bool
    {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$hash, $id]);
    }

    /** Cập nhật trạng thái (active / locked ...) */
    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
}
