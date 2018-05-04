-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2018 at 09:56 AM
-- Server version: 5.7.14
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mywebsite`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `article_id` int(11) NOT NULL,
  `article_title` text NOT NULL,
  `article_content` text NOT NULL,
  `article_image` varchar(255) NOT NULL,
  `create_time` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `featured` tinyint(4) NOT NULL DEFAULT '0',
  `allow_comments` tinyint(1) NOT NULL DEFAULT '1',
  `category_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`article_id`, `article_title`, `article_content`, `article_image`, `create_time`, `created_by`, `published`, `featured`, `allow_comments`, `category_id`, `product_id`) VALUES
(1, 'why you must take a lot of forever living products', '<p>Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using Content here, content here, making it look like readable English. Many desktop publishing packages and web page editorsLorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors</p>', '1.jpg', '2017-02-27 10:41:25', 1, 1, 1, 1, 5, 1),
(2, 'why you must use aloe vera all day everday', '<p>gddg djgdgd gdkgd</p>\r\n<p>dgdkgdjkdmn</p>', '2.jpg', '2017-03-08 08:23:36', 1, 1, 1, 1, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `banner_id` int(11) NOT NULL,
  `banner_description` varchar(255) DEFAULT NULL,
  `published` int(11) NOT NULL DEFAULT '1',
  `banner_image` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`banner_id`, `banner_description`, `published`, `banner_image`) VALUES
(1, 'just testing out', 1, '1.jpe'),
(3, 'this is just a test', 1, '3.jpg'),
(9, '', 1, '9.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `category_description` text,
  `create_time` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `category_description`, `create_time`, `created_by`, `published`) VALUES
(5, 'aloe vera gel', 'nice to use', '2017-02-12 02:30:20', 1, 1),
(6, 'weight management', 'just trying out', '2017-01-29 00:00:00', 1, 1),
(7, 'Glucotin', 'None for now', '2017-03-14 08:50:27', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `comment` text NOT NULL,
  `article_id` int(11) NOT NULL,
  `create_time` datetime NOT NULL,
  `block` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `name`, `email`, `comment`, `article_id`, `create_time`, `block`) VALUES
(1, 'charles', 'charlesudoh1@gmail.com', 'bla bla blaaaaaaa', 1, '2017-03-19 12:07:18', 0),
(3, 'Nsikak', 'charlieudoh@yahoo.com', 'This is just a test comment. Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editorsLorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors', 1, '2017-03-19 01:19:59', 0),
(4, 'Charlie', 'Charlieboy@gmail.com', 'This is just to test the comment functionality', 1, '2017-08-19 07:43:47', 0);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `create_time` datetime NOT NULL DEFAULT '2017-01-01 12:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `sender`, `email`, `message`, `create_time`) VALUES
(1, 'Charles Udoh', 'charlesudoh1@gmail.com', 'dgfdg djgd', '2017-01-01 12:00:00'),
(2, 'Charles Udoh', 'charlesudoh1@gmail.com', 'sfdggm bdjbd jueb ', '2017-01-01 12:00:00'),
(3, 'Charles Udoh', 'charlesudoh1@gmail.com', '<div>\r\n							<h4>Sent at @ 21/03/2017 08:31 pm</h4>\r\n							<h6><b>Name: </b> Charles Udoh</span><br>\r\n							<h6><b>Email: </b>charlesudoh1@gmail.com</span><br>\r\n							<div><b>Message: </b>dg dgjdkg dkgd kdg dkg dklgbb sfdggm bdjbd jueb  </span><br>\r\n							</div>', '2017-01-01 12:00:00'),
(4, 'Charles Udoh', 'charlesudoh1@gmail.com', 'hvfe hev ejuev jev jwv j swv', '2017-01-01 12:00:00'),
(5, 'Charles Udoh', 'charlieudoh@yahoo.com', 'Hello there. Just testing', '2017-01-01 12:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text,
  `quantity` int(11) NOT NULL DEFAULT '1',
  `product_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'PENDING',
  `create_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `phone`, `email`, `address`, `quantity`, `product_id`, `status`, `create_time`) VALUES
(1, '+2348133242320', 'charlesudoh1@gmail.com', '2 abc street', 1, 1, 'SUPPLIED', '2017-03-10 11:38:39'),
(2, '+2348133242320', 'charlesudoh1@gmail.com', '2 abc street', 1, 1, 'PENDING', '2017-03-10 11:40:53'),
(4, '+2348133242320', 'charlesudoh1@gmail.com', 'Ajao road', 1, 1, 'PENDING', '2017-03-10 11:42:46'),
(11, '', 'charlesudoh1@gmail.com', '', 34, 1, 'PENDING', '2017-03-14 09:49:44'),
(12, '08185083623', 'charlesudoh1@gmail.com', '08185083623', 4, 1, 'PENDING', '2017-03-18 02:42:45'),
(13, '+2348133242320', 'charlesudoh1@gmail.com', '+2348133242320', 43, 1, 'PENDING', '2017-03-18 02:44:18'),
(14, '+2348133242320', 'charlesudoh1@gmail.com', '+2348133242320', 43, 1, 'PENDING', '2017-03-18 02:48:58'),
(15, '08133242320', 'charlesudoh1@gmail.com', '5 xyz street', 1, 1, 'PENDING', '2017-03-21 08:35:57');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_short_description` text NOT NULL,
  `product_description` text NOT NULL,
  `product_price` double NOT NULL,
  `product_quantity` int(11) NOT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `create_time` datetime NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `featured` tinyint(4) NOT NULL DEFAULT '0',
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_short_description`, `product_description`, `product_price`, `product_quantity`, `product_image`, `create_time`, `published`, `featured`, `category_id`) VALUES
(1, 'product one', 'product testing', 'product testingproduct testingproduct testingproduct testingproduct testingproduct testingproduct testingproduct testingproduct testingproduct testing', 10000, 6, '1.png', '2017-02-13 00:12:00', 1, 1, 5),
(2, 'aloe vera prod', 'This is a short description', 'This is a larger detailed description', 10000, 200, '2.png', '2017-02-26 06:41:19', 1, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `phone` varchar(16) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `facebook_page` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `first_name`, `last_name`, `phone`, `email`, `address`, `facebook_page`) VALUES
(1, 'charles', '7417026759c3b1263441683ed7fc7972', 'Charles', 'Udoh', '+2348133242320', 'charlieudoh@yahoo.com', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`article_id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`banner_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `article_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `banner_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
