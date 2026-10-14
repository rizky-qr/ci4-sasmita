<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'User') ?> — SASMITA</title>
</head>
<body>
    <h1><?= esc($title ?? 'User') ?></h1>
    <?php if (isset($user)): ?>
        <p>Nama: <?= esc($user['nama']) ?></p>
        <p>Email: <?= esc($user['email']) ?></p>
        <p>Role: <?= esc($user['role']) ?></p>
    <?php endif ?>

    <?php if (isset($users)): ?>
        <ul>
        <?php foreach ($users as $u): ?>
            <li><?= esc($u['nama']) ?> — <?= esc($u['role']) ?></li>
        <?php endforeach ?>
        </ul>
    <?php endif ?>
</body>
</html>
