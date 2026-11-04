<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    //
    public function getPromotions(){
        // Lấy danh sách khuyến mãi từ cơ sở dữ liệu
        $promotions = Promotion::all();

        // Trả về dữ liệu dưới dạng JSON
        return response()->json($promotions);
    }
    public function searchPromotions(Request $request){
        $query = $request->input('query');

        // Tìm kiếm khuyến mãi theo tên hoặc mã code
        $promotions = Promotion::Where('MaCode', 'like', '%' . $query . '%')
            ->get();

        // Trả về kết quả tìm kiếm dưới dạng JSON
        return response()->json($promotions);
    }
}
