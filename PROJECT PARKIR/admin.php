<?php
/**
 * from/admin.php
 * -----------------
 * Halaman "TABEL USER" — kelola akun user. Hanya admin.
 * Sekarang dengan modal "Tambah User" dan dropdown Filter.
 */

require_once '../koneksi.php';
require_once '../auth/session.php';
requireRole(['admin']);

$activePage = 'management';
$q      = trim($_GET['q'] ?? '');
$sort   = $_GET['sort'] ?? '';   // 'asc' atau 'desc'
$role_f = $_GET['role'] ?? '';   // 'admin' atau 'petugas'
$stat_f = $_GET['status'] ?? ''; // 'aktif' atau 'nonaktif'

// Bangun query dinamis sesuai filter yang aktif
$where  = [];
$params = [];
$types  = '';

if ($q !== '') {
    $where[] = '(u.nama LIKE ? OR u.id_pengguna LIKE ?)';
    $params[] = "%{$q}%"; $params[] = "%{$q}%";
    $types .= 'ss';
}
if (in_array($role_f, ['admin', 'petugas'], true)) {
    $where[] = 'u.role = ?';
    $params[] = $role_f;
    $types .= 's';
}
if (in_array($stat_f, ['aktif', 'nonaktif'], true)) {
    $where[] = 'u.status = ?';
    $params[] = $stat_f;
    $types .= 's';
}

$orderBy = 'u.id_user ASC';
if ($sort === 'asc')  $orderBy = 'u.nama ASC';
if ($sort === 'desc') $orderBy = 'u.nama DESC';

$sql = "SELECT u.id_user, u.nama, u.no_telepon, u.id_pengguna, u.role, u.status,
               (SELECT MAX(waktu) FROM log_activity la WHERE la.id_user = u.id_user AND la.aktivitas LIKE 'Login%') AS terakhir_login
        FROM users u";
if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
$sql .= " ORDER BY {$orderBy}";

$stmt = $koneksi->prepare($sql);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$totalUser    = $koneksi->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c'];
$userAktif    = $koneksi->query("SELECT COUNT(*) c FROM users WHERE status='aktif'")->fetch_assoc()['c'];
$userNonaktif = $koneksi->query("SELECT COUNT(*) c FROM users WHERE status='nonaktif'")->fetch_assoc()['c'];

$success    = $_GET['success'] ?? null;
$error      = $_GET['error'] ?? null;
$openModal  = isset($_GET['modal']); // dipakai untuk buka ulang modal kalau ada error validasi
$oldNama    = $_GET['old_nama'] ?? '';
$oldTelepon = $_GET['old_telepon'] ?? '';
$formErrors = $_GET['errors'] ?? [];

/**
 * Helper: bikin URL filter, gabungin dengan query yang lagi aktif,
 * dan toggle-off kalau value yang sama diklik lagi.
 */
function filterUrl(string $key, string $value): string
{
    $params = $_GET;
    unset($params['errors'], $params['modal'], $params['old_nama'], $params['old_telepon'], $params['success'], $params['error']);

    if (($params[$key] ?? '') === $value) {
        unset($params[$key]); // klik lagi = matiin filter itu
    } else {
        $params[$key] = $value;
    }
    return 'admin.php?' . http_build_query($params);
}
function isActiveFilter(string $key, string $value): bool
{
    return ($_GET[$key] ?? '') === $value;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PARK-iR | Tabel User</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,700;0,900;1,900&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  *{box-sizing:border-box;}
  body{margin:0;font-family:'Poppins','Segoe UI',sans-serif;color:#26272b;background:#fafafa;}
  .content{flex:1;padding:26px 34px;position:relative;}
  .content-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;}
  h1{font-size:20px;font-weight:700;letter-spacing:.5px;margin:0;color:#26272b;}
  .btn-tambah{
    background:#37409c;color:#fff;border:none;border-radius:8px;padding:12px 20px;
    font-weight:700;font-size:12.5px;letter-spacing:.4px;cursor:pointer;text-decoration:none;
    display:inline-flex;align-items:center;gap:8px;
  }
  .btn-tambah:hover{background:#2f3789;}

  .alert{padding:10px 14px;border-radius:8px;font-size:12.5px;margin-bottom:16px;}
  .alert-ok{background:#dff0d8;color:#3c763d;}
  .alert-err{background:#fbeceb;color:#c0392b;}

  .stats{display:flex;gap:18px;margin-bottom:22px;flex-wrap:wrap;}
  .stat-card{background:#fff;border:1px solid #e2e2e2;border-radius:12px;padding:16px 22px;display:flex;align-items:center;gap:14px;min-width:200px;}
  .stat-icon{width:46px;height:46px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex:0 0 auto;}
  .stat-icon.total{background:#e8e8ea;color:#26272b;}
  .stat-icon.aktif{background:#dff0d8;color:#3c763d;}
  .stat-icon.nonaktif{background:#fbdede;color:#c0392b;}
  .stat-icon svg{width:24px;height:24px;}
  .stat-label{font-size:10px;letter-spacing:.6px;color:#888;text-transform:uppercase;}
  .stat-value{font-size:24px;font-weight:700;line-height:1.2;}
  .stat-unit{font-size:11px;color:#888;}

  .toolbar{display:flex;gap:12px;margin-bottom:8px;position:relative;}
  .search-box{flex:1;display:flex;align-items:center;gap:8px;background:#fff;border:1px solid #ccc;border-radius:8px;padding:0 14px;height:42px;}
  .search-box svg{width:16px;height:16px;color:#999;flex:0 0 auto;}
  .search-box input{flex:1;border:none;outline:none;font-size:13px;font-family:inherit;}
  .btn-filter{display:flex;align-items:center;gap:6px;border:1.5px solid #6a78e0;color:#5b69d6;background:#fff;border-radius:8px;padding:0 18px;height:42px;font-weight:700;font-size:12.5px;cursor:pointer;}
  .btn-filter svg{width:15px;height:15px;}

  /* --- Dropdown filter --- */
  .filter-dropdown{
    position:absolute; top:48px; right:0; background:#fff; border:1px solid #ccc; border-radius:8px;
    width:170px; box-shadow:0 8px 20px rgba(0,0,0,.15); z-index:20; display:none; overflow:hidden;
  }
  .filter-dropdown.show{display:block;}
  .filter-dropdown a, .filter-dropdown button{
    display:block; width:100%; text-align:left; padding:10px 14px; font-size:12.5px;
    text-decoration:none; color:#333; border:none; background:none; cursor:pointer; font-family:inherit;
  }
  .filter-dropdown a:hover, .filter-dropdown button:hover{background:#f0f1ff;}
  .filter-dropdown a.active-opt{background:#e8eaff;color:#37409c;font-weight:700;}
  .filter-dropdown .cancel-opt{color:#c0392b;font-weight:700;border-bottom:1px solid #eee;}
  .filter-dropdown .divider{border-top:1px solid #eee;margin:2px 0;}

  table{width:100%;border-collapse:collapse;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,.06);margin-top:14px;}
  th,td{padding:13px 14px;text-align:left;font-size:13px;border-bottom:1px solid #eee;}
  th{background:#f5f5f7;font-size:10.5px;letter-spacing:.6px;color:#666;text-transform:uppercase;}
  tr:last-child td{border-bottom:none;}

  .pill{padding:5px 14px;border-radius:20px;font-size:11px;font-weight:700;display:inline-block;}
  .pill-admin{background:#dcdfff;color:#37409c;}
  .pill-petugas{background:#dcf5e0;color:#2f8a4a;}
  .pill-aktif{background:#dcf5e0;color:#2f8a4a;}
  .pill-nonaktif{background:#fbdede;color:#c0392b;}

  .icon-btn{width:32px;height:32px;border-radius:8px;border:none;display:inline-flex;align-items:center;justify-content:center;cursor:pointer;text-decoration:none;margin-right:6px;}
  .icon-btn svg{width:15px;height:15px;}
  .icon-edit{background:#dcdfff;color:#37409c;}
  .icon-hapus{background:#fbdede;color:#c0392b;}

  /* --- Modal Tambah User --- */
  .modal-overlay{
    display:none; position:fixed; inset:0; background:rgba(20,22,50,.55); z-index:100;
    align-items:center; justify-content:center;
  }
  .modal-overlay.show{display:flex;}
  .modal-card{
    background:#eee; border-radius:22px; padding:26px 32px 30px; width:100%; max-width:340px;
    box-shadow:0 25px 60px rgba(0,0,0,.4); text-align:center; max-height:92vh; overflow-y:auto;
  }
  .modal-card .badge{width:56px;height:56px;border-radius:50%;background:#111214;display:inline-flex;flex-direction:column;align-items:center;justify-content:center;margin-bottom:6px;}
  .modal-card .badge .P{font-family:'Montserrat',sans-serif;font-weight:900;font-style:italic;font-size:22px;color:#2fb6b0;}
  .modal-logo{width:64px;height:64px;border-radius:50%;object-fit:cover;margin-bottom:6px;}
  .modal-card .brand{font-family:'Montserrat',sans-serif;font-weight:900;font-style:italic;font-size:26px;color:#5b69d6;margin:2px 0 0;}
  .modal-card .brand-sub{font-size:10px;font-weight:600;letter-spacing:1px;margin-top:2px;}
  .modal-card hr{border:none;border-top:2px dashed #9a9a9c;margin:12px 0;}
  .modal-card h2{font-size:17px;margin:0 0 3px;color:#26272b;}
  .modal-card .desc{font-size:10.5px;color:#666;margin:0 0 16px;}
  .modal-card .field{text-align:left;margin-bottom:12px;}
  .modal-card label{display:block;font-size:10px;font-weight:700;letter-spacing:.4px;margin-bottom:5px;}
  .input-wrap{display:flex;align-items:center;gap:8px;background:#e2e2e2;border:1px solid #b9b9bb;border-radius:6px;padding:0 10px;height:38px;}
  .input-wrap.err{border-color:#d9534f;background:#fbeceb;}
  .input-wrap svg{width:15px;height:15px;color:#8a8a8c;flex:0 0 auto;}
  .input-wrap input{flex:1;border:none;background:transparent;outline:none;font-size:12px;font-family:inherit;}
  .field-error{color:#d9534f;font-size:9.5px;margin-top:4px;}
  .role-toggle{display:flex;gap:10px;}
  .role-toggle input{position:absolute;opacity:0;pointer-events:none;}
  .role-btn{
    flex:1;text-align:center;background:#5b69d6;color:#fff;border-radius:8px;
    padding:11px 0;font-size:12.5px;font-weight:700;letter-spacing:.5px;cursor:pointer;
    margin:0;transition:.15s;opacity:.55;
  }
  .role-toggle input:checked + .role-btn{opacity:1;box-shadow:0 0 0 2px #26307a inset;}
  .modal-remember{display:flex;align-items:center;gap:8px;margin:10px 0 16px;font-size:11px;font-weight:700;}
  .btn-tambah-modal{width:100%;height:44px;border:none;border-radius:8px;background:#5b69d6;color:#fff;font-weight:700;font-size:14px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;}
  .btn-tambah-modal:hover{background:#4d5bcf;}
  .modal-close{position:absolute;top:14px;right:18px;background:none;border:none;font-size:18px;cursor:pointer;color:#666;}
</style>
</head>
<body>
<div class="app-shell">
  <?php include '../partials/header.php'; ?>
  <div class="app-body">
    <?php include '../partials/sidebar.php'; ?>

    <div class="content">
      <div class="content-head">
        <h1>TABEL USER</h1>
        <button class="btn-tambah" onclick="document.getElementById('modalTambah').classList.add('show')">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          TAMBAH USER
        </button>
      </div>

      <?php if ($success): ?><div class="alert alert-ok"><?= htmlspecialchars($success) ?></div><?php endif; ?>
      <?php if ($error && !$openModal): ?><div class="alert alert-err"><?= htmlspecialchars($error) ?></div><?php endif; ?>

      <div class="stats">
        <div class="stat-card">
          <div class="stat-icon total"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.7 0 4.9-2.2 4.9-4.9S14.7 2.2 12 2.2 7.1 4.4 7.1 7.1 9.3 12 12 12zm0 2.5c-3.3 0-9.8 1.6-9.8 4.9V22h19.6v-2.6c0-3.3-6.5-4.9-9.8-4.9z"/></svg></div>
          <div><div class="stat-label">Total User</div><div class="stat-value"><?= str_pad($totalUser,3,'0',STR_PAD_LEFT) ?> <span class="stat-unit">User</span></div></div>
        </div>
        <div class="stat-card">
          <div class="stat-icon aktif"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="7" r="4"/><path d="M2 21v-2a4 4 0 0 1 4-4h6"/><path d="m16 11 2 2 4-4"/></svg></div>
          <div><div class="stat-label">User Aktif</div><div class="stat-value"><?= str_pad($userAktif,3,'0',STR_PAD_LEFT) ?> <span class="stat-unit">User</span></div></div>
        </div>
        <div class="stat-card">
          <div class="stat-icon nonaktif"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="7" r="4"/><path d="M2 21v-2a4 4 0 0 1 4-4h6"/><line x1="16" y1="10" x2="21" y2="15"/><line x1="21" y1="10" x2="16" y2="15"/></svg></div>
          <div><div class="stat-label">User Nonaktif</div><div class="stat-value"><?= str_pad($userNonaktif,3,'0',STR_PAD_LEFT) ?> <span class="stat-unit">User</span></div></div>
        </div>
      </div>

      <form class="toolbar" method="GET">
        <div class="search-box">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Cari nama, username...">
        </div>
        <button class="btn-filter" type="button" onclick="document.getElementById('filterDropdown').classList.toggle('show')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
          Filter
        </button>

        <div class="filter-dropdown" id="filterDropdown">
          <button type="button" class="cancel-opt" onclick="document.getElementById('filterDropdown').classList.remove('show')">Cancel</button>
          <a href="<?= filterUrl('sort','asc') ?>" class="<?= isActiveFilter('sort','asc') ? 'active-opt' : '' ?>">Aa-Zz</a>
          <a href="<?= filterUrl('sort','desc') ?>" class="<?= isActiveFilter('sort','desc') ? 'active-opt' : '' ?>">Zz-Aa</a>
          <div class="divider"></div>
          <a href="<?= filterUrl('role','admin') ?>" class="<?= isActiveFilter('role','admin') ? 'active-opt' : '' ?>">Admin</a>
          <a href="<?= filterUrl('role','petugas') ?>" class="<?= isActiveFilter('role','petugas') ? 'active-opt' : '' ?>">Petugas</a>
          <div class="divider"></div>
          <a href="<?= filterUrl('status','aktif') ?>" class="<?= isActiveFilter('status','aktif') ? 'active-opt' : '' ?>">Aktif</a>
          <a href="<?= filterUrl('status','nonaktif') ?>" class="<?= isActiveFilter('status','nonaktif') ? 'active-opt' : '' ?>">Nonaktif</a>
        </div>
      </form>

      <table>
        <thead>
          <tr><th>NO.</th><th>NAMA</th><th>ID</th><th>ROLE</th><th>STATUS</th><th>TERAKHIR LOGIN</th><th>AKSI</th></tr>
        </thead>
        <tbody>
          <?php if (empty($users)): ?>
            <tr><td colspan="7" style="text-align:center;color:#999;padding:24px;">Tidak ada data user.</td></tr>
          <?php else: foreach ($users as $i => $u): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><?= htmlspecialchars($u['nama']) ?></td>
              <td><?= htmlspecialchars($u['id_pengguna']) ?></td>
              <td><span class="pill pill-<?= $u['role'] ?>"><?= $u['role'] === 'admin' ? 'Administrator' : 'Petugas' ?></span></td>
              <td><span class="pill pill-<?= $u['status'] ?>"><?= ucfirst($u['status']) ?></span></td>
              <td><?= $u['terakhir_login'] ? date('d M Y H:i', strtotime($u['terakhir_login'])) : '-' ?></td>
              <td>
                <a class="icon-btn icon-edit" href="edit_user.php?id=<?= $u['id_user'] ?>" title="Edit">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </a>
                <a class="icon-btn icon-hapus" href="hapus_user.php?id=<?= $u['id_user'] ?>" title="Hapus" onclick="return confirm('Yakin hapus user ini?');">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                </a>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- ============ MODAL TAMBAH USER ============ -->
<div class="modal-overlay <?= $openModal ? 'show' : '' ?>" id="modalTambah">
  <div class="modal-card" style="position:relative;">
    <button class="modal-close" onclick="document.getElementById('modalTambah').classList.remove('show')">&times;</button>

    <!-- ⚠️ GANTI src DI BAWAH DENGAN PATH LOGO PARK-iR KAMU (misal: ../auth/LOGO PARK-iR.png) -->
    <img src="../auth/LOGO PARK-iR.png" alt="Logo PARK-iR" class="modal-logo"
         onerror="this.style.display='none'">
    <div class="brand">PARK-iR</div>
    <div class="brand-sub">SISTEM PARKIR BERBASIS DIGITAL</div>
    <hr>
    <h2>TAMBAH USER</h2>
    <p class="desc">SILAHKAN LENGKAPI DATA USER !</p>

    <?php if ($openModal && $error): ?>
      <div class="alert alert-err" style="text-align:left;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="tambah_user.php" method="POST">
      <div class="field">
        <label>NAMA LENGKAP</label>
        <div class="input-wrap <?= isset($formErrors['nama']) ? 'err' : '' ?>">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          <input type="text" name="nama" placeholder="MASUKKAN NAMA LENGKAP" value="<?= htmlspecialchars($oldNama) ?>">
        </div>
        <?php if (isset($formErrors['nama'])): ?><div class="field-error"><?= htmlspecialchars($formErrors['nama']) ?></div><?php endif; ?>
      </div>

      <div class="field">
        <label>PASSWORD</label>
        <div class="input-wrap <?= isset($formErrors['password']) ? 'err' : '' ?>">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          <input type="password" name="password" placeholder="MASUKAN PASSWORD">
        </div>
        <?php if (isset($formErrors['password'])): ?><div class="field-error"><?= htmlspecialchars($formErrors['password']) ?></div><?php endif; ?>
      </div>

      <div class="field">
        <label>ROLE</label>
        <div class="role-toggle">
          <input type="radio" name="role" id="role-admin" value="admin">
          <label for="role-admin" class="role-btn">ADMIN</label>

          <input type="radio" name="role" id="role-user" value="petugas" checked>
          <label for="role-user" class="role-btn">USER</label>
        </div>
      </div>

      <label class="modal-remember">
        <input type="checkbox" name="langsung_aktif" checked>
        INGATKAN SAYA
      </label>

      <button class="btn-tambah-modal" type="submit">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
        TAMBAH
      </button>
    </form>
  </div>
</div>

<script>
  // Tutup dropdown filter kalau klik di luar area filter
  document.addEventListener('click', function (e) {
    const dropdown = document.getElementById('filterDropdown');
    const filterBtn = document.querySelector('.btn-filter');
    if (dropdown && dropdown.classList.contains('show') &&
        !dropdown.contains(e.target) && e.target !== filterBtn && !filterBtn.contains(e.target)) {
      dropdown.classList.remove('show');
    }
  });
</script>
</body>
</html>