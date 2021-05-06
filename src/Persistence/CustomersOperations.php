<?php

namespace App\Persistence;

use PDO;

class CustomersOperations
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(string $firstname, string $lastname, string $email): int
    {
        $sql = "INSERT INTO customer VALUES (null, :firstname, :lastname, :email)";

        $query = $this->pdo->prepare($sql);

        $query->execute([
            "firstname" => $firstname,
            "lastname" => $lastname,
            "email" => $email
        ]);

        return $this->pdo->lastInsertId();
    }
}
