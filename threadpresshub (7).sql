-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2026 at 08:33 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
-- Table structure for table `audit_log`
--

CREATE TABLE `audit_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `entity_type` varchar(50) DEFAULT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_log`
--

INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 3, 'user_login', 'user', 3, 'Login successful', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-03-27 02:09:23'),
(2, 2, 'user_login', 'user', 2, 'Login successful', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-03-27 02:17:17'),
(3, 3, 'user_login', 'user', 3, 'Login successful', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-03-27 02:18:12'),
(4, 2, 'user_login', 'user', 2, 'Login successful', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-03-27 06:18:23'),
(5, 3, 'user_login', 'user', 3, 'Login successful', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-03-27 06:23:12'),
(6, 3, 'product_added', 'product', 36, 'Product: Sunglass', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-03-27 06:24:28'),
(7, 3, 'user_login', 'user', 3, 'Login successful', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-03-27 06:32:31'),
(8, 5, 'user_login', 'user', 5, 'Login successful', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-03-27 06:56:21'),
(9, 3, 'user_login', 'user', 3, 'Login successful', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-03-29 05:26:30'),
(10, 3, 'product_updated', 'product', 34, 'Product: Kids Athletic Shoes', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-03-29 05:35:23'),
(11, 2, 'user_login', 'user', 2, 'Login successful', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-03-29 07:07:08'),
(12, 3, 'user_login', 'user', 3, 'Login successful', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-03-30 04:35:01'),
(13, 6, 'user_login', 'user', 6, 'Login successful', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-01 02:57:35'),
(14, 3, 'user_login', 'user', 3, 'Login successful', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-01 15:37:08'),
(15, 6, 'user_login', 'user', 6, 'Login successful', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-06 10:25:08'),
(16, 3, 'user_login', 'user', 3, 'Login successful', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 06:05:16'),
(17, 6, 'user_login', 'user', 6, 'Login successful', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 06:06:02'),
(18, 3, 'user_login', 'user', 3, 'Login successful', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 07:02:58'),
(19, 7, 'user_login', 'user', 7, 'Login successful', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 07:07:42'),
(20, 3, 'user_login', 'user', 3, 'Login successful', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 07:12:03'),
(21, 7, 'user_login', 'user', 7, 'Login successful', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 08:39:29'),
(22, 6, 'user_login', 'user', 6, 'Login successful', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 10:36:09'),
(23, 6, 'user_login', 'user', 6, 'Login successful', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-15 21:00:35'),
(24, 3, 'user_login', 'user', 3, 'Login successful', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-16 06:20:49');

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

--
-- Dumping data for table `chat_history`
--

INSERT INTO `chat_history` (`id`, `user_id`, `user_message`, `bot_response`, `created_at`) VALUES
(1, 2, 'hi', 'Hello there! 👋 Welcome to Thread and Press Hub. I\'m your AI assistant, ready to help you with any fashion queries or information about our products. How can I assist you today?', '2026-03-16 08:20:11'),
(2, 2, 'Find Products', 'Certainly! We have a wide range of stylish apparel and accessories at Thread and Press Hub. You can browse our full collection on the Shop page of our website.\n\nAre you looking for something specific, perhaps a **dress**, **hoodie**, **jeans**, or **accessories**? I\'d be happy to help you find the perfect item! ✨', '2026-03-16 08:32:40'),
(3, 2, 'Order Help', 'Hello Raymond! I\'d be happy to assist you with your orders.\n\nYou can always reach our customer support team at support@threadpresshub.com or call us at +63 (02) 8123-4567 for any order-related inquiries.\n\nI can see your recent orders under the name Raymond:\n*   **Order #15**: Status: Pending, Total: ₱749.00, Date: Mar 15, 2026, Items: **Men\'s Leather Belt**\n*   **Order #14**: Status: Confirmed, Total: ₱749.00, Date: Mar 15, 2026, Items: **Men\'s Leather Belt**\n*   **Order #10**: Status: Completed, Total: ₱749.00, Date: Mar 08, 2026, Items: **Premium Leather Belt Black**\n*   **Order #9**: Status: Confirmed, Total: ₱749.00, Date: Mar 07, 2026, Items: **Premium Leather Belt Black**\n*   **Order #1**: Status: Pending, Total: ₱6,945.00, Date: Feb 28, 2026, Items: **Premium Leather Belt Black**, **Elegant Black Dress Supreme**, **Navy Midi Dress Classy**\n\nIs there a specific order you\'d like to inquire about?', '2026-03-16 08:32:46'),
(4, 2, 'Order Help', 'I\'m here to help, Raymond! To assist you best, could you please tell me which order you need help with, or what specific question you have about your orders?\n\nFor example, are you wondering about the status of a particular order, or do you need to make a change? Just let me know! 😊', '2026-03-16 08:33:50'),
(5, 2, 'payment', 'We offer two convenient payment methods at Thread and Press Hub: GCash (mobile) and Cash on Delivery (COD). ✨\n\nFor your past orders, I see that **Order #14**, **Order #10**, and **Order #9** were paid via GCash, while **Order #15** and **Order #1** are set for Cash on Delivery.\n\nDid you have a question about making a payment for a new order, or perhaps about a payment on one of your existing orders?', '2026-03-16 08:34:04'),
(6, 2, 'Style Advice', 'Hello there! I\'d be delighted to offer some style advice. ✨ A great starting point is focusing on versatile pieces that can be dressed up or down, ensuring a good fit, and investing in quality basics.\n\nCould you tell me a bit about what kind of style you\'re interested in, or perhaps an occasion you\'re shopping for? I can help you find some perfect pieces from Thread and Press Hub!', '2026-03-16 08:41:46'),
(7, 2, 'Order Help', 'Certainly, Raymond! I can help you with your orders.\n\nI see you have a few recent orders:\n*   **Order #15**: Placed on Mar 15, 2026, for a **Men\'s Leather Belt**, with a total of ₱749.00. Its status is Pending.\n*   **Order #14**: Also on Mar 15, 2026, for a **Men\'s Leather Belt**, total ₱749.00. This order is Confirmed.\n*   **Order #1**: Placed on Feb 28, 2026, for several items including an **Elegant Black Dress Supreme** and **Navy Midi Dress Classy**, totaling ₱6,945.00. Its status is also Pending.\n\nHow may I assist you with these orders today? 😊', '2026-03-16 08:41:53'),
(8, 2, 'Find Products', 'Wonderful! I can certainly help you find some amazing products from Thread and Press Hub. ✨\n\nTo help me narrow down the options, could you tell me what kind of item you\'re looking for? For example, are you interested in dresses, hoodies, accessories, or something specific for men, women, or kids?', '2026-03-16 08:42:08'),
(9, 2, 'hello', 'Hello Raymond! Welcome to Thread and Press Hub! 👋 I\'m your AI customer support assistant, and I\'m here to help you with any questions about our fashion items, your orders, or anything else you need.\n\nHow can I assist you today?', '2026-03-16 08:42:18'),
(10, 2, 'payment', 'We offer two convenient payment methods at Thread and Press Hub: GCash (for mobile payments) and Cash on Delivery (COD). You can select your preferred option during checkout! 💳', '2026-03-16 08:42:23'),
(11, 2, 'ai design', 'Hello! I\'m not quite sure what you mean by \"ai design.\" Could you please clarify or let me know if you have any questions about our products, your orders, or anything else related to Thread and Press Hub? I\'m here to help! 😊', '2026-03-19 06:53:45'),
(12, 2, 'AI Design', 'Thank you for repeating that. As the customer support assistant for Thread and Press Hub, I specialize in fashion and our apparel products. \"AI Design\" isn\'t something our store currently offers or is related to our clothing line.\n\nIs there anything I can assist you with regarding our stylish apparel, your recent orders, or general store information? I\'m happy to help with any fashion-related inquiries! ✨', '2026-03-19 06:56:02'),
(13, 2, 'Custom Design', 'While we don\'t offer custom design services at the moment, we have a wonderful selection of unique and stylish apparel that you might love! ✨ You can explore our current collections on our website\'s Shop page.', '2026-03-20 02:10:44'),
(14, 2, 'Design', 'Welcome to Thread and Press Hub! ✨ Are you curious about the design philosophy behind our apparel, or perhaps looking for items with a specific design style? Please tell me more about what you\'re interested in, and I\'d be happy to assist!', '2026-03-21 09:19:34'),
(15, 2, 'hi', 'Hello there! 👋 Welcome to Thread and Press Hub. How can I assist you with your fashion needs today?', '2026-03-22 13:56:30'),
(16, 2, 'hi', 'Hello there! 👋 Welcome to Thread and Press Hub. How can I assist you with your fashion needs or any questions about our products today?', '2026-03-26 07:23:01'),
(17, 2, 'Find Products', 'Certainly! We have a wide range of stylish apparel and accessories. You can browse our collections by category, such as **Dresses**, **Hoodies**, **Pants**, **T-Shirts**, and **Accessories**.\n\nIs there a specific type of item or category you\'re looking for today? You can also visit our Shop page on the website to see all our offerings! 🛍️', '2026-03-26 07:23:09'),
(18, 2, 'Order Help', 'Certainly, Raymond! I can help you with your orders.\n\nYou have a few recent orders with us. Could you please provide the order number you need assistance with? For example, your latest orders are #24, #23, and #21.\n\nOnce I have the order number, I can provide you with its status and details. 😊', '2026-03-26 07:23:21'),
(19, 2, 'AI Design', 'Hello there! 👋 While I\'m here to assist you with everything related to fashion and products from Thread and Press Hub, I\'m not equipped to discuss AI design.\n\nHow can I help you today with our apparel, your recent orders, or anything about our store? Feel free to ask about our **Elegant Black Dress Supreme** or our **Luxury Gray Zip Hoodie**! ✨', '2026-03-26 10:23:23'),
(20, 2, 'Find Products', 'Absolutely! You can browse our full collection of premium apparel and accessories on the Thread and Press Hub website\'s Shop page. 🛍️\n\nDo you have a specific item in mind, or are you looking for something in particular, like dresses, hoodies, or accessories? For example, we have beautiful pieces like the **Elegant Black Maxi Dress** and cozy items like the **Luxury Gray Zip Hoodie**! Let me know what you\'re interested in, and I can help you find it.', '2026-03-26 10:23:58'),
(21, 2, 'Order Help', 'I can certainly help you with your orders, Raymond! I see your recent orders, including #24 for a **Kids Cartoon Tee** and #23 for a **Men\'s Polo Shirt**.\n\nWhat specific assistance do you need today regarding your orders? Are you looking for a status update, details about a particular order, or something else? ✨', '2026-03-26 10:24:03'),
(22, 2, 'recommend', 'I\'d be delighted to recommend some of our fantastic products, Raymond! To give you the best suggestions, could you tell me what you\'re looking for? Are you interested in something for men, women, or kids? Or perhaps a specific category like dresses, hoodies, or accessories?\n\nIn the meantime, here are a few popular items:\n*   For a sophisticated look, consider the **Elegant Black Dress Supreme** at ₱2,299.00.\n*   If you\'re looking for comfort, our **Luxury Gray Zip Hoodie** is a great choice at ₱1,399.00.\n*   And for a versatile accessory, the **Premium Leather Belt Black** is ₱699.00.\n\nLet me know if any of these catch your eye, or if you have other preferences! ✨', '2026-03-26 10:24:19'),
(23, 2, 'Style Advice', 'Hello there! I\'d be delighted to offer some style advice. ✨ To help me best, could you tell me what kind of style you\'re looking for, or perhaps an occasion you\'re dressing for? I can help you find perfect pieces from our collection!', '2026-03-26 14:35:14'),
(24, 6, 'Find Products', 'Hello there! I\'d be happy to help you find some amazing products at Thread and Press Hub. ✨\n\nTo assist you best, could you tell me what kind of items you\'re looking for today? For example, are you interested in dresses, hoodies, or perhaps accessories? You can also browse our full collection on our website\'s Shop page!', '2026-04-06 16:21:12'),
(25, 6, 'Style Advice', 'I\'d love to help you with some style advice! Fashion is all about expressing yourself. 😊\n\nTo give you the best recommendations, could you tell me what kind of style advice you\'re looking for? For example, are you planning an outfit for a specific occasion, looking to style a particular item, or exploring a new aesthetic?', '2026-04-06 16:21:20'),
(26, 6, 'custom design', 'Hello there! 👋 We\'d love to help you bring your unique apparel designs to life.\n\nYou can submit your custom designs through our \'Custom Design\' page on the website. There, you can upload your artwork, choose the garment type (like a T-shirt or Hoodie), select colors, and add text or graphics. We even have AI-powered suggestions to spark your creativity! ✨\n\nAfter submission, your design goes through an admin review. Once approved, you\'ll receive a price quote, and production typically takes 5-7 business days after payment confirmation. You can track your custom order status on the \'My Custom Orders\' page.', '2026-04-06 16:32:30'),
(27, 6, 'faq', 'Hello there! 👋 Here are some of our Frequently Asked Questions:\n\n*   **How do I track my order?** Go to the \'My Orders\' page after logging in to see real-time status updates.\n*   **Can I cancel my order?** Orders can be cancelled if their status is still \'Pending\'. For orders already being processed, please contact support.\n*   **How do I apply my PWD/Senior discount?** Upload your valid ID during registration, and the 20% discount will apply automatically at checkout.\n*   **Do you ship nationwide?** Yes, we deliver across the Philippines! Metro Manila orders arrive in 1-2 business days, and Provincial orders in 2-3 business days.\n*   **What if my item doesn\'t fit?** You can exchange it within 30 days. The item must be unused with original tags attached.\n\nIf you have any other questions, feel free to ask!', '2026-04-06 16:35:06'),
(28, 6, 'What size should I get? I\'m 38 inches chest', 'Based on our size guide for T-Shirts and Hoodies, a chest measurement of 38 inches would best fit a **Medium (M)**. This size typically accommodates chest sizes between 38-40 inches.\n\nIf you prefer a more relaxed fit, you might consider sizing up, but M should be a great fit for you! 😊', '2026-04-06 16:36:29');

-- --------------------------------------------------------

--
-- Table structure for table `contact_categories`
--

CREATE TABLE `contact_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL COMMENT 'Category name',
  `description` text DEFAULT NULL COMMENT 'Category description',
  `active` tinyint(1) DEFAULT 1 COMMENT 'Is category active?'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_categories`
--

INSERT INTO `contact_categories` (`id`, `name`, `description`, `active`) VALUES
(1, 'General Inquiry', 'General questions about our services', 1),
(2, 'Product Support', 'Questions about specific products', 1),
(3, 'Order Issue', 'Problems with existing orders', 1),
(4, 'Payment Issue', 'Payment-related concerns', 1),
(5, 'Shipping Inquiry', 'Delivery and shipping questions', 1),
(6, 'Feedback', 'General feedback or suggestions', 1),
(7, 'Bug Report', 'Technical issues/bugs to report', 1),
(8, 'Business Partnership', 'Partnership and collaboration inquiries', 1),
(9, 'Return/Refund', 'Return and refund requests', 1),
(10, 'Other', 'Other inquiries', 1);

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL COMMENT 'Sender name',
  `email` varchar(150) NOT NULL COMMENT 'Sender email',
  `phone` varchar(20) DEFAULT NULL COMMENT 'Optional phone number',
  `subject` varchar(255) DEFAULT NULL COMMENT 'Message subject',
  `message` longtext NOT NULL COMMENT 'Contact message',
  `category` varchar(50) DEFAULT 'general' COMMENT 'Message category',
  `priority` varchar(20) DEFAULT 'normal' COMMENT 'Priority level (low, normal, high, urgent)',
  `status` varchar(50) DEFAULT 'new' COMMENT 'Status (new, read, responded, closed)',
  `ip_address` varchar(45) DEFAULT NULL COMMENT 'Sender IP address',
  `user_agent` text DEFAULT NULL COMMENT 'Browser info',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'When submitted',
  `responded_at` timestamp NULL DEFAULT NULL COMMENT 'When admin responded',
  `closed_at` timestamp NULL DEFAULT NULL COMMENT 'When ticket was closed',
  `admin_notes` longtext DEFAULT NULL COMMENT 'Internal admin notes',
  `assigned_to` int(11) DEFAULT NULL COMMENT 'Admin user ID assigned to handle this'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `phone`, `subject`, `message`, `category`, `priority`, `status`, `ip_address`, `user_agent`, `created_at`, `responded_at`, `closed_at`, `admin_notes`, `assigned_to`) VALUES
(1, 'Juan Dela Cruz', 'juan@example.com', '+63 912 345 6789', 'Interested in Custom Printing', 'I am interested in custom printing services for our company event. Can you provide more information about bulk orders and pricing?', 'Business Partnership', 'high', 'responded', '192.168.1.100', NULL, '2026-03-10 02:16:36', '2026-03-10 02:32:05', NULL, 'asqadddaSSD', 3),
(2, 'Maria Santos', 'maria@example.com', '+63 917 123 4567', 'Order Delivery Question', 'My order ID is 5. When will it be delivered? Have not received any updates yet.', 'Order Issue', 'normal', 'new', '192.168.1.101', NULL, '2026-03-10 02:16:36', NULL, NULL, NULL, NULL),
(3, 'Carlos Reyes', 'carlos@example.com', NULL, 'Product Quality Feedback', 'I received my order yesterday and I\'m very happy with the quality of the products! Great work!', 'Feedback', 'low', 'new', '192.168.1.102', NULL, '2026-03-10 02:16:36', NULL, NULL, NULL, NULL),
(4, 'Raymond Ligayaf', 'admin@apparelstudio.com', '09212856506', 'asad', 'ang lopit idols', 'Feedback', 'high', 'responded', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-10 02:18:22', '2026-03-10 02:31:24', NULL, 'asdasddasdasd', 3);

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages_responses`
--

CREATE TABLE `contact_messages_responses` (
  `id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL COMMENT 'Foreign key to contact_messages',
  `admin_id` int(11) NOT NULL COMMENT 'Admin user who responded',
  `response_message` longtext NOT NULL COMMENT 'Admin response',
  `attachments` varchar(500) DEFAULT NULL COMMENT 'File attachments (JSON)',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_messages_responses`
--

INSERT INTO `contact_messages_responses` (`id`, `contact_id`, `admin_id`, `response_message`, `attachments`, `created_at`) VALUES
(1, 4, 3, 'ganon ba salamat idols', NULL, '2026-03-10 02:31:24'),
(2, 1, 3, 'ok\r\n', NULL, '2026-03-10 02:32:05');

-- --------------------------------------------------------

--
-- Table structure for table `custom_designs`
--

CREATE TABLE `custom_designs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_type` varchar(50) NOT NULL DEFAULT 'tshirt',
  `design_image` longtext NOT NULL,
  `design_image_back` longtext DEFAULT NULL,
  `design_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`design_data`)),
  `notes` text DEFAULT NULL,
  `status` enum('pending','approved','revision','completed','cancelled') DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `custom_designs`
--

INSERT INTO `custom_designs` (`id`, `user_id`, `product_type`, `design_image`, `design_image_back`, `design_data`, `notes`, `status`, `admin_notes`, `order_id`, `created_at`, `updated_at`) VALUES
(1, 2, 'tshirt', 'uploads/designs/design_2_1773900691_cc9ccef4.png', NULL, '{\"apparelColor\":\"#FFFFFF\",\"apparelType\":\"tshirt\",\"elementsCount\":2}', '', 'approved', 'sadddd', NULL, '2026-03-19 06:11:31', '2026-03-19 06:14:44'),
(2, 2, 'tshirt', 'uploads/designs/design_2_1773969901_4ba6ce6d.png', NULL, '{\"apparelColor\":\"#FFFFFF\",\"apparelType\":\"tshirt\",\"elementsCount\":1,\"printSize\":\"medium\",\"colorsUsed\":2,\"size\":\"M\",\"quantity\":1,\"discountType\":\"senior\",\"baseCost\":350,\"printSizeCost\":100,\"colorCost\":25}', '', 'approved', '', NULL, '2026-03-20 01:25:01', '2026-03-20 01:26:33'),
(3, 2, 'tshirt', 'uploads/designs/design_2_1773974123_577a3e02.png', NULL, '{\"apparelColor\":\"#FFFFFF\",\"apparelType\":\"tshirt\",\"elementsCount\":2,\"printSize\":\"medium\",\"colorsUsed\":2,\"size\":\"M\",\"quantity\":1,\"discountType\":\"regular\",\"baseCost\":350,\"printSizeCost\":100,\"colorCost\":25}', '', 'approved', NULL, NULL, '2026-03-20 02:35:23', '2026-03-20 02:35:33'),
(4, 5, 'tshirt', 'uploads/designs/design_5_front_1774099971_b1d6f22e.png', 'uploads/designs/design_5_back_1774099971_66820c8d.png', '{\"apparelColor\":\"#000000\",\"apparelType\":\"tshirt\",\"elementsCount\":0,\"printSize\":\"medium\",\"colorsUsed\":2,\"size\":\"M\",\"quantity\":1,\"discountType\":\"regular\",\"baseCost\":350,\"printSizeCost\":100,\"colorCost\":25}', '', 'approved', NULL, NULL, '2026-03-21 13:32:51', '2026-03-21 13:33:15'),
(5, 5, 'tshirt', 'uploads/designs/design_5_front_1774767729_73887a19.png', 'uploads/designs/design_5_back_1774767729_de4faf44.png', '{\"apparelColor\":\"#000000\",\"apparelType\":\"tshirt\",\"elementsCount\":0,\"printSize\":\"medium\",\"colorsUsed\":4,\"size\":\"M\",\"quantity\":1,\"discountType\":\"pwd\",\"baseCost\":350,\"printSizeCost\":100,\"colorCost\":75}', '', 'approved', NULL, NULL, '2026-03-29 07:02:09', '2026-03-29 07:02:35'),
(6, 1, 'hoodie', 'uploads/designs/design_1_front_1774844223_65e9aadb.png', 'uploads/designs/design_1_back_1774844223_0ecc6253.png', '{\"apparelColor\":\"#000000\",\"apparelType\":\"hoodie\",\"elementsCount\":1,\"printSize\":\"large\",\"colorsUsed\":2,\"size\":\"XL\",\"quantity\":1,\"discountType\":\"regular\",\"baseCost\":650,\"printSizeCost\":180,\"colorCost\":25}', '', 'approved', NULL, NULL, '2026-03-30 04:17:03', '2026-03-30 04:17:16'),
(7, 6, 'tshirt', 'uploads/designs/design_6_front_1775558334_96413295.png', 'uploads/designs/design_6_back_1775558334_33ce15b8.png', '{\"apparelColor\":\"#000000\",\"apparelType\":\"tshirt\",\"elementsCount\":1,\"printSize\":\"medium\",\"colorsUsed\":1,\"size\":\"M\",\"quantity\":1,\"discountType\":\"regular\",\"baseCost\":350,\"printSizeCost\":100,\"colorCost\":0}', '', 'approved', NULL, NULL, '2026-04-07 10:38:54', '2026-04-07 10:39:00');

-- --------------------------------------------------------

--
-- Table structure for table `custom_orders`
--

CREATE TABLE `custom_orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `design_id` int(11) NOT NULL,
  `design_image` varchar(500) NOT NULL,
  `product_type` varchar(50) NOT NULL DEFAULT 'tshirt',
  `apparel_color` varchar(20) DEFAULT '#FFFFFF',
  `size` varchar(10) NOT NULL DEFAULT 'M',
  `quantity` int(11) NOT NULL DEFAULT 1,
  `base_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `print_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `color_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_type` varchar(20) DEFAULT 'regular',
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `total_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending_payment','payment_uploaded','payment_verified','processing','printing','ready_pickup','delivered','cancelled') DEFAULT 'pending_payment',
  `notes` text DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `custom_orders`
--

INSERT INTO `custom_orders` (`id`, `user_id`, `design_id`, `design_image`, `product_type`, `apparel_color`, `size`, `quantity`, `base_price`, `print_cost`, `color_cost`, `subtotal`, `discount_type`, `discount_amount`, `total_price`, `status`, `notes`, `admin_notes`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 'uploads/designs/design_2_1773969901_4ba6ce6d.png', 'tshirt', '#FFFFFF', 'M', 1, 350.00, 100.00, 0.00, 450.00, 'regular', 0.00, 450.00, 'payment_verified', '', NULL, '2026-03-20 01:33:11', '2026-03-26 07:49:04'),
(2, 2, 2, 'uploads/designs/design_2_1773969901_4ba6ce6d.png', 'tshirt', '#FFFFFF', 'M', 1, 350.00, 100.00, 25.00, 475.00, 'regular', 0.00, 475.00, 'payment_verified', '', '', '2026-03-20 01:35:25', '2026-03-26 07:48:50'),
(3, 2, 3, 'uploads/designs/design_2_1773974123_577a3e02.png', 'tshirt', '#FFFFFF', 'M', 1, 350.00, 100.00, 25.00, 475.00, 'regular', 0.00, 475.00, 'payment_verified', '', NULL, '2026-03-20 02:35:33', '2026-03-26 07:48:47'),
(4, 5, 4, 'uploads/designs/design_5_front_1774099971_b1d6f22e.png', 'tshirt', '#000000', 'M', 1, 350.00, 100.00, 25.00, 475.00, 'regular', 0.00, 475.00, 'payment_verified', '', NULL, '2026-03-21 13:33:15', '2026-03-26 07:48:36'),
(5, 5, 5, 'uploads/designs/design_5_front_1774767729_73887a19.png', 'tshirt', '#000000', 'M', 1, 350.00, 100.00, 75.00, 525.00, 'pwd', 105.00, 420.00, 'delivered', '', '', '2026-03-29 07:02:35', '2026-03-29 07:03:32'),
(6, 1, 6, 'uploads/designs/design_1_front_1774844223_65e9aadb.png', 'hoodie', '#000000', 'XL', 1, 650.00, 180.00, 25.00, 855.00, 'regular', 0.00, 855.00, 'delivered', '', '', '2026-03-30 04:17:16', '2026-03-30 04:43:32'),
(7, 6, 7, 'uploads/designs/design_6_front_1775558334_96413295.png', 'tshirt', '#000000', 'M', 1, 350.00, 100.00, 0.00, 450.00, 'regular', 0.00, 450.00, 'payment_uploaded', '', NULL, '2026-04-07 10:39:00', '2026-04-07 10:39:07');

-- --------------------------------------------------------

--
-- Table structure for table `custom_order_payments`
--

CREATE TABLE `custom_order_payments` (
  `id` int(11) NOT NULL,
  `custom_order_id` int(11) NOT NULL,
  `payment_method` enum('gcash','maya','cod') NOT NULL,
  `payment_proof` varchar(500) DEFAULT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('pending','verified','rejected') DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `custom_order_payments`
--

INSERT INTO `custom_order_payments` (`id`, `custom_order_id`, `payment_method`, `payment_proof`, `reference_number`, `amount`, `payment_status`, `admin_notes`, `created_at`, `updated_at`) VALUES
(1, 2, 'maya', 'uploads/payments/payment_2_1773970559_a265307b.jpg', '12345566778', 475.00, 'verified', NULL, '2026-03-20 01:35:59', '2026-03-26 07:48:50'),
(2, 1, 'cod', NULL, NULL, 450.00, 'verified', NULL, '2026-03-20 01:38:49', '2026-03-26 07:49:04'),
(3, 3, 'cod', NULL, NULL, 475.00, 'verified', NULL, '2026-03-20 02:35:35', '2026-03-26 07:48:47'),
(4, 4, 'cod', NULL, NULL, 475.00, 'verified', NULL, '2026-03-21 13:33:19', '2026-03-26 07:48:36'),
(5, 5, 'cod', NULL, NULL, 420.00, 'pending', NULL, '2026-03-29 07:02:52', '2026-03-29 07:02:52'),
(6, 6, 'cod', NULL, NULL, 855.00, 'pending', NULL, '2026-03-30 04:17:28', '2026-03-30 04:17:28'),
(7, 7, 'cod', NULL, NULL, 450.00, 'pending', NULL, '2026-04-07 10:39:07', '2026-04-07 10:39:07');

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
(4, 13, '12345566778', 749.00, 'pending', '2026-03-10 01:37:49', '2026-03-10 01:37:49'),
(5, 14, '12345566778', 749.00, 'pending', '2026-03-15 12:01:22', '2026-03-15 12:01:22'),
(6, 18, '1212345432', 1039.20, 'pending', '2026-03-21 09:27:56', '2026-03-21 09:27:56'),
(7, 23, '1212345432', 749.00, 'pending', '2026-03-22 07:08:54', '2026-03-22 07:08:54'),
(8, 25, '1212345432', 749.00, 'pending', '2026-03-26 10:35:48', '2026-03-26 10:35:48'),
(9, 34, '1212345432', 549.00, 'pending', '2026-04-15 21:01:25', '2026-04-15 21:01:25');

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `attempted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `payment_method` enum('gcash','cod','maya') NOT NULL,
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
(13, 3, 699.00, 0.00, 'regular', 50.00, 749.00, 'gcash', '12345566778', 'sad', 'asd', 'confirmed', '2026-03-10 01:37:41', '2026-03-10 01:37:49'),
(14, 2, 699.00, 0.00, 'regular', 50.00, 749.00, 'gcash', '12345566778', '123', '123', 'confirmed', '2026-03-15 12:01:18', '2026-03-15 12:01:22'),
(15, 2, 699.00, 0.00, 'regular', 50.00, 749.00, 'cod', NULL, 'sad', 'asd', 'completed', '2026-03-15 12:08:37', '2026-03-16 08:50:41'),
(16, 2, 599.00, 71.88, 'pwd', 50.00, 577.12, 'cod', NULL, '11122asdjbvdhgvgvh', 'adddvab', 'pending', '2026-03-16 08:56:44', '2026-03-16 08:56:44'),
(17, 2, 699.00, 0.00, 'regular', 50.00, 749.00, 'cod', NULL, '4378 axis st san francisco, brgy san juan, cainta rizal, rizal, 1900', 'none', 'pending', '2026-03-20 02:15:11', '2026-03-20 02:15:11'),
(18, 2, 1299.00, 259.80, 'pwd', 0.00, 1039.20, 'gcash', '1212345432', 'Store Pickup', '', 'confirmed', '2026-03-21 09:27:46', '2026-03-21 09:27:56'),
(19, 2, 699.00, 0.00, 'regular', 50.00, 749.00, 'cod', NULL, '4378 axis st san francisco, brgy san juan, cainta rizal, rizal, 1900', '', 'pending', '2026-03-21 11:59:29', '2026-03-21 11:59:29'),
(20, 2, 1999.00, 0.00, 'regular', 50.00, 2049.00, 'cod', NULL, '4378 axis st san francisco, brgy san juan, cainta rizal, rizal, 1900', '', 'pending', '2026-03-21 12:11:53', '2026-03-21 12:11:53'),
(21, 2, 1999.00, 0.00, 'regular', 50.00, 2049.00, 'cod', NULL, '4378 axis st san francisco, brgy san juan, cainta rizal, rizal, 1900', '', 'pending', '2026-03-21 12:12:44', '2026-03-21 12:12:44'),
(22, 5, 699.00, 139.80, 'pwd', 50.00, 609.20, 'cod', NULL, '4378 Axis Street, san juan, Cainta, Rizal, 1900', '', 'pending', '2026-03-22 04:14:31', '2026-03-22 04:14:31'),
(23, 2, 699.00, 0.00, 'regular', 50.00, 749.00, 'gcash', '1212345432', '4378 axis st san francisco, brgy san juan, cainta rizal, rizal, 1900', '', 'confirmed', '2026-03-22 07:08:47', '2026-03-22 07:08:54'),
(24, 2, 299.00, 0.00, 'regular', 50.00, 349.00, 'cod', NULL, '4378 axis st san francisco, brgy san juan, cainta rizal, rizal, 1900', '', 'pending', '2026-03-22 07:09:34', '2026-03-22 07:09:34'),
(25, 2, 699.00, 0.00, 'regular', 50.00, 749.00, 'maya', '1212345432', '4378 axis st san francisco, brgy san juan, cainta rizal, rizal, 1900', '', 'confirmed', '2026-03-26 10:35:44', '2026-03-26 10:35:48'),
(26, 5, 399.00, 79.80, 'pwd', 50.00, 369.20, 'cod', NULL, '4378 Axis Street, san juan, Cainta, Rizal, 1900', '', 'pending', '2026-03-29 07:00:20', '2026-03-29 07:00:20'),
(27, 5, 399.00, 79.80, 'pwd', 50.00, 369.20, 'cod', NULL, '4378 Axis Street, san juan, Cainta, Rizal, 1900', '', 'pending', '2026-03-29 07:00:22', '2026-03-29 07:00:22'),
(28, 6, 1899.00, 0.00, 'regular', 50.00, 1949.00, 'cod', NULL, '4378 Axis Street, san juan, Cainta, Rizal, 1900', '', 'pending', '2026-04-01 02:58:37', '2026-04-01 02:58:37'),
(29, 6, 299.00, 0.00, 'regular', 50.00, 349.00, 'cod', NULL, '4378 Axis Street, san juan, Cainta, Rizal, 1900', '', 'pending', '2026-04-01 08:31:53', '2026-04-01 08:31:53'),
(30, 6, 299.00, 0.00, 'regular', 50.00, 349.00, 'cod', NULL, '4378 Axis Street, san juan, Cainta, Rizal, 1900', '', 'pending', '2026-04-06 10:26:20', '2026-04-06 10:26:20'),
(31, 6, 299.00, 0.00, 'regular', 50.00, 349.00, 'cod', NULL, '4378 Axis Street, san juan, Cainta, Rizal, 1900', '', 'pending', '2026-04-06 10:33:53', '2026-04-06 10:33:53'),
(32, 7, 2298.00, 0.00, 'regular', 50.00, 2348.00, 'cod', NULL, '123, Sto. Domingo, Cainta, Rizal, 1900', '', 'pending', '2026-04-07 07:10:09', '2026-04-07 07:10:09'),
(33, 6, 499.00, 0.00, 'regular', 50.00, 549.00, 'gcash', NULL, '4378 Axis Street, san juan, Cainta, Rizal, 1900', '', 'pending', '2026-04-15 21:01:07', '2026-04-15 21:01:07'),
(34, 6, 499.00, 0.00, 'regular', 50.00, 549.00, 'gcash', '1212345432', '4378 Axis Street, san juan, Cainta, Rizal, 1900', '', 'confirmed', '2026-04-15 21:01:12', '2026-04-15 21:01:25');

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
  `size` varchar(10) DEFAULT NULL,
  `custom_design_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `unit_price`, `subtotal`, `color`, `size`, `custom_design_id`) VALUES
(1, 1, 15, 3, 699.00, 2097.00, NULL, NULL, NULL),
(2, 1, 12, 1, 2299.00, 2299.00, NULL, NULL, NULL),
(3, 1, 14, 1, 2499.00, 2499.00, NULL, NULL, NULL),
(4, 7, 12, 1, 2299.00, 2299.00, 'White', 'S', NULL),
(5, 7, 15, 1, 699.00, 699.00, 'White', 'S', NULL),
(6, 8, 34, 1, 899.00, 899.00, 'Black', 'M', NULL),
(7, 9, 15, 1, 699.00, 699.00, 'Black', 'XS', NULL),
(8, 10, 15, 1, 699.00, 699.00, 'Navy', 'M', NULL),
(9, 11, 15, 1, 699.00, 699.00, 'Navy', 'M', NULL),
(10, 12, 11, 1, 1349.00, 1349.00, 'White', 'S', NULL),
(11, 13, 15, 1, 699.00, 699.00, 'White', 'XS', NULL),
(12, 14, 25, 1, 699.00, 699.00, 'Black', 'L', NULL),
(13, 15, 25, 1, 699.00, 699.00, 'Black', 'L', NULL),
(14, 16, 32, 1, 599.00, 599.00, 'Red', 'One Size', NULL),
(15, 17, 25, 1, 699.00, 699.00, 'Brown', 'XL', NULL),
(16, 18, 23, 1, 1299.00, 1299.00, 'Black', 'L', NULL),
(17, 19, 25, 1, 699.00, 699.00, 'Black', 'L', NULL),
(18, 20, 22, 1, 1999.00, 1999.00, 'Blue', 'L', NULL),
(19, 21, 22, 1, 1999.00, 1999.00, 'Blue', 'M', NULL),
(20, 22, 15, 1, 699.00, 699.00, 'Black', 'S', NULL),
(21, 23, 21, 1, 699.00, 699.00, 'Navy', 'M', NULL),
(22, 24, 26, 1, 299.00, 299.00, 'Yellow', 'S', NULL),
(23, 25, 25, 1, 699.00, 699.00, 'Black', 'M', NULL),
(24, 26, 30, 1, 399.00, 399.00, 'Blue', 'XS', NULL),
(25, 27, 30, 1, 399.00, 399.00, 'Blue', 'XS', NULL),
(26, 28, 16, 1, 1899.00, 1899.00, 'White', 'S', NULL),
(27, 29, 36, 1, 299.00, 299.00, 'Navy', 'S', NULL),
(28, 30, 36, 1, 299.00, 299.00, 'White', 'S', NULL),
(29, 31, 36, 1, 299.00, 299.00, 'Navy', 'S', NULL),
(30, 32, 36, 1, 299.00, 299.00, 'Navy', 'N/A', NULL),
(31, 32, 22, 1, 1999.00, 1999.00, 'Black', 'M', NULL),
(32, 33, 18, 1, 499.00, 499.00, 'Black', 'S', NULL),
(33, 34, 18, 1, 499.00, 499.00, 'Black', 'S', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(13, 'Summer Floral Garden Dress', 'Beautiful floral print dress ideal for warm weather and outdoor events.', 1999.00, 'dresses', 'womens', 'Black,White,Navy', 'XS,S,M,L,XL,XXL', 'floral_dress1.jpg', 'active', '2026-02-28 04:51:02', '2026-03-20 13:00:46'),
(14, 'Navy Midi Dress Classy', 'Premium navy midi dress for a sophisticated and elegant look.', 2499.00, 'dresses', 'womens', 'Black,White,Navy', 'XS,S,M,L,XL,XXL', 'navy_dress.jpg', 'active', '2026-02-28 04:51:02', '2026-02-28 05:53:56'),
(15, 'Premium Leather Belt Black', 'Genuine leather belt with classic buckle. Essential accessory.', 699.00, 'accessories', 'mens', 'Black,White,Navy', '', 'belt_black.jpg', 'active', '2026-02-28 04:51:02', '2026-04-06 11:22:32'),
(16, 'Floral Summer Dress', 'Light and breezy floral dress perfect for summer outings.', 1899.00, 'dresses', 'womens', 'Pink,White,Yellow', 'S,M,L', 'floral_dress.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(17, 'Elegant Black Maxi Dress', 'Elegant black maxi dress suitable for evening events.', 2499.00, 'dresses', 'womens', 'Black,Maroon', 'M,L,XL', 'black_dress.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(18, 'Women\'s Graphic Tee', 'Trendy graphic tee made from soft cotton.', 499.00, 't-shirts', 'womens', 'White,Black,Blue', 'XS,S,M,L', 'womens_graphic_tshirt.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(19, 'Women\'s Cotton Hoodie', 'Comfortable cotton hoodie for all-day wear.', 1299.00, 'hoodies', 'womens', 'Gray,Pink', 'S,M,L,XL', 'women_hoodie.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(20, 'Women\'s Slim Fit Jeans', 'Stylish slim fit jeans designed for a flattering look.', 1599.00, 'pants', 'womens', 'Blue,Black', 'S,M,L,XL', 'women_jeans.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(21, 'Men\'s Polo Shirt', 'Classic polo shirt perfect for casual and semi-formal occasions.', 699.00, 't-shirts', 'mens', 'Navy,Black,White', 'S,M,L,XL,XXL', 'mens_polo.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(22, 'Men\'s Denim Jacket', 'Durable denim jacket with a modern cut.', 1999.00, 'hoodies', 'mens', 'Blue,Black', 'M,L,XL,XXL', 'mens_denim_jacket.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(23, 'Men\'s Jogger Pants', 'Comfortable jogger pants with elastic waistband.', 1299.00, 'pants', 'mens', 'Gray,Black', 'M,L,XL,XXL', 'mens_joggers.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(24, 'Men\'s Graphic Tee', 'Stylish graphic tee made from premium fabric.', 499.00, 't-shirts', 'mens', 'White,Black,Red', 'S,M,L,XL', 'mens_graphic_tshirt.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(25, 'Men\'s Leather Belt', 'Premium leather belt with classic buckle.', 699.00, 'accessories', 'mens', 'Black,Brown', '', 'mens_belt.jpg', 'active', '2026-02-28 05:58:49', '2026-04-06 11:22:32'),
(26, 'Kids Cartoon Tee', 'Fun cartoon print tee for kids.', 299.00, 't-shirts', 'kids', 'Yellow,Blue,Green', 'XS,S,M', 'kids_cartoon_tshirt.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(27, 'Kids Hoodie', 'Cozy hoodie little ones will love to wear.', 899.00, 'hoodies', 'kids', 'Blue,Pink', 'XS,S,M,L', 'kids_hoodie.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(28, 'Kids Denim Jeans', 'Durable denim jeans perfect for playtime.', 799.00, 'pants', 'kids', 'Blue', 'XS,S,M,L', 'kids_jeans.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(29, 'Kids Floral Dress', 'Cute floral dress designed for kids.', 599.00, 'dresses', 'kids', 'Pink,White', 'XS,S,M', 'kids_floral_dress.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(30, 'Kids Sports Shorts', 'Lightweight shorts for active children.', 399.00, 'pants', 'kids', 'Black,Blue', 'XS,S,M,L', 'kids_sports_shorts.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(32, 'Kids Backpack', 'Colorful backpack suitable for school.', 599.00, 'accessories', 'kids', 'Red,Blue,Green', '', 'kids_backpack.jpg', 'inactive', '2026-02-28 05:58:49', '2026-04-06 11:26:35'),
(33, 'Kids Winter Coat', 'Warm coat to keep kids cozy during cold seasons.', 1299.00, 'hoodies', 'kids', 'Red,Blue', 'S,M,L', 'kids_coat.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(34, 'Kids Athletic Shoes', 'Durable shoes for running and play.', 899.00, 'accessories', 'kids', 'Black,White', '', 'kids_shoes.jpg', 'inactive', '2026-02-28 05:58:49', '2026-04-06 11:26:35'),
(35, 'Kids Swim Trunks', 'Bright swim trunks perfect for pool days.', 499.00, 'pants', 'kids', 'Blue,Green', 'XS,S,M,L', 'kids_swim_trunks.jpg', 'active', '2026-02-28 05:58:49', '2026-02-28 05:58:49'),
(36, 'Sunglass', 'none', 299.00, 'accessories', 'mens', 'Black,White,Navy', '', 'apparel_1774592668_unnisex_sunglass.jpg', 'active', '2026-03-27 06:24:28', '2026-04-06 11:22:32');

-- --------------------------------------------------------

--
-- Table structure for table `support_conversations`
--

CREATE TABLE `support_conversations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `support_conversations`
--

INSERT INTO `support_conversations` (`id`, `user_id`, `subject`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Apparel', 'open', '2026-03-15 09:35:43', '2026-03-15 09:35:43'),
(2, 2, 'Apparel', 'open', '2026-03-15 09:36:40', '2026-03-29 07:07:39');

-- --------------------------------------------------------

--
-- Table structure for table `support_messages`
--

CREATE TABLE `support_messages` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `sender_type` enum('user','admin') NOT NULL,
  `message` text DEFAULT NULL,
  `image_path` varchar(500) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `support_messages`
--

INSERT INTO `support_messages` (`id`, `conversation_id`, `sender_id`, `sender_type`, `message`, `image_path`, `is_read`, `created_at`) VALUES
(1, 1, 1, 'user', 'iuadjhdszbhADVBNanadssabnbw', 'uploads/support/support_1773567343_111fc6ed3a5089e3.png', 1, '2026-03-15 09:35:43'),
(2, 2, 2, 'user', 'Aaassasasdd', 'uploads/support/support_1773567400_cbfcb1f05b11b664.jpg', 1, '2026-03-15 09:36:40'),
(3, 2, 3, 'admin', 'hi', 'uploads/support/support_1773567455_ee25379134239487.png', 1, '2026-03-15 09:37:35'),
(4, 2, 2, 'user', NULL, 'uploads/support/support_1774522089_f6bea03ff36cc5da.jpg', 1, '2026-03-26 10:48:09'),
(5, 2, 3, 'admin', 'hi', NULL, 1, '2026-03-26 10:51:31'),
(6, 2, 2, 'user', 'pede mag hello', NULL, 1, '2026-03-26 14:35:58'),
(7, 2, 2, 'user', 'hi', NULL, 1, '2026-03-29 07:07:39');

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
  `street_address` varchar(255) DEFAULT NULL,
  `barangay` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `zipcode` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `phone`, `password`, `user_type`, `pwd_id`, `senior_id`, `street_address`, `barangay`, `city`, `province`, `zipcode`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@thradpresshub', NULL, '$2y$10$ScnDDUtUQi1TqJpD8Pg7GO1iWeNhocrhET/9QUgvsuvnkbO6oS6ry', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-28 04:51:02', '2026-02-28 05:36:51'),
(2, 'raymond', 'rayligaya@gmail.com', '09947214569', '$2y$10$odRPvewUnqANlrSQiOXAqOwlWv.ZgAdYhM3N8MPGp6JBrZQ8qX5y2', 'regular', '', '', '4378 axis st san francisco', 'brgy san juan', 'cainta rizal', 'rizal', '1900', '2026-02-28 04:55:18', '2026-03-20 02:08:17'),
(3, 'Admin', 'myadmin@threadpresshub.com', NULL, '$2y$10$2BnxOSPEW.NR9gw2OvMZtOZW6gEyJZfUhMJ3CJ40vOCc/1ZxcNV9y', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-02 04:58:03', '2026-03-02 04:58:03'),
(4, 'renzo', 'kurozakishizume@gmail.com', '09212856506', '$2y$10$MeWcbG6OD71jtKxdB53V4OFpzhqOEzhYVUby50HyItfN7.fsmbuqa', 'senior', '12313456111', '54631254456', NULL, NULL, NULL, NULL, NULL, '2026-03-09 09:09:53', '2026-03-09 09:09:53'),
(5, 'Raymond Ligaya', 'shiraishikottoo13@gmail.com', '09212856506', '$2y$10$WXsBp4w6HepcXqj9ArhaW.2B/mT4w.g3ETq1AJo162crmF.Lr57vy', 'pwd', '12313456111', '', '4378 Axis Street', 'san juan', 'Cainta', 'Rizal', '1900', '2026-03-21 12:14:27', '2026-03-21 12:14:27'),
(6, 'Renzo Ligaya', 'renzoligaya@gmail.com', '09640996694', '$2y$10$y2cw0ue8QSMj70Py2gGYlO8Tpl1DQrY9hcEDwJtvao0E5mmzx4B3m', 'regular', '', '', '4378 Axis Street', 'san juan', 'Cainta', 'Rizal', '1900', '2026-04-01 02:56:41', '2026-04-01 02:56:41'),
(7, 'Mc. Gregor V. Lemon', 'reo@gmail.com', '09058669502', '$2y$10$rIJEs6kpyPBM/xauZdM8DeAi.NGpQwQwvL8WsT./j3fyVNoiIq9Za', 'regular', '', '', '123', 'Sto. Domingo', 'Cainta', 'Rizal', '1900', '2026-04-07 07:06:55', '2026-04-07 07:06:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_entity` (`entity_type`,`entity_id`),
  ADD KEY `idx_created_at` (`created_at`);

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
-- Indexes for table `contact_categories`
--
ALTER TABLE `contact_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_priority` (`priority`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_assigned_to` (`assigned_to`);

--
-- Indexes for table `contact_messages_responses`
--
ALTER TABLE `contact_messages_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_contact_id` (`contact_id`),
  ADD KEY `idx_admin_id` (`admin_id`);

--
-- Indexes for table `custom_designs`
--
ALTER TABLE `custom_designs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `custom_orders`
--
ALTER TABLE `custom_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `design_id` (`design_id`);

--
-- Indexes for table `custom_order_payments`
--
ALTER TABLE `custom_order_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `custom_order_id` (`custom_order_id`);

--
-- Indexes for table `gcash_transactions`
--
ALTER TABLE `gcash_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email_ip` (`email`,`ip_address`),
  ADD KEY `idx_attempted_at` (`attempted_at`);

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
  ADD KEY `product_id` (`product_id`),
  ADD KEY `custom_design_id` (`custom_design_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_token` (`token`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_expires` (`expires_at`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product_id` (`category`);

--
-- Indexes for table `support_conversations`
--
ALTER TABLE `support_conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `support_messages`
--
ALTER TABLE `support_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_conversation_id` (`conversation_id`),
  ADD KEY `idx_sender` (`sender_id`,`sender_type`);

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
-- AUTO_INCREMENT for table `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `chatbot_faq`
--
ALTER TABLE `chatbot_faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `chat_history`
--
ALTER TABLE `chat_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `contact_categories`
--
ALTER TABLE `contact_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contact_messages_responses`
--
ALTER TABLE `contact_messages_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `custom_designs`
--
ALTER TABLE `custom_designs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `custom_orders`
--
ALTER TABLE `custom_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `custom_order_payments`
--
ALTER TABLE `custom_order_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `gcash_transactions`
--
ALTER TABLE `gcash_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `support_conversations`
--
ALTER TABLE `support_conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `support_messages`
--
ALTER TABLE `support_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat_history`
--
ALTER TABLE `chat_history`
  ADD CONSTRAINT `chat_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contact_messages_responses`
--
ALTER TABLE `contact_messages_responses`
  ADD CONSTRAINT `fk_contact_message` FOREIGN KEY (`contact_id`) REFERENCES `contact_messages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `custom_designs`
--
ALTER TABLE `custom_designs`
  ADD CONSTRAINT `custom_designs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `custom_designs_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `custom_orders`
--
ALTER TABLE `custom_orders`
  ADD CONSTRAINT `custom_orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `custom_orders_ibfk_2` FOREIGN KEY (`design_id`) REFERENCES `custom_designs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `custom_order_payments`
--
ALTER TABLE `custom_order_payments`
  ADD CONSTRAINT `custom_order_payments_ibfk_1` FOREIGN KEY (`custom_order_id`) REFERENCES `custom_orders` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`custom_design_id`) REFERENCES `custom_designs` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `support_conversations`
--
ALTER TABLE `support_conversations`
  ADD CONSTRAINT `fk_support_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `support_messages`
--
ALTER TABLE `support_messages`
  ADD CONSTRAINT `fk_support_conversation` FOREIGN KEY (`conversation_id`) REFERENCES `support_conversations` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
