<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Material;
use App\Models\Color;
use App\Models\Size;

class AdminProductController extends Controller
{
    /**
     * Upload ảnh vào thư mục public.
     */
    private function uploadImage(
        UploadedFile $file,
        string $folder,
        array &$uploadedFiles
    ) {
        $directory = public_path($folder);

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $fileName = Str::uuid() . '.' .
            $file->getClientOriginalExtension();

        $file->move($directory, $fileName);

        $fullPath = $directory . DIRECTORY_SEPARATOR . $fileName;

        $uploadedFiles[] = $fullPath;

        return $fileName;
    }
    private function deleteFiles(array $files)
    {
        foreach ($files as $file) {
            if ($file && is_file($file)) {
                @unlink($file);
            }
        }
    }
    private function imagePath(string $folder, ?string $fileName)
    {
        if (!$fileName) {
            return null;
        }

        return public_path(
            $folder . '/' . basename($fileName)
        );
    }
    public function index(Request $request)
    {
        $query = Product::with([
            'chatlieu',

            'hinhanhsanpham' => function ($q) {
                $q->where('IsDeleted', 0)
                  ->orderBy('ThuTu');
            },

            'danhMucs',

            'chiTietSanPham' => function ($q) {
                $q->where('IsDeleted', 0)
                  ->with(['color', 'size']);
            }

        ])->where('IsDeleted', 0);
        $categories = Category::where('IsDeleted', 0)->get();

        $materials = Material::where('IsDeleted', 0)->get();

        $colors = Color::where('IsDeleted', 0)->get();
        // TÌM KIẾM SẢN PHẨM
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('TenSanPham', 'like', "%{$search}%")

                  ->orWhereHas('chatlieu', function ($sub) use ($search) {
                      $sub->where('TenChatLieu', 'like', "%{$search}%");
                  });

            });
        }
        if ($request->filled('category')) {

            $query->whereHas('danhMucs', function ($q) use ($request) {

                $q->where(
                    'danhmucsp.MaDanhMuc',
                    $request->category
                );

                $q->where('danhmucsp.IsDeleted', 0);

            });
        }
        if ($request->filled('material')) {

            $query->where('MaChatLieu', $request->material);

        }

        if ($request->filled('color')) {

            $query->whereHas('chiTietSanPham', function ($q) use ($request) {

                $q->where('MaMau', $request->color)
                  ->where('IsDeleted', 0);

            });
        }
        if ($request->filled('status')) {

            $query->where('TrangThai', $request->status);

        }
        $products = $query
        ->orderBy('NgayTao', 'desc')
        ->paginate(10)
        ->withQueryString();

        $products->appends($request->query());

        $totalProducts = Product::where('IsDeleted', 0)->count();

        $activeProducts = Product::where('IsDeleted', 0)
            ->where('TrangThai', 1)
            ->count();

        return view('admin.products.index', compact(
            'products',
            'totalProducts',
            'activeProducts',
            'categories',
            'materials',
            'colors'
        ));
    }
    public function create()
    {
        return view('admin.products.create', [

            'materials' => Material::where('IsDeleted', 0)->get(),

            'categories' => Category::where('IsDeleted', 0)->get(),

            'colors' => Color::where('IsDeleted', 0)->get(),

            'sizes' => Size::where('IsDeleted', 0)->get(),

        ]);
    }
    public function store(Request $request)
{
    // VALIDATE
    $request->validate([
        'TenSanPham' => 'required|string|max:200',
        'MoTa' => 'nullable|string',

        'MaChatLieu' => 'required|exists:chatlieu,MaChatLieu',

        // Nhiều danh mục
        'MaDanhMuc' => 'required|array|min:1',
        'MaDanhMuc.*' => 'required|distinct|exists:danhmucsp,MaDanhMuc',

        // Nhiều màu sắc
        'MaMau' => 'required|array|min:1',
        'MaMau.*' => 'required|distinct|exists:mausac,MaMau',

        // Biến thể màu sắc + kích cỡ
        'variants' => 'required|array|min:1',
        'variants.*' => 'required|array|min:1',
        'variants.*.*.Gia' => 'required|numeric|min:0',
        'variants.*.*.SoLuong' => 'required|integer|min:0',

        'HinhAnh' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',

        'AlbumHinh' => 'nullable|array',
        'AlbumHinh.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',

    ], [
        'TenSanPham.required' => 'Vui lòng nhập tên sản phẩm.',
        'MaChatLieu.required' => 'Vui lòng chọn chất liệu.',

        'MaDanhMuc.required' => 'Vui lòng chọn ít nhất một danh mục.',
        'MaDanhMuc.min' => 'Vui lòng chọn ít nhất một danh mục.',

        'MaMau.required' => 'Vui lòng chọn ít nhất một màu sắc.',
        'MaMau.min' => 'Vui lòng chọn ít nhất một màu sắc.',

        'variants.required' => 'Vui lòng nhập thông tin biến thể sản phẩm.',
        'variants.*.*.Gia.required' => 'Vui lòng nhập giá bán cho từng biến thể.',
        'variants.*.*.Gia.numeric' => 'Giá bán phải là số.',
        'variants.*.*.Gia.min' => 'Giá bán không được nhỏ hơn 0.',

        'variants.*.*.SoLuong.required' => 'Vui lòng nhập số lượng tồn kho.',
        'variants.*.*.SoLuong.integer' => 'Số lượng phải là số nguyên.',
        'variants.*.*.SoLuong.min' => 'Số lượng không được nhỏ hơn 0.',

        'HinhAnh.image' => 'Ảnh đại diện không đúng định dạng.',
    ]);

    // Kiểm tra màu sắc gửi lên có khớp với các biến thể hay không
    $selectedColors = array_map('strval', $request->MaMau);
    $variantColors = array_map('strval', array_keys($request->variants));

    if (array_diff($variantColors, $selectedColors)) {
        return back()
            ->withInput()
            ->with('error', 'Thông tin biến thể không khớp với màu sắc đã chọn.');
    }

    DB::beginTransaction();

    $uploadedFiles = [];

    try {
        // UPLOAD ẢNH ĐẠI DIỆN
        $fileNameMain = null;

        if ($request->hasFile('HinhAnh')) {
            $fileNameMain = $this->uploadImage(
                $request->file('HinhAnh'),
                'img/products',
                $uploadedFiles
            );
        }

        // TẠO SẢN PHẨM
        $product = Product::create([
            'TenSanPham' => $request->TenSanPham,
            'MaChatLieu' => $request->MaChatLieu,
            'MoTa' => $request->MoTa,
            'HinhAnh' => $fileNameMain,
            'TrangThai' => 1,
            'NguoiTao' => Auth::id(),
            'NgayTao' => now(),
            'IsDeleted' => 0,
        ]);

        // LƯU NHIỀU DANH MỤC
        $product->danhMucs()->sync($request->MaDanhMuc);

        // TẠO CÁC BIẾN THỂ MÀU SẮC + KÍCH CỠ
        foreach ($request->variants as $colorId => $sizes) {

            foreach ($sizes as $sizeId => $variant) {

                ProductDetail::create([
                    'MaSanPham' => $product->MaSanPham,
                    'MaMau' => $colorId,
                    'MaSize' => $sizeId,

                    'Gia' => $variant['Gia'],
                    'SoLuong' => $variant['SoLuong'],

                    'NguoiTao' => Auth::id(),
                    'NgayTao' => now(),
                    'IsDeleted' => 0,
                ]);
            }
        }

        // UPLOAD ALBUM ẢNH
        if ($request->hasFile('AlbumHinh')) {

            foreach ($request->file('AlbumHinh') as $index => $file) {

                $fileName = $this->uploadImage(
                    $file,
                    'img/details',
                    $uploadedFiles
                );

                ProductImage::create([
                    'MaSanPham' => $product->MaSanPham,
                    'LinkAnh' => $fileName,
                    'ThuTu' => $index + 1,
                    'NguoiTao' => Auth::id(),
                    'NgayTao' => now(),
                    'IsDeleted' => 0,
                ]);
            }
        }

        DB::commit();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Thêm sản phẩm thành công!');

    } catch (\Throwable $e) {

        if (DB::transactionLevel() > 0) {
            DB::rollBack();
        }

        // XÓA ẢNH ĐÃ UPLOAD NẾU LƯU THẤT BẠI
        $this->deleteFiles($uploadedFiles);

        return back()
            ->withInput()
            ->with('error', 'Không thể thêm sản phẩm: ' . $e->getMessage());
    }
}    
public function edit($MaSanPham)
{
    $product = Product::with([
        'hinhanhsanpham' => function ($q) {
            $q->where('IsDeleted', 0)->orderBy('ThuTu');
        },
        'danhMucs',
        'chiTietSanPham' => function ($q) {
            $q->where('IsDeleted', 0)
              ->with(['color', 'size']);
        }
    ])
    ->where('IsDeleted', 0)
    ->findOrFail($MaSanPham);

    return view('admin.products.edit', [
        'product' => $product,
        'materials' => Material::where('IsDeleted', 0)->get(),
        'categories' => Category::where('IsDeleted', 0)->get(),
        'colors' => Color::where('IsDeleted', 0)->get(),
        'sizes' => Size::where('IsDeleted', 0)->get(),
    ]);
}
    public function update(Request $request, $MaSanPham)
{
    $product = Product::where('IsDeleted', 0)
        ->findOrFail($MaSanPham);

    // VALIDATE
    $request->validate([
        'TenSanPham' => 'required|string|max:200',
        'MoTa' => 'nullable|string',
        'MaChatLieu' => 'required|exists:chatlieu,MaChatLieu',

        'MaDanhMuc' => 'required|array|min:1',
        'MaDanhMuc.*' => 'required|distinct|exists:danhmucsp,MaDanhMuc',

        'MaMau' => 'required|array|min:1',
        'MaMau.*' => 'required|distinct|exists:mausac,MaMau',

        'variants' => 'required|array|min:1',
        'variants.*' => 'required|array|min:1',
        'variants.*.*.Gia' => 'required|numeric|min:0',
        'variants.*.*.SoLuong' => 'required|integer|min:0',

        'HinhAnh' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',

        'AlbumHinh' => 'nullable|array',
        'AlbumHinh.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',

        'replace_images' => 'nullable|array',
        'replace_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',

        'delete_images' => 'nullable|array',
        'delete_images.*' => 'integer',

        'TrangThai' => 'required|boolean',
    ], [
        'TenSanPham.required' => 'Vui lòng nhập tên sản phẩm.',
        'MaChatLieu.required' => 'Vui lòng chọn chất liệu.',
        'MaDanhMuc.required' => 'Vui lòng chọn ít nhất một danh mục.',
        'MaMau.required' => 'Vui lòng chọn ít nhất một màu sắc.',
        'variants.required' => 'Vui lòng nhập thông tin biến thể sản phẩm.',
        'variants.*.*.Gia.required' => 'Vui lòng nhập giá bán cho từng biến thể.',
        'variants.*.*.SoLuong.required' => 'Vui lòng nhập số lượng cho từng biến thể.',
    ]);

    // Kiểm tra màu được chọn có khớp với màu trong biến thể
    $selectedColors = array_map('strval', $request->MaMau);
    $variantColors = array_map('strval', array_keys($request->variants));

    if (array_diff($variantColors, $selectedColors)) {
        return back()
            ->withInput()
            ->with('error', 'Thông tin biến thể không khớp với màu sắc đã chọn.');
    }

    DB::beginTransaction();

    $uploadedFiles = [];
    $oldFiles = [];

    try {
        // CẬP NHẬT THÔNG TIN SẢN PHẨM
        $data = [
            'TenSanPham' => $request->TenSanPham,
            'MaChatLieu' => $request->MaChatLieu,
            'MoTa' => $request->MoTa,
            'TrangThai' => $request->TrangThai,
            'NguoiCapNhat' => Auth::id(),
            'NgayCapNhat' => now(),
        ];

        // ẢNH ĐẠI DIỆN
        if ($request->hasFile('HinhAnh')) {
            $fileName = $this->uploadImage(
                $request->file('HinhAnh'),
                'img/products',
                $uploadedFiles
            );

            if ($product->HinhAnh) {
                $oldFiles[] = $this->imagePath(
                    'img/products',
                    $product->HinhAnh
                );
            }

            $data['HinhAnh'] = $fileName;
        }

        $product->update($data);

        // CẬP NHẬT NHIỀU DANH MỤC
        $product->danhMucs()->sync($request->MaDanhMuc);

        // CẬP NHẬT BIẾN THỂ
        $submittedVariantIds = [];

        foreach ($request->variants as $colorId => $sizes) {
            foreach ($sizes as $sizeId => $variant) {

                $detail = ProductDetail::where('MaSanPham', $product->MaSanPham)
                ->where('MaMau', $colorId)
                ->where('MaSize', $sizeId)
                ->where('IsDeleted', 0)
                ->first();

                if (!$detail) {
                    $detail = ProductDetail::where('MaSanPham', $product->MaSanPham)
                        ->where('MaMau', $colorId)
                        ->where('MaSize', $sizeId)
                        ->where('IsDeleted', 1)
                        ->first();
                }

                $variantData = [
                    'Gia' => $variant['Gia'],
                    'SoLuong' => $variant['SoLuong'],
                    'IsDeleted' => 0,
                    'NguoiCapNhat' => Auth::id(),
                    'NgayCapNhat' => now(),
                    'NguoiXoa' => null,
                    'NgayXoa' => null,
                ];

                if ($detail) {
                    $detail->update($variantData);
                    $submittedVariantIds[] = $detail->MaChiTietSP;
                } else {
                    $detail = ProductDetail::create([
                        'MaSanPham' => $product->MaSanPham,
                        'MaMau' => $colorId,
                        'MaSize' => $sizeId,
                        'Gia' => $variant['Gia'],
                        'SoLuong' => $variant['SoLuong'],
                        'NguoiTao' => Auth::id(),
                        'NgayTao' => now(),
                        'IsDeleted' => 0,
                    ]);

                    $submittedVariantIds[] = $detail->MaChiTietSP;
                }
            }
        }

        // ẨN CÁC BIẾN THỂ ĐÃ BỎ KHỎI FORM
        ProductDetail::where('MaSanPham', $product->MaSanPham)
            ->where('IsDeleted', 0)
            ->whereNotIn('MaChiTietSP', $submittedVariantIds)
            ->update([
                'IsDeleted' => 1,
                'NguoiXoa' => Auth::id(),
                'NgayXoa' => now(),
            ]);

        // THAY THẾ ẢNH ALBUM
        if ($request->hasFile('replace_images')) {
            foreach ($request->file('replace_images') as $id => $file) {

                $image = ProductImage::where('MaHinhAnh', $id)
                    ->where('MaSanPham', $product->MaSanPham)
                    ->where('IsDeleted', 0)
                    ->first();

                if (!$image) {
                    continue;
                }

                $oldFiles[] = $this->imagePath(
                    'img/details',
                    $image->LinkAnh
                );

                $newFileName = $this->uploadImage(
                    $file,
                    'img/details',
                    $uploadedFiles
                );

                $image->update([
                    'LinkAnh' => $newFileName,
                    'NguoiCapNhat' => Auth::id(),
                    'NgayCapNhat' => now(),
                ]);
            }
        }

        // XÓA ẢNH ALBUM
        if ($request->filled('delete_images')) {
            foreach ($request->delete_images as $id) {

                $image = ProductImage::where('MaHinhAnh', $id)
                    ->where('MaSanPham', $product->MaSanPham)
                    ->where('IsDeleted', 0)
                    ->first();

                if (!$image) {
                    continue;
                }

                $oldFiles[] = $this->imagePath(
                    'img/details',
                    $image->LinkAnh
                );

                $image->update([
                    'IsDeleted' => 1,
                    'NguoiXoa' => Auth::id(),
                    'NgayXoa' => now(),
                ]);
            }
        }

        // THÊM ẢNH MỚI VÀO ALBUM
        if ($request->hasFile('AlbumHinh')) {

            $maxOrder = ProductImage::where(
                'MaSanPham',
                $product->MaSanPham
            )->max('ThuTu') ?? 0;

            foreach ($request->file('AlbumHinh') as $index => $file) {

                $fileName = $this->uploadImage(
                    $file,
                    'img/details',
                    $uploadedFiles
                );

                ProductImage::create([
                    'MaSanPham' => $product->MaSanPham,
                    'LinkAnh' => $fileName,
                    'ThuTu' => $maxOrder + $index + 1,
                    'NguoiTao' => Auth::id(),
                    'NgayTao' => now(),
                    'IsDeleted' => 0,
                ]);
            }
        }

        DB::commit();

        // Chỉ xóa ảnh cũ sau khi cập nhật database thành công
        $this->deleteFiles($oldFiles);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Cập nhật sản phẩm thành công!');

    } catch (\Throwable $e) {

        if (DB::transactionLevel() > 0) {
            DB::rollBack();
        }

        $this->deleteFiles($uploadedFiles);

        return back()
            ->withInput()
            ->with('error', 'Không thể cập nhật sản phẩm: ' . $e->getMessage());
    }
}
    public function destroy($MaSanPham)
    {
        try {

            DB::beginTransaction();
            $product = Product::where('IsDeleted', 0)
                ->findOrFail($MaSanPham);

            $product->update([

                'IsDeleted' => 1,

                'TrangThai' => 0,

                'NguoiXoa' => Auth::id(),

                'NgayXoa' => now(),
            ]);

            ProductDetail::where('MaSanPham', $MaSanPham)
                ->where('IsDeleted', 0)
                ->update([
                    'IsDeleted' => 1,
                    'NguoiXoa' => Auth::id(),
                    'NgayXoa' => now(),
                ]);

            ProductImage::where('MaSanPham', $MaSanPham)
                ->where('IsDeleted', 0)
                ->update([
                    'IsDeleted' => 1,
                    'NguoiXoa' => Auth::id(),
                    'NgayXoa' => now(),
                ]);

            DB::commit();
            return response()->json([

                'success' => true,
                'message' => 'Xóa sản phẩm thành công!'
            ]);
        } catch (\Throwable $e) {

            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa sản phẩm: ' . $e->getMessage()
            ], 500);

        }
    }
    public function toggleStatus($MaSanPham)
    {
        try {

            $product = Product::where('IsDeleted', 0)
                ->findOrFail($MaSanPham);
            $product->TrangThai = $product->TrangThai ? 0 : 1;
            $product->NguoiCapNhat = Auth::id();
            $product->NgayCapNhat = now();
            $product->save();
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật trạng thái sản phẩm thành công.',
                'TrangThai' => $product->TrangThai
            ]);
        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật trạng thái: ' . $e->getMessage()
            ], 500);

        }
    }
}
