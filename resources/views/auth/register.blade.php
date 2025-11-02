<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Connectify | Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #ff9a9e, #fad0c4);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Poppins', sans-serif;
    }
    .card {
      width: 400px;
      border: none;
      border-radius: 20px;
      backdrop-filter: blur(8px);
      background: rgba(255, 255, 255, 0.2);
      box-shadow: 0 8px 30px rgba(0,0,0,0.2);
      color: #fff;
      padding: 35px;
    }
    .form-control {
      background-color: rgba(255,255,255,0.25);
      border: none;
      color: #fff;
    }
    .form-control::placeholder {
      color: #eee;
    }
    .btn-success {
      background: #667eea;
      border: none;
    }
    .btn-success:hover {
      background: #5563c1;
    }
    a {
      color: #667eea;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="card">
    <h3 class="text-center fw-bold mb-3">Join Connectify</h3>
    <p class="text-center mb-4">Create your free account</p>

    @if ($errors->any())
      <div class="alert alert-danger py-1">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('register.submit') }}">
      @csrf
      <div class="mb-3">
        <input type="text" name="name" class="form-control" placeholder="Full Name" required>
      </div>
      <div class="mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
      </div>
      <div class="mb-3">
        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
      </div>
      <button class="btn btn-success w-100">Register</button>
    </form>

    <p class="text-center mt-3">Already have an account? <a href="{{ route('login') }}">Login</a></p>
  </div>
</body>
</html>
