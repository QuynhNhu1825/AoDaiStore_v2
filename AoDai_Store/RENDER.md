# Triển khai lên Render

## Môi trường local và production

- **Local:** Laravel đọc `.env`. Cấu hình MySQL trên máy với `DB_HOST=127.0.0.1`, `DB_PORT=3306`, `DB_DATABASE=aodaistore_db`, `DB_USERNAME=root` và `DB_PASSWORD` theo máy của bạn. Không đặt `MYSQL_ATTR_SSL_CA` hoặc `DB_URL` khi dùng cấu hình MySQL local thông thường. Bật MySQL, tạo database và nhập schema/dữ liệu cửa hàng hiện có. Các migration hiện tại chưa tạo đủ bảng nghiệp vụ. Sau khi thay đổi cấu hình, chạy `php artisan config:clear` rồi `php artisan serve`.
- **Production:** Trong Render → Environment, nhập các biến theo `.env.production.example`, thay các giá trị mẫu bằng thông tin thật của TiDB và các dịch vụ cần dùng. Tệp mẫu không được Laravel hoặc Render tự động nạp. Đặt `MYSQL_ATTR_SSL_CA=/etc/ssl/certs/ca-certificates.crt` để kết nối TiDB qua TLS. Đặt `APP_KEY` production cố định, riêng với local. Chọn Save và deploy lại. Không commit mật khẩu hay khóa thật.
- `.env` chỉ dành cho máy local và được Docker loại khỏi image. Render không dùng `.env` local; biến trong Render Environment quyết định cấu hình production. Nếu chạy `config:cache`, xóa hoặc tạo lại cache sau khi thay đổi biến.

## Sửa lỗi `php: command not found`

Lỗi này cho thấy lệnh khởi động đang chạy trong môi trường không có PHP. Dockerfile của dự án đã cung cấp PHP và Apache; service cần sử dụng image này.

1. Mở service hiện tại trên Render, vào **Settings → Build → Source → Edit**.
2. Chọn lại repository/branch hiện tại, đặt **Runtime = Docker** và lưu/Deploy.
3. Với cấu trúc repository hiện tại, đặt **Root Directory = `AoDai_Store`**, **Dockerfile Path = `./Dockerfile`**, **Docker Build Context Directory = `.`**. Nếu đã chuyển nội dung ứng dụng ra gốc repository thì để trống Root Directory.
4. Để trống **Docker Command** để dùng `CMD` của Dockerfile. Xóa giá trị `php artisan serve` nếu đang có trong trường ghi đè này. Docker tự build và chạy ứng dụng; không cấu hình riêng lệnh Yarn hay `php artisan serve`.
5. Giữ các biến môi trường trong Environment, rồi deploy lại. Log build phải có các bước Docker như `FROM php:8.2-apache`; tiến trình chạy cuối cùng là Apache.

Tham khảo: https://render.com/docs/native-runtimes#changing-a-services-runtime và https://render.com/docs/docker.

Nếu build báo thiếu `resources/css/app.css`, hãy chắc chắn tệp này đã được commit và push cùng mã nguồn. Log dùng Yarn với đường dẫn `/opt/render/project/src` cho thấy cần kiểm tra runtime: ứng dụng Laravel này cần Web Service chạy Docker. Chọn `Dockerfile` (không phải tệp `.Dockerfile` trống). Nếu repository chứa ứng dụng trong thư mục `AoDai_Store`, đặt Root Directory là `AoDai_Store`, Dockerfile Path là `./Dockerfile` và Docker Build Context Directory là `.`. Nếu ứng dụng nằm ngay gốc repository thì để trống Root Directory, các đường dẫn Docker vẫn như trên.

1. Đẩy mã nguồn lên GitHub và tạo **Web Service** trên Render, chọn repository và runtime **Docker**. Render sẽ sử dụng `Dockerfile` ở thư mục gốc.
2. Trong **Environment**, cấu hình tối thiểu:
   - `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://<ten-service>.onrender.com`
   - `APP_KEY`: tạo một lần bằng `php artisan key:generate --show`, sau đó giữ nguyên giá trị này giữa các lần deploy.
   - `DB_CONNECTION=mysql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`: thông tin của máy chủ MySQL có thể truy cập từ Render. `127.0.0.1` trên Render không phải MySQL trên máy cá nhân.
   - `LOG_CHANNEL=stderr` để xem log trong Render.
   - Cấu hình thêm biến mail, thanh toán và các dịch vụ khác theo môi trường thực tế nếu sử dụng.
3. Khởi tạo cơ sở dữ liệu và dữ liệu cần thiết trước khi sử dụng. Hiện thư mục migrations chỉ có các bảng mặc định của Laravel, chưa có đầy đủ bảng nghiệp vụ của cửa hàng; cần nhập schema/dữ liệu MySQL hiện có hoặc bổ sung migrations tương ứng. Chạy `php artisan migrate --force` trong Render Shell hoặc công việc triển khai phù hợp để tạo các bảng có migration. Không chạy seed trên dữ liệu production nếu chưa kiểm tra nội dung seed.

Render cấp `PORT` tự động. Không đưa tệp `.env` hoặc thông tin bí mật vào image. Bộ nhớ cục bộ của service không được đảm bảo tồn tại qua các lần deploy/restart; các tệp người dùng tải lên cần persistent disk hoặc dịch vụ lưu trữ bên ngoài. Session/cache dạng file cũng không phù hợp khi chạy nhiều instance. Nếu dùng queue `database`, cần chạy thêm queue worker riêng.
