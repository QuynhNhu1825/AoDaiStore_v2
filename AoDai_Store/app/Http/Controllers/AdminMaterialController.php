<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class AdminMaterialController extends Controller
{
    // Danh sách + tìm kiếm
    public function index(Request $request)
    {
        $search = $request->search;

        $materials = Material::when($search, function ($q) use ($search) {
                $q->where('TenChatLieu', 'like', "%$search%")
                  ->orWhere('Xuatxu', 'like', "%$search%");
            })
            ->orderBy('MaChatLieu', 'asc')
            ->get();

        $totalMaterials = Material::count();

        return view('admin.materials.index', compact(
            'materials',
            'totalMaterials',
            'search'
        ));
    }

    // Form thêm
    public function create()
    {
        return view('admin.materials.create');
    }

    // Lưu thêm mới
    public function store(Request $request)
    {
        $request->validate([
            'TenChatLieu'       => 'required|string|max:255',
            'Xuatxu'            => 'nullable|string|max:255',
            'HuongDanBaoQuan'   => 'nullable|string',
            'TrangThai'         => 'required'
        ]);

        Material::create([
            'TenChatLieu'     => $request->TenChatLieu,
            'Xuatxu'          => $request->Xuatxu,
            'HuongDanBaoQuan' => $request->HuongDanBaoQuan,
            'TrangThai'       => $request->TrangThai,
        ]);

        return redirect()
            ->route('admin.materials.index')
            ->with('success', 'Thêm chất liệu thành công!');
    }

    // Form sửa
    public function edit($id)
    {
        $material = Material::findOrFail($id);
        return view('admin.materials.edit', compact('material'));
    }

    // Cập nhật
    public function update(Request $request, $id)
    {
        $request->validate([
            'TenChatLieu'       => 'required|string|max:255',
            'Xuatxu'            => 'nullable|string|max:255',
            'HuongDanBaoQuan'   => 'nullable|string',
            'TrangThai'         => 'required'
        ]);

        $material = Material::findOrFail($id);

        $material->update([
            'TenChatLieu'     => $request->TenChatLieu,
            'Xuatxu'          => $request->Xuatxu,
            'HuongDanBaoQuan' => $request->HuongDanBaoQuan,
            'TrangThai'       => $request->TrangThai,
        ]);

        return redirect()
            ->route('admin.materials.index')
            ->with('success', 'Cập nhật chất liệu thành công!');
    }

    // Xóa
    public function destroy($id)
    {
        $material = Material::findOrFail($id);

        if ($material->products()->exists()) {
            return redirect()
                ->route('admin.materials.index')
                ->with('error', 'Không thể xóa chất liệu vì vẫn còn sản phẩm đang sử dụng!');
        }

        $material->delete();

        return redirect()
            ->route('admin.materials.index')
            ->with('success', 'Xóa chất liệu thành công!');
    }

}
