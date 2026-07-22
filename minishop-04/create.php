<?php
require 'config.php';

$error = '';

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
            $stmt = db()->prepare('INSERT INTO categories (name, description) VALUES (?, ?)');
            $stmt->execute([$name, $description ?: null]);
            
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
    <title>Thêm danh mục</title>
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
            border-bottom: 3px solid #28a745;
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
            background-color: #28a745;
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
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>➕ Thêm danh mục</h1>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="name">Tên danh mục <span style="color: red;">*</span></label>
                <input type="text" id="name" name="name" placeholder="VD: Tai nghe..." value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea id="description" name="description" placeholder="Mô tả chi tiết..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>
            
            <div class="btn-group">
                <button type="submit">✅ Thêm</button>
                <a href="list.php" class="btn-cancel">❌ Hủy</a>
            </div>
        </form>
    </div>
</body>
</html>