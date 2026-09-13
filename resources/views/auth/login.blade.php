<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - el'sCoffe POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    /* Background diubah menjadi Light Pink */
    body { background-color: #ffc3e2; } 
    
    /* Bayangan (shadow) disesuaikan warnanya agar agak ke-pink-an */
    .login-card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(194, 24, 91, 0.15); } 
    
    /* Tombol utama diubah menjadi Pink */
    .btn-coffee { background-color: #FF69B4; color: white; border: none; } 
    
    /* Tombol saat di-hover diubah menjadi Dark Pink */
    .btn-coffee:hover { background-color: #C2185B; color: white; } 
</style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="card login-card p-4" style="width: 100%; max-width: 400px;">
        <div class="text-center mb-4">
            <h2 style="color: #3E2723; font-weight: bold;">el'sCoffe</h2>
            <p class="text-muted">Point of Sales System</p>
        </div>
        
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form action="{{ url('/login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-coffee w-100 py-2">Masuk</button>
        </form>
    </div>
</body>
</html>