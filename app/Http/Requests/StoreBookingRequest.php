<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // cho phép cả khách chưa đăng nhập đặt lịch, tuỳ bạn siết lại nếu cần
    }

    public function rules(): array
    {
        return [
            'car_id' => ['required', 'exists:cars,id'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'car_id.exists' => 'Xe không tồn tại.',
            'scheduled_at.after' => 'Thời gian hẹn phải ở tương lai.',
            'full_name.required' => 'Vui lòng nhập họ tên.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
        ];
    }
}