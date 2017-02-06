# nv4-xenforo-integrate
Integrate NukeViet4 into Xenforo
Tested: Xenforo 1.5.2
------------------------------------------------------------

## HƯỚNG DẪN SỬ DỤNG

1) Cài đặt hoặc sử dụng Nukeviet 4 bản mới nhất (Đã thử nghiệm với bản 4.0.29 - 4.1). 


1.1) Cài đặt hoàn chỉnh Xenforo (Test trên Xenforo 1.5.2)


(Diễn đàn và Nukeviet phải cùng 1 cơ sở dữ liệu)


2) Backup lại CSDL các bảng nv4_users, nv4_authors (bởi khi tích hợp toàn bộ các tài khoản các thành viên và quản trị sẽ bị xoá hết).


3) Chuyển thư mục của diễn đàn vào trong thư mục của nukeviet. 


(Diễn đàn và portal phải cùng 1 cơ sở dữ liệu)


4) Đăng nhập với quyền Quản trị tối cao (God Administrator).


Truy cập vào:


Tài khoản => Cấu hình Module.

Điền vào "Thư mục chứa diễn đàn". Ví dụ: xenforo.

Nhấn lưu lại.


Đánh dấu vào mục: Sử dụng tài khoản của Diễn đàn.


Nhấn lưu lại.


+ Lưu ý: Nếu không chọn `Sử dụng tài khoản của Diễn đàn` thì Diễn đàn và NukeViet sẽ sử dụng hệ thống tài khoản riêng biệt.


Khi đó có thể bỏ qua bước 8 bên dưới để giữ lại hoàn toàn các thành viên đang có của NukeViet.


5) Download các file sau:

https://github.com/anhyeuviolet/nv4-xenforo-integrate/tree/master/NukeViet_Files

Copy thư mục `nukeviet` vào thư mục chứa forum xenforo

Khi đó phải tồn tại các file sau:

> forum/nukeviet/changepass.php

> forum/nukeviet/editinfo.php

> forum/nukeviet/is_user.php

> forum/nukeviet/login.php

> forum/nukeviet/logout.php

> forum/nukeviet/lostpass.php

> forum/nukeviet/register.php


+ Thư mục chứa forum phải ngang hàng với file index.php và mainfile.php của Nukeviet.

6) Download file sau:

https://github.com/anhyeuviolet/nv4-xenforo-integrate/blob/master/NukeViet_Files/forumxenforo.php

Upload file `forumxenforo.php` lên thư mục gốc của nukeviet (file `forumxenforo.php` ngang hàng với file `index.php` và `mainfile.php` của Nukeviet 4).

7) Nếu bạn đang đăng nhập với tài khoản quản trị hoặc thành viên, cần logout các tài khoản này.

8) Chạy Tool tích hợp với đường dẫn: 

`http://my_site.com/forumxenforo.php`



Nếu gặp một thông báo lỗi nào đó bạn cần kiểm tra lại các bước trên.

Nếu thành công bạn sẽ được thông báo tài khoản quản trị nukeviet (Thường là tài khoản khi cài Xenforo), mật khẩu chính là mật khẩu của diễn đàn. Sau đó bạn cần xoá ngay lập tức file `forumxenforo.php`.

Đóng tất cả các sửa số sau đó chạy lại trình duyệt bạn thử login ngoài site với bất kỳ thành viên nào hoặc vơi thành viên quản trị tối cao trong admin.

Chú ý:

+++ Các thành viên khác muốn thêm vào ban quản trị site của nukviet cần đăng nhập 1 lần trên portal của Nukeviet.
