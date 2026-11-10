<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminColorController extends Controller
{
    // DANH SÁCH VÀ TÌM KIẾM
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Color::where('IsDeleted', 0);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('TenMau', 'like', "%{$search}%")
                  ->orWhere('MaMau', 'like', "%{$search}%")
                  ->orWhere('MaHex', 'like', "%{$search}%");
            });
        }

        $colors = $query->orderBy('MaMau', 'desc')->get();

        return view('admin.colors.index', compact(
            'colors',
            'search'
        ));
    }

    // FORM THÊM
    public function create()
    {
        return view('admin.colors.create');
    }

    // LƯU MÀU MỚI
    public function store(Request $request)
    {
        $validated = $request->validate([
            'TenMau' => [
                'required',
                'string',
                'max:255',
                Rule::unique('mausac', 'TenMau'),
            ],

            'MaHex' => [
                'nullable',
                'regex:/^#[0-9A-Fa-f]{6}$/',
            ],
        ], [
            'TenMau.required' => 'Vui lòng nhập tên màu sắc.',
            'TenMau.max' => 'Tên màu không được vượt quá 255 ký tự.',
            'TenMau.unique' => 'Tên màu này đã tồn tại.',
            'MaHex.regex' => 'Mã màu phải có định dạng HEX, ví dụ #FF0000.',
        ]);

        $validated['NguoiTao'] = auth()->check() ? auth()->id() : null;
        $validated['NgayTao'] = now();
        $validated['IsDeleted'] = 0;

        Color::create($validated);

        return redirect()
            ->route('admin.colors.index')
            ->with('success', 'Thêm màu sắc thành công!');
    }

    // FORM CHỈNH SỬA
    public function edit($id)
    {
        $color = Color::where('IsDeleted', 0)
            ->findOrFail($id);

        return view('admin.colors.edit', compact('color'));
    }

    // CẬP NHẬT
    public function update(Request $request, $id)
    {
        $color = Color::where('IsDeleted', 0)
            ->findOrFail($id);

        $validated = $request->validate([
            'TenMau' => [
                'required',
                'string',
                'max:255',
                Rule::unique('mausac', 'TenMau')
                    ->ignore($id, 'MaMau'),
            ],

            'MaHex' => [
                'nullable',
                'regex:/^#[0-9A-Fa-f]{6}$/',
            ],
        ], [
            'TenMau.required' => 'Vui lòng nhập tên màu sắc.',
            'TenMau.max' => 'Tên màu không được vượt quá 255 ký tự.',
            'TenMau.unique' => 'Tên màu này đã tồn tại.',
            'MaHex.regex' => 'Mã màu phải có định dạng HEX, ví dụ #FF0000.',
        ]);

        $validated['NguoiCapNhat'] = auth()->check() ? auth()->id() : null;
        $validated['NgayCapNhat'] = now();

        $color->update($validated);

        return redirect()
            ->route('admin.colors.index')
            ->with('success', 'Cập nhật màu sắc thành công!');
    }

    // XÓA MỀM
    public function destroy($id)
    {
        $color = Color::where('IsDeleted', 0)
            ->findOrFail($id);

        // Kiểm tra màu có đang được sử dụng trong chi tiết sản phẩm không
        $isUsing = ProductDetail::where('MaMau', $id)
            ->where('IsDeleted', 0)
            ->exists();

        if ($isUsing) {
            return redirect()
                ->route('admin.colors.index')
                ->with('error', 'Không thể xóa màu sắc vì đang được sử dụng trong chi tiết sản phẩm!');
        }

        $color->update([
            'IsDeleted' => 1,
            'NguoiXoa' => auth()->check() ? auth()->id() : null,
            'NgayXoa' => now(),
        ]);

        return redirect()
            ->route('admin.colors.index')
            ->with('success', 'Xóa màu sắc thành công!');
    }
}
