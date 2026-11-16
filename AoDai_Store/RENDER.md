# Triển khai lên Render

1. Đẩy mã nguồn lên GitHub và tạo **Web Service** trên Render, chọn repository và runtime **Docker**. Render sẽ sử dụng `Dockerfile` ở thư mục gốc.
2. Trong **Environment**, cấu hình tối thiểu:
   - `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://<ten-service>.onrender.com`
   - `APP_KEY`: tạo một lần bằng `php artisan key:generate --show`, sau đó giữ nguyên giá trị này giữa các lần deploy.
   - `DB_CONNECTION=mysql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`: thông tin của máy chủ MySQL có thể truy cập từ Render. `127.0.0.1` trên Render không phải MySQL trên máy cá nhân.
   - `LOG_CHANNEL=stderr` để xem log trong Render.
   - Cấu hình thêm biến mail, thanh toán và các dịch vụ khác theo môi trường thực tế nếu sử dụng.
3. Khởi tạo cơ sở dữ liệu và dữ liệu cần thiết trước khi sử dụng. Hiện thư mục migrations chỉ có các bảng mặc định của Laravel, chưa có đầy đủ bảng nghiệp vụ của cửa hàng; cần nhập schema/dữ liệu MySQL hiện có hoặc bổ sung migrations tương ứng. Chạy `php artisan migrate --force` trong Render Shell hoặc công việc triển khai phù hợp để tạo các bảng có migration. Không chạy seed trên dữ liệu production nếu chưa kiểm tra nội dung seed.

Render cấp `PORT` tự động. Không đưa tệp `.env` hoặc thông tin bí mật vào image. Bộ nhớ cục bộ của service không được đảm bảo tồn tại qua các lần deploy/restart; các tệp người dùng tải lên cần persistent disk hoặc dịch vụ lưu trữ bên ngoài. Session/cache dạng file cũng không phù hợp khi chạy nhiều instance. Nếu dùng queue `database`, cần chạy thêm queue worker riêng.
