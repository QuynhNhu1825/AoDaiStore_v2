<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query()
            ->where('IsDeleted', 0)
            ->withCount('products');

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('TenDanhMuc', 'like', '%' . $search . '%');
                if (ctype_digit($search)) {
                    $q->orWhere('MaDanhMuc', (int) $search);
                }
            });
        }

        $categories = $query->orderByDesc('MaDanhMuc')
            ->paginate(8)
            ->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'TenDanhMuc' => ['required', 'string', 'max:255', Rule::unique('danhmucsp', 'TenDanhMuc')],
            'MoTa' => ['required', 'string'],
        ], [
            'TenDanhMuc.required' => 'Vui lòng nhập tên danh mục.',
            'TenDanhMuc.unique' => 'Danh mục này đã tồn tại trong hệ thống.',
            'MoTa.required' => 'Vui lòng nhập mô tả danh mục.',
        ]);

        Category::create([
            'TenDanhMuc' => trim($validated['TenDanhMuc']),
            'MoTa' => trim($validated['MoTa']),
            'IsDeleted' => 0,
            'NguoiTao' => auth()->id(),
            'NgayTao' => now(),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Thêm danh mục thành công!');
    }

    public function edit($MaDanhMuc)
    {
        $category = Category::where('IsDeleted', 0)->findOrFail($MaDanhMuc);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $MaDanhMuc)
    {
        $category = Category::where('IsDeleted', 0)->findOrFail($MaDanhMuc);

        $validated = $request->validate([
            'TenDanhMuc' => [
                'required', 'string', 'max:255',
                Rule::unique('danhmucsp', 'TenDanhMuc')->ignore($category->MaDanhMuc, 'MaDanhMuc'),
            ],
            'MoTa' => ['required', 'string'],
        ], [
            'TenDanhMuc.required' => 'Vui lòng nhập tên danh mục.',
            'TenDanhMuc.unique' => 'Danh mục này đã tồn tại trong hệ thống.',
            'MoTa.required' => 'Vui lòng nhập mô tả danh mục.',
        ]);

        $category->update([
            'TenDanhMuc' => trim($validated['TenDanhMuc']),
            'MoTa' => trim($validated['MoTa']),
            'NguoiCapNhat' => auth()->id(),
            'NgayCapNhat' => now(),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroy($MaDanhMuc)
    {
        return DB::transaction(function () use ($MaDanhMuc) {
            $category = Category::where('IsDeleted', 0)
                ->lockForUpdate()->findOrFail($MaDanhMuc);

            // Không xóa danh mục còn liên kết với sản phẩm.
            $productCount = $category->products()->count();
            if ($productCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Không thể xóa! Danh mục đang chứa {$productCount} sản phẩm.",
                ], 422);
            }

            $category->update([
                'IsDeleted' => 1,
                'NguoiXoa' => auth()->id(),
                'NgayXoa' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Xóa danh mục thành công!',
            ]);
        });
    }
}
