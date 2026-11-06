<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class AdminMaterialController extends Controller
{
    /**
     * Danh sách chất liệu
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Material::where('IsDeleted', 0);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('TenChatLieu', 'like', "%{$search}%")
                  ->orWhere('XuatXu', 'like', "%{$search}%");
            });
        }

        $materials = $query
            ->orderBy('MaChatLieu', 'asc')
            ->get();

        $totalMaterials = Material::where('IsDeleted', 0)->count();

        return view('admin.materials.index', compact(
            'materials',
            'totalMaterials',
            'search'
        ));
    }

    /**
     * Form thêm chất liệu
     */
    public function create()
    {
        return view('admin.materials.create');
    }

    /**
     * Lưu chất liệu
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'TenChatLieu' => 'required|string|max:100',
            'XuatXu' => 'nullable|string|max:100',
            'HuongDanBaoQuan' => 'nullable|string|max:500',
            'MoTa' => 'nullable|string|max:500',
        ]);

        $validated['IsDeleted'] = 0;
        $validated['NgayTao'] = now();

        if (auth()->check()) {
            $validated['NguoiTao'] = auth()->id();
        }

        Material::create($validated);

        return redirect()
            ->route('admin.materials.index')
            ->with('success', 'Thêm chất liệu thành công!');
    }

    /**
     * Form sửa chất liệu
     */
    public function edit($id)
    {
        $material = Material::where('IsDeleted', 0)
            ->findOrFail($id);

        return view('admin.materials.edit', compact('material'));
    }

    /**
     * Cập nhật chất liệu
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'TenChatLieu' => 'required|string|max:100',
            'XuatXu' => 'nullable|string|max:100',
            'HuongDanBaoQuan' => 'nullable|string|max:500',
            'MoTa' => 'nullable|string|max:500',
        ]);

        $material = Material::where('IsDeleted', 0)
            ->findOrFail($id);

        $validated['NguoiCapNhat'] = auth()->check()
            ? auth()->id()
            : null;

        $validated['NgayCapNhat'] = now();

        $material->update($validated);

        return redirect()
            ->route('admin.materials.index')
            ->with('success', 'Cập nhật chất liệu thành công!');
    }

    /**
     * Xóa mềm chất liệu
     */
    public function destroy($id)
    {
        $material = Material::where('IsDeleted', 0)
            ->findOrFail($id);

        if ($material->products()->exists()) {
            return redirect()
                ->route('admin.materials.index')
                ->with('error', 'Không thể xóa chất liệu vì vẫn còn sản phẩm đang sử dụng!');
        }

        $material->update([
            'IsDeleted' => 1,
            'NguoiXoa' => auth()->check() ? auth()->id() : null,
            'NgayXoa' => now(),
        ]);

        return redirect()
            ->route('admin.materials.index')
            ->with('success', 'Xóa chất liệu thành công!');
    }
}
