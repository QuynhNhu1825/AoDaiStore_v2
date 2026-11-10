<?php

namespace App\Http\Controllers;

use App\Models\Size;
use App\Models\ProductDetail;
use Illuminate\Http\Request;

class AdminSizeController extends Controller
{
    /**
     * Danh sách kích cỡ
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Size::where('IsDeleted', 0);

        // Tìm kiếm theo tên size
        if (!empty($search)) {
            $query->where('TenSize', 'like', "%{$search}%");
        }

        $sizes = $query->orderBy('MaSize', 'desc')->get();

        return view('admin.sizes.index', compact(
            'sizes',
            'search'
        ));
    }

    /**
     * Form thêm kích cỡ
     */
    public function create()
    {
        return view('admin.sizes.create');
    }

    /**
     * Thêm kích cỡ
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'TenSize' => [
                'required',
                'string',
                'max:20',
                'unique:size,TenSize',
            ],
        ], [
            'TenSize.required' => 'Vui lòng nhập tên kích cỡ.',
            'TenSize.max' => 'Tên kích cỡ không được vượt quá 20 ký tự.',
            'TenSize.unique' => 'Kích cỡ này đã tồn tại trong hệ thống.',
        ]);

        $validated['IsDeleted'] = 0;
        $validated['NgayTao'] = now();
        $validated['NguoiTao'] = auth()->check()
            ? auth()->id()
            : null;

        Size::create($validated);

        return redirect()
            ->route('admin.sizes.index')
            ->with('success', 'Thêm kích cỡ mới thành công!');
    }

    /**
     * Form chỉnh sửa kích cỡ
     */
    public function edit($MaSize)
    {
        $size = Size::where('IsDeleted', 0)
            ->findOrFail($MaSize);

        return view('admin.sizes.edit', compact('size'));
    }

    /**
     * Cập nhật kích cỡ
     */
    public function update(Request $request, $MaSize)
    {
        $size = Size::where('IsDeleted', 0)
            ->findOrFail($MaSize);

        $validated = $request->validate([
            'TenSize' => [
                'required',
                'string',
                'max:20',
                'unique:size,TenSize,' . $MaSize . ',MaSize',
            ],
        ], [
            'TenSize.required' => 'Tên kích cỡ không được để trống.',
            'TenSize.max' => 'Tên kích cỡ không được vượt quá 20 ký tự.',
            'TenSize.unique' => 'Kích cỡ này đã tồn tại trong hệ thống.',
        ]);

        $validated['NguoiCapNhat'] = auth()->check()
            ? auth()->id()
            : null;

        $validated['NgayCapNhat'] = now();

        $size->update($validated);

        return redirect()
            ->route('admin.sizes.index')
            ->with('success', 'Cập nhật kích cỡ thành công!');
    }

    /**
     * Xóa mềm kích cỡ
     */
    public function destroy($MaSize)
    {
        $size = Size::where('IsDeleted', 0)
            ->findOrFail($MaSize);

        // Kiểm tra size có đang được sử dụng
        // trong chi tiết sản phẩm chưa bị xóa hay không
        $productCount = ProductDetail::where('MaSize', $MaSize)
            ->where('IsDeleted', 0)
            ->count();

        if ($productCount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa kích cỡ này vì đang được sử dụng trong chi tiết sản phẩm!'
            ], 422);
        }

        // Xóa mềm
        $size->update([
            'IsDeleted' => 1,
            'NguoiXoa' => auth()->check()
                ? auth()->id()
                : null,
            'NgayXoa' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Xóa kích cỡ thành công!'
        ]);
    }
}
