<?php
require 'config.php';

try {
    $stmt = db()->query('SELECT id, name, description, created_at FROM categories ORDER BY id');
    $categories = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = '❌ Lỗi: ' . $e->getMessage();
    $categories = [];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh mục sản phẩm</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .btn-add {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 12px 24px;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 20px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table thead {
            background-color: #007bff;
            color: white;
        }
        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .btn {
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-edit {
            background-color: #17a2b8;
            color: white;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 Danh mục sản phẩm</h1>
        
        <a href="create.php" class="btn-add">➕ Thêm danh mục</a>
        
        <?php if (count($categories) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên danh mục</th>
                        <th>Mô tả</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td><?= htmlspecialchars($cat['id']) ?></td>
                            <td><?= htmlspecialchars($cat['name']) ?></td>
                            <td><?= htmlspecialchars($cat['description'] ?? '') ?></td>
                            <td><?= htmlspecialchars($cat['created_at']) ?></td>
                            <td>
                                <a href="edit.php?id=<?= $cat['id'] ?>" class="btn btn-edit">✏️ Sửa</a>
                                <form method="POST" action="delete.php" style="display: inline;">
                                    <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                                    <button type="submit" class="btn btn-delete" onclick="return confirm('Xóa?')">🗑️ Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Chưa có danh mục nào.</p>
        <?php endif; ?>
    </div>
</body>
</html>