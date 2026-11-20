<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FilesystemException;
use League\Flysystem\UnableToWriteFile;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RatingController extends Controller
{
    public function image(Request $request): BinaryFileResponse
    {
        $filename = $request->query('file');
        abort_unless(is_string($filename), 404);

        $path = Review::imageFile($filename);
        abort_if($path === null, 404);

        $mimeType = mime_content_type($path);
        abort_unless(in_array($mimeType, [
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/gif',
            'image/bmp',
            'image/x-ms-bmp',
        ], true), 404);

        return response()->file($path, [
            'Content-Type' => $mimeType,
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function store(Request $request, $MaSanPham)
    {
        $validated = $request->validateWithBag('rating', [
            'MaDonHang' => 'required|integer',
            'SoSao' => 'required|integer|min:1|max:5',
            'NoiDung' => 'nullable|string|max:1000',
            'HinhAnh' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;

        if ($image = $request->file('HinhAnh')) {
            try {
                $imagePath = $image->store('ratings', 'public');

                if ($imagePath === false) {
                    throw UnableToWriteFile::atLocation('ratings');
                }
            } catch (FilesystemException $exception) {
                report($exception);

                return back()->with('error', 'Không thể lưu ảnh đánh giá. Vui lòng thử lại.');
            }
        }

        try {
            DB::table('danhgia')->insert([
                'MaDonHang'   => $validated['MaDonHang'],
                'SoSao'       => $validated['SoSao'],
                'NoiDung'     => $validated['NoiDung'] ?? null,
                'HinhAnh'     => $imagePath,
                'MaSanPham'   => $MaSanPham,
                'MaTaiKhoan'  => Auth::id(),
                'NgayDanhGia' => now(),
                'TrangThai'   => 1,
            ]);
        } catch (QueryException $exception) {
            if ($imagePath !== null) {
                Storage::disk('public')->delete($imagePath);
            }

            throw $exception;
        }

        return back()->with('success', 'Đánh giá của bạn đã được gửi!');
    }
}
