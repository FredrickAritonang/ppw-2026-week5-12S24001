<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — SIPUS-Del</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --del:#4C1D95; --del-dark:#3B0764; --del-mid:#6D28D9; }
        body { font-family:'Poppins',sans-serif; min-height:100vh; display:flex; align-items:center; justify-content:center; padding:1.5rem;
               background:radial-gradient(circle at 12% 18%,#E9DDFF,transparent 45%),radial-gradient(circle at 88% 82%,#DCEBFF,transparent 45%),#F6F4FB; }
        .login-box { width:100%; max-width:440px; background:#fff; border:1px solid #E5E0F2; border-radius:.5rem; padding:2rem 2rem 1.6rem; box-shadow:0 22px 55px rgba(76,29,149,.16); }
        .login-box .logo { display:block; max-width:260px; width:100%; margin:0 auto .4rem; }
        .login-box .inst { text-align:center; color:#5b5575; font-size:.95rem; }
        .login-box hr { border-color:#E9E4F5; opacity:1; margin:1.2rem 0 1.4rem; }
        .lbl { font-weight:700; color:#4b4565; margin:.9rem 0 .35rem; display:block; }
        .form-control { border-radius:.3rem; padding:.7rem .9rem; border-color:#D9D3EA; }
        .form-control:focus { border-color:var(--del); box-shadow:0 0 0 .2rem rgba(76,29,149,.15); }
        .btn-signin { background:linear-gradient(135deg,var(--del),var(--del-mid)); color:#fff; border:0; border-radius:.3rem; padding:.65rem 1.8rem; font-weight:600; box-shadow:0 6px 16px rgba(76,29,149,.3); }
        .btn-signin:hover { color:#fff; background:var(--del-dark); }
        .demo { margin-top:1.4rem; font-size:.78rem; }
        .demo button { border:1px solid #E5E0F2; background:#FAF8FF; border-radius:50rem; padding:.25rem .7rem; margin:.15rem; color:var(--del); font-size:.75rem; }
        .demo button:hover { background:var(--del); color:#fff; }
    </style>
</head>
<body>
    <div class="login-box">
        <img class="logo" src="{{ asset('images/logo.png') }}" alt="Logo IT Del Library">
        <div class="inst">Perpustakaan Institut Teknologi Del</div>
        <hr>

        <form method="POST" action="{{ route('login.proses') }}">
            @csrf
            <label class="lbl" for="username">Username atau Nama</label>
            <input type="text" id="username" name="username" list="daftarAkun" value="{{ old('username') }}" class="form-control @error('username') is-invalid @enderror" placeholder="mis. chandropardede atau Chandro Pardede" autocomplete="off" autofocus required>
            @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <datalist id="daftarAkun">
                @foreach($akunSaran as $a)
                    <option value="{{ $a->username }}">{{ $a->name }}</option>
                @endforeach
            </datalist>

            <label class="lbl" for="password">Password</label>
            <div class="input-group">
                <input type="password" id="password" name="password" class="form-control" autocomplete="current-password" required>
                <button type="button" class="btn btn-outline-secondary" id="tglPw" tabindex="-1" title="Tampilkan password"><i class="bi bi-eye"></i></button>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1">
                    <label class="form-check-label" for="remember">Remember Me</label>
                </div>
                <button type="submit" class="btn btn-signin">Sign In</button>
            </div>
        </form>

        <div class="demo">
            <div class="text-muted mb-1"><i class="bi bi-info-circle me-1"></i>Akun demo, klik untuk mengisi username dan password:</div>
            <button type="button" data-u="iss24001">Mahasiswa</button>
            <button type="button" data-u="chandropardede">Dosen</button>
            <button type="button" data-u="jesichaulinahutabarat">Pegawai Perpustakaan</button>
            <button type="button" data-u="stafakademik1">Staf Akademik</button>
            <div class="text-muted mt-2">Semua dosen dan pegawai bisa masuk dengan username (nama tanpa gelar dan spasi) atau nama lengkap. Ketik nama untuk melihat saran. Password semua akun: <b>PustakaDel2026</b></div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.demo button').forEach(function (b) {
            b.addEventListener('click', function () {
                document.getElementById('username').value = b.dataset.u;
                document.getElementById('password').value = 'PustakaDel2026';
            });
        });
        document.getElementById('tglPw').addEventListener('click', function () {
            var p = document.getElementById('password');
            p.type = p.type === 'password' ? 'text' : 'password';
        });
    </script>
</body>
</html>