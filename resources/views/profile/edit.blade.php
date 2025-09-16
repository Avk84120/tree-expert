<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; margin: 0; padding: 0; }
        .container { max-width: 900px; margin: 30px auto; padding: 20px; }
        h2 { margin-bottom: 15px; color: #333; }
        .card { background: #fff; padding: 20px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 6px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; }
        button { margin-top: 15px; padding: 10px 16px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-primary { background: #007bff; color: #fff; }
        .btn-danger { background: #e3342f; color: #fff; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Profile</h2>

        <!-- Update Profile Information -->
        <div class="card">
            <h3>Update Profile Information</h3>
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <label for="name">Name</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}">

                <label for="email">Email</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}">

                <button type="submit" class="btn-primary">Save</button>
            </form>
        </div>

        <!-- Update Password -->
        <div class="card">
            <h3>Update Password</h3>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <label for="current_password">Current Password</label>
                <input type="password" name="current_password">

                <label for="password">New Password</label>
                <input type="password" name="password">

                <label for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation">

                <button type="submit" class="btn-primary">Update Password</button>
            </form>
        </div>

        <!-- Delete Account -->
        <div class="card">
            <h3>Delete Account</h3>
            <p>
                Once your account is deleted, all resources and data will be permanently deleted. 
                Please enter your password to confirm.
            </p>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')

                <label for="password">Password</label>
                <input type="password" name="password">

                <button type="submit" class="btn-danger">Delete Account</button>
            </form>
        </div>
    </div>
</body>
</html>
