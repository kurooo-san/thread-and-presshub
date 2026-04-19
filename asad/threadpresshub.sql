-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2026 at 03:34 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `threadpresshub`
--

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_faq`
--

CREATE TABLE `chatbot_faq` (
  `id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL COMMENT 'Question keywords',
  `answer` longtext NOT NULL COMMENT 'Bot response',
  `category` varchar(50) DEFAULT 'general' COMMENT 'FAQ category',
  `priority` int(11) DEFAULT 100 COMMENT 'Priority for matching (higher = matched first)',
  `active` tinyint(1) DEFAULT 1 COMMENT 'Is this FAQ active?',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL COMMENT 'Admin user who created this'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chatbot_faq`
--

INSERT INTO `chatbot_faq` (`id`, `question`, `answer`, `category`, `priority`, `active`, `created_at`, `updated_at`, `created_by`) VALUES
(1, 'help', 'I\'m here to help! You can ask me about:\n• 👕 Products & Collections\n• 💸 Prices & Discounts\n• 🚚 Delivery & Shipping\n• 💳 Payment Methods\n• 📦 Order Tracking\n• 🔄 Returns & Exchanges\n• 👥 Account Help\n\nWhat would you like to know?', 'general', 100, 1, '2026-03-10 01:29:52', '2026-03-10 01:29:52', NULL),
(2, 'products clothing apparel', 'We offer premium apparel for women, men, and kids! 👕👔\n• High-quality fabrics\n• Trendy designs\n• Affordable luxury\n• Wide size range (XS-XXL)\n\nWould you like to browse our collection? 🛍️', 'products', 90, 1, '2026-03-10 01:29:52', '2026-03-10 01:29:52', NULL),
(3, 'mens men clothes', 'Our men\'s collection features:\n• Premium T-shirts & Polos (₱499-₱699)\n• Comfortable Hoodies (₱1,299-₱1,399)\n• Stylish Pants & Jeans (₱1,299-₱1,599)\n• Quality Accessories (₱699+)\n\nAll items available in multiple colors and sizes! 👕', 'products', 85, 1, '2026-03-10 01:29:52', '2026-03-10 01:29:52', NULL),
(4, 'womens women clothes', 'Our women\'s collection includes:\n• Elegant Dresses (₱1,999-₱2,499)\n• Cozy Hoodies (₱1,299-₱1,399)\n• Trendy Pants & Jeans (₱1,499-₱1,599)\n• Comfortable T-shirts (₱499-₱599)\n\nFind your perfect style today! ✨👗', 'products', 85, 1, '2026-03-10 01:29:52', '2026-03-10 01:29:52', NULL),
(5, 'kids children baby', 'We have adorable kids collection:\n• Fun Cartoon Tees (₱299)\n• Cozy Hoodies (₱899)\n• Durable Jeans (₱799)\n• Cute Dresses (₱599)\n• Accessories & Shoes (₱499-₱899)\n\nPerfect for all ages! 👶', 'products', 85, 1, '2026-03-10 01:29:52', '2026-03-10 01:29:52', NULL),
(6, 'price cost how much', 'Our prices are very competitive! 💰\n• Most items: ₱699 - ₱2,999\n• Accessories: Starting from ₱499\n• Seasonal sales: Up to 40% OFF\n\nFree shipping on orders above ₱1,500! 🎁', 'pricing', 80, 1, '2026-03-10 01:29:52', '2026-03-10 01:29:52', NULL),
(7, 'discount sale promo promotion', 'Great news! 🎉 We offer:\n• Regular promotions on seasonal items\n• Up to 40% off during sales\n• Free shipping on orders above ₱1,500\n• Special discounts for PWD & Senior Citizens\n• Loyalty rewards for frequent buyers\n\nCheck our Promotion page for current deals!', 'pricing', 80, 1, '2026-03-10 01:29:52', '2026-03-10 01:29:52', NULL),
(8, 'shipping delivery free', '🚚 Delivery Info:\n• Metro Manila: 1-2 business days\n• Provincial areas: 2-3 business days\n• Free shipping on orders above ₱1,500\n• Standard delivery fee: ₱50\n• All orders are carefully packaged', 'shipping', 75, 1, '2026-03-10 01:29:52', '2026-03-10 01:29:52', NULL),
(9, 'free shipping threshold minimum', 'Yes! We offer free shipping on orders above ₱1,500. 🎁\nThis applies to all areas in the Philippines!\nSave on shipping and enjoy your purchases! 🛍️', 'shipping', 75, 1, '2026-03-10 01:29:52', '2026-03-10 01:29:52', NULL),
(10, 'payment methods pay', '💳 We accept:\n• GCash (Mobile payment)\n• Cash on Delivery (COD)\n• Both options available for all orders\n• Secure transaction process\n• Fast payment confirmation', 'payment', 70, 1, '2026-03-10 01:29:52', '2026-03-10 01:29:52', NULL),
(11, 'gcash online payment', 'GCash Payment Info:\n📱 Quick and safe online payment\n✅ Instant confirmation\n🔒 Secure transaction\n📦 Get your order faster\n\nChoose GCash at checkout for quick processing!', 'payment', 70, 1, '2026-03-10 01:29:52', '2026-03-10 01:29:52', NULL),
(12, 'cod cash on delivery', 'Cash on Delivery (COD) Details:\n💵 Pay when your order arrives\n🚚 Perfect for local deliveries\n✅ No online payment stress\n🔒 Verify items before payment\n\nAvailable for all areas!', 'payment', 70, 1, '2026-03-10 01:29:52', '2026-03-10 01:29:52', NULL),
(13, 'order track status', '📦 Need help with your order?\n• Check status: Login to your account\n• Track delivery: Get real-time updates\n• Returns: 30-day policy available\n• Cancellations: Contact support asap\n\nHow can I help you with your order?', 'orders', 95, 1, '2026-03-10 01:29:52', '2026-03-10 01:29:52', NULL),
(14, 'tracking trace delivery', '📍 Order Tracking:\n1. Log in to your account\n2. Go to \"Orders\" section\n3. Click on your order\n4. View real-time status updates\n5. Get delivery notifications\n\nNeed more help? Contact us!', 'orders', 95, 1, '2026-03-10 01:29:52', '2026-03-10 01:29:52', NULL),
(15, 'return refund policy', 'Return Policy 🔄:\n• 30-day return window from purchase\n• Item must be unused & in original condition\n• Tag and packaging must be intact\n• Free returns on damaged items\n• Contact support to start process\n\nWe want you to be 100% satisfied!', 'support', 60, 1, '2026-03-10 01:29:52', '2026-03-10 01:29:52', NULL),
(16, 'exchange swap change', 'Exchange Service 🔁:\n• Easy size/color changes\n• Within 30 days of purchase\n• Free shipping on exchanges\n• Quick turnaround time\n• Contact support to request\n\nWe make it easy!', 'support', 60, 1, '2026-03-10 01:29:52', '2026-03-10 01:29:52', NULL),
(17, 'account profile manage', '👤 Account Management:\n• Create an account anytime\n• Save favorite items\n• View purchase history\n• Track orders\n• Manage delivery addresses\n• Profile settings & preferences', 'account', 50, 1, '2026-03-10 01:29:52', '2026-03-10 01:29:52', NULL),
(18, 'contact support help email phone', '📞 Contact Thread and Press Hub:\n• 📧 Email: support@threadpresshub.com\n• 📱 Phone: +63 (02) 8123-4567\n• 💬 Chat: Available 24/7 right here!\n• 📍 Serving all of Philippines\n\nWe\'re always happy to help!', 'support', 85, 1, '2026-03-10 01:29:52', '2026-03-10 01:29:52', NULL),
(19, 'thank you thanks', 'Happy to help! 🙏 Enjoy your shopping experience at Thread and Press Hub!', 'general', 40, 1, '2026-03-10 01:29:52', '2026-03-10 01:29:52', NULL),
(20, 'bye goodbye', 'Thank you for visiting! 👋 Come back soon! 🛍️ Happy shopping! ✨', 'general', 40, 1, '2026-03-10 01:29:52', '2026-03-10 01:29:52', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `chat_history`
--

CREATE TABLE `chat_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_message` text NOT NULL,
  `bot_response` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gcash_transactions`
--

CREATE TABLE `gcash_transactions` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','failed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gcash_transactions`
--

INSERT INTO `gcash_transactions` (`id`, `order_id`, `reference_number`, `amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 8, '1212345432', 949.00, 'pending', '2026-03-06 09:33:09', '2026-03-06 09:33:09'),
(2, 9, '1212345432', 749.00, 'pending', '2026-03-07 08:55:17', '2026-03-07 08:55:17'),
(3, 10, '1212345432', 749.00, 'pending', '2026-03-07 16:20:36', '2026-03-07 16:20:36'),
(4, 13, '12345566778', 749.00, 'pending', '2026-03-10 01:37:49', '2026-03-10 01:37:49');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `discount_type` enum('regular','pwd','senior') DEFAULT 'regular',
  `delivery_fee` decimal(10,2) DEFAULT 50.00,
  `total` decimal(10,2) NOT NULL,
  `payment_method` enum('gcash','cod') NOT NULL,
  `payment_reference` varchar(100) DEFAULT NULL,
  `delivery_address` text NOT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','confirmed','preparing','out_for_delivery','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `subtotal`, `discount_amount`, `discount_type`, `delivery_fee`, `total`, `payment_method`, `payment_reference`, `delivery_address`, `notes`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 6895.00, 0.00, 'regular', 50.00, 6945.00, 'cod', NULL, 'abc 1223 cainta rizal', 'none', 'pending', '2026-02-28 05:34:17', '2026-03-09 09:16:08'),
(2, 3, 2998.00, 0.00, 'regular', 50.00, 3048.00, 'gcash', NULL, '123', '456', 'pending', '2026-03-05 10:29:04', '2026-03-05 10:29:04'),
(3, 3, 2998.00, 0.00, 'regular', 50.00, 3048.00, 'gcash', NULL, '123', '456', 'pending', '2026-03-05 10:30:08', '2026-03-05 10:30:08'),
(4, 3, 2998.00, 0.00, 'regular', 50.00, 3048.00, 'gcash', NULL, '123', '456', 'pending', '2026-03-05 10:30:13', '2026-03-05 10:30:13'),
(5, 3, 2998.00, 0.00, 'regular', 50.00, 3048.00, 'cod', NULL, 'qwe', 'asddd', 'out_for_delivery', '2026-03-05 10:30:33', '2026-03-07 02:46:57'),
(6, 3, 2998.00, 0.00, 'regular', 50.00, 3048.00, 'cod', NULL, 'qwe', 'asddd', 'confirmed', '2026-03-05 10:31:41', '2026-03-07 16:29:57'),
(7, 3, 2998.00, 0.00, 'regular', 50.00, 3048.00, 'cod', NULL, 'sad', 'asdd', 'confirmed', '2026-03-05 10:35:12', '2026-03-05 10:53:25'),
(8, 3, 899.00, 0.00, 'regular', 50.00, 949.00, 'gcash', '1212345432', '123 cainta rizal', 'none', 'confirmed', '2026-03-06 09:33:02', '2026-03-06 09:33:09'),
(9, 2, 699.00, 0.00, 'regular', 50.00, 749.00, 'gcash', '1212345432', 'sad', 'sad', 'confirmed', '2026-03-07 08:55:12', '2026-03-07 08:55:17'),
(10, 2, 699.00, 0.00, 'regular', 50.00, 749.00, 'gcash', '1212345432', '123', '123', 'completed', '2026-03-07 16:20:31', '2026-03-07 16:21:05'),
(11, 3, 699.00, 0.00, 'regular', 50.00, 749.00, 'cod', NULL, '123', 'asd', 'pending', '2026-03-07 16:30:40', '2026-03-07 16:30:40'),
(12, 4, 1349.00, 202.35, 'senior', 50.00, 1196.65, 'cod', NULL, '123', '13233', 'pending', '2026-03-09 09:14:02', '2026-03-09 09:14:02'),
(13, 3, 699.00, 0.00, 'regular', 50.00, 749.00, 'gcash', '12345566778', 'sad', 'asd', 'confirmed', '2026-03-10 01:37:41', '2026-03-10 01:37:49');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `color` varchar(50) DEFAULT NULL,
  `size` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `unit_price`, `subtotal`, `color`, `size`) VALUES
(1, 1, 15, 3, 699.00, 2097.00, NULL, NULL),
(2, 1, 12, 1, 2299.00, 2299.00, NULL, NULL),
(3, 1, 14, 1, 2499.00, 2499.00, NULL, NULL),
(4, 7, 12, 1, 2299.00, 2299.00, 'White', 'S'),
(5, 7, 15, 1, 699.00, 699.00, 'White', 'S'),
(6, 8, 34, 1, 899.00, 899.00, 'Black', 'M'),
(7, 9, 15, 1, 699.00, 699.00, 'Black', 'XS'),
(8, 10, 15, 1, 699.00, 699.00, 'Navy', 'M'),
(9, 11, 15, 1, 699.00, 699.00, 'Navy', 'M'),
(10, 12, 11, 1, 1349.00, 1349.00, 'White', 'S'),
(11, 13, 15, 1, 699.00, 699.00, 'White', 'XS');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(50) NOT NULL,
  `gender` enum('mens','womens','kids') DEFAULT 'mens',
  `available_colors` varchar(255) DEFAULT 'Black,White,Navy',
  `available_sizes` varchar(255) DEFAULT 'XS,S,M,L,XL,XXL',
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category`, `gender`, `available_colors`, `available_sizes`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Premium Black Classic T-Shirt', '100% organic cotton, ultra-soft comfort wear. Perfect daily essential.', 499.00, 't-shirts', 'mens', 'Black,White,Navy', 'XS,S,M,L,XL,XXL', 'black_tshirt.jpg', 'active', '2026-02-28 04:51:02', '2026-02-28 04:51:02'),
(2, 'White Designer Graphic T-Shirt', 'Trendy graphic print on premium white cotton. Limited edition design.', 599.00, 't-shirts', 'mens', 'Black,White,Navy', 'XS,S,M,L,XL,XXL', 'white_graphic_tshirt.jpg', 'active', '2026-02-28 04:51:02', '2026-02-28 04:51:02'),
(3, 'Navy Blue Casual Tee', 'Versatile navy blue shirt for any occasion. Durable and stylish.', 449.00, 't-shirts', 'mens', 'Black,White,Navy', 'XS,S,M,L,XL,XXL', 'navy_tshirt.jpg', 'active', '2026-02-28 04:51:02', '2026-02-28 04:51:02'),
(4, 'Gray Essential T-Shirt', 'Minimalist gray tee, perfect for layering or standalone wear.', 399.00, 't-shirts', 'mens', 'Black,White,Navy', 'XS,S,M,L,XL,XXL', 'gray_tshirt.jpg', 'active', '2026-02-28 04:51:02', '2026-02-28 04:51:02'),
(5, 'Premium Black Zip Hoodie', 'Warm and cozy black hoodie with kangaroo pocket and adjustable drawstring.', 1299.00, 'hoodies', 'womens', 'Black,White,Navy', 'XS,S,M,L,XL,XXL', 'black_hoodie.jpg', 'active', '2026-02-28 04:51:02', '2026-02-28 05:53:56'),
(6, 'Luxury Gray Zip Hoodie', 'High-quality gray hoodie with full zip closure and premium lining.', 1399.00, 'hoodies', 'womens', 'Black,White,Navy', 'XS,S,M,L,XL,XXL', 'gray_zip_hoodie.jpg', 'active', '2026-02-28 04:51:02', '2026-02-28 05:53:56'),
(7, 'Navy Pullover Hoodie Supreme', 'Soft navy blue pullover hoodie for maximum comfort and style.', 1349.00, 'hoodies', 'womens', 'Black,White,Navy', 'XS,S,M,L,XL,XXL', 'navy_hoodie.jpg', 'active', '2026-02-28 04:51:02', '2026-02-28 05:53:56'),
(8, 'Maroon Heritage Hoodie', 'Stylish maroon hoodie perfect for casual streetwear looks.', 1299.00, 'hoodies', 'womens', 'Black,White,Navy', 'XS,S,M,L,XL,XXL', 'maroon_hoodie.jpg', 'active', '2026-02-28 04:51:02', '2026-02-28 05:53:56'),
(9, 'Classic Black Denim Jeans', 'Premium black denim with perfect fit and durability. Timeless style.', 1599.00, 'pants', 'womens', 'Black,White,Navy', 'XS,S,M,L,XL,XXL', 'black_jeans.jpg', 'active', '2026-02-28 04:51:02', '2026-02-28 05:53:56'),
(10, 'Modern Blue Slim Fit Jeans', 'Contemporary blue slim fit jeans with stretch comfort technology.', 1499.00, 'pants', 'womens', 'Black,White,Navy', 'XS,S,M,L,XL,XXL', 'blue_jeans.jpg', 'active', '2026-02-28 04:51:02', '2026-02-28 05:53:56'),
(11, 'Versatile Khaki Chinos', 'Premium khaki chinos for smart casual occasions. Easy to style.', 1349.00, 'pants', 'womens', 'Black,White,Navy', 'XS,S,M,L,XL,XXL', 'khaki_chinos.jpg', 'active', '2026-02-28 04:51:02', '2026-02-28 05:53:56'),
(12, 'Elegant Black Dress Supreme', 'Sophisticated black dress perfect for formal and casual occasions.', 2299.00, 'dresses', 'womens', 'Black,White,Navy', 'XS,S,M,L,XL,XXL', 'black_dress.jpg', 'active', '2026-02-28 04:51:02', '2026-02-28 05:53:56'),
(13, 'Summer Floral Garden Dress', 'Beautiful floral print dress ideal for warm weather and outdoor events.', 1999.00, 'dresses', 'womens', 'Black,White,Navy', 'XS,S,M,L,XL,XXL', 'floral_dress.jpg', 'active', '2026-02-28 04:51:02', '2026-02-28 05:53:56'),
(14, 'Navy Midi Dress Classy', 'Premium navy midi dress for a sophisticated and elegant look.', 2499.00, 'dresses', 'womens', 'Black,White,Navy', 'XS,S,M,L,XL,XXL', 'navy_dress.jpg', 'active', '2026-02-28 04:51:02', '2026-02-28 05:53:56'),
(15, 'Premium Leather Belt Black', 'Genuine leather belt with classic buckle. Essential accessory.', 699.00, 'accessories', 'mens', 'Black,White,Navy', 'XS,S,M,L,XL,XXL', 'belt_black.jpg', 'active', '2026-02-28 04:51:02', '2026-02-28 04:51:02'),
(16, 'Floral Summer Dress', 'Light and breezy floral dress perfect for summer outings.', 1899.00, 'dresses', 'womens', 'Pink,White,Yellow', 'S,M,L', 'floral_dress.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(17, 'Elegant Black Maxi Dress', 'Elegant black maxi dress suitable for evening events.', 2499.00, 'dresses', 'womens', 'Black,Maroon', 'M,L,XL', 'black_dress.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(18, 'Women\'s Graphic Tee', 'Trendy graphic tee made from soft cotton.', 499.00, 't-shirts', 'womens', 'White,Black,Blue', 'XS,S,M,L', 'womens_graphic_tshirt.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(19, 'Women\'s Cotton Hoodie', 'Comfortable cotton hoodie for all-day wear.', 1299.00, 'hoodies', 'womens', 'Gray,Pink', 'S,M,L,XL', 'women_hoodie.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(20, 'Women\'s Slim Fit Jeans', 'Stylish slim fit jeans designed for a flattering look.', 1599.00, 'pants', 'womens', 'Blue,Black', 'S,M,L,XL', 'women_jeans.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(21, 'Men\'s Polo Shirt', 'Classic polo shirt perfect for casual and semi-formal occasions.', 699.00, 't-shirts', 'mens', 'Navy,Black,White', 'S,M,L,XL,XXL', 'mens_polo.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(22, 'Men\'s Denim Jacket', 'Durable denim jacket with a modern cut.', 1999.00, 'hoodies', 'mens', 'Blue,Black', 'M,L,XL,XXL', 'mens_denim_jacket.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(23, 'Men\'s Jogger Pants', 'Comfortable jogger pants with elastic waistband.', 1299.00, 'pants', 'mens', 'Gray,Black', 'M,L,XL,XXL', 'mens_joggers.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(24, 'Men\'s Graphic Tee', 'Stylish graphic tee made from premium fabric.', 499.00, 't-shirts', 'mens', 'White,Black,Red', 'S,M,L,XL', 'mens_graphic_tshirt.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(25, 'Men\'s Leather Belt', 'Premium leather belt with classic buckle.', 699.00, 'accessories', 'mens', 'Black,Brown', 'M,L,XL', 'mens_belt.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(26, 'Kids Cartoon Tee', 'Fun cartoon print tee for kids.', 299.00, 't-shirts', 'kids', 'Yellow,Blue,Green', 'XS,S,M', 'kids_cartoon_tshirt.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(27, 'Kids Hoodie', 'Cozy hoodie little ones will love to wear.', 899.00, 'hoodies', 'kids', 'Blue,Pink', 'XS,S,M,L', 'kids_hoodie.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(28, 'Kids Denim Jeans', 'Durable denim jeans perfect for playtime.', 799.00, 'pants', 'kids', 'Blue', 'XS,S,M,L', 'kids_jeans.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(29, 'Kids Floral Dress', 'Cute floral dress designed for kids.', 599.00, 'dresses', 'kids', 'Pink,White', 'XS,S,M', 'kids_floral_dress.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(30, 'Kids Sports Shorts', 'Lightweight shorts for active children.', 399.00, 'pants', 'kids', 'Black,Blue', 'XS,S,M,L', 'kids_sports_shorts.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(31, 'Kids Sandals', 'Comfortable sandals for everyday wear.', 499.00, 'accessories', 'kids', 'Brown,Black', '30,32,34', 'kids_sandals.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(32, 'Kids Backpack', 'Colorful backpack suitable for school.', 599.00, 'accessories', 'kids', 'Red,Blue,Green', 'One Size', 'kids_backpack.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(33, 'Kids Winter Coat', 'Warm coat to keep kids cozy during cold seasons.', 1299.00, 'hoodies', 'kids', 'Red,Blue', 'S,M,L', 'kids_coat.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(34, 'Kids Athletic Shoes', 'Durable shoes for running and play.', 899.00, 'accessories', 'kids', 'White,Black', '34,35,36', 'kids_shoes.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(35, 'Kids Swim Trunks', 'Bright swim trunks perfect for pool days.', 499.00, 'pants', 'kids', 'Blue,Green', 'XS,S,M,L', 'kids_swim_trunks.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('regular','pwd','senior','admin') DEFAULT 'regular',
  `pwd_id` varchar(50) DEFAULT NULL,
  `senior_id` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `phone`, `password`, `user_type`, `pwd_id`, `senior_id`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@thradpresshub', NULL, '$2y$10$ScnDDUtUQi1TqJpD8Pg7GO1iWeNhocrhET/9QUgvsuvnkbO6oS6ry', 'admin', NULL, NULL, '2026-02-28 04:51:02', '2026-02-28 05:36:51'),
(2, 'raymond', 'rayligaya@gmail.com', '09947214569', '$2y$10$odRPvewUnqANlrSQiOXAqOwlWv.ZgAdYhM3N8MPGp6JBrZQ8qX5y2', 'regular', '', '', '2026-02-28 04:55:18', '2026-02-28 04:55:18'),
(3, 'Admin', 'myadmin@threadpresshub.com', NULL, '$2y$10$2BnxOSPEW.NR9gw2OvMZtOZW6gEyJZfUhMJ3CJ40vOCc/1ZxcNV9y', 'admin', NULL, NULL, '2026-03-02 04:58:03', '2026-03-02 04:58:03'),
(4, 'renzo', 'kurozakishizume@gmail.com', '09212856506', '$2y$10$MeWcbG6OD71jtKxdB53V4OFpzhqOEzhYVUby50HyItfN7.fsmbuqa', 'senior', '12313456111', '54631254456', '2026-03-09 09:09:53', '2026-03-09 09:09:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chatbot_faq`
--
ALTER TABLE `chatbot_faq`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_active` (`active`),
  ADD KEY `idx_priority` (`priority`);

--
-- Indexes for table `chat_history`
--
ALTER TABLE `chat_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `gcash_transactions`
--
ALTER TABLE `gcash_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_order_status` (`status`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product_id` (`category`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chatbot_faq`
--
ALTER TABLE `chatbot_faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `chat_history`
--
ALTER TABLE `chat_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gcash_transactions`
--
ALTER TABLE `gcash_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat_history`
--
ALTER TABLE `chat_history`
  ADD CONSTRAINT `chat_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `gcash_transactions`
--
ALTER TABLE `gcash_transactions`
  ADD CONSTRAINT `gcash_transactions_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
