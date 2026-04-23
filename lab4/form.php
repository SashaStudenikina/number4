<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Задание 4 — Форма с валидацией</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .card {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            padding: 30px;
        }
        h1 {
            font-size: 28px;
            color: #1e3c72;
            border-left: 5px solid #2a5298;
            padding-left: 15px;
            margin-bottom: 10px;
        }
        .sub {
            color: #555;
            margin-bottom: 25px;
            font-size: 14px;
            padding-left: 20px;
        }
        .form-group { margin-bottom: 20px; }
        label {
            font-weight: 600;
            display: block;
            margin-bottom: 6px;
            color: #1e2a3a;
        }
        .required { color: #e53e3e; }
        input, select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            transition: 0.2s;
        }
        input:focus, select:focus {
            border-color: #2a5298;
            outline: none;
            box-shadow: 0 0 0 3px rgba(42,82,152,0.2);
        }
        .error-field {
            border-color: #e53e3e !important;
            background-color: #fff5f5 !important;
        }
        .help-text {
            font-size: 12px;
            color: #718096;
            margin-top: 6px;
        }
        .radio-group {
            display: flex;
            gap: 25px;
            align-items: center;
            padding: 8px 0;
        }
        .radio-group label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: normal;
            cursor: pointer;
        }
        .radio-group input {
            width: 18px;
            height: 18px;
        }
        button {
            width: 100%;
            background: linear-gradient(95deg, #1e3c72, #2a5298);
            color: white;
            border: none;
            padding: 14px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 40px;
            cursor: pointer;
            transition: 0.2s;
            margin-top: 10px;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .message {
            padding: 12px 16px;
            border-radius: 16px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .message.success {
            background: #c6f6d5;
            color: #22543d;
            border-left: 5px solid #2f855a;
        }
        .message.error {
            background: #fed7d7;
            color: #742a2a;
            border-left: 5px solid #e53e3e;
        }
        hr { margin: 25px 0; border: none; border-top: 1px solid #e2e8f0; }
        .footer-info {
            background: #edf2f7;
            padding: 12px;
            border-radius: 16px;
            font-size: 13px;
            text-align: center;
            color: #2d3748;
        }
    </style>
</head>
<body>
<div class="card">
    <h1>📋 Задание 4</h1>
    <div class="sub">Валидация полей через PHP + Cookies (без JS)</div>

    <?php if (!empty($messages)): ?>
        <?php foreach ($messages as $msg): ?>
            <?= $msg ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <form method="POST" action="">
        <!-- ФИО -->
        <div class="form-group">
            <label>ФИО <span class="required">*</span></label>
            <input type="text" name="fio" value="<?= htmlspecialchars($values['fio'] ?? '') ?>"
                   class="<?= !empty($errors['fio']) ? 'error-field' : '' ?>"
                   placeholder="Иванов Иван Иванович">
            <div class="help-text">Только буквы (рус/англ), пробелы, дефис.</div>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label>Email <span class="required">*</span></label>
            <input type="email" name="email" value="<?= htmlspecialchars($values['email'] ?? '') ?>"
                   class="<?= !empty($errors['email']) ? 'error-field' : '' ?>"
                   placeholder="user@example.com">
            <div class="help-text">Формат: name@domain.ru</div>
        </div>

        <!-- Телефон -->
        <div class="form-group">
            <label>Телефон <span class="required">*</span></label>
            <input type="tel" name="phone" value="<?= htmlspecialchars($values['phone'] ?? '') ?>"
                   class="<?= !empty($errors['phone']) ? 'error-field' : '' ?>"
                   placeholder="+7 (123) 456-78-90">
            <div class="help-text">Цифры, +, -, пробелы, скобки. 10–20 символов.</div>
        </div>

        <!-- Дата рождения -->
        <div class="form-group">
            <label>Дата рождения <span class="required">*</span></label>
            <input type="date" name="birthdate" value="<?= htmlspecialchars($values['birthdate'] ?? '') ?>"
                   class="<?= !empty($errors['birthdate']) ? 'error-field' : '' ?>">
            <div class="help-text">Формат ГГГГ-ММ-ДД</div>
        </div>

        <!-- Пол -->
        <div class="form-group">
            <label>Пол <span class="required">*</span></label>
            <div class="radio-group">
                <label><input type="radio" name="gender" value="male" <?= (($values['gender'] ?? '') == 'male') ? 'checked' : '' ?>> Мужской</label>
                <label><input type="radio" name="gender" value="female" <?= (($values['gender'] ?? '') == 'female') ? 'checked' : '' ?>> Женский</label>
            </div>
        </div>

        <button type="submit">📨 Отправить</button>
    </form>

    <hr>
    <div class="footer-info">
        🔐 Ошибки хранятся в Cookies до закрытия браузера.<br>
        ✅ Успешные данные сохраняются в Cookies на 1 год.<br>
        🔁 Перезагрузка методом GET при ошибках.
    </div>
</div>
</body>
</html>