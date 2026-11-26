@extends('admin.layout')

@section('title', 'Đổi mật khẩu admin')
@section('section', 'Đổi mật khẩu admin')

@section('content')
    <div class="page-heading">
        <div>
            <h1>Đổi mật khẩu admin</h1>
            <p>Cập nhật mật khẩu để bảo vệ tài khoản quản trị showroom.</p>
        </div>
    </div>

    <form class="form-panel" method="POST" action="{{ route('admin.password.update') }}">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="form-field full">
                <label for="current_password">Mật khẩu hiện tại</label>
                <input class="field" id="current_password" name="current_password" type="password" autocomplete="current-password" required>
                @error('current_password')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-field">
                <label for="password">Mật khẩu mới</label>
                <input class="field" id="password" name="password" type="password" autocomplete="new-password" minlength="8" required>
                @error('password')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-field">
                <label for="password_confirmation">Xác nhận mật khẩu mới</label>
                <input class="field" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="8" required>
            </div>
        </div>

        <div class="form-actions">
            <button class="button" type="submit">Cập nhật mật khẩu</button>
        </div>
    </form>
@endsection
