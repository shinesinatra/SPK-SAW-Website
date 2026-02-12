<!DOCTYPE html>
<html lang="en">
    <?php
        session_start();
        include "connection.php";
    ?>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login | Website</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
        <style>
            .login-container { max-width: 400px; margin: 0 auto; padding: 20px; }
            .login-box { border: 1px solid #ccc; padding: 20px; border-radius: 10px; }
            .container { display: flex; align-items: center; justify-content: center; }
            .container img { margin-right: 10px; max-width: 50%; height: auto; }
        </style>
    </head>
    <body>
        <div class="container-fluid d-flex align-items-center justify-content-center" style="height: 100vh;">
            <div class="login-box">
                <img src="gambar/spk.jpg" class="img-fluid" alt="Gambar">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <div class="input-group-text"><i class="fas fa-user"></i></div>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required>
                        </div>
                        <small class="text-danger" id="username-error"></small>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <div class="input-group-text"><i class="fas fa-lock"></i></div>
                            <input type="password" class="form-control toggle-password" id="password" name="password" placeholder="Masukkan password" required>
                            <div class="input-group-text toggle-password-btn"><i class="fas fa-eye"></i></div>
                        </div>
                        <small class="text-danger" id="password-error"></small>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" name="login">LOGIN</button>
                </form>
                <?php
                    //Proses Tombol Login
                    if (isset($_POST['login'])) {
                        $username = mysqli_real_escape_string($koneksi, $_POST['username']);
                        $password = mysqli_real_escape_string($koneksi, $_POST['password']);
                        $sql = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND password='$password'");
                        $cek = mysqli_num_rows($sql);
                        if ($cek > 0) {
                            $user = mysqli_fetch_assoc($sql);
                            $_SESSION['username'] = $user['username'];
                            $_SESSION['full_name'] = $user['full_name'];
                            echo "<meta http-equiv=refresh content=0;URL='menu.php'>";
                        } else {
                            echo "<script>alert('Username atau Password Salah')</script>";
                            echo "<meta http-equiv=refresh content=2;URL='index.php'>";
                        }
                    }
                ?>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Script menampilkan/menyembunyikan password -->
        <script>
            $(document).ready(function () {
                $(".toggle-password-btn").click(function () {
                    var passwordField = $(this).closest('.input-group').find('.toggle-password');
                    var fieldType = passwordField.attr("type");
                    if (fieldType === "password") {
                        passwordField.attr("type", "text");
                        $(this).find('i').removeClass("fa-eye");
                        $(this).find('i').addClass("fa-eye-slash");
                    } else {
                        passwordField.attr("type", "password");
                        $(this).find('i').removeClass("fa-eye-slash");
                        $(this).find('i').addClass("fa-eye");
                    }
                });
            });
        </script>
    </body>
</html>