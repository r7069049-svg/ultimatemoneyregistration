<?php
require __DIR__ . '/config.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $mobile = trim($_POST['mobile'] ?? '');
  $password = $_POST['password'] ?? '';
  if (!$name || !$mobile || !$password || strlen($mobile) < 7) {
    $msg = 'Please fill all fields correctly';
  } else {
    $users = get_users();
    foreach ($users as $u) { if ($u['mobile'] === $mobile) { $msg = 'Mobile already registered'; break; } }
    if (!$msg) {
      $users[] = ['name'=>$name,'mobile'=>$mobile,'passwordHash'=>password_hash($password, PASSWORD_DEFAULT),'role'=>'user'];
      save_users($users);
      $msg = 'Registered! Please login.';
      header('Refresh: 1; url=login.php');
    }
  }
}
?>
<!DOCTYPE html>
<html lang="hi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <div class="card">
      <h2>Register</h2>
      <div class="space"></div>
      <form method="post" class="grid">
        <div class="field">
          <label for="name">Name</label>
          <input id="name" name="name" class="input" type="text" placeholder="Your name" required>
        </div>
        <div class="field">
          <label for="mobile">Mobile Number</label>
          <input id="mobile" name="mobile" class="input" type="tel" placeholder="e.g. 9876543210" required>
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input id="password" name="password" class="input" type="password" placeholder="Choose a password" required>
        </div>
        <button class="btn success" type="submit">Register</button>
        <a class="btn" href="login.php">Already registered? Login</a>
      </form>
      <div class="space"></div>
      <div class="muted"><?php echo htmlspecialchars($msg); ?></div>
    </div>
  </div>
</body>
</html>
