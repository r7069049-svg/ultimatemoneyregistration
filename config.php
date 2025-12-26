<?php
$baseDir = __DIR__;
$dataDir = $baseDir . DIRECTORY_SEPARATOR . 'data';
$uploadDir = $baseDir . DIRECTORY_SEPARATOR . 'uploads';
$usersFile = $dataDir . DIRECTORY_SEPARATOR . 'users.json';
$depositsFile = $dataDir . DIRECTORY_SEPARATOR . 'deposits.json';
$qrFile = $dataDir . DIRECTORY_SEPARATOR . 'qr.png';
if (!is_dir($dataDir)) { mkdir($dataDir, 0777, true); }
if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
function read_json($file, $default) { if (!file_exists($file)) return $default; $c = file_get_contents($file); $j = json_decode($c, true); return is_array($j) ? $j : $default; }
function write_json($file, $data) { file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT)); }
session_start();
$users = read_json($usersFile, []);
$ownerMobile = '7379035467';
$ownerPass = '88888888';
$found = null;
foreach ($users as $u) { if (isset($u['mobile']) && $u['mobile'] === $ownerMobile) { $found = $u; break; } }
if (!$found) {
  $users[] = ['name'=>'Owner','mobile'=>$ownerMobile,'passwordHash'=>password_hash($ownerPass, PASSWORD_DEFAULT),'role'=>'owner'];
  write_json($usersFile, $users);
}
function get_users() { global $usersFile; return read_json($usersFile, []); }
function save_users($arr) { global $usersFile; write_json($usersFile, $arr); }
function get_deposits() { global $depositsFile; return read_json($depositsFile, []); }
function save_deposits($arr) { global $depositsFile; write_json($depositsFile, $arr); }
function current_user() { return isset($_SESSION['user']) ? $_SESSION['user'] : null; }
function require_role($role) { $u = current_user(); if (!$u || $u['role'] !== $role) { header('Location: login.php'); exit; } }
function qr_path() { global $qrFile; return file_exists($qrFile) ? 'data/qr.png' : null; }
function save_qr_upload($file) { global $qrFile; if (!$file || !isset($file['tmp_name'])) return false; $mime = mime_content_type($file['tmp_name']); if (strpos((string)$mime,'image/') !== 0) return false; return move_uploaded_file($file['tmp_name'], $qrFile); }
function safe_image_upload($file, $uploadDir) {
  if (!$file || !isset($file['tmp_name'])) return null;
  $mime = mime_content_type($file['tmp_name']);
  if (strpos((string)$mime,'image/') !== 0) return null;
  $ext = 'png';
  if (isset($file['name'])) {
    $pi = pathinfo($file['name']);
    if (isset($pi['extension'])) $ext = strtolower($pi['extension']);
  }
  $name = time() . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
  $path = $uploadDir . DIRECTORY_SEPARATOR . $name;
  if (move_uploaded_file($file['tmp_name'], $path)) return $name;
  return null;
}
