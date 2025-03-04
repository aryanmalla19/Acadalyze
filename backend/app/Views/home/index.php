<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
</head>
<body>
    <h1><?php echo $title; ?></h1>
    <?php if (isset($users)): ?>
        <ul>
            <?php foreach ($users as $user): ?>
                <li><?php echo htmlspecialchars($user['first_name']); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php elseif (isset($param)): ?>
        <p>Parameter: <?php echo htmlspecialchars($param); ?></p>
    <?php endif; ?>
</body>
</html>