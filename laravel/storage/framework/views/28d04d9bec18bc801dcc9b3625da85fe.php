

<?php $__env->startSection('title', 'Tabel User'); ?>

<?php $__env->startSection('container'); ?>

    <!-- Notifikasi Sukses -->
    <?php if(session()->has('success')): ?>
        <div style="background: #dcfce7; color: #155724; padding: 15px 20px; border-radius: 12px; margin-bottom: 20px; border-left: 5px solid #16a34a; font-size: 14px;">
            <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <!-- Notifikasi Error -->
    <?php if(session()->has('error')): ?>
        <div style="background: #fee2e2; color: #dc2626; padding: 15px 20px; border-radius: 12px; margin-bottom: 20px; border-left: 5px solid #dc2626; font-size: 14px;">
            <i class="fa-solid fa-circle-xmark" style="margin-right: 8px;"></i> <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div style="background: #fee2e2; color: #dc2626; padding: 15px 20px; border-radius: 12px; margin-bottom: 20px; border-left: 5px solid #dc2626; font-size: 14px;">
            <strong><i class="fa-solid fa-triangle-exclamation" style="margin-right: 8px;"></i> Terjadi Kesalahan:</strong>
            <ul style="margin-top: 8px; margin-left: 20px;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Header & Tombol Tambah User -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 style="font-size: 20px; font-weight: 700; color: #1e293b;">TABEL USER</h2>
        <button onclick="openModal()" style="background: #4361ee; color: white; border: none; padding: 10px 18px; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-plus"></i> TAMBAH USER
        </button>
    </div>

    <!-- Kotak Statistik User -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 25px;">
        <div class="card-white" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px;">
            <div>
                <div style="font-size: 11px; color: #888; font-weight: 600; text-transform: uppercase;">Total User</div>
                <div style="font-size: 22px; font-weight: 700; color: #333; margin-top: 2px;"><?php echo e($totalUser ?? 0); ?></div>
            </div>
            <div style="width: 40px; height: 40px; background: #e0e7ff; color: #4361ee; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <div class="card-white" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px;">
            <div>
                <div style="font-size: 11px; color: #888; font-weight: 600; text-transform: uppercase;">User Aktif</div>
                <div style="font-size: 22px; font-weight: 700; color: #16a34a; margin-top: 2px;"><?php echo e($userAktif ?? 0); ?></div>
            </div>
            <div style="width: 40px; height: 40px; background: #dcfce7; color: #16a34a; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                <i class="fa-solid fa-user-check"></i>
            </div>
        </div>

        <div class="card-white" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px;">
            <div>
                <div style="font-size: 11px; color: #888; font-weight: 600; text-transform: uppercase;">User Nonaktif</div>
                <div style="font-size: 22px; font-weight: 700; color: #dc2626; margin-top: 2px;"><?php echo e($userNonAktif ?? 0); ?></div>
            </div>
            <div style="width: 40px; height: 40px; background: #fee2e2; color: #dc2626; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                <i class="fa-solid fa-user-slash"></i>
            </div>
        </div>
    </div>

    <!-- Bar Pencarian & Filter -->
    <div class="card-white" style="margin-bottom: 25px; padding: 15px 20px;">
        <form action="<?php echo e(route('user.index')); ?>" method="GET" style="display: flex; justify-content: space-between; align-items: center; gap: 15px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 10px; background: #f8fafc; border: 1px solid #e2e8f0; padding: 8px 15px; border-radius: 8px; flex: 1; min-width: 250px;">
                <i class="fa-solid fa-magnifying-glass" style="color: #94a3b8;"></i>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama, username..." style="border: none; background: transparent; outline: none; width: 100%; font-size: 13px;">
            </div>
            <div>
                <select name="role" onchange="this.form.submit()" style="padding: 9px 15px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc; font-size: 13px; color: #475569; outline: none;">
                    <option value="">-- Semua Role --</option>
                    <option value="admin" <?php echo e(request('role') == 'admin' ? 'selected' : ''); ?>>Admin</option>
                    <option value="petugas" <?php echo e(request('role') == 'petugas' ? 'selected' : ''); ?>>Petugas</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Tabel Daftar User -->
    <div class="card-white">
        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">NO.</th>
                    <th>NAMA</th>
                    <th>ID / USERNAME</th>
                    <th>ROLE</th>
                    <th>STATUS</th>
                    <th>TERAKHIR LOGIN</th>
                    <th style="text-align: center; width: 15%;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $usr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($users->firstItem() + $index); ?></td>
                        <td style="font-weight: 600; color: #1e293b;"><?php echo e($usr->name); ?></td>
                        <td><?php echo e($usr->username ?? '-'); ?></td>
                        <td>
                            <?php $roleUser = strtolower($usr->role ?? 'petugas'); ?>
                            <span style="padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; background: <?php echo e($roleUser == 'admin' ? '#e0e7ff' : '#dcfce7'); ?>; color: <?php echo e($roleUser == 'admin' ? '#4f46e5' : '#16a34a'); ?>;">
                                <?php echo e(ucfirst($roleUser)); ?>

                            </span>
                        </td>

                        <td>
         <?php if(($usr->status ?? 'aktif') == 'aktif'): ?>
            <span style="padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; background: #dcfce7; color: #16a34a;">
                Aktif
            </span>
        <?php else: ?>
            <span style="padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; background: #fee2e2; color: #dc2626;">
                Nonaktif
            </span>
        <?php endif; ?>
    </td>
                       <td style="color: #64748b; font-size: 13px;">
        <?php echo e($usr->updated_at ? $usr->updated_at->format('d M Y H:i') : '-'); ?>

    </td>
                        <td style="text-align: center;">
                            <div style="display: flex; justify-content: center; gap: 8px;">
                                <!-- Tombol Edit -->
                                <button type="button" 
        onclick="openEditModal('<?php echo e($usr->id); ?>', '<?php echo e($usr->name); ?>', '<?php echo e($usr->username); ?>', '<?php echo e($usr->role); ?>', '<?php echo e($usr->status ?? 'aktif'); ?>')" 
        style="background: #e0e7ff; color: #4f46e5; border: none; padding: 6px 10px; border-radius: 6px; cursor: pointer; font-size: 12px;" 
        title="Edit">
    <i class="fa-solid fa-pen-to-square"></i>
</button>

                                <!-- Tombol Hapus -->
                                <form action="<?php echo e(route('user.destroy', $usr->id)); ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user <?php echo e($usr->name); ?>?')" style="margin: 0;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" 
                                            style="background: #fee2e2; color: #dc2626; border: none; padding: 6px 10px; border-radius: 6px; cursor: pointer; font-size: 12px;" 
                                            title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: #888; padding: 30px;">Belum ada data user yang terdaftar.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- MODAL POPUP TAMBAH USER -->
    <div id="userModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
        <div style="background: white; padding: 30px; border-radius: 12px; width: 100%; max-width: 450px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="font-size: 16px; font-weight: 700; color: #1e293b;">Tambah User Baru</h3>
                <button onclick="closeModal()" style="background: none; border: none; font-size: 18px; cursor: pointer; color: #888;"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <form action="<?php echo e(route('user.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #555; margin-bottom: 5px;">NAMA LENGKAP</label>
                    <input type="text" name="name" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; outline: none;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #555; margin-bottom: 5px;">USERNAME</label>
                    <input type="text" name="username" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; outline: none;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #555; margin-bottom: 5px;">PASSWORD</label>
                    <input type="password" name="password" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; outline: none;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #555; margin-bottom: 5px;">ROLE HAK AKSES</label>
                    <select name="role" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; background: white; outline: none;">
                        <option value="petugas">Petugas</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                            <div style="margin-bottom: 20px;">
    <label style="display: block; font-size: 12px; font-weight: 600; color: #555; margin-bottom: 5px;">STATUS AKUN</label>
    <select id="edit_status" name="status" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; background: white; outline: none;">
        <option value="aktif">Aktif</option>
        <option value="nonaktif">Nonaktif</option>
    </select>
</div>

                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" onclick="closeModal()" style="background: #e2e8f0; color: #475569; border: none; padding: 10px 16px; border-radius: 6px; font-weight: 600; font-size: 13px; cursor: pointer;">Batal</button>
                    <button type="submit" style="background: #4361ee; color: white; border: none; padding: 10px 16px; border-radius: 6px; font-weight: 600; font-size: 13px; cursor: pointer;">Simpan User</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL POPUP EDIT USER -->
    <div id="editUserModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
        <div style="background: white; padding: 30px; border-radius: 12px; width: 100%; max-width: 450px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="font-size: 16px; font-weight: 700; color: #1e293b;">Edit Data User</h3>
                <button onclick="closeEditModal()" style="background: none; border: none; font-size: 18px; cursor: pointer; color: #888;"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <form id="editForm" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #555; margin-bottom: 5px;">NAMA LENGKAP</label>
                    <input type="text" id="edit_name" name="name" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; outline: none;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #555; margin-bottom: 5px;">USERNAME</label>
                    <input type="text" id="edit_username" name="username" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; outline: none;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #555; margin-bottom: 5px;">PASSWORD (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" placeholder="******" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; outline: none;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #555; margin-bottom: 5px;">ROLE HAK AKSES</label>
                    <select id="edit_role" name="role" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; background: white; outline: none;">
                        <option value="petugas">Petugas</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <!-- Tambahkan ini tepat di bawah Role Hak Akses -->
<div style="margin-bottom: 20px;">
    <label style="display: block; font-size: 12px; font-weight: 600; color: #555; margin-bottom: 5px;">STATUS AKUN</label>
    <select id="edit_status" name="status" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; background: white; outline: none;">
        <option value="aktif">Aktif</option>
        <option value="nonaktif">Nonaktif</option>
    </select>
</div>

                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" onclick="closeEditModal()" style="background: #e2e8f0; color: #475569; border: none; padding: 10px 16px; border-radius: 6px; font-weight: 600; font-size: 13px; cursor: pointer;">Batal</button>
                    <button type="submit" style="background: #4361ee; color: white; border: none; padding: 10px 16px; border-radius: 6px; font-weight: 600; font-size: 13px; cursor: pointer;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openEditModal(id, name, username, role, status) {
        document.getElementById('editForm').action = '/user/update/' + id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_username').value = username;
        document.getElementById('edit_role').value = role;
        document.getElementById('edit_status').value = status || 'aktif'; // Set status
        
        document.getElementById('editUserModal').style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('editUserModal').style.display = 'none';
    }
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\parkir-\laravel\resources\views/user.blade.php ENDPATH**/ ?>