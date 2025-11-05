<?php
// Sertakan file config.php (sudah termasuk session_start())
include 'config.php';

// 1. Cek apakah form sudah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 2. Ambil data dari form (Basic sanitasi)
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // 3. Buat query untuk mencari user
    // Gunakan prepared statements untuk keamanan
    $sql = "SELECT id, username, password, role FROM users WHERE username = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variabel ke prepared statement
        mysqli_stmt_bind_param($stmt, "s", $username);
        
        // Eksekusi statement
        if (mysqli_stmt_execute($stmt)) {
            // Simpan hasil
            mysqli_stmt_store_result($stmt);
            
            // 4. Cek apakah user ditemukan
            if (mysqli_stmt_num_rows($stmt) == 1) {
                // Bind hasil ke variabel
                mysqli_stmt_bind_result($stmt, $id, $db_username, $hashed_password, $role);
                
                if (mysqli_stmt_fetch($stmt)) {
                    // 5. Verifikasi password 
                    if (password_verify($password, $hashed_password)) {
                        
                        // 6. Password benar! Buat session
                        session_regenerate_id(); // Keamanan
                        $_SESSION['user_id'] = $id;
                        $_SESSION['username'] = $db_username;
                        $_SESSION['role'] = $role;
                        
                        // 7. Alihkan ke dashboard
                        header("Location: dashboard.php");
                        exit();
                        
                    } else {
                        // Password salah
                        header("Location: index.php?error=Username atau password salah");
                        exit();
                    }
                }
            } else {
                // User tidak ditemukan
                header("Location: index.php?error=Username atau password salah");
                exit();
            }
        } else {
            header("Location: index.php?error=Terjadi kesalahan. Coba lagi.");
            exit();
        }
        // Tutup statement
        mysqli_stmt_close($stmt);
    }
    // Tutup koneksi
    mysqli_close($conn);
    
} else {
    // Jika diakses langsung, alihkan ke index
    header("Location: index.php");
    exit();
}
?>