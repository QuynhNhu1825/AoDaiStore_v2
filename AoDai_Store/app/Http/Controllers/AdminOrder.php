<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOrder extends Controller
{
    //
    public function index(Request $request) 
{
    $query = Order::query();

    // 1. Tìm kiếm theo Số điện thoại (nếu có nhập)
    if ($request->filled('search_phone')) {
        $query->where('SDTNguoiNhan', 'LIKE', '%' . $request->search_phone . '%');
    }

    // 2. Lọc theo Trạng thái (nếu có chọn)
    if ($request->filled('status')) {
        $query->where('TrangThai', $request->status);
    }

    // Lấy danh sách đơn hàng đã lọc và phân trang
    // Dùng appends để khi chuyển trang (pagination) vẫn giữ được điều kiện lọc
    $orders = $query->orderBy('NgayTao', 'desc')->paginate(10)->withQueryString();

  

    return view('admin.order.index', compact('orders'));
}
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $newStatus = $request->status;
        $currentStatus = $order->TrangThai;

        // Định nghĩa quy tắc: Trạng thái hiện tại => Các trạng thái mới được phép
        $rules = [
            'ChoXacNhan' => ['DaXacNhan', 'DaHuy'],
            'DaXacNhan'  => ['DangGiao', 'DaHuy'],
            'DangGiao'   => ['DaGiao', 'DaHuy'],
            'DaGiao'     => [], // Không được đi đâu hết
            'DaHuy'      => [], // Không được đi đâu hết
        ];

        // Nếu trạng thái mới không nằm trong danh sách được cho phép của trạng thái cũ
        if ($newStatus !== $currentStatus && !in_array($newStatus, $rules[$currentStatus] ?? [])) {
            return back()->with('error', 'Chuyển đổi trạng thái không hợp lệ!');
        }

        $order->update(['TrangThai' => $newStatus]);

        // Thêm logic phụ: Nếu là DaGiao thì cập nhật NgayThanhToan nếu chưa có
        if ($newStatus === 'DaGiao' && !$order->NgayThanhToan) {
            $order->update(['NgayThanhToan' => now()]);
        }

        return back()->with('success', 'Cập nhật trạng thái thành công.');
    }
    public function show($id)
{
    // Lấy thông tin hóa đơn
    $order = Order::where('MaHoaDon', $id)->firstOrFail();
    
    // Lấy chi tiết các sản phẩm trong hóa đơn đó (kèm theo thông tin sản phẩm để lấy ảnh/tên)
    $orderDetails = OrderDetail::where('MaHoaDon', $id)->with('product')->get();

    return view('admin.order.show', compact('order', 'orderDetails'));
}
 public function confirmstatus(Request $request, $id)
{
    $validStatuses = ['ChoXacNhan', 'DaXacNhan', 'DangGiao', 'DaGiao', 'DaHuy'];
    
    if (!in_array($request->status, $validStatuses)) {
        return back()->with('error', 'Trạng thái không hợp lệ');
    }

    Order::where('MaHoaDon', $id)->update(['TrangThai' => $request->status]);

    return back()->with('success', 'Cập nhật trạng thái thành công!');
}
}