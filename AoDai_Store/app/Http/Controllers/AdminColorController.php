<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;

class AdminColorController extends Controller
{
    // Danh sách + tìm kiếm
    public function index(Request $request)
    {
        $search = $request->search;

        $colors = Color::when($search, function ($q) use ($search) {
                $q->where('TenLoaiMau', 'like', "%$search%")
                  ->orWhere('MaLoaiMau', 'like', "%$search%");
            })
            ->orderBy('MaLoaiMau', 'asc')
            ->get();

        $totalColors = Color::count();

        return view('admin.colors.index', compact(
            'colors',
            'totalColors',
            'search'
        ));
    }

    // Form thêm
    public function create()
    {
        return view('admin.colors.create');
    }

    // Lưu thêm mới
    public function store(Request $request)
    {
        $request->validate([
            'TenLoaiMau'   => 'required|string|max:255',
            'MoTa'         => 'nullable|string',
            'TrangThai'    => 'required|in:0,1',
            'HinhAnhMau'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $data = $request->only(['TenLoaiMau', 'MoTa', 'TrangThai']);

        if ($request->hasFile('HinhAnhMau')) {
            $data['HinhAnhMau'] = $request->file('HinhAnhMau')
                                           ->store('colors', 'public');
        }

        Color::create($data);

        return redirect()
            ->route('admin.colors.index')
            ->with('success', 'Thêm loại màu thành công!');
    }

    // Form sửa
    public function edit($id)
    {
        $color = Color::findOrFail($id);
        return view('admin.colors.edit', compact('color'));
    }

    // Cập nhật
    public function update(Request $request, $id)
    {
        $request->validate([
            'TenLoaiMau'   => 'required|string|max:255',
            'MoTa'         => 'nullable|string',
            'TrangThai'    => 'required|in:0,1',
            'HinhAnhMau'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $color = Color::findOrFail($id);

        $data = $request->only(['TenLoaiMau', 'MoTa', 'TrangThai']);

        if ($request->hasFile('HinhAnhMau')) {
            $data['HinhAnhMau'] = $request->file('HinhAnhMau')
                                           ->store('colors', 'public');
        }

        $color->update($data);

        return redirect()
            ->route('admin.colors.index')
            ->with('success', 'Cập nhật loại màu thành công!');
    }

    // Xóa
    public function destroy($id)
    {
        $color = Color::findOrFail($id);

        if ($color->products()->exists()) {
            return redirect()
                ->route('admin.colors.index')
                ->with('error', 'Không thể xóa loại màu vì vẫn còn sản phẩm đang sử dụng!');
        }

        $color->delete();

        return redirect()
            ->route('admin.colors.index')
            ->with('success', 'Xóa loại màu thành công!');
    }
}
