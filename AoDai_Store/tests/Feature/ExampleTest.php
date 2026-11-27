<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_home_page_renders_with_an_empty_catalog(): void
    {
        Schema::create('sanpham', function (Blueprint $table) {
            $table->id('MaSanPham');
            $table->boolean('TrangThai');
            $table->boolean('IsDeleted');
            $table->timestamp('NgayTao');
        });

        Schema::create('chitietsanpham', function (Blueprint $table) {
            $table->unsignedBigInteger('MaSanPham');
            $table->boolean('IsDeleted');
        });

        Schema::create('danhmucsp', function (Blueprint $table) {
            $table->id('MaDanhMuc');
            $table->boolean('IsDeleted');
        });

        Schema::create('mausac', function (Blueprint $table) {
            $table->id('MaMau');
            $table->boolean('IsDeleted');
        });

        $this->get('/')
            ->assertOk()
            ->assertViewIs('client.home')
            ->assertSeeText('Không có sản phẩm mới.');
    }
}
