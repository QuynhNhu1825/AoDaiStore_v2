<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminSizeController extends Controller
{
    public function index(Request $request)
    {
        $query = Size::query();

        if ($request->filled('search')) {
            $query->where('TenSize', 'like', '%' . $request->search . '%');
        }

        $sizes = $query->orderBy('MaSize', 'desc')->get();
        $totalSizeCount = Size::count();
        $activeSizeCount = Size::where('TrangThai', 1)->count();
        return view('admin.sizes.index', compact('sizes', 'totalSizeCount', 'activeSizeCount'));
    }
    public function create()
    {
        return view('admin.sizes.create');
    }
    public function store(Request $request)
    {

        $request->validate([
            'TenSize' => 'required|string|max:255|unique:size,TenSize',
            'MoTa' => 'required|string',
        ], [
            'TenSize.unique' => 'Kích cỡ này đã tồn tại trong hệ thống.',
            'TenSize.required' => 'Vui lòng nhập tên kích cỡ.',
            'MoTa.required' => 'Vui lòng nhập mô tả chi tiết.',
        ]);

        $data = $request->only(['TenSize', 'MoTa']);
        $data['TrangThai'] = $request->TrangThai ?? 1;

        Size::create($data);

        return redirect()->route('admin.sizes.index')
            ->with('success', 'Thêm kích cỡ mới thành công');
    }
    public function edit($MaSize)
    {

        $size = Size::findOrFail($MaSize);

        return view('admin.sizes.edit', compact('size'));
    }

    public function update(Request $request, $MaSize)
    {
        $size = Size::findOrFail($MaSize);

        $request->validate([
            'TenSize' => 'required|string|max:255|unique:size,TenSize,' . $MaSize . ',MaSize',
            'MoTa' => 'required|string',
        ], [
            'TenSize.unique' => 'Kích cỡ này đã tồn tại trong hệ thống, vui lòng nhập tên khác.',
            'TenSize.required' => 'Tên kích cỡ không được để trống.',
        ]);

        $data = $request->only(['TenSize', 'MoTa']);
        $data['TrangThai'] = $request->TrangThai ?? 1;

        $size->update($data);

        return redirect()->route('admin.sizes.index')
            ->with('success', 'Cập nhật kích cỡ thành công');
    }
    public function destroy($MaSize)
    {
        $size = Size::findOrFail($MaSize);
        $productCount = DB::table('sanpham_size')->where('MaSize', $MaSize)->count();

        if ($productCount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa! Size tồn tại sản phẩm'
            ]);
        }

        $size->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa thành công!'
        ]);
    }
    public function toggleStatus($MaSize) 
    {
        $size = Size::findOrFail($MaSize);
        $size->TrangThai = $size->TrangThai == 1 ? 0 : 1;
        $size->save();

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật trạng thái size thành công.'
        ]);
    }
}
