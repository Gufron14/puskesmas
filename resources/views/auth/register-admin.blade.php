<!DOCTYPE html>
<html>
<head>
    <title>Register Admin - SIM RM</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Register Admin/Mantri/Puskesmas Induk</h1>
    
    @if ($errors->any())
        <div style="color: red; border: 1px solid red; padding: 10px; margin: 10px 0;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div style="color: green; border: 1px solid green; padding: 10px; margin: 10px 0;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('register.admin.post') }}">
        @csrf
        
        <div style="margin: 10px 0;">
            <label>Role:</label><br>
            <select name="role" required style="padding: 5px; width: 200px;">
                <option value="">Pilih Role</option>
                <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                <option value="Mantri" {{ old('role') == 'Mantri' ? 'selected' : '' }}>Mantri</option>
                <option value="Puskesmas Induk" {{ old('role') == 'Puskesmas Induk' ? 'selected' : '' }}>Puskesmas Induk</option>
            </select>
        </div>

        <div style="margin: 10px 0;">
            <label>Nama Lengkap:</label><br>
            <input type="text" name="name" value="{{ old('name') }}" required style="padding: 5px; width: 300px;">
        </div>

        <div style="margin: 10px 0;">
            <label>Nomor Telepon (08xxxxxxxxxx):</label><br>
            <input type="text" name="telepon" value="{{ old('telepon') }}" required style="padding: 5px; width: 200px;" placeholder="08123456789">
        </div>

        <div style="margin: 10px 0;">
            <label>Email:</label><br>
            <input type="email" name="email" value="{{ old('email') }}" required style="padding: 5px; width: 300px;" placeholder="admin@puskesmas.com">
        </div>

        <div style="margin: 10px 0;">
            <label>Password (minimal 8 karakter):</label><br>
            <input type="password" name="password" required style="padding: 5px; width: 200px;">
        </div>

        <div style="margin: 10px 0;">
            <label>Konfirmasi Password:</label><br>
            <input type="password" name="password_confirmation" required style="padding: 5px; width: 200px;">
        </div>

        <div style="margin: 10px 0;">
            <label>Jenis Kelamin:</label><br>
            <select name="jenis_kelamin" required style="padding: 5px; width: 200px;">
                <option value="">Pilih</option>
                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        <div style="margin: 10px 0;">
            <label>Usia:</label><br>
            <input type="number" name="usia" value="{{ old('usia') }}" required min="18" max="70" style="padding: 5px; width: 100px;">
        </div>

        <div style="margin: 10px 0;">
            <label>NIK (16 digit):</label><br>
            <input type="text" name="nik" value="{{ old('nik') }}" required style="padding: 5px; width: 200px;" maxlength="16" placeholder="1234567890123456">
        </div>

        <div style="margin: 10px 0;">
            <label>Alamat:</label><br>
            <textarea name="alamat" required style="padding: 5px; width: 400px; height: 60px;">{{ old('alamat') }}</textarea>
        </div>

        <div style="margin: 20px 0;">
            <button type="submit" style="padding: 10px 20px; background: green; color: white; border: none; cursor: pointer;">
                Daftar
            </button>
            <a href="{{ route('login') }}" style="margin-left: 10px; padding: 10px 20px; background: gray; color: white; text-decoration: none; display: inline-block;">
                Kembali ke Login
            </a>
        </div>
    </form>

    <hr>
    <p><strong>Catatan:</strong> Halaman ini hanya untuk membuat akun admin/mantri/puskesmas induk. Setelah selesai debug, halaman ini sebaiknya dihapus atau diberi proteksi khusus.</p>
</body>
</html>
