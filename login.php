<?php
require __DIR__ . '/config.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $mobile = trim($_POST['mobile'] ?? '');
  $password = $_POST['password'] ?? '';
  if (!$mobile || !$password) {
    $msg = 'Please fill all fields';
  } else {
    $users = get_users();
    $user = null;
    foreach ($users as $u) { if ($u['mobile'] === $mobile) { $user = $u; break; } }
    if (!$user || !password_verify($password, $user['passwordHash'])) {
      $msg = 'Invalid mobile or password';
    } else {
      $_SESSION['user'] = ['name'=>$user['name'],'mobile'=>$user['mobile'],'role'=>$user['role']];
      if ($user['role'] === 'owner') { header('Location: admin.php'); exit; }
      header('Location: user.php'); exit;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="hi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <div class="card">
      <h2>Login</h2>
      <div class="space"></div>
      <form method="post" class="grid">
        <div class="field">
          <label for="mobile">Mobile Number</label>
          <input id="mobile" name="mobile" class="input" type="tel" placeholder="e.g. 9876543210" required>
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input id="password" name="password" class="input" type="password" placeholder="Your password" required>
        </div>
        <button class="btn accent" type="submit">Login</button>
        <a class="btn" href="register.php">Register</a>
      </form>
      <div class="space"></div>
      <div class="muted"><?php echo htmlspecialchars($msg); ?></div>
    </div>
  </div>
</body>
</html>
