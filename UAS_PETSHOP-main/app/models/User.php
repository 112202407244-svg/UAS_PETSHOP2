<?php

class User extends model
{
    protected string $table = 'users';

    public function findByEmail(string $email): array|false
    {
        return $this->db->fetchOne(
            "SELECT * FROM users WHERE email = ?",
            [$email]        
        );
    }
}

