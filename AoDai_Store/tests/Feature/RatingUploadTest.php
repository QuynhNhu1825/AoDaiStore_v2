<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Review;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RatingUploadTest extends TestCase
{
    private const LEGACY_IMAGE = '1792056901_1791879828596_2072234381909061819_g6404381936356103833_1c03d71bbb6baa17e0664c72da11eb4e.jpg';

    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('danhgia', function (Blueprint $table) {
            $table->id('MaDanhGia');
            $table->integer('MaDonHang');
            $table->integer('MaSanPham');
            $table->integer('MaTaiKhoan');
            $table->integer('SoSao');
            $table->text('NoiDung')->nullable();
            $table->string('HinhAnh')->nullable();
            $table->text('PhanHoi')->nullable();
            $table->dateTime('NgayDanhGia');
            $table->boolean('TrangThai');
        });

        $this->actingAs((new Account)->forceFill(['MaTaiKhoan' => 5]));
        Storage::fake('public');
    }

    public function test_rating_upload_is_saved_and_has_a_public_url(): void
    {
        $this->from('/order/12')->post(route('rating.store', 7), [
            'MaDonHang' => 12,
            'SoSao' => 5,
            'HinhAnh' => $this->imageUpload(str_repeat('a', 240) . '.jpg'),
        ])->assertRedirect('/order/12')->assertSessionHas('success');

        $review = Review::query()->sole();
        $this->assertSame(5, $review->MaTaiKhoan);
        $this->assertMatchesRegularExpression('~^ratings/[A-Za-z0-9]{40}\.jpe?g$~', $review->HinhAnh);
        Storage::disk('public')->assertExists($review->HinhAnh);
        config(['filesystems.disks.public.url' => 'http://localhost:9999/storage']);
        $imageUrl = Review::imageUrl($review->HinhAnh);
        $this->assertStringStartsWith('/review-images?', $imageUrl);
        $imageResponse = $this->get($imageUrl)->assertOk()->assertHeader('Content-Type', 'image/jpeg');
        $this->assertSame(
            Storage::disk('public')->path($review->HinhAnh),
            $imageResponse->baseResponse->getFile()->getPathname(),
        );
        $this->get(Review::imageUrl(self::LEGACY_IMAGE))->assertOk()->assertHeader('Content-Type', 'image/jpeg');
    }

    public function test_admin_sees_loadable_review_images_and_a_message_for_missing_files(): void
    {
        Schema::create('taikhoan', function (Blueprint $table) {
            $table->id('MaTaiKhoan');
            $table->string('TenDangNhap');
        });
        Schema::create('sanpham', function (Blueprint $table) {
            $table->id('MaSanPham');
            $table->string('TenSanPham');
        });
        DB::table('taikhoan')->insert(['MaTaiKhoan' => 5, 'TenDangNhap' => 'khachhang']);
        DB::table('sanpham')->insert(['MaSanPham' => 7, 'TenSanPham' => 'Áo dài']);
        $imageName = 'Ảnh đánh giá #1 100%.jpg';
        Storage::disk('public')->put('ratings/' . $imageName, $this->imageUpload('review.jpg')->getContent());
        foreach (['ratings/' . $imageName, 'ratings/missing.jpg'] as $imagePath) {
            DB::table('danhgia')->insert([
                'MaDonHang' => 12,
                'MaSanPham' => 7,
                'MaTaiKhoan' => 5,
                'SoSao' => 5,
                'HinhAnh' => $imagePath,
                'NgayDanhGia' => now(),
                'TrangThai' => 1,
            ]);
        }

        $this->actingAs((new Account)->forceFill(['MaTaiKhoan' => 9, 'Role' => 'Admin']));
        $imageUrl = Review::imageUrl('ratings/' . $imageName);
        $this->get(route('admin.comments.index'))->assertOk()
            ->assertSee('src="' . $imageUrl . '"', false)
            ->assertSee('Ảnh không có trên máy chủ');
        $this->get($imageUrl)->assertOk()->assertHeader('Content-Type', 'image/jpeg');
        $this->get('/review-images?file=missing.jpg')->assertNotFound();
        $this->get('/review-images?file=..%2Fmissing.jpg')->assertNotFound();
    }

    public function test_rating_can_still_be_submitted_without_an_image(): void
    {
        $this->post(route('rating.store', 7), [
            'MaDonHang' => 12,
            'SoSao' => 5,
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('danhgia', ['MaSanPham' => 7, 'HinhAnh' => null]);
    }

    public function test_rating_returns_to_the_form_when_the_image_cannot_be_written(): void
    {
        Storage::disk('public')->put('ratings', 'This file blocks creation of the ratings directory.');

        $this->from('/order/12')->post(route('rating.store', 7), [
            'MaDonHang' => 12,
            'SoSao' => 5,
            'HinhAnh' => $this->imageUpload('review.jpg'),
        ])->assertRedirect('/order/12')->assertSessionHas('error');

        $this->assertDatabaseCount('danhgia', 0);
    }

    private function imageUpload(string $filename): UploadedFile
    {
        return UploadedFile::fake()->createWithContent(
            $filename,
            file_get_contents(public_path('img/ratings/' . self::LEGACY_IMAGE)),
        );
    }
}
