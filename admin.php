<?php
require __DIR__ . '/config.php';
require_role('owner');
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_FILES['qr'])) {
    if (save_qr_upload($_FILES['qr'])) { $msg = 'QR saved'; } else { $msg = 'Invalid QR image'; }
  }
}
$qr = qr_path();
$deposits = get_deposits();
?>
<!DOCTYPE html>
<html lang="hi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <div class="card">
      <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2>Admin Panel</h2>
        <a class="btn danger" href="logout.php">Logout</a>
      </div>
      <div class="space"></div>
      <div class="grid">
        <div class="field">
          <label>Current QR</label>
          <div class="qr"><?php if ($qr) { ?><img src="<?php echo htmlspecialchars($qr); ?>" alt="QR"><?php } else { echo 'No QR'; } ?></div>
        </div>
        <form method="post" enctype="multipart/form-data">
          <div class="field">
            <label>Upload New QR</label>
            <input class="input" type="file" name="qr" accept="image/*" required>
          </div>
          <button class="btn success" type="submit">Save QR</button>
        </form>
      </div>
      <div class="space"></div>
      <p><strong>Total Deposits:</strong> <?php echo count($deposits); ?></p>
      <table class="table">
        <thead>
          <tr><th>Name</th><th>Mobile</th><th>Time</th><th>Screenshot</th></tr>
        </thead>
        <tbody>
          <?php if (!$deposits) { ?>
            <tr><td colspan="4" class="center muted">No deposits yet</td></tr>
          <?php } else { foreach ($deposits as $d) { ?>
            <tr>
              <td><?php echo htmlspecialchars($d['userName']); ?></td>
              <td><?php echo htmlspecialchars($d['userMobile']); ?></td>
              <td><?php echo htmlspecialchars(date('d M Y, h:i A', strtotime($d['uploadedAt']))); ?></td>
              <td><a class="btn" target="_blank" href="<?php echo 'uploads/' . htmlspecialchars($d['fileName']); ?>">View</a></td>
            </tr>
          <?php } } ?>
        </tbody>
      </table>
      <div class="space"></div>
      <div class="muted"><?php echo htmlspecialchars($msg); ?></div>
    </div>
  </div>
</body>
</html>
