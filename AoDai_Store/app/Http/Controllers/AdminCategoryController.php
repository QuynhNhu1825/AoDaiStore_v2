<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    public function index(Request $request)
    {

        $query = Category::withCount('sanpham');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('TenLoaiSP', 'like', '%' . $request->search . '%')
                    ->orWhere('MaLoaiSP', $request->search);
            });
        }

        $categories = $query->orderBy('CreatedDate', 'desc')->get();
        $totalCategories = Category::count();
        $activeCategories = Category::where('TrangThai', 1)->count();

        return view('admin.categories.index', compact('categories', 'totalCategories', 'activeCategories'));
    }
    public function create()
    {
        return view('admin.categories.create');
    }
    public function store(Request $request)
    {

        $request->validate([
            'TenLoaiSP' => 'required|string|max:255|unique:loaisanpham,TenLoaiSP',
            'MoTa' => 'required|string',
        ], [
            'TenLoaiSP.unique' => 'Danh mục này đã tồn tại trong hệ thống',
            'TenLoaiSP.required' => 'Vui lòng nhập tên danh mục',
            'MoTa.required' => 'Vui lòng nhập mô tả'
        ]);

        $data = $request->only(['TenLoaiSP', 'MoTa']);
        $data['TrangThai'] = $request->TrangThai ?? 1;

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Thêm danh mục thành công');
    }
    public function edit($MaLoaiSP)
    {
        $category = Category::findOrFail($MaLoaiSP);
        return view('admin.categories.edit', compact('category'));
    }
    public function update(Request $request, $MaLoaiSP)
    {
        $category = Category::findOrFail($MaLoaiSP);
        $request->validate([
            'TenLoaiSP' => 'required|string|max:255|unique:loaisanpham,TenLoaiSP,' . $MaLoaiSP . ',MaLoaiSP',
            'MoTa' => 'required|string',
        ], [
            'TenLoaiSP.unique' => 'Danh mục đã tồn tại trong hệ thống, vui lòng nhập tên khác',
            'TenLoaiSP.required' => 'Tên danh mục không được bỏ trống'
        ]);

        $data = $request->only(['TenLoaiSP', 'MoTa']);
        $data['TrangThai'] = $request->TrangThai ?? 1;

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Cập nhật danh mục thành công');
    }
    public function destroy($MaLoaiSP)
    {

        $category = Category::findOrFail($MaLoaiSP);
        $productCount = Product::where('MaLoaiSP', $MaLoaiSP)->count();

        if ($productCount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa! Danh mục tồn tại sản phẩm'
            ]);
        }

        $category->delete();
        return response()->json([
            'success' => true,
            'message' => 'Xóa thành công!',
        ]);
    }
    public function toggleStatus($MaLoaiSP)
    {
        $category = Category::findOrFail($MaLoaiSP);
        $category->TrangThai = $category->TrangThai == 1 ? 0 : 1;
        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật trạng thái danh mục thành công.'
        ]);
    }
}
