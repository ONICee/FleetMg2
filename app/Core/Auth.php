<?php
namespace Core;

use PDO;

class Auth
{
    private Session $session;
    private PDO $pdo;

    public function __construct(Session $session, PDO $pdo)
    {
        $this->session = $session;
        $this->pdo = $pdo;
    }

    public function user(): ?array
    {
        return $this->session->get('user');
    }

    public function check(): bool
    {
        return (bool) $this->user();
    }

    public function attempt(string $username, string $password): bool
    {
        $stmt = $this->pdo->prepare('SELECT u.id, u.username, u.password_hash, u.role_id, r.name AS role_name
                                      FROM users u JOIN roles r ON u.role_id = r.id
                                      WHERE u.username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password_hash'])) {
            $this->session->set('user', [
                'id'        => $user['id'],
                'username'  => $user['username'],
                'role_id'   => $user['role_id'],
                'role_name' => $user['role_name'],
            ]);
            return true;
        }
        return false;
    }

    public function logout(): void
    {
        $this->session->destroy();
    }

    public function hasRole(int $role): bool
    {
        return $this->check() && $this->user()['role_id'] === $role;
    }
}