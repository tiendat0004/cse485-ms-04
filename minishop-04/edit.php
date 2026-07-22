<?php
require 'config.php';

$id = (int)($_GET['id'] ?? 0);
$error = '';
$category = null;

// Lấy dữ liệu danh mục
try {
    $stmt = db()->prepare('SELECT id, name, description FROM categories WHERE id = ?');
    $stmt->execute([$id]);
    $category = $stmt->fetch();
} catch (PDOException $e) {
    $error = '❌ Lỗi: ' . htmlspecialchars($e->getMessage());
}

// Kiểm tra danh mục có tồn tại không
if ($category === null) {
    http_response_code(404);
    die('<h1>❌ Danh mục không tồn tại</h1>');
}

// Xử lý form POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    // Validate
    if (strlen($name) < 2) {
        $error = '❌ Tên phải ít nhất 2 ký tự';
    } elseif (strlen($name) > 100) {
        $error = '❌ Tên không vượt quá 100 ký tự';
    } else {
        try {
            $stmt = db()->prepare('UPDATE categories SET name = ?, description = ? WHERE id = ?');
            $stmt->execute([$name, $description ?: null, $id]);
            
            // Redirect về list.php
            header('Location: list.php?success=1');
            exit;
        } catch (PDOException $e) {
            // Bắt lỗi UNIQUE constraint (trùng tên)
            if ($e->getCode() === '23000') {
                $error = '❌ Danh mục "' . htmlspecialchars($name) . '" đã tồn tại!';
            } else {
                $error = '❌ Lỗi: ' . htmlspecialchars($e->getMessage());
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa danh mục</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #17a2b8;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            font-family: Arial;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        .btn-group {
            display: flex;
            gap: 10px;
        }
        button, .btn-cancel {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            text-align: center;
        }
        button {
            background-color: #17a2b8;
            color: white;
        }
        .btn-cancel {
            background-color: #6c757d;
            color: white;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>✏️ Sửa danh mục (ID: <?= $category['id'] ?>)</h1>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="name">Tên danh mục <span style="color: red;">*</span></label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($category['name']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea id="description" name="description"><?= htmlspecialchars($category['description'] ?? '') ?></textarea>
            </div>
            
            <div class="btn-group">
                <button type="submit">✅ Cập nhật</button>
                <a href="list.php" class="btn-cancel">❌ Hủy</a>
            </div>
        </form>
    </div>
</body>
</html>