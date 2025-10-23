-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 20, 2025 lúc 12:40 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `tmdt_bc`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `binh_luan`
--

CREATE TABLE `binh_luan` (
  `ma_bl` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `chuyen_xe_id` int(11) NOT NULL,
  `noi_dung` text NOT NULL,
  `noi_dung_tl` text NOT NULL,
  `so_sao` tinyint(1) DEFAULT NULL,
  `ngay_bl` timestamp NOT NULL DEFAULT current_timestamp(),
  `ngay_tl` timestamp NOT NULL DEFAULT current_timestamp(),
  `nv_id` int(11) DEFAULT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp(),
  `trang_thai` enum('cho_duyet','da_duyet','tu_choi') DEFAULT 'cho_duyet' COMMENT 'Trạng thái duyệt bình luận',
  `ngay_duyet` datetime DEFAULT NULL COMMENT 'Ngày duyệt bình luận',
  `ly_do_tu_choi` text DEFAULT NULL COMMENT 'Lý do từ chối duyệt'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `binh_luan`
--

INSERT INTO `binh_luan` (`ma_bl`, `parent_id`, `user_id`, `chuyen_xe_id`, `noi_dung`, `noi_dung_tl`, `so_sao`, `ngay_bl`, `ngay_tl`, `nv_id`, `ngay_tao`, `trang_thai`, `ngay_duyet`, `ly_do_tu_choi`) VALUES
(2, NULL, 2, 2, 'Giá Quá Thấp', '', 4, '2025-09-30 05:33:18', '2025-10-01 05:33:18', 2, '2025-10-06 17:02:46', 'da_duyet', '2025-09-30 12:33:18', NULL),
(21, NULL, 15, 17, 'quá trinh cao', '', 5, '2025-10-07 00:37:35', '2025-10-07 00:37:35', 1, '2025-10-07 07:37:35', 'da_duyet', '2025-10-07 07:37:35', NULL),
(22, NULL, 15, 17, 'xàm', '', 1, '2025-10-07 00:38:32', '2025-10-07 00:38:32', 1, '2025-10-07 07:38:32', 'tu_choi', NULL, 'xàm'),
(23, 21, 20, 17, 'ok chưa ban', '', 5, '2025-10-07 00:39:14', '2025-10-07 00:39:14', 1, '2025-10-07 07:39:14', 'da_duyet', '2025-10-07 07:39:14', NULL),
(24, NULL, 15, 17, '**', '', 5, '2025-10-07 00:42:23', '2025-10-07 00:42:23', 1, '2025-10-07 07:42:23', 'da_duyet', '2025-10-07 07:42:23', NULL),
(26, 21, 20, 17, 'ok', '', 5, '2025-10-07 00:48:23', '2025-10-07 00:48:23', 1, '2025-10-07 07:48:23', 'da_duyet', '2025-10-07 07:48:23', NULL),
(27, NULL, 20, 17, 'pp', '', 5, '2025-10-07 00:50:42', '2025-10-07 00:50:42', 1, '2025-10-07 07:50:42', 'da_duyet', '2025-10-07 07:50:42', NULL),
(29, 27, 31, 17, 'chao', '', NULL, '2025-10-16 21:03:14', '2025-10-16 21:03:14', NULL, '2025-10-17 04:03:14', 'da_duyet', '2025-10-17 04:03:14', NULL),
(30, 27, 31, 17, 'chao', '', NULL, '2025-10-17 02:16:10', '2025-10-17 02:16:10', NULL, '2025-10-17 09:16:10', 'da_duyet', '2025-10-17 09:16:10', NULL),
(31, 27, 31, 17, 'co', '', NULL, '2025-10-19 16:24:42', '2025-10-19 16:24:42', NULL, '2025-10-19 07:24:42', 'da_duyet', '2025-10-19 07:24:42', NULL),
(32, 27, 39, 17, '', 'chao', NULL, '2025-10-19 16:59:25', '2025-10-19 16:59:25', NULL, '2025-10-18 22:59:25', 'da_duyet', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `booking_sessions`
--

CREATE TABLE `booking_sessions` (
  `id` int(11) NOT NULL,
  `session_key` varchar(128) NOT NULL,
  `user_id` int(11) NOT NULL,
  `chuyen_xe_id` int(11) NOT NULL,
  `seats` text NOT NULL,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('Đang giữ chỗ','Đã thanh toán','Đã hủy','Đã hủy do quá hạn') NOT NULL DEFAULT 'Đang giữ chỗ',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL,
  `meta` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chuyen_xe`
--

CREATE TABLE `chuyen_xe` (
  `id` int(11) NOT NULL,
  `ma_xe` varchar(20) NOT NULL,
  `ten_xe` varchar(100) NOT NULL,
  `ma_nha_xe` int(11) NOT NULL,
  `ten_tai_xe` varchar(100) NOT NULL,
  `sdt_tai_xe` varchar(15) DEFAULT NULL,
  `ma_tram_di` int(11) NOT NULL,
  `ma_tram_den` int(11) NOT NULL,
  `tram_trung_gian` text DEFAULT NULL COMMENT 'Danh sách mã trạm trung gian, cách nhau bởi dấu phẩy',
  `ngay_di` date NOT NULL,
  `gio_di` time NOT NULL,
  `loai_xe` varchar(50) NOT NULL,
  `so_cho` int(11) DEFAULT NULL CHECK (`so_cho` <= 45),
  `so_ve` int(11) DEFAULT NULL CHECK (`so_ve` <= 10),
  `gia_ve` decimal(10,2) NOT NULL,
  `loai_chuyen` enum('Một chiều','Khứ hồi') DEFAULT 'Một chiều',
  `gio_den` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chuyen_xe`
--

INSERT INTO `chuyen_xe` (`id`, `ma_xe`, `ten_xe`, `ma_nha_xe`, `ten_tai_xe`, `sdt_tai_xe`, `ma_tram_di`, `ma_tram_den`, `tram_trung_gian`, `ngay_di`, `gio_di`, `loai_xe`, `so_cho`, `so_ve`, `gia_ve`, `loai_chuyen`, `gio_den`) VALUES
(1, 'XE001', 'Xe Giường Nằm 1', 1, 'Nguyen Van A', '0911111112', 1, 6, NULL, '2025-10-01', '07:30:00', 'Giường nằm', 45, 10, 5000.00, 'Một chiều', NULL),
(2, 'XE002', 'Xe Limousine 2', 2, 'Tran Van B', '0912222222', 1, 6, NULL, '2025-10-02', '08:00:00', 'Giường nằm', 30, 5, 400000.00, 'Khứ hồi', NULL),
(3, 'XE003', 'Xe Ghế Ngồi 311', 3, 'Le Van Van1', '0901000003', 3, 6, NULL, '2025-10-01', '10:00:00', 'Giường nằm', 40, 8, 200000.00, 'Một chiều', NULL),
(4, 'XE004', 'Xe Giường Nằm 4', 4, 'Pham Van D', '0914444444', 4, 5, NULL, '2025-10-04', '10:30:00', 'Giường nằm', 42, 7, 250000.00, 'Một chiều', NULL),
(5, 'XE005', 'Xe Limousine 5', 5, 'Nguyen Van E', '0915555555', 8, 4, NULL, '2025-10-05', '12:00:00', 'Limousine', 20, 6, 450000.00, 'Khứ hồi', NULL),
(6, 'XE006', 'Xe Ghế Ngồi 6', 6, 'Le Van F', '0916666666', 1, 3, NULL, '2025-10-06', '14:00:00', 'Ghế ngồi', 44, 9, 150000.00, 'Một chiều', NULL),
(7, 'XE007', 'Xe Giường Nằm 7', 7, 'Tran Van G', '0917777777', 6, 7, NULL, '2025-10-07', '15:00:00', 'Giường nằm', 45, 10, 300000.00, 'Khứ hồi', NULL),
(8, 'XE008', 'Xe Limousine 8', 8, 'Pham Van H', '0918888888', 5, 4, NULL, '2025-10-08', '16:30:00', 'Limousine', 16, 4, 500000.00, 'Một chiều', NULL),
(9, 'XE009', 'Xe Ghế Ngồi 9', 9, 'Nguyen Van I', '0919999999', 9, 2, NULL, '2025-10-09', '18:00:00', 'Ghế ngồi', 38, 6, 180000.00, 'Một chiều', NULL),
(10, 'XE010', 'Xe Giường Nằm 10', 10, 'Le Van J', '0910000000', 10, 6, NULL, '2025-10-10', '19:30:00', 'Giường nằm', 45, 8, 600000.00, 'Khứ hồi', NULL),
(11, 'XE011', 'Xe Giường Nằm 11', 11, 'Nguyen Van K', '0921111111', 11, 7, NULL, '2025-10-11', '07:00:00', 'Giường nằm', 40, 10, 370000.00, 'Một chiều', NULL),
(12, 'XE012', 'Xe Limousine 12', 12, 'Tran Van L', '0922222222', 12, 8, NULL, '2025-10-12', '09:15:00', 'Limousine', 18, 6, 420000.00, 'Khứ hồi', NULL),
(13, 'XE013', 'Xe Ghế Ngồi 13', 13, 'Le Van M', '0923333333', 13, 3, NULL, '2025-10-13', '10:45:00', 'Ghế ngồi', 35, 9, 190000.00, 'Một chiều', NULL),
(14, 'XE014', 'Xe Giường Nằm 14', 14, 'Pham Van N', '0924444444', 14, 10, NULL, '2025-10-14', '12:30:00', 'Giường nằm', 45, 7, 310000.00, 'Một chiều', NULL),
(15, 'XE015', 'Xe Limousine 15', 15, 'Hoang Van Tai', '0925555555', 15, 5, NULL, '2025-10-15', '13:00:00', 'Limousine', 20, 8, 480000.00, 'Khứ hồi', NULL),
(17, 'CX1759212724', 'ADMIN', 15, 'Hoang Huy', '0939206174', 61, 61, NULL, '2025-10-01', '19:00:00', 'Giường nằm', 30, 10, 200000.00, 'Một chiều', NULL),
(19, 'XE0004', 'huy', 3, 'Le Van Van1', '0901000003', 69, 70, NULL, '2025-10-17', '22:29:00', 'Giường nằm', 30, 0, 100000.00, 'Một chiều', '11:29:00'),
(20, 'XE0020', 'Test_Trung Gian1', 3, 'Le Van Van1', '0901000003', 69, 70, '3,6,69', '2025-10-17', '17:44:00', 'Ghế ngồi', 30, 0, 100000.00, 'Một chiều', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contact`
--

CREATE TABLE `contact` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `contact`
--

INSERT INTO `contact` (`id`, `branch`, `fullname`, `email`, `phone`, `subject`, `message`, `created_at`, `updated_at`) VALUES
(1, 'huy', 'huy', 'huy@gmail.com', '0939206174', 'huy', 'huy', '2025-10-17 05:18:00', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dat_ve`
--

CREATE TABLE `dat_ve` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `chuyen_xe_id` int(11) NOT NULL,
  `ma_ve` varchar(20) NOT NULL,
  `so_ghe` varchar(255) DEFAULT NULL,
  `ngay_dat` timestamp NOT NULL DEFAULT current_timestamp(),
  `trang_thai` enum('Đã đặt','Đã thanh toán','Đã hủy') DEFAULT 'Đã đặt'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `dat_ve`
--

INSERT INTO `dat_ve` (`id`, `user_id`, `chuyen_xe_id`, `ma_ve`, `so_ghe`, `ngay_dat`, `trang_thai`) VALUES
(47, 15, 17, 'VE1001', 'A20', '2025-10-06 04:03:56', 'Đã hủy'),
(49, 2, 14, 'BK20251014022429908', 'B02', '2025-10-14 00:24:29', 'Đã hủy'),
(64, 34, 1, 'BK20251015034759992', 'A10', '2025-10-15 03:47:59', 'Đã thanh toán'),
(86, 31, 7, 'VE07', 'A01', '2025-10-03 06:20:00', 'Đã hủy'),
(87, 31, 7, 'VE07', 'A02', '2025-10-03 06:20:00', 'Đã hủy'),
(88, 31, 7, 'VE07', 'A03', '2025-10-03 06:20:00', 'Đã hủy'),
(89, 31, 7, 'VE07', 'A10', '2025-10-03 06:20:00', 'Đã hủy'),
(93, 34, 1, 'BK20251015035501962', 'B17', '2025-10-15 03:55:01', 'Đã thanh toán'),
(94, 34, 1, 'BK20251015035610461', 'B15', '2025-10-15 03:56:10', 'Đã đặt'),
(95, 34, 1, 'BK20251015040109251', 'A11', '2025-10-15 04:01:09', 'Đã đặt'),
(96, 34, 1, 'BK20251015040127672', 'A16', '2025-10-15 04:01:27', 'Đã thanh toán'),
(97, 34, 1, 'BK20251015060206001', 'A05', '2025-10-15 04:02:06', 'Đã thanh toán'),
(99, 34, 1, 'BK20251015040234723', 'A01', '2025-10-15 04:02:34', 'Đã thanh toán'),
(100, 34, 1, 'BK20251015040856902', 'A03', '2025-10-15 04:08:56', 'Đã thanh toán'),
(101, 34, 1, 'BK20251015041346263', 'B16', '2025-10-15 04:13:46', 'Đã thanh toán'),
(102, 15, 3, 'BK20251015074539353', 'A08', '2025-10-15 07:45:39', 'Đã thanh toán'),
(103, 15, 17, 'BK20251015190202336', 'B17', '2025-10-15 19:02:02', 'Đã đặt');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `doanh_thu`
--

CREATE TABLE `doanh_thu` (
  `ma_dt` int(11) NOT NULL,
  `chuyen_xe_id` int(11) NOT NULL,
  `tong_ve` int(11) NOT NULL,
  `tong_tien` decimal(12,2) NOT NULL,
  `ngay_thong_ke` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `doanh_thu`
--

INSERT INTO `doanh_thu` (`ma_dt`, `chuyen_xe_id`, `tong_ve`, `tong_tien`, `ngay_thong_ke`) VALUES
(1, 1, 10, 3500000.00, '2025-09-01'),
(2, 2, 8, 3200000.00, '2025-09-02'),
(3, 3, 7, 1400000.00, '2025-09-03'),
(4, 4, 6, 1500000.00, '2025-09-04'),
(5, 5, 9, 4050000.00, '2025-09-05'),
(6, 6, 10, 1500000.00, '2025-09-06'),
(7, 7, 8, 2400000.00, '2025-09-07'),
(8, 8, 5, 2500000.00, '2025-09-08'),
(9, 9, 9, 1620000.00, '2025-09-09'),
(10, 10, 7, 4200000.00, '2025-09-10'),
(11, 3, 2, 400000.00, '2025-09-25');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khuyen_mai`
--

CREATE TABLE `khuyen_mai` (
  `ma_km` int(11) NOT NULL,
  `ten_km` varchar(100) NOT NULL,
  `ma_code` varchar(20) NOT NULL,
  `giam_gia` decimal(5,2) DEFAULT NULL CHECK (`giam_gia` <= 100),
  `ngay_bat_dau` date NOT NULL,
  `ngay_ket_thuc` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `khuyen_mai`
--

INSERT INTO `khuyen_mai` (`ma_km`, `ten_km`, `ma_code`, `giam_gia`, `ngay_bat_dau`, `ngay_ket_thuc`) VALUES
(1, 'KM tháng 1', 'KM01', 10.00, '2025-09-30', '2030-12-31'),
(2, 'KM tháng 2', 'KM02', 20.00, '2025-09-30', '2030-12-31'),
(3, 'KM tháng 3', 'KM03', 30.00, '2025-09-30', '2030-12-31'),
(4, 'KM tháng 4', 'KM04', 40.00, '2025-09-30', '2030-12-31'),
(5, 'KM tháng 5', 'KM05', 50.00, '2025-09-30', '2030-12-31'),
(6, 'KM tháng 6', 'KM06', 60.00, '2025-09-30', '2030-12-31'),
(7, 'KM tháng 7', 'KM07', 70.00, '2025-09-30', '2030-12-31'),
(8, 'KM tháng 8', 'KM08', 80.00, '2025-09-30', '2030-12-31'),
(9, 'KM tháng 9', 'KM09', 90.00, '2025-09-30', '2030-12-31'),
(10, 'KM tháng 10', 'KM10', 100.00, '2025-10-01', '2025-10-31'),
(12, 'KM11', 'KM11', 99.00, '2025-10-03', '2030-12-31'),
(13, 'KM_Tet1', 'KM_Tet', 90.00, '2025-10-06', '2025-10-31');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_10_03_004313_create_sessions_table', 1),
(2, '2025_10_09_171601_create_contacts_table', 2),
(3, '2025_10_15_173821_add_ma_nha_xe_to_users_table', 3),
(4, '2025_10_15_081016_add_profile_fields_to_users_table', 4),
(5, '2025_10_15_162000_allow_null_so_sao_binh_luan', 4),
(6, '2025_10_16_051205_allow_null_nv_id_in_binh_luan_table', 4),
(7, '2025_10_16_075221_add_reset_token_to_users_table', 4),
(9, '2025_10_16_083259_add_customer_info_to_dat_ve_table', 5),
(10, '2025_10_17_103835_add_tram_trung_gian_to_chuyen_xe_table', 6),
(11, '2025_10_19_082932_fix_tin_tuc_charset_for_emoji_support', 7);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhan_vien`
--

CREATE TABLE `nhan_vien` (
  `ma_nv` int(11) NOT NULL,
  `ten_nv` varchar(100) NOT NULL,
  `chuc_vu` enum('tài xế','phụ xe','nhân viên văn phòng','quản lý') NOT NULL,
  `so_dien_thoai` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `ma_nha_xe` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nhan_vien`
--

INSERT INTO `nhan_vien` (`ma_nv`, `ten_nv`, `chuc_vu`, `so_dien_thoai`, `email`, `ma_nha_xe`) VALUES
(1, 'Nguyen Van Tai1', 'tài xế', '0901000001', 'tai1@bus.vn', 1),
(2, 'Tran Thi Phu', 'phụ xe', '0901000002', 'phu2@bus.vn', 2),
(3, 'Le Van Van1', 'tài xế', '0901000003', 'vp3@bus.vn', 3),
(4, 'Pham Thi Quan', 'quản lý', '0901000004', 'quan4@bus.vn', 4),
(5, 'Hoang Van Lai', 'tài xế', '0901000005', 'lai5@bus.vn', 5),
(6, 'Bui Thi Huong', 'phụ xe', '0901000006', 'phuxe6@bus.vn', 6),
(7, 'Do Van Kiem', 'quản lý', '0901000007', 'ql7@bus.vn', 7),
(8, 'Ngo Thi Ha', 'nhân viên văn phòng', '0901000008', 'vp8@bus.vn', 8),
(9, 'Dang Van Phuc', 'tài xế', '0901000009', 'tai9@bus.vn', 9),
(10, 'Vo Thi Mai', 'phụ xe', '0901000010', 'phuxe10@bus.vn', 10),
(11, 'HuyTest1', 'tài xế', '0939206174', 'ontapttnt@gmail.com', 3),
(12, 'huy1', 'phụ xe', '1234567890', 'huy@gmail.com', 3),
(13, 'huy2', 'quản lý', '1234567891', 'huy2@gmail.com', 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nha_xe`
--

CREATE TABLE `nha_xe` (
  `ma_nha_xe` int(11) NOT NULL,
  `ten_nha_xe` varchar(100) NOT NULL,
  `dia_chi` varchar(255) NOT NULL,
  `so_dien_thoai` varchar(15) NOT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nha_xe`
--

INSERT INTO `nha_xe` (`ma_nha_xe`, `ten_nha_xe`, `dia_chi`, `so_dien_thoai`, `email`) VALUES
(1, 'FUTA Bus', 'Cần Thơ', '02923888888', 'futa@bus.vn'),
(2, 'Phương Trang', 'TPHCM', '02838383838', 'pt@bus.vn'),
(3, 'Mai Linh12', 'Hà Nội', '02439393939', 'ml@bus.vn'),
(4, 'Thành Bưởi', 'Đà Lạt', '02633888888', 'tb@bus.vn'),
(5, 'Hoàng Long', 'Hải Phòng', '02253888888', 'hl@bus.vn'),
(6, 'Xe Việt', 'Hà Nội', '02437778888', 'xeviet@bus.vn'),
(7, 'An Phú Bus', 'Cần Thơ', '02923882222', 'ap@bus.vn'),
(8, 'OpenTour', 'Đà Nẵng', '02363881111', 'opentour@bus.vn'),
(9, 'Kumho Samco', 'TPHCM', '02837776666', 'kumho@bus.vn'),
(10, 'Hà Lan', 'Ninh Bình', '02293889999', 'halan@bus.vn'),
(11, 'Tây Đô', 'Cần Thơ', '02923887777', 'taydo@bus.vn'),
(12, 'Cúc Tùng', 'Nha Trang', '02583889999', 'cuctung@bus.vn'),
(13, 'Thanh Buoi Express', 'TPHCM', '02822223333', 'tbexpress@bus.vn'),
(14, 'HTX Vận Tải Sài Gòn', 'TPHCM', '02844556677', 'htxsg@bus.vn'),
(15, 'Hoang Huy', 'Vũng Tàu', '02543887766', 'hoamai@bus.vn'),
(17, 'huy', 'huy', '0939206174', 'huy@gmail.com'),
(18, 'huy', 'huy@gmail.com', '1234567890', 'huy@gmail.com');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('iIN0uVJXiBqMSgKD3m3BIGePWyGHE5S2ZamxBS31', 31, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTlpocFZJVG5EUXFrRDFZb3pWMjZrNnFPSEsxODlmaFFlQmR5Y092eSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjMxO30=', 1760496601);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tin_tuc`
--

CREATE TABLE `tin_tuc` (
  `ma_tin` int(11) NOT NULL,
  `tieu_de` varchar(200) DEFAULT NULL,
  `noi_dung` text DEFAULT NULL,
  `hinh_anh` varchar(255) DEFAULT NULL,
  `ngay_dang` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  `ma_nha_xe` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tin_tuc`
--

INSERT INTO `tin_tuc` (`ma_tin`, `tieu_de`, `noi_dung`, `hinh_anh`, `ngay_dang`, `user_id`, `ma_nha_xe`) VALUES
(2, 'Mở tuyến mới', 'Khai trương tuyến Hà Nội - Sapa', 'daca0127e0801231.jpg', '2025-09-22 23:24:29', 2, 2),
(12, 'ĐẶT VÉ XE KHÁCH PHƯƠNG TRANG TRÊN CÁC ỨNG DỤNG NGÂN HÀNG HOẶC ỨNG DỤNG VNPAY GIẢM TỚI 50K', 'Với mong muốn mang đến cho khách hàng trải nghiệm di chuyển tiện lợi, nhanh chóng và chi phí hợp lý, Công ty Phương Trang phối hợp cùng VNPAY triển khai chương trình ưu đãi hấp dẫn, áp dụng trực tiếp khi đặt vé xe khách trên các Ứng dụng Ngân hàng hoặc VNPAY App.\r\nBanner FUTA.jpg\r\n\r\nCơ cấu ưu đãi:\r\n\r\nKhách hàng mới: Nhập mã VNPAYFUTA50 - Giảm 20%, tối đa 50K\r\n\r\nKhách hàng hiện hữu: Nhập mã VNPAYFUTA30 - Giảm 20%, tối đa 30K\r\n\r\n\r\n⏰ Thời gian áp dụng: đến hết ngày 31/12/2025\r\n⚡ Số lượng ưu đãi có hạn mỗi ngày – nhanh tay đặt vé để không bỏ lỡ!\r\n\r\n📞Tổng đài đặt vé & CSKH Phương Trang: 1900 6067\r\n📞 Hotline VNPay: *6789\r\n\r\nƯu điểm nổi bật khi đặt vé FUTA qua VNPAY & App Ngân hàng\r\n\r\nTiện lợi & nhanh chóng: Đặt vé xe mọi lúc, mọi nơi chỉ với vài thao tác.\r\n\r\nĐa dạng lựa chọn: Hàng trăm tuyến xe, nhiều khung giờ linh hoạt.\r\n\r\nThanh toán an toàn, hiện đại: Thanh toán ngay trên Ứng dụng Ngân hàng.\r\n\r\nƯu đãi liên tục: Nhiều chương trình khuyến mại hấp dẫn, tối ưu chi phí di chuyển.\r\n\r\nHướng dẫn đặt vé và nhận ưu đãi:\r\n\r\nMở Ứng dụng Ngân hàng hoặc VNPAY App.\r\n\r\nChọn tính năng “Vé xe khách”\r\n\r\nLựa chọn tuyến FUTA phù hợp, giờ khởi hành, ghế ngồi.\r\n\r\nNhập mã ưu đãi\r\n\r\nHoàn tất thanh toán và nhận vé\r\n\r\nĐiều kiện áp dụng:\r\n\r\nƯu đãi chỉ áp dụng cho giao dịch đặt vé xe khách Phương Trang trên các kênh: App Ngân hàng và VNPAY App.\r\n\r\nMỗi khách hàng được sử dụng 1 mã ưu đãi/số điện thoại\r\n\r\nChương trình không áp dụng đồng thời với các ưu đãi khác.\r\n\r\nChương trình có thể kết thúc sớm khi hết ngân sách ưu đãi.\r\n\r\nNhanh tay đặt vé FUTA – Săn ưu đãi cực chất cùng VNPAY!', 'https://storage.googleapis.com/futa-busline-web-cms-prod/Banner_FUTA_868af2d59f/Banner_FUTA_868af2d59f.jpg', '2025-10-06 23:00:23', 20, 7),
(13, 'ĐẶT VÉ XE KHÁCH PHƯƠNG TRANG TRÊN CÁC ỨNG DỤNG NGÂN HÀNG HOẶC ỨNG DỤNG VNPAY GIẢM TỚI 50K', 'Với mong muốn mang đến cho khách hàng trải nghiệm di chuyển tiện lợi, nhanh chóng và chi phí hợp lý, Công ty Phương Trang phối hợp cùng VNPAY triển khai chương trình ưu đãi hấp dẫn, áp dụng trực tiếp khi đặt vé xe khách trên các Ứng dụng Ngân hàng hoặc VNPAY App.\r\nBanner FUTA.jpg\r\n\r\nCơ cấu ưu đãi:\r\n\r\nKhách hàng mới: Nhập mã VNPAYFUTA50 - Giảm 20%, tối đa 50K\r\n\r\nKhách hàng hiện hữu: Nhập mã VNPAYFUTA30 - Giảm 20%, tối đa 30K\r\n\r\n\r\n⏰ Thời gian áp dụng: đến hết ngày 31/12/2025\r\n⚡ Số lượng ưu đãi có hạn mỗi ngày – nhanh tay đặt vé để không bỏ lỡ!\r\n\r\n📞Tổng đài đặt vé & CSKH Phương Trang: 1900 6067\r\n📞 Hotline VNPay: *6789\r\n\r\nƯu điểm nổi bật khi đặt vé FUTA qua VNPAY & App Ngân hàng\r\n\r\nTiện lợi & nhanh chóng: Đặt vé xe mọi lúc, mọi nơi chỉ với vài thao tác.\r\n\r\nĐa dạng lựa chọn: Hàng trăm tuyến xe, nhiều khung giờ linh hoạt.\r\n\r\nThanh toán an toàn, hiện đại: Thanh toán ngay trên Ứng dụng Ngân hàng.\r\n\r\nƯu đãi liên tục: Nhiều chương trình khuyến mại hấp dẫn, tối ưu chi phí di chuyển.\r\n\r\nHướng dẫn đặt vé và nhận ưu đãi:\r\n\r\nMở Ứng dụng Ngân hàng hoặc VNPAY App.\r\n\r\nChọn tính năng “Vé xe khách”\r\n\r\nLựa chọn tuyến FUTA phù hợp, giờ khởi hành, ghế ngồi.\r\n\r\nNhập mã ưu đãi\r\n\r\nHoàn tất thanh toán và nhận vé\r\n\r\nĐiều kiện áp dụng:\r\n\r\nƯu đãi chỉ áp dụng cho giao dịch đặt vé xe khách Phương Trang trên các kênh: App Ngân hàng và VNPAY App.\r\n\r\nMỗi khách hàng được sử dụng 1 mã ưu đãi/số điện thoại\r\n\r\nChương trình không áp dụng đồng thời với các ưu đãi khác.\r\n\r\nChương trình có thể kết thúc sớm khi hết ngân sách ưu đãi.\r\n\r\nNhanh tay đặt vé FUTA – Săn ưu đãi cực chất cùng VNPAY!', 'https://storage.googleapis.com/futa-busline-web-cms-prod/Banner_FUTA_868af2d59f/Banner_FUTA_868af2d59f.jpg', '2025-10-07 08:09:19', 32, NULL),
(14, 'TRUNG CHUYỂN MIỄN PHÍ TỪ BẾN XE MIỀN ĐÔNG MỚI', 'Đặt vé dễ dàng - Di chuyển nhanh - Lộ trình rõ ràng\r\nWebsite (599x337px).png\r\n\r\n✨Quý khách hàng từ xa đến TP.HCM để:\r\n\r\n- Thăm khám tại các BV?\r\n\r\n- Đi công tác, học tập?\r\n\r\n- Giao thương, buôn bán?\r\n\r\n- Thăm người thân?\r\n\r\nĐừng lo lắng về việc di chuyển, vì Công ty Phương Trang hỗ trợ TRUNG CHUYỂN MIỄN PHÍ từ Bến xe Miền Đông đến các điểm trọng điểm như bệnh viện, trường đại học, khu dân cư, siêu thị lớn,... tại TP. Thủ Đức và khu vực lân cận.\r\n\r\n🚌 Từ Bến xe Miền Đông mới có 4 lộ trình trung chuyển chính và trung chuyển thẳng đến 6 văn phòng ở quận trung tâm với hàng trăm điểm đến.\r\n\r\nĐẶC BIỆT, từ Bến xe Miền Đông mới sẽ trung chuyển thẳng đến 10 bệnh viện tuyến đầu.\r\n\r\n👉TRẢI NGHIỆM NGAY!\r\n\r\nDịch vụ trung chuyển tiện lợi, GIÚP:\r\n\r\n- Tiết kiệm thời gian, chi phí\r\n\r\n- Lộ trình rõ ràng, chi tiết, di chuyển liền mạch\r\n\r\n- Không còn nỗi lo bị “hét giá” khi bắt xe ngoài\r\n\r\n- Không lo lạc đường\r\n\r\n📝Đặt ngay vé xe Công ty Phương Trang để trải nghiệm các lộ trình trung chuyển tối ưu, Quý khách hàng sẽ được phục vụ chu đáo!\r\n\r\n🚌🚌🚌Chi tiết dịch vụ trung chuyển:\r\n\r\n🚌Lộ trình 1: BXMĐ Mới – Siêu thị GO Dĩ An BD\r\n\r\nBXMĐ MỚI -> đường Hoàng Hữu Nam -> đường 400 (BV Ung Bướu) -> đường Võ Nguyên Giáp (ĐH An Ninh) -> đường Tân Lập (Chung cư Bcons) -> đường Tạ Quang Bửu (KTX A) -> đường Quảng Trường Sáng Tạo (trường Khoa Học Xã Hội NHân Văn) => đường Lưu Hữu PHước (Nhà Văn Hóa Sinh Viên) -> đường Nguyễn Du (Nhà khách Đại học Quốc Gia) => đường Mạc Đỉnh Chi (KTX Khu B)->Quốc Lộ 1K (Go Dĩ An)\r\n\r\n🚌Lộ trình 2: BXMĐ Mới – VinHome Q9\r\n\r\nBXMĐ MỚI -> Đường số 13 -> Đường số 11 -> Nguyễn Xiển -> Phước Thiện -> Đường Cầu Vòng 5 (Khu Vinhome Q9)\r\n\r\n🚌 Lộ trình 3: BXMĐ Mới – Bệnh viện TP. Thủ Đức\r\n\r\nBXMĐ MỚI -> Hoàng Hữu Nam -> Võ Nguyên Giáp -> Quốc lộ 1A ->Đường số 17 (Đại học Nông Lâm) -> Đường 16 (Bệnh viện Đa Khoa Khu Vực Thủ Đức) -> Lê Văn Chí (Nhà may Việt Thắng) -> Linh Trung (Chợ Linh Trung) -> đường số 6 -> Hoàng Diệu 2 (Đại học Ngân Hàng) -> Kha Vạn Cân (Học viện tư pháp, Đại học Cảnh sát) -> Tô Ngọc Vân(Vòng xoay Chợ Thủ Đức) -> Tam Hà (Giáo xứ Tam Hà, Bệnh Viện Thành Phố Thủ Đức)\r\n\r\n🚌 Lộ trình 4: BXMĐ Mới – Vòng Xoay Liên Phường\r\n\r\nBXMĐ MỚI -> Hoàng Hữu Nam -> Lê Văn Việt -> Man Thiện (Chung cư C3,6,7, Học Viện Chính Trị) ->Lê Văn Việt -> Đình Phong Phú (Đình phong phú) -> Tăng Nhơn Phú (Cao Đẳng Công Thương) -> Đỗ Xuân Hợp (Đại học văn hóa CS 2) -> Liên Phường (Cao đẳng Sư Phạm Trung Ương 2, Vòng Xoay Liên Phường)\r\n\r\n📍Lộ trình trung chuyển Văn phòng - Văn phòng:\r\n\r\n1. Hàng Xanh\r\n\r\n2. BXMĐ cũ\r\n\r\n3. VP Suối Linh\r\n\r\n4. Xa Lộ Hà Nội\r\n\r\n5. Lê Hồng Phong\r\n\r\n6. Quận 1 (43 NCT, 205 PNL)\r\n\r\n🏥10 Bệnh viện lớn từ BXMĐ mới:\r\n\r\n1. Bệnh Viện Đại Học Y Dược\r\n\r\n2. Bệnh Viện Chợ Rẫy\r\n\r\n3. Bệnh Viện Hòa Hảo\r\n\r\n4. Bệnh Viện Mắt\r\n\r\n5. Bệnh Viện Phạm Ngọc Thạch\r\n\r\n6. Bệnh Viện Ung Bướu - Cs2\r\n\r\n7. Bệnh Viện Nhi Đồng 1\r\n\r\n8. Bệnh Viện Chấn Thương Chỉnh Hình\r\n\r\n9. Bệnh Viện Từ Dũ\r\n\r\n10. Bệnh Viện Đa Khoa Khu Vực Thủ Đức\r\n\r\nQuý khách hàng cần thông tin trung chuyển ở bến xe khác? Bình luận ngay để Công ty Phương Trang hỗ trợ kịp thời nhé!👇👇👇\r\n\r\n♥️Công Ty Cổ Phần Xe Khách Phương Trang – FUTA Bus Lines hân hạnh được phục vụ Quý Khách!\r\n\r\n📌Thông tin chi tiết xin vui lòng liên hệ:\r\n\r\n☎ Trung Tâm Tổng Đài & CSKH: 𝟏𝟗𝟎𝟎.𝟔𝟎𝟔𝟕\r\n\r\n☎ Tổng đài đón trả: 𝟏𝟗𝟎𝟎.𝟔𝟗𝟏𝟖', 'https://storage.googleapis.com/futa-busline-web-cms-prod/Website_599x337px_f228dc3a1e/Website_599x337px_f228dc3a1e.png', '2025-10-07 09:33:27', 32, NULL),
(17, 'TRẢI NGHIỆM DỊCH VỤ TRUNG CHUYỂN ĐÓN TRẢ ĐIỂM TẠI TP.HCM TỪ NGÀY 26/03', 'Không còn những chuyến xe trung chuyển vòng vèo làm mất thời gian của Quý khách. Nay Công ty Phương Trang chính thức thay đổi dịch vụ trung chuyển Đón Trả Tận Nơi thành ĐÓN TRẢ ĐIỂM trong khu vực TP.HCM, từ ngày 26/03/2025.\r\n599.png\r\n\r\nVới dịch vụ trung chuyển ĐÓN TRẢ ĐIỂM, Quý khách sẽ được phục vụ THUẬN TIỆN HƠN – NHANH CHÓNG HƠN :\r\n\r\n💯Giảm thiểu tối đa thời gian chờ đợi xe trung chuyển và rút ngắn đáng kể thời gian di chuyển trên mỗi chuyến xe.\r\n\r\n💯Tối ưu hóa lộ trình di chuyển:\r\n\r\nCó nhiều điểm đón trả cố định tại các vị trí dễ nhận biết (Trung tâm thương mại, bệnh viện, trường học,…), tập trung nhiều tại các khu dân cư.\r\n\r\nLộ trình rõ ràng, phù hợp, giúp Quý khách dễ dàng lựa chọn điểm đón trả thuận tiện nhất.\r\n\r\nThông tin chi tiết về dịch vụ mới:\r\n\r\n🌟Thời gian hoạt động: 24/7, tất cả các ngày trong tuần (Kể cả Lễ/Tết).\r\n\r\n🌟Thời gian xe trung chuyển Đón/Trả điểm:\r\n\r\nThời gian Đăng ký Đơn Đón Điểm trước 120 phút giờ xe khởi hành; đăng ký Đơn Trả Điểm bất kỳ lúc nào (không giới hạn thời gian)\r\n\r\nXe trung chuyển sẽ Đón khách trước: Từ 30 phút đến 75 phút tùy theo vị trí xa/gần theo từng điểm đón (giảm đáng kể thời gian đón khách so với Đón Tận Nơi là 90 phút -120 phút)\r\n\r\n🌟Cách thức đặt dịch vụ dễ dàng, chỉ cần gọi ngay tổng đài Trung chuyển 𝟏𝟗𝟎𝟎 𝟔𝟗𝟏𝟖 để được hỗ trợ hoặc đăng ký trực tiếp tại các Quầy vé trong hệ thống Công ty Phương Trang.\r\n\r\n❤️Công Ty Phương Trang hân hạnh được phục vụ Quý Khách!\r\n\r\n📌Thông tin chi tiết xin vui lòng liên hệ:\r\n\r\n☎️Tổng đài đón trả khách: 1900.6918\r\n\r\n☎️ Trung Tâm Tổng Đài & CSKH: 1900.6067', 'https://storage.googleapis.com/futa-busline-web-cms-prod/599_2c7fe6e7e8/599_2c7fe6e7e8.png', '2025-10-16 15:00:35', 38, NULL),
(18, 'ĐẶT VÉ FUTA ONLINE, KHỞI HÀNH NGAY - DEAL HỜI TRONG TAY', 'Không cần ra bến, không lo chờ đợi – chỉ vài thao tác trên App FUTA hoặc Website futabus.vn, Quý khách đã có vé trong tay cùng ưu đãi cực hấp dẫn.\r\n599 Đặt vé Online.jpg\r\n\r\nNhằm mang đến cho Quý khách trải nghiệm thuận tiện - nhanh chóng - tiết kiệm, Công ty Phương Trang triển khai chương trình khuyến mãi đặc biệt dành cho khách hàng khi mua vé trực tuyến qua Website futabus.vn hoặc Ứng dụng FUTA.\r\n\r\n🎁 Ưu đãi hấp dẫn:\r\n\r\nGiảm ngay 2% cho tất cả giao dịch mua vé online thành công (áp dụng cho vé một chiều).\r\n\r\nGiảm ngay 4% khi khách hàng mua vé khứ hồi (bao gồm chiều đi và chiều về trên cùng tuyến).\r\n\r\n⚡ Điểm nổi bật của chương trình:\r\n\r\nÁp dụng toàn quốc cho tất cả các tuyến xe do Công ty Phương Trang đang khai thác.\r\n\r\nKhông giới hạn số lượng vé, khách hàng có thể thoải mái đặt vé tùy theo nhu cầu.\r\n\r\nƯu đãi được trừ trực tiếp ngay khi thanh toán trực tuyến mà không cần áp mã.\r\n\r\nVé vẫn được áp dụng đầy đủ chính sách hủy/đổi theo quy định hiện hành.\r\n\r\nChương trình bắt đầu từ ngày 16/09/2025 - 31/01/2026\r\n\r\n📌 Lưu ý: Chương trình KHÔNG áp dụng trong các ngày cao điểm Lễ/Tết.\r\n\r\nTài khoản phải được đăng nhập mới sử dụng chương trình chương trình khuyến mãi.\r\n\r\n👉Hành trình về quê, đi chơi hay công tác đều trở nên trọn vẹn hơn khi đặt vé FUTA online vừa nhanh, vừa tiện, vừa tiết kiệm.\r\n\r\n♥️Công Ty Phương Trang hân hạnh được phục vụ Quý Khách!\r\n\r\n📌Thông tin chi tiết xin vui lòng liên hệ:\r\n\r\n☎Trung Tâm Tổng Đài & CSKH: 𝟏𝟗𝟎𝟎.𝟔𝟎𝟔𝟕', 'https://storage.googleapis.com/futa-busline-web-cms-prod/599_Dat_ve_Online_10b0c4557f/599_Dat_ve_Online_10b0c4557f.jpg', '2025-10-19 17:31:11', 39, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tram_xe`
--

CREATE TABLE `tram_xe` (
  `ma_tram_xe` int(11) NOT NULL,
  `ten_tram` varchar(100) NOT NULL,
  `dia_chi` varchar(255) NOT NULL,
  `tinh_thanh` varchar(100) NOT NULL,
  `ma_nha_xe` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tram_xe`
--

INSERT INTO `tram_xe` (`ma_tram_xe`, `ten_tram`, `dia_chi`, `tinh_thanh`, `ma_nha_xe`) VALUES
(1, 'An Giang', 'Đường Cách Mạng Tháng Tám, Thành phố Long Xuyên', 'An Giang', 1),
(2, 'Bà Rịa - Vũng Tàu', 'Quốc lộ 51, Thành phố Vũng Tàu', 'Bà Rịa - Vũng Tàu', 2),
(3, 'Bắc Giang', 'Đường Hùng Vương, Thành phố Bắc Giang', 'Bắc Giang', 3),
(4, 'Bắc Kạn', 'Đường Hùng Vương, Thành phố Bắc Kạn', 'Bắc Kạn', 1),
(5, 'Bạc Liêu', 'Đường Trần Huỳnh, Thành phố Bạc Liêu', 'Bạc Liêu', 15),
(6, 'Bắc Ninh', 'Đường Trần Hưng Đạo, Thành phố Bắc Ninh', 'Bắc Ninh', 3),
(7, 'Bến Tre', 'Đường Đồng Khởi, Thành phố Bến Tre', 'Bến Tre', NULL),
(8, 'Bình Định', 'Đường Trần Hưng Đạo, Thành phố Quy Nhơn', 'Bình Định', NULL),
(9, 'Bình Dương', 'Đại lộ Bình Dương, Thành phố Thủ Dầu Một', 'Bình Dương', NULL),
(10, 'Bình Phước', 'Đường ĐT741, Thành phố Đồng Xoài', 'Bình Phước', NULL),
(11, 'Bình Thuận', 'Đường Trần Quý Cáp, Thành phố Phan Thiết', 'Bình Thuận', NULL),
(12, 'Cà Mau', 'Đường Trần Hưng Đạo, Thành phố Cà Mau', 'Cà Mau', NULL),
(13, 'Cần Thơ', 'Đường 30 Tháng 4, Quận Ninh Kiều', 'Cần Thơ', NULL),
(14, 'Cao Bằng', 'Đường Hoàng Đình Giong, Thành phố Cao Bằng', 'Cao Bằng', NULL),
(15, 'Đà Nẵng', 'Đường 2 Tháng 9, Quận Hải Châu', 'Đà Nẵng', NULL),
(16, 'Đắk Lắk', 'Đường Nguyễn Tất Thành, Thành phố Buôn Ma Thuột', 'Đắk Lắk', NULL),
(17, 'Đắk Nông', 'Đường Lê Duẩn, Thành phố Gia Nghĩa', 'Đắk Nông', NULL),
(18, 'Điện Biên', 'Đường Võ Nguyên Giáp, Thành phố Điện Biên Phủ', 'Điện Biên', NULL),
(19, 'Đồng Nai', 'Đường Hưng Đạo Vương, Thành phố Biên Hòa', 'Đồng Nai', NULL),
(20, 'Đồng Tháp', 'Đường Nguyễn Huệ, Thành phố Cao Lãnh', 'Đồng Tháp', NULL),
(21, 'Gia Lai', 'Đường Hùng Vương, Thành phố Pleiku', 'Gia Lai', NULL),
(22, 'Hà Giang', 'Đường Nguyễn Trãi, Thành phố Hà Giang', 'Hà Giang', NULL),
(23, 'Hà Nam', 'Đường Trần Hưng Đạo, Thành phố Phủ Lý', 'Hà Nam', NULL),
(24, 'Hà Nội', 'Đường Trần Duy Hưng, Quận Cầu Giấy', 'Hà Nội', NULL),
(25, 'Hà Tĩnh', 'Đường Trần Phú, Thành phố Hà Tĩnh', 'Hà Tĩnh', NULL),
(26, 'Hải Dương', 'Đường Nguyễn Lương Bằng, Thành phố Hải Dương', 'Hải Dương', NULL),
(27, 'Hải Phòng', 'Đường Lạch Tray, Quận Ngô Quyền', 'Hải Phòng', NULL),
(28, 'Hậu Giang', 'Đường 30 Tháng 4, Thành phố Vị Thanh', 'Hậu Giang', NULL),
(29, 'Hòa Bình', 'Đường Trần Hưng Đạo, Thành phố Hòa Bình', 'Hòa Bình', NULL),
(30, 'Hồ Chí Minh', 'Đường Điện Biên Phủ, Quận Bình Thạnh', 'Hồ Chí Minh', NULL),
(31, 'Hưng Yên', 'Đường Nguyễn Văn Linh, Thành phố Hưng Yên', 'Hưng Yên', NULL),
(32, 'Khánh Hòa', 'Đường Lê Hồng Phong, Thành phố Nha Trang', 'Khánh Hòa', NULL),
(33, 'Kiên Giang', 'Đường Nguyễn Trung Trực, Thành phố Rạch Giá', 'Kiên Giang', NULL),
(34, 'Kon Tum', 'Đường Phan Đình Phùng, Thành phố Kon Tum', 'Kon Tum', NULL),
(35, 'Lai Châu', 'Đường Trần Hưng Đạo, Thành phố Lai Châu', 'Lai Châu', NULL),
(36, 'Lâm Đồng', 'Đường Trần Quốc Toản, Thành phố Đà Lạt', 'Lâm Đồng', NULL),
(37, 'Lạng Sơn', 'Đường Hùng Vương, Thành phố Lạng Sơn', 'Lạng Sơn', NULL),
(38, 'Lào Cai', 'Đường Hoàng Liên, Thành phố Lào Cai', 'Lào Cai', NULL),
(39, 'Long An', 'Đường Hùng Vương, Thành phố Tân An', 'Long An', NULL),
(40, 'Nam Định', 'Đường Trần Hưng Đạo, Thành phố Nam Định', 'Nam Định', NULL),
(41, 'Nghệ An', 'Đường Quang Trung, Thành phố Vinh', 'Nghệ An', NULL),
(42, 'Ninh Bình', 'Đường Trần Hưng Đạo, Thành phố Ninh Bình', 'Ninh Bình', NULL),
(43, 'Ninh Thuận', 'Đường Thống Nhất, Thành phố Phan Rang-Tháp Chàm', 'Ninh Thuận', NULL),
(44, 'Phú Thọ', 'Đường Hùng Vương, Thành phố Việt Trì', 'Phú Thọ', NULL),
(45, 'Phú Yên', 'Đường Trần Hưng Đạo, Thành phố Tuy Hòa', 'Phú Yên', NULL),
(46, 'Quảng Bình', 'Đường Trần Hưng Đạo, Thành phố Đồng Hới', 'Quảng Bình', NULL),
(47, 'Quảng Nam', 'Đường Trần Phú, Thành phố Tam Kỳ', 'Quảng Nam', NULL),
(48, 'Quảng Ngãi', 'Đường Hùng Vương, Thành phố Quảng Ngãi', 'Quảng Ngãi', NULL),
(49, 'Quảng Ninh', 'Đường Hạ Long, Thành phố Hạ Long', 'Quảng Ninh', NULL),
(50, 'Quảng Trị', 'Đường Hùng Vương, Thành phố Đông Hà', 'Quảng Trị', NULL),
(51, 'Sóc Trăng', 'Đường Trần Hưng Đạo, Thành phố Sóc Trăng', 'Sóc Trăng', NULL),
(52, 'Sơn La', 'Đường Tô Hiệu, Thành phố Sơn La', 'Sơn La', NULL),
(53, 'Tây Ninh', 'Đường Cách Mạng Tháng Tám, Thành phố Tây Ninh', 'Tây Ninh', NULL),
(54, 'Thái Bình', 'Đường Trần Hưng Đạo, Thành phố Thái Bình', 'Thái Bình', NULL),
(55, 'Thái Nguyên', 'Đường Hoàng Văn Thụ, Thành phố Thái Nguyên', 'Thái Nguyên', NULL),
(56, 'Thanh Hóa', 'Đường Trần Phú, Thành phố Thanh Hóa', 'Thanh Hóa', NULL),
(57, 'Thừa Thiên Huế', 'Đường Hùng Vương, Thành phố Huế', 'Thừa Thiên Huế', NULL),
(58, 'Tiền Giang', 'Đường Hùng Vương, Thành phố Mỹ Tho', 'Tiền Giang', NULL),
(59, 'Trà Vinh', 'Đường Điện Biên Phủ, Thành phố Trà Vinh', 'Trà Vinh', NULL),
(60, 'Tuyên Quang', 'Đường Quang Trung, Thành phố Tuyên Quang', 'Tuyên Quang', NULL),
(61, 'Vĩnh Long', 'Đường Hùng Vương, Thành phố Vĩnh Long', 'Vĩnh Long', NULL),
(62, 'Vĩnh Phúc', 'Đường Trần Phú, Thành phố Vĩnh Yên', 'Vĩnh Phúc', NULL),
(63, 'Yên Bái', 'Đường Trần Hưng Đạo, Thành phố Yên Bái', 'Yên Bái', NULL),
(66, 'TEST1', 'TEST', 'TEST', 15),
(67, 'test_tram1', 'test_tram', 'Vĩnh Long', 3),
(69, 'Tram_di', 'Tram_di', 'Hà Nội', 3),
(70, 'tram_den', 'tram_den', 'Hồ Chí Minh', 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tuyenphobien`
--

CREATE TABLE `tuyenphobien` (
  `id` int(11) NOT NULL,
  `matpb` varchar(20) NOT NULL,
  `tentpb` varchar(100) NOT NULL,
  `imgtpb` varchar(255) NOT NULL,
  `soluongdatdi` int(11) DEFAULT 0,
  `soyeuthich` int(11) DEFAULT 0,
  `ma_xe` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tuyenphobien`
--

INSERT INTO `tuyenphobien` (`id`, `matpb`, `tentpb`, `imgtpb`, `soluongdatdi`, `soyeuthich`, `ma_xe`) VALUES
(1, 'TPB001', 'Sài Gòn - Cần Thơ', 'header.jpg', 100, 50, 1),
(2, 'TPB002', 'Hà Nội - Hải Phòng', 'header.jpg', 80, 40, 2),
(3, 'TPB003', 'Đà Nẵng - Huế', 'header.jpg', 70, 35, 3),
(4, 'TPB004', 'Sài Gòn - Đà Lạt', 'header.jpg', 90, 60, 4),
(5, 'TPB005', 'Hà Nội - Sapa', 'header.jpg', 120, 80, 5),
(6, 'TPB006', 'Cần Thơ - Cà Mau', 'header.jpg', 60, 30, 6),
(7, 'TPB007', 'Hà Nội - Nghệ An', 'header.jpg', 95, 45, 7),
(8, 'TPB008', 'Sài Gòn - Nha Trang', 'header.jpg', 85, 50, 8),
(9, 'TPB009', 'Đà Nẵng - Quảng Ngãi', 'header.jpg', 55, 20, 9),
(10, 'TPB010', 'Hà Nội - Lào Cai', 'header.jpg', 110, 70, 10);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tu_ngu_nhay_cam`
--

CREATE TABLE `tu_ngu_nhay_cam` (
  `id` int(11) NOT NULL,
  `tu_khoa` varchar(100) NOT NULL,
  `mo_ta` varchar(255) DEFAULT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tu_ngu_nhay_cam`
--

INSERT INTO `tu_ngu_nhay_cam` (`id`, `tu_khoa`, `mo_ta`, `ngay_tao`) VALUES
(1, 'đồ ngu', 'Từ ngữ xúc phạm', '2025-10-07 00:30:02'),
(2, 'ngu ngốc', 'Từ ngữ xúc phạm', '2025-10-07 00:30:02'),
(3, 'đồ khốn', 'Từ ngữ xúc phạm', '2025-10-07 00:30:02'),
(4, 'súc vật', 'Từ ngữ xúc phạm', '2025-10-07 00:30:02'),
(5, 'đồ chó', 'Từ ngữ xúc phạm', '2025-10-07 00:30:02'),
(6, 'con lợn', 'Từ ngữ xúc phạm', '2025-10-07 00:30:02'),
(7, 'đồ ngu xuẩn', 'Từ ngữ xúc phạm', '2025-10-07 00:30:02'),
(8, 'mẹ mày', 'Từ ngữ thô tục', '2025-10-07 00:30:02'),
(9, 'địt', 'Từ ngữ thô tục', '2025-10-07 00:30:02'),
(10, 'lồn', 'Từ ngữ thô tục', '2025-10-07 00:30:02'),
(11, 'cặc', 'Từ ngữ thô tục', '2025-10-07 00:30:02'),
(12, 'dm', 'Từ ngữ thô tục', '2025-10-07 00:30:02'),
(13, 'đm', 'Từ ngữ thô tục', '2025-10-07 00:30:02'),
(14, 'vcl', 'Từ ngữ thô tục', '2025-10-07 00:30:02'),
(15, 'vãi', 'Từ ngữ thô tục', '2025-10-07 00:30:02'),
(16, 'shit', 'Từ ngữ tiếng Anh thô tục', '2025-10-07 00:30:02'),
(17, 'fuck', 'Từ ngữ tiếng Anh thô tục', '2025-10-07 00:30:02'),
(18, 'bitch', 'Từ ngữ tiếng Anh thô tục', '2025-10-07 00:30:02'),
(19, 'scam', 'Từ ngữ liên quan đến lừa đảo', '2025-10-07 00:30:02'),
(20, 'lừa đảo', 'Từ ngữ tiêu cực', '2025-10-07 00:30:02');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('Nam','Nữ','Khác') DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `role` enum('user','admin','bus_owner','staff','driver') DEFAULT 'user',
  `ma_nha_xe` varchar(10) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expires_at` timestamp NULL DEFAULT NULL,
  `reset_expiry` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `phone`, `address`, `date_of_birth`, `gender`, `email`, `password`, `fullname`, `role`, `ma_nha_xe`, `reset_token`, `reset_token_expires_at`, `reset_expiry`, `created_at`) VALUES
(2, 'user2', '0902222222', NULL, NULL, NULL, 'user2@mail.com', 'pass2', 'Tran Thi BC', 'user', NULL, NULL, NULL, NULL, '2025-09-23 06:24:29'),
(15, 'admin', '0915555555', NULL, NULL, NULL, 'admin@gmail.com', 'admin@gmail.com', 'Admin System', 'admin', NULL, NULL, NULL, NULL, '2025-09-23 06:24:29'),
(20, '09390120331', '09390120331', NULL, NULL, NULL, 'vofanh1710@gmail.com', '$2y$10$Yqldr0xo1aprTsWbkNYcMOj1YQMpPeQd2njCv1tTbslMfAMs7mbDy', 'ADMIN1000', 'admin', NULL, NULL, NULL, NULL, '2025-09-30 05:57:37'),
(31, 'ADMIN2', '1234567890', NULL, NULL, NULL, 'ADMIN2@gmail.com', '$2y$10$pH12MGthFRwmb2H3iqyHle.ebb1Le31D35qz3c5g8b.ddYEXIXH6q', 'ADMIN2', 'admin', NULL, NULL, NULL, NULL, '2025-10-04 00:05:43'),
(32, '0939206179', '0939206179', NULL, NULL, NULL, 'huy@gmail.com', '$2y$10$xUOgvW4BAku4sU1YSO7luOSXsYKkUzicNH7C0o8BVnCIKgC7Yw5FO', 'hoang huy', 'user', NULL, NULL, NULL, NULL, '2025-10-07 00:44:51'),
(34, 'thanhloine', '0966421557', NULL, NULL, NULL, 'admin100@gmail.com', '$2y$12$kQQbSvBpZHMOuwBKzLeBkeCa/U9r8fFgReqfd8g6qXbGJxds48Uhq', 'Lê Thành Lợi', 'user', NULL, NULL, NULL, NULL, '2025-10-15 03:08:46'),
(35, 'teststaff', '0123456789', NULL, NULL, NULL, 'teststaff@test.com', '$2y$12$f4vG1TblrDoYZtLH8wBgH.2eqs3mK3pGxkCBbwW/0xdnivXBMfWiW', 'Test Staff', 'staff', NULL, NULL, NULL, NULL, '2025-10-15 11:39:47'),
(36, 'testbusowner', '0987654322', NULL, NULL, NULL, 'testbusowner@test.com', '$2y$12$LuWoJs.nMEc.6JLj9AUcxuan0rOiD7gQ4FGkYmUJHRL91t33ACqyS', 'Test Bus Owner', 'bus_owner', '1', NULL, NULL, NULL, '2025-10-15 11:39:47'),
(37, 'staff', '0123456789', NULL, NULL, NULL, 'staff@busbooking.com', 'staff@busbooking.com', 'Staff User', 'staff', NULL, NULL, NULL, NULL, '2025-10-15 11:41:10'),
(38, 'busowner', '0987654321', NULL, NULL, NULL, 'busower@gmail.com', '$2y$12$JKnitiVOJGFQWDKmoNj.1ubRXXvJixfmrCaQYbdhMGluLoFCzZARi', 'Bus Owner', 'bus_owner', '3', NULL, NULL, NULL, '2025-10-15 11:41:24'),
(39, 'nhanvien', '0939206174', NULL, NULL, NULL, 'nhnavien@gmail.com', '$2y$12$7y0ljkbIcL7lNEeJbJ3Ws.ASdC6Wn7cZbz3Ew9k3qNXsy5ff.YCyK', 'nhanvien', 'staff', NULL, NULL, NULL, NULL, '2025-10-18 01:21:22');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ve_khuyenmai`
--

CREATE TABLE `ve_khuyenmai` (
  `id` int(11) NOT NULL,
  `dat_ve_id` int(11) NOT NULL,
  `ma_km` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `ve_khuyenmai`
--

INSERT INTO `ve_khuyenmai` (`id`, `dat_ve_id`, `ma_km`) VALUES
(14, 47, 13),
(15, 47, 13);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `binh_luan`
--
ALTER TABLE `binh_luan`
  ADD PRIMARY KEY (`ma_bl`),
  ADD KEY `fk_bl_user` (`user_id`),
  ADD KEY `fk_bl_chuyen` (`chuyen_xe_id`),
  ADD KEY `fk_bl_nv` (`nv_id`),
  ADD KEY `fk_bl_parent` (`parent_id`);

--
-- Chỉ mục cho bảng `booking_sessions`
--
ALTER TABLE `booking_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_key` (`session_key`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `chuyen_xe_id` (`chuyen_xe_id`);

--
-- Chỉ mục cho bảng `chuyen_xe`
--
ALTER TABLE `chuyen_xe`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_chuyen_nhaxe` (`ma_nha_xe`),
  ADD KEY `fk_chuyen_tramdi` (`ma_tram_di`),
  ADD KEY `fk_chuyen_tramden` (`ma_tram_den`);

--
-- Chỉ mục cho bảng `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `dat_ve`
--
ALTER TABLE `dat_ve`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_datve_user` (`user_id`),
  ADD KEY `fk_datve_chuyen` (`chuyen_xe_id`);

--
-- Chỉ mục cho bảng `doanh_thu`
--
ALTER TABLE `doanh_thu`
  ADD PRIMARY KEY (`ma_dt`),
  ADD KEY `fk_dt_chuyen` (`chuyen_xe_id`);

--
-- Chỉ mục cho bảng `khuyen_mai`
--
ALTER TABLE `khuyen_mai`
  ADD PRIMARY KEY (`ma_km`),
  ADD UNIQUE KEY `ma_code` (`ma_code`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `nhan_vien`
--
ALTER TABLE `nhan_vien`
  ADD PRIMARY KEY (`ma_nv`),
  ADD KEY `fk_nv_nhaxe` (`ma_nha_xe`);

--
-- Chỉ mục cho bảng `nha_xe`
--
ALTER TABLE `nha_xe`
  ADD PRIMARY KEY (`ma_nha_xe`),
  ADD UNIQUE KEY `uq_ma_nha_xe` (`ma_nha_xe`);

--
-- Chỉ mục cho bảng `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Chỉ mục cho bảng `tin_tuc`
--
ALTER TABLE `tin_tuc`
  ADD PRIMARY KEY (`ma_tin`),
  ADD KEY `fk_tintuc_user` (`user_id`),
  ADD KEY `fk_tintuc_nhaxe` (`ma_nha_xe`);

--
-- Chỉ mục cho bảng `tram_xe`
--
ALTER TABLE `tram_xe`
  ADD PRIMARY KEY (`ma_tram_xe`),
  ADD UNIQUE KEY `uq_ma_tram_xe` (`ma_tram_xe`),
  ADD KEY `fk_tramxe_nhaxe` (`ma_nha_xe`);

--
-- Chỉ mục cho bảng `tuyenphobien`
--
ALTER TABLE `tuyenphobien`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `matpb` (`matpb`),
  ADD KEY `fk_tpb_chuyen` (`ma_xe`);

--
-- Chỉ mục cho bảng `tu_ngu_nhay_cam`
--
ALTER TABLE `tu_ngu_nhay_cam`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tu_khoa` (`tu_khoa`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `uq_username` (`username`),
  ADD UNIQUE KEY `uq_email` (`email`);

--
-- Chỉ mục cho bảng `ve_khuyenmai`
--
ALTER TABLE `ve_khuyenmai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_vkm_datve` (`dat_ve_id`),
  ADD KEY `fk_vkm_km` (`ma_km`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `binh_luan`
--
ALTER TABLE `binh_luan`
  MODIFY `ma_bl` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT cho bảng `booking_sessions`
--
ALTER TABLE `booking_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `chuyen_xe`
--
ALTER TABLE `chuyen_xe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `contact`
--
ALTER TABLE `contact`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `dat_ve`
--
ALTER TABLE `dat_ve`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT cho bảng `doanh_thu`
--
ALTER TABLE `doanh_thu`
  MODIFY `ma_dt` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `khuyen_mai`
--
ALTER TABLE `khuyen_mai`
  MODIFY `ma_km` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `nhan_vien`
--
ALTER TABLE `nhan_vien`
  MODIFY `ma_nv` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `nha_xe`
--
ALTER TABLE `nha_xe`
  MODIFY `ma_nha_xe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `tin_tuc`
--
ALTER TABLE `tin_tuc`
  MODIFY `ma_tin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `tram_xe`
--
ALTER TABLE `tram_xe`
  MODIFY `ma_tram_xe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT cho bảng `tuyenphobien`
--
ALTER TABLE `tuyenphobien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `tu_ngu_nhay_cam`
--
ALTER TABLE `tu_ngu_nhay_cam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT cho bảng `ve_khuyenmai`
--
ALTER TABLE `ve_khuyenmai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `binh_luan`
--
ALTER TABLE `binh_luan`
  ADD CONSTRAINT `fk_bl_chuyen` FOREIGN KEY (`chuyen_xe_id`) REFERENCES `chuyen_xe` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_bl_nv` FOREIGN KEY (`nv_id`) REFERENCES `nhan_vien` (`ma_nv`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_bl_parent` FOREIGN KEY (`parent_id`) REFERENCES `binh_luan` (`ma_bl`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_bl_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `booking_sessions`
--
ALTER TABLE `booking_sessions`
  ADD CONSTRAINT `fk_bs_chuyen` FOREIGN KEY (`chuyen_xe_id`) REFERENCES `chuyen_xe` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_bs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `chuyen_xe`
--
ALTER TABLE `chuyen_xe`
  ADD CONSTRAINT `fk_chuyen_nhaxe` FOREIGN KEY (`ma_nha_xe`) REFERENCES `nha_xe` (`ma_nha_xe`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_chuyen_tramden` FOREIGN KEY (`ma_tram_den`) REFERENCES `tram_xe` (`ma_tram_xe`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_chuyen_tramdi` FOREIGN KEY (`ma_tram_di`) REFERENCES `tram_xe` (`ma_tram_xe`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `dat_ve`
--
ALTER TABLE `dat_ve`
  ADD CONSTRAINT `fk_datve_chuyen` FOREIGN KEY (`chuyen_xe_id`) REFERENCES `chuyen_xe` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_datve_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `doanh_thu`
--
ALTER TABLE `doanh_thu`
  ADD CONSTRAINT `fk_dt_chuyen` FOREIGN KEY (`chuyen_xe_id`) REFERENCES `chuyen_xe` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `nhan_vien`
--
ALTER TABLE `nhan_vien`
  ADD CONSTRAINT `fk_nv_nhaxe` FOREIGN KEY (`ma_nha_xe`) REFERENCES `nha_xe` (`ma_nha_xe`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tin_tuc`
--
ALTER TABLE `tin_tuc`
  ADD CONSTRAINT `fk_tintuc_nhaxe` FOREIGN KEY (`ma_nha_xe`) REFERENCES `nha_xe` (`ma_nha_xe`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_tintuc_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tram_xe`
--
ALTER TABLE `tram_xe`
  ADD CONSTRAINT `fk_tramxe_nhaxe` FOREIGN KEY (`ma_nha_xe`) REFERENCES `nha_xe` (`ma_nha_xe`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `tuyenphobien`
--
ALTER TABLE `tuyenphobien`
  ADD CONSTRAINT `fk_tpb_chuyen` FOREIGN KEY (`ma_xe`) REFERENCES `chuyen_xe` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `ve_khuyenmai`
--
ALTER TABLE `ve_khuyenmai`
  ADD CONSTRAINT `fk_vkm_datve` FOREIGN KEY (`dat_ve_id`) REFERENCES `dat_ve` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_vkm_km` FOREIGN KEY (`ma_km`) REFERENCES `khuyen_mai` (`ma_km`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
