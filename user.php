<?php
require __DIR__ . '/config.php';
require_role('user');
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $file = $_FILES['screenshot'] ?? null;
  $name = safe_image_upload($file, $uploadDir);
  if (!$name) {
    $msg = 'Please upload a valid image';
  } else {
    $deposits = get_deposits();
    $u = current_user();
    $deposits[] = [
      'id' => time() . '-' . bin2hex(random_bytes(4)),
      'userMobile' => $u['mobile'],
      'userName' => $u['name'],
      'uploadedAt' => date('c'),
      'fileName' => $name
    ];
    save_deposits($deposits);
    $msg = 'Submitted! Thank you.';
  }
}
$qr = qr_path();
?>
<!DOCTYPE html>
<html lang="hi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Deposit</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <div class="card">
      <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2>Deposit</h2>
        <a class="btn danger" href="logout.php">Logout</a>
      </div>
      <div class="space"></div>
      <div class="grid" style="grid-template-columns: repeat(auto-fit,minmax(280px,1fr)); align-items: start;">
        <div>
          <div class="qr"><?php if ($qr) { ?><img src="<?php echo htmlspecialchars($qr); ?>" alt="QR"><?php } else { echo 'SCAN QR'; } ?></div>
          <p class="muted center">Scan and pay, then upload screenshot</p>
        </div>
        <div>
          <form method="post" enctype="multipart/form-data">
            <div class="field">
              <label for="screenshot">Upload Payment Screenshot</label>
              <input id="screenshot" name="screenshot" class="input" type="file" accept="image/*" required>
            </div>
            <div class="space"></div>
            <button class="btn success" type="submit">Submit Deposit</button>
          </form>
          <div class="space"></div>
          <div class="muted"><?php echo htmlspecialchars($msg); ?></div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
