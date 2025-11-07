// assets/js/script.js

document.addEventListener('DOMContentLoaded', function() {
    console.log('Script utama proyek dimuat.');
    
    // --- 1. Loading Animation Logic ---
    const overlay = document.getElementById('loading-overlay');
    if (overlay) {
        // Mengatur opacity menjadi 0 (transisi)
        overlay.style.opacity = '0';
        
        // Setelah transisi selesai (0.5 detik), sembunyikan sepenuhnya
        setTimeout(() => {
            overlay.classList.add('d-none'); 
        }, 500); 
    }

    // --- 2. Tambahkan Konfirmasi pada Semua Tombol Hapus (Optional, sebagai praktik baik) ---
    // Pastikan konfirmasi berjalan untuk semua form delete
    document.querySelectorAll('form').forEach(form => {
        if (form.querySelector('button[type="submit"].btn-outline-danger')) {
            form.addEventListener('submit', function(e) {
                if (!confirm('Apakah Anda yakin ingin menghapus item ini? Tindakan ini tidak dapat dibatalkan.')) {
                    e.preventDefault();
                }
            });
        }
    });

    // --- 3. Toggle Dropdown PM (khusus manage_users.php) ---
    const roleSelect = document.getElementById('role');
    if (roleSelect) {
        roleSelect.addEventListener('change', togglePMDropdown);
        // Panggil saat load untuk set state awal
        togglePMDropdown();
    }
    
    function togglePMDropdown() {
        var role = document.getElementById('role').value;
        var pmGroup = document.getElementById('pm-group');
        if (pmGroup) {
            if (role === 'Team Member') {
                pmGroup.style.display = 'block';
            } else {
                pmGroup.style.display = 'none';
            }
        }
    }
});