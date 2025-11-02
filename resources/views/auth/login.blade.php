<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Connectify | Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #667eea, #764ba2);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Poppins', sans-serif;
    }
    .card {
      width: 380px;
      border: none;
      border-radius: 15px;
      backdrop-filter: blur(10px);
      background: rgba(255, 255, 255, 0.15);
      box-shadow: 0 8px 25px rgba(0,0,0,0.3);
      color: #fff;
      padding: 30px;
    }
    .form-control {
      background-color: rgba(255,255,255,0.2);
      border: none;
      color: #fff;
    }
    .form-control::placeholder {
      color: #eee;
    }
    .btn-primary {
      background: #ff7eb3;
      border: none;
      transition: 0.3s;
    }
    .btn-primary:hover {
      background: #ff4f81;
    }
    a {
      color: #ff7eb3;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="card">
    <h3 class="text-center mb-3 fw-bold">Welcome to Connectify</h3>
    <p class="text-center text-light mb-4">Login to manage your contacts</p>

    @if ($errors->any())
      <div class="alert alert-danger py-1">{{ $errors->first() }}</div>
    @endif
    @if (session('success'))
      <div class="alert alert-success py-1">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}">
      @csrf
      <div class="mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
      </div>
      <button class="btn btn-primary w-100">Login</button>
    </form>

    <p class="text-center mt-3">Don't have an account? <a href="{{ route('register') }}">Register</a></p>
  </div>
</body>
</html>
