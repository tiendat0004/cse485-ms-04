<?php

/**
 * Hàm kết nối cơ sở dữ liệu
 * Chỉ tạo 1 kết nối duy nhất trong suốt chương trình
 */
function db()
{
    static $pdo = null;

    if ($pdo === null) {

        $host = "127.0.0.1";
        $dbname = "minishop_cse485";
        $username = "root";
        $password = "";

        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

        try {

            $pdo = new PDO(
                $dsn,
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );

        } catch (PDOException $e) {

            die("Không thể kết nối CSDL: " . $e->getMessage());

        }
    }

    return $pdo;
}