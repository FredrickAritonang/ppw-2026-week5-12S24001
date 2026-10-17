<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — SIPUS-Del</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --del:#0F766E; --del-dark:#134E4A; }
        body { font-family:'Figtree',sans-serif; min-height:100vh; display:flex; align-items:center; justify-content:center; padding:1.5rem; background:#F5F4EF; }
        .login-box { width:100%; max-width:440px; background:#fff; border:1px solid #E4E2D9; border-radius:.5rem; padding:2rem 2rem 1.8rem; box-shadow:0 22px 55px rgba(15,118,110,.16); }
        .login-box .logo { display:block; max-width:260px; width:100%; margin:0 auto .4rem; }
        .login-box .inst { text-align:center; color:#64706E; font-size:.95rem; font-family:'Bricolage Grotesque',sans-serif; }
        .login-box hr { border-color:#E4E2D9; opacity:1; margin:1.2rem 0 1.4rem; }
        .lbl { font-weight:700; color:#14302D; margin:.9rem 0 .35rem; display:block; }
        .form-control { border-radius:.3rem; padding:.7rem .9rem; border-color:#D5D3C8; }
        .form-control:focus { border-color:var(--del); box-shadow:0 0 0 .2rem rgba(15,118,110,.18); }
        .btn-signin { background:var(--del); color:#fff; border:0; border-radius:.3rem; padding:.65rem 1.8rem; font-weight:600; box-shadow:0 6px 16px rgba(15,118,110,.3); }
        .btn-signin:hover { color:#fff; background:var(--del-dark); }
    </style>
</head>
<body>
    <div class="login-box">
        <img class="logo" src="{{ asset('images/logo.png') }}" alt="Logo IT Del Library">
        <div class="inst">Perpustakaan Institut Teknologi Del</div>
        <hr>

        @if(session('success'))<div class="alert alert-success py-2 small">{{ session('success') }}</div>@endif

        <form method="POST" action="{{ route('login.proses') }}">
            @csrf
            <label class="lbl" for="username">Username</label>
            <input type="text" id="username" name="username" list="daftarAkun" value="{{ old('username', $terakhir ?? '') }}" class="form-control @error('username') is-invalid @enderror" autocomplete="off" autofocus required>
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
    </div>

    <script>
        document.getElementById('tglPw').addEventListener('click', function () {
            var p = document.getElementById('password');
            p.type = p.type === 'password' ? 'text' : 'password';
        });
    </script>
</body>
</html>