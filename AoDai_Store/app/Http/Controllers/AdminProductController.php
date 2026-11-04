<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Material;
use App\Models\Size;
use App\Models\Supplier;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['chatlieu', 'loaisanpham', 'loaimau', 'sizes']);
        $categories = Category::all();
        $materials = Material::all();
        $colors = Color::all();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('TenSanPham', 'like', '%' . $search . '%')
                    ->orWhereHas('chatlieu', function ($sub) use ($search) {
                        $sub->where('TenChatLieu', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('category')) {
            $query->where('MaLoaiSP', $request->category);
        }

        if ($request->filled('material')) {
            $query->where('MaChatLieu', $request->material);
        }

        if ($request->filled('color')) {
            $query->where('MaLoaiMau', $request->color);
        }

        $products = $query->orderBy('CreatedDate', 'desc')->paginate(10);
        $products->appends($request->all());
        $totalProducts = Product::count();
        $activeProducts = Product::where('TrangThai', 1)->count();
        return view('admin.products.index', compact('products', 'totalProducts', 'categories', 'materials', 'colors', 'activeProducts'));
    }


    public function create()
    {
        return view('admin.products.create', [
            'materials' => Material::all(),
            'categories' => Category::all(),
            'suppliers' => Supplier::all(),
            'colors' => Color::all(),
            'sizes' => Size::all()
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'TenSanPham' => 'required|string|max:255',
            'MoTa' => 'required|string',
            'HinhAnh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'AlbumHinh.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'GiaBan' => 'required|numeric',
            'MaLoaiSP' => 'required|exists:loaisanpham,MaLoaiSP',
            'MaChatLieu' => 'required|exists:chatlieu,MaChatLieu',
            'MaNCC' => 'required|exists:nhacungcap,MaNCC',
            'MaLoaiMau' => 'required|exists:loaimau,MaLoaiMau',
            'sizes' => 'required|array',
            'sizes.*' => 'integer|min:0'
        ]);

        $fileNameMain = null;
        if ($request->hasFile('HinhAnh')) {
            $file = $request->file('HinhAnh');
            $fileNameMain = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/products'), $fileNameMain);
        }

        $product = Product::create([
            'TenSanPham' => $request->TenSanPham,
            'GiaBan' => $request->GiaBan,
            'MaLoaiSP' => $request->MaLoaiSP,
            'MaChatLieu' => $request->MaChatLieu,
            'MaNCC' => $request->MaNCC,
            'MaLoaiMau' => $request->MaLoaiMau,
            'HinhAnh' => $fileNameMain,
            'TrangThai' => 1,
            'MoTa' => $request->MoTa,
            'CreatedDate' => now(),
        ]);

        if ($request->hasFile('AlbumHinh')) {
            foreach ($request->file('AlbumHinh') as $file) {
                $fileNameSub = time() . '_album_' . $file->getClientOriginalName();
                $file->move(public_path('img/details'), $fileNameSub);

                ProductImage::create([
                    'MaSanPham' => $product->MaSanPham,
                    'TenHinh' => $fileNameSub,
                    'DuongDan' => 'img/details/' . $fileNameSub,
                ]);
                
            }
        }

        foreach ($request->sizes as $sizeId => $soLuong) {
            if ($soLuong > 0) {
                $product->sizes()->attach($sizeId, [
                    'SoLuong' => $soLuong
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Thêm áo dài và ảnh thành công');
    }
    public function edit($MaSanPham)
    {
        $product = Product::with('hinhanhsanpham')->where('MaSanPham', $MaSanPham)->firstOrFail();

        return view('admin.products.edit', [
            'product' => $product,
            'materials' => Material::all(),
            'categories' => Category::all(),
            'suppliers' => Supplier::all(),
            'colors' => Color::all(),
            'sizes' => Size::all(),
        ]);
    }
    public function update(Request $request, $MaSanPham)
    {
        $product = Product::findOrFail($MaSanPham);

        $request->validate([
            'TenSanPham' => 'required|string|max:255',
            'MoTa' => 'required|string',
            'HinhAnh' => 'nullable|image|max:10240',
            'replace_images.*' => 'nullable|image|max:10240',
            'AlbumHinh.*' => 'nullable|image|max:10240',
            'GiaBan' => 'required|numeric',
            'MaLoaiSP' => 'required|exists:loaisanpham,MaLoaiSP',
            'MaChatLieu' => 'required|exists:chatlieu,MaChatLieu',
            'MaNCC' => 'required|exists:nhacungcap,MaNCC',
            'MaLoaiMau' => 'required|exists:loaimau,MaLoaiMau',
            'sizes' => 'required|array',
            'sizes.*' => 'integer|min:0'
        ]);

        $data = $request->only([
            'TenSanPham',
            'GiaBan',
            'MaLoaiSP',
            'MaChatLieu',
            'MaNCC',
            'MaLoaiMau',
            'MoTa'
        ]);
        $data['TrangThai'] = $request->TrangThai ?? 1;

        if ($request->hasFile('HinhAnh')) {

            if (
                $product->HinhAnh &&
                file_exists(public_path('img/products/' . $product->HinhAnh)) &&
                is_file(public_path('img/products/' . $product->HinhAnh))
            ) {
                unlink(public_path('img/products/' . $product->HinhAnh));
            }

            $file = $request->file('HinhAnh');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/products'), $fileName);
            $data['HinhAnh'] = $fileName;
        }

        $product->update($data);

        if ($request->hasFile('replace_images')) {
            foreach ($request->file('replace_images') as $id => $file) {

                $imageRecord = ProductImage::find($id);
                if (!$imageRecord || !$imageRecord->TenHinh)
                    continue;

                $oldPath = public_path('img/details/' . $imageRecord->TenHinh);

                if (file_exists($oldPath) && is_file($oldPath)) {
                    unlink($oldPath);
                }

                $newFileName = time() . '_replace_' . $file->getClientOriginalName();
                $file->move(public_path('img/details'), $newFileName);

                $imageRecord->update([
                    'TenHinh' => $newFileName,
                    'DuongDan' => 'img/details/' . $newFileName,
                ]);
            }
        }

        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $id) {

                $imageRecord = ProductImage::find($id);
                if (!$imageRecord || !$imageRecord->TenHinh)
                    continue;

                $filePath = public_path('img/details/' . $imageRecord->TenHinh);

                if (file_exists($filePath) && is_file($filePath)) {
                    unlink($filePath);
                }

                $imageRecord->delete();
            }
        }

        if ($request->hasFile('AlbumHinh')) {
            foreach ($request->file('AlbumHinh') as $file) {

                $fileName = time() . '_album_' . $file->getClientOriginalName();
                $file->move(public_path('img/details'), $fileName);

                ProductImage::create([
                    'MaSanPham' => $product->MaSanPham,
                    'TenHinh' => $fileName,
                    'DuongDan' => 'img/details/' . $fileName,
                ]);
            }
        }

        $syncData = [];
        foreach ($request->sizes as $sizeId => $soLuong) {
            $syncData[$sizeId] = ['SoLuong' => $soLuong];
        }
        $product->sizes()->sync($syncData);

        return redirect()->route('admin.products.index')
            ->with('success', 'Cập nhật sản phẩm thành công');
    }

    public function destroy($MaSanPham)
    {
        try {
            $product = Product::findOrFail($MaSanPham);
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Mẫu thiết kế đã được xóa thành công.'
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa vì sản phẩm này đang có trong giỏ hàng hoặc hóa đơn.'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }
    public function toggleStatus($MaSanPham)
    {
        $products = Product::findOrFail($MaSanPham);
        $products->TrangThai = $products->TrangThai == 1 ? 0 : 1;
        $products->save();

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật trạng thái danh mục thành công.'
        ]);
    }
}
