-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2017 at 07:41 AM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 7.1.7
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
--
-- Database: `aerotemplate`
--
-- --------------------------------------------------------
--
-- Table structure for table `oc_article`
--
DROP TABLE IF EXISTS `oc_article`;
CREATE TABLE `oc_article` (
  `article_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Dumping data for table `oc_article`
--
INSERT INTO `oc_article` (`article_id`, `sort_order`, `status`, `date_added`, `date_modified`, `image`, `author`) VALUES
(1, 1, 1, '2017-10-01 20:45:11', '2017-11-07 09:34:46', 'catalog/blog/post2.jpg', 'Plazathemes'),
(2, 1, 1, '2017-10-01 20:46:33', '2017-11-07 09:34:30', 'catalog/blog/post1.jpg', 'Plazathemes');
-- --------------------------------------------------------
--
-- Table structure for table `oc_article_description`
--
DROP TABLE IF EXISTS `oc_article_description`;
CREATE TABLE `oc_article_description` (
  `article_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `intro_text` text NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `meta_keyword` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Dumping data for table `oc_article_description`
--
INSERT INTO `oc_article_description` (`article_id`, `language_id`, `name`, `description`, `intro_text`, `meta_title`, `meta_description`, `meta_keyword`) VALUES
(1, 1, 'Claritas est etiam processus dynamicus.', '&lt;p&gt;Lorem ipsum dolor sit amet. Integer adipiscing erat llentesque s sollicitudin pellentesque et non erat. Lorem ipsum dolor sit amet. Integer adipiscing erat eget risus Lorem ipsum dolor. Lorem ipsum dolor sit amet. Integer adipiscing erat llentesque s sollicitudin pellentesque et non erat. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Integer adipiscing erat llentesque s sollicitudin pellentesque et non erat. Lorem ipsum dolor sit amet.&lt;/p&gt;', '&lt;p&gt;Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer...&lt;/p&gt;', 'Claritas est etiam processus dynamicus.', '', ''),
(2, 1, 'Claritas est etiam processus dynamicus.', '&lt;p&gt;Lorem ipsum dolor sit amet. Integer adipiscing erat llentesque s sollicitudin pellentesque et non erat. Lorem ipsum dolor sit amet. Integer adipiscing erat eget risus Lorem ipsum dolor. Lorem ipsum dolor sit amet. Integer adipiscing erat llentesque s sollicitudin pellentesque et non erat. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Integer adipiscing erat llentesque s sollicitudin pellentesque et non erat. Lorem ipsum dolor sit amet.&lt;/p&gt;', '&lt;p&gt;Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer...&lt;/p&gt;', 'Claritas est etiam processus dynamicus.', '', '');
-- --------------------------------------------------------
--
-- Table structure for table `oc_article_list`
--
DROP TABLE IF EXISTS `oc_article_list`;
CREATE TABLE `oc_article_list` (
  `article_list_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Dumping data for table `oc_article_list`
--
INSERT INTO `oc_article_list` (`article_list_id`, `name`, `status`) VALUES
(1, 'Articles List 1', 1);
-- --------------------------------------------------------
--
-- Table structure for table `oc_article_to_list`
--
DROP TABLE IF EXISTS `oc_article_to_list`;
CREATE TABLE `oc_article_to_list` (
  `article_list_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Dumping data for table `oc_article_to_list`
--
INSERT INTO `oc_article_to_list` (`article_list_id`, `article_id`) VALUES
(1, 1),
(1, 2);
-- --------------------------------------------------------
--
-- Table structure for table `oc_article_to_store`
--
DROP TABLE IF EXISTS `oc_article_to_store`;
CREATE TABLE `oc_article_to_store` (
  `article_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Dumping data for table `oc_article_to_store`
--
INSERT INTO `oc_article_to_store` (`article_id`, `store_id`) VALUES
(1, 0),
(2, 0);
--
-- Table structure for table `oc_testimonial`
--
DROP TABLE IF EXISTS `oc_testimonial`;
CREATE TABLE `oc_testimonial` (
  `testimonial_id` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Dumping data for table `oc_testimonial`
--
INSERT INTO `oc_testimonial` (`testimonial_id`, `status`, `sort_order`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3);
-- --------------------------------------------------------
--
-- Table structure for table `oc_testimonial_description`
--
DROP TABLE IF EXISTS `oc_testimonial_description`;
CREATE TABLE `oc_testimonial_description` (
  `testimonial_id` int(10) UNSIGNED NOT NULL,
  `language_id` int(10) UNSIGNED NOT NULL,
  `image` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `customer_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Dumping data for table `oc_testimonial_description`
--
INSERT INTO `oc_testimonial_description` (`testimonial_id`, `language_id`, `image`, `customer_name`, `content`) VALUES
(1, 0, 'catalog/testimonial/testimor1.png', 'Rebecka Filson', ' This is Photoshops version  of Lorem Ipsum. Proin gravida nibh vel velit.Lorem ipsum dolor sit amet, consectetur adipiscing elit. In molestie augue magna. Pellentesque felis lorem, pulvinar sed eros non, sagittis consequat urna. Proin id dui tempor, imperdiet nisi et, hendrerit quam. Quisque tempus lorem nisl, non adipiscing arcu tristique ac. Sed eget mollis tellus, a varius diam. In a consectetur tellus, quis molestie ligula. Vivamus sit amet sem faucibus, dignissim augue ac, interdum metus. '),
(2, 0, 'catalog/testimonial/testimor1.png', 'Nathanael Jaworski', ' Mauris blandit, metus a venenatis lacinia, felis enim tincidunt est, condimentum vulputate orci augue eu metus. Fusce dictum, nisi et semper ultricies, felis tortor blandit odio, egestas consequat purus nisi eu est. Morbi porttitor porta nunc in elementum. Aliquam congue, nibh at dignissim scelerisque, tortor nisl placerat tortor, sit amet suscipit augue nisi quis elit. Nam dapibus, diam at blandit molestie, dolor dui vulputate ante, a tincidunt leo turpis quis enim. Etiam facilisis adipiscing lorem eget aliquam. Aliquam ac nisi vulputate, mattis nunc non, sollicitudin lorem. Nulla velit leo, dictum non massa vitae, commodo facilisis lacus. Vivamus ultricies urna a massa aliquet, et congue neque commodo. '),
(3, 0, 'catalog/testimonial/testimor1.png', 'Magdalena Valencia', ' Sed vel urna at dui iaculis gravida. Maecenas pretium, velit vitae placerat faucibus, velit quam facilisis elit, sit amet lacinia est est id ligula. Duis feugiat quam non justo faucibus, in gravida diam tempor. Nam viverra enim non ipsum ornare, condimentum blandit diam mattis. Maecenas gravida molestie felis ac tincidunt. Vivamus auctor magna sit amet nisl luctus consequat. Donec viverra leo viverra, auctor justo eu, venenatis eros. Praesent metus lectus, tempor id leo vel, convallis lobortis tellus. ');

-- --------------------------------------------------------
--
-- Table structure for table `oc_cmsblock`
--
DROP TABLE IF EXISTS `oc_cmsblock`;
CREATE TABLE `oc_cmsblock` (
  `cmsblock_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `sort_order` tinyint(1) DEFAULT NULL,
  `identify` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL,
  `banner_store` varchar(255) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
-- Dumping data for table `oc_cmsblock`
--
INSERT INTO `oc_cmsblock` (`cmsblock_id`, `status`, `sort_order`, `identify`, `link`, `type`, `banner_store`) VALUES
(20, 1, 0, 'static_top_aero1', '', 1, '1,2,0'),
(23, 1, 0, 'payment', '', 1, '1,2,3,6,7,0'),
(30, 1, 0, 'footer_static', '', 1, '1,2,3,0'),
(55, 1, 0, 'static_sidebar', '', 1, '1,2,3,0'),
(32, 1, 0, 'corporate', '', 1, '1,2,3,0'),
(36, 1, 0, 'static_bottom_aero1', '', 1, '1,0'),
(42, 1, 0, 'blog', 'index.php?route=blog/blog', 0, '1,2,3,6,7,0'),
(53, 1, 0, 'static_middle_aero3', '', 1, '2,3,0'),
(54, 1, 0, 'static_top_aero4', '', 1, '3,0'),
(52, 1, 0, 'social', '', 1, '1,2,3,0'),
(51, 1, 0, 'static_welcome', '', 1, '0');
-- --------------------------------------------------------
--
-- Table structure for table `oc_cmsblock_description`
--
DROP TABLE IF EXISTS `oc_cmsblock_description`;
CREATE TABLE `oc_cmsblock_description` (
  `cmsblock_des_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `cmsblock_id` int(11) NOT NULL,
  `title` varchar(64) NOT NULL,
  `sub_title` varchar(64) DEFAULT NULL,
  `description` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
-- Dumping data for table `oc_cmsblock_description`
--
INSERT INTO `oc_cmsblock_description` (`cmsblock_des_id`, `language_id`, `cmsblock_id`, `title`, `sub_title`, `description`) VALUES
(273, 1, 30, 'Footer Static                ', NULL, '&lt;div class=&quot;footer-title&quot;&gt;&lt;h3&gt;about us&lt;/h3&gt;&lt;/div&gt;\r\n&lt;div class=&quot;footer-content&quot;&gt;\r\n&lt;a href=&quot;index.php?route=common/home&quot; class=&quot;logo-footer&quot;&gt;&lt;img src=&quot;image/catalog/logo/logo-footer.png&quot; alt=&quot;logo&quot;&gt;&lt;/a&gt;\r\n&lt;p class=&quot;des&quot;&gt;\r\nDuis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan.\r\n&lt;/p&gt;\r\n&lt;ul class=&quot;footer-contact&quot;&gt;\r\n&lt;li class=&quot;address&quot;&gt;&lt;span&gt;Address : No 40 Baria Sreet 133/2, NewYork, USA.&lt;/span&gt;&lt;/li&gt;\r\n&lt;li class=&quot;phone&quot;&gt;&lt;span&gt;Phone: +(012) 800 456 789&lt;/span&gt;&lt;/li&gt;\r\n&lt;li class=&quot;email&quot;&gt;&lt;span&gt;Email: Infor@aero.com&lt;/span&gt;&lt;/li&gt;\r\n&lt;/ul&gt;																	 									 									 									 									\r\n&lt;/div&gt;\r\n\r\n																											 									 									 									 									 									 									 									 									 									 									 									'),
(280, 1, 53, 'Static Middle Aero3   ', NULL, '										  										  										  &lt;div class=&quot;banner-static static-middle-aero3&quot;&gt;	\r\n&lt;div class=&quot;row&quot;&gt;					\r\n&lt;div class=&quot;col col-sm-6 col-xs-12&quot;&gt;\r\n&lt;div class=&quot;image&quot;&gt;\r\n&lt;a href=&quot;index.php?route=product/category&amp;amp;path=1&quot;&gt;\r\n&lt;img src=&quot;image/catalog/cmsblock/img1-middle-aero3.jpg&quot; alt=&quot;image&quot;&gt;\r\n&lt;span class=&quot;text&quot;&gt;\r\n&lt;span class=&quot;text1&quot;&gt;new arivals&lt;/span&gt;\r\n&lt;span class=&quot;text2&quot;&gt;Good help &amp;amp; Guard&lt;/span&gt;\r\n&lt;span class=&quot;text3&quot;&gt;Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros&lt;/span&gt;\r\n\r\n&lt;/span&gt;\r\n&lt;/a&gt;\r\n&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;div class=&quot;col col-sm-6 col-xs-12&quot;&gt;\r\n&lt;div class=&quot;image&quot;&gt;\r\n&lt;a href=&quot;index.php?route=product/category&amp;amp;path=17&quot;&gt;\r\n&lt;img src=&quot;image/catalog/cmsblock/img2-middle-aero3.jpg&quot; alt=&quot;image&quot;&gt;\r\n&lt;span class=&quot;text&quot;&gt;\r\n&lt;span class=&quot;text1&quot;&gt;bestseller products&lt;/span&gt;\r\n&lt;span class=&quot;text2&quot;&gt;Top 10 vehicles off week&lt;/span&gt;\r\n&lt;span class=&quot;text3&quot;&gt;Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros&lt;/span&gt;\r\n\r\n&lt;/span&gt;\r\n&lt;/a&gt;\r\n&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;/div&gt;			\r\n																																																																																																																																																																				  									 									 									 									 									 									 									 									 									 									 									'),
(279, 1, 54, 'Static Top Aero4 ', NULL, '										  										  										  										  										  										  										  &lt;div class=&quot;banner-static static-top-aero4&quot;&gt;\r\n&lt;div class=&quot;row&quot;&gt;\r\n&lt;div class=&quot;col col-sm-4 col-xs-12&quot;&gt;\r\n&lt;div class=&quot;image&quot;&gt;\r\n&lt;a href=&quot;index.php?route=product/category&amp;amp;path=1&quot;&gt;&lt;img src=&quot;image/catalog/cmsblock/img1-top-aero4.jpg&quot; alt=&quot;image&quot;&gt;\r\n&lt;span class=&quot;text&quot;&gt;\r\n&lt;span class=&quot;text1&quot;&gt;new collection&lt;/span&gt;\r\n&lt;span class=&quot;text2&quot;&gt;New trending 2017 - 2018&lt;/span&gt;\r\n&lt;span class=&quot;text3&quot;&gt;sale up to 20% off&lt;/span&gt;\r\n&lt;/span&gt;\r\n&lt;/a&gt;\r\n&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;div class=&quot;col col-sm-4 col-xs-12&quot;&gt;\r\n&lt;div class=&quot;image&quot;&gt;\r\n&lt;a href=&quot;index.php?route=product/category&amp;amp;path=17&quot;&gt;&lt;img src=&quot;image/catalog/cmsblock/img2-top-aero4.jpg&quot; alt=&quot;image&quot;&gt;\r\n&lt;span class=&quot;text&quot;&gt;\r\n&lt;span class=&quot;text1&quot;&gt;best Low price&lt;/span&gt;\r\n&lt;span class=&quot;text2&quot;&gt;High performance&lt;/span&gt;\r\n&lt;span class=&quot;text3&quot;&gt;sale up to 10% off&lt;/span&gt;\r\n&lt;/span&gt;\r\n&lt;/a&gt;\r\n&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;div class=&quot;col col-sm-4 col-xs-12&quot;&gt;\r\n&lt;div class=&quot;image&quot;&gt;\r\n&lt;a href=&quot;index.php?route=product/category&amp;amp;path=30&quot;&gt;&lt;img src=&quot;image/catalog/cmsblock/img3-top-aero4.jpg&quot; alt=&quot;image&quot;&gt;\r\n&lt;span class=&quot;text&quot;&gt;\r\n&lt;span class=&quot;text1&quot;&gt;Honda motorcycles&lt;/span&gt;\r\n&lt;span class=&quot;text2&quot;&gt;Red Sale ends april 30&lt;/span&gt;\r\n&lt;span class=&quot;text3&quot;&gt;sale up to 40% off&lt;/span&gt;\r\n&lt;/span&gt;\r\n&lt;/a&gt;\r\n&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;/div&gt; 									 									 									 									 									 									 									 									 									 									 									 									'),
(268, 1, 52, 'Social', NULL, '&lt;ul class=&quot;link-follow&quot;&gt;\r\n&lt;li class=&quot;first&quot;&gt;&lt;a class=&quot;twitter fa fa-twitter&quot; title=&quot;Twitter&quot; href=&quot;https://twitter.com/plazathemes&quot;&gt;&lt;span&gt;twitter&lt;/span&gt;&lt;/a&gt;&lt;/li&gt;\r\n&lt;li&gt;&lt;a class=&quot;google fa fa-google-plus&quot; title=&quot;Google&quot; href=&quot;#&quot;&gt;&lt;span&gt;google &lt;/span&gt;&lt;/a&gt;&lt;/li&gt;\r\n&lt;li&gt;&lt;a class=&quot;facebook fa fa-facebook&quot; title=&quot;Facebook&quot; href=&quot;https://www.facebook.com/plazathemes1&quot;&gt;&lt;span&gt;facebook&lt;/span&gt;&lt;/a&gt;&lt;/li&gt;\r\n&lt;li&gt;&lt;a class=&quot;youtube fa fa-youtube&quot; title=&quot;Youtube&quot; href=&quot;https://www.youtube.com/user/plazathemes&quot;&gt;&lt;span&gt;youtube &lt;/span&gt;&lt;/a&gt;&lt;/li&gt;\r\n&lt;li&gt;&lt;a class=&quot;flickr fa fa-flickr&quot; title=&quot;Flickr&quot; href=&quot;https://www.youtube.com/user/plazathemes&quot;&gt;&lt;span&gt;Flickr &lt;/span&gt;&lt;/a&gt;&lt;/li&gt;\r\n&lt;/ul&gt;																																			 									'),
(277, 1, 20, 'Static Top Aero1      ', NULL, '										  										  										  										  										  &lt;div class=&quot;banner-static static-top-aero1&quot;&gt;\r\n&lt;div class=&quot;row&quot;&gt;\r\n&lt;div class=&quot;col col-sm-4 col-xs-12&quot;&gt;\r\n&lt;div class=&quot;image&quot;&gt;\r\n&lt;a href=&quot;index.php?route=product/category&amp;amp;path=1&quot;&gt;&lt;img src=&quot;image/catalog/cmsblock/img1-top-aero1.jpg&quot; alt=&quot;image&quot;&gt;\r\n&lt;span class=&quot;text&quot;&gt;\r\n&lt;span class=&quot;text1&quot;&gt;new collection&lt;/span&gt;\r\n&lt;span class=&quot;text2&quot;&gt;New trending 2017 - 2018&lt;/span&gt;\r\n&lt;span class=&quot;text3&quot;&gt;sale up to 20% off&lt;/span&gt;\r\n&lt;/span&gt;\r\n&lt;/a&gt;\r\n&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;div class=&quot;col col-sm-4 col-xs-12&quot;&gt;\r\n&lt;div class=&quot;image&quot;&gt;\r\n&lt;a href=&quot;index.php?route=product/category&amp;amp;path=17&quot;&gt;&lt;img src=&quot;image/catalog/cmsblock/img2-top-aero1.jpg&quot; alt=&quot;image&quot;&gt;\r\n&lt;span class=&quot;text&quot;&gt;\r\n&lt;span class=&quot;text1&quot;&gt;best Low price&lt;/span&gt;\r\n&lt;span class=&quot;text2&quot;&gt;High performance&lt;/span&gt;\r\n&lt;span class=&quot;text3&quot;&gt;sale up to 10% off&lt;/span&gt;\r\n&lt;/span&gt;\r\n&lt;/a&gt;\r\n&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;div class=&quot;col col-sm-4 col-xs-12&quot;&gt;\r\n&lt;div class=&quot;image&quot;&gt;\r\n&lt;a href=&quot;index.php?route=product/category&amp;amp;path=30&quot;&gt;&lt;img src=&quot;image/catalog/cmsblock/img3-top-aero1.jpg&quot; alt=&quot;image&quot;&gt;\r\n&lt;span class=&quot;text&quot;&gt;\r\n&lt;span class=&quot;text1&quot;&gt;Honda motorcycles&lt;/span&gt;\r\n&lt;span class=&quot;text2&quot;&gt;Red Sale ends april 30&lt;/span&gt;\r\n&lt;span class=&quot;text3&quot;&gt;sale up to 40% off&lt;/span&gt;\r\n&lt;/span&gt;\r\n&lt;/a&gt;\r\n&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;/div&gt; 									 									 									 									 									 									 									 									 									 									'),
(260, 1, 32, 'Corporate       ', NULL, '										  										  										  										  										  &lt;div class=&quot;ma-corporate-about&quot;&gt;\r\n&lt;div class=&quot;row&quot;&gt;\r\n&lt;div class=&quot;col col-sm-4 col-xs-12&quot;&gt;\r\n&lt;div class=&quot;block-wrapper wrapper1&quot;&gt;\r\n&lt;div class=&quot;text-des&quot;&gt;\r\n&lt;h3&gt;Free delivery&lt;/h3&gt;\r\n&lt;p&gt;Nam liber tempor cum soluta nobis\r\neleifend option congue.&lt;/p&gt;\r\n&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;div class=&quot;col col-sm-4 col-xs-12&quot;&gt;\r\n&lt;div class=&quot;block-wrapper wrapper2&quot;&gt;\r\n&lt;div class=&quot;text-des&quot;&gt;\r\n&lt;h3&gt;Money guarantee&lt;/h3&gt;\r\n&lt;p&gt;Nam liber tempor cum soluta nobis\r\neleifend option congue.&lt;/p&gt;\r\n&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;div class=&quot;col col-sm-4 col-xs-12&quot;&gt;\r\n&lt;div class=&quot;block-wrapper wrapper3&quot;&gt;\r\n&lt;div class=&quot;text-des&quot;&gt;\r\n&lt;h3&gt;Online support&lt;/h3&gt;\r\n&lt;p&gt;Nam liber tempor cum soluta nobis\r\neleifend option congue.&lt;/p&gt;\r\n&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;/div&gt;\r\n\r\n&lt;/div&gt;\r\n&lt;/div&gt;\r\n																											 									 									 									 									 									 									'),
(226, 1, 23, 'Payment  ', NULL, '										  &lt;div class=&quot;footer-payment&quot;&gt;&lt;a href=&quot;#&quot;&gt;&lt;img src=&quot;image/catalog/cmsblock/payment.png&quot; alt=&quot;payment&quot;&gt;&lt;/a&gt;&lt;/div&gt; 									 									'),
(246, 1, 42, '  Blog ', NULL, '										  										  									 									'),
(281, 1, 55, 'Static Sidebar', NULL, '&lt;div class=&quot;banner-static static-sidebar&quot;&gt;\r\n&lt;div class=&quot;image&quot;&gt;\r\n&lt;a href=&quot;#&quot;&gt;&lt;img src=&quot;image/catalog/cmsblock/img-sidebar.jpg&quot; alt=&quot;image&quot;&gt;&lt;/a&gt;\r\n&lt;/div&gt;\r\n&lt;/div&gt;'),
(272, 1, 36, '  Static Bottom Aero1 ', NULL, '										  										  										  										  										  										  &lt;div class=&quot;banner-static static-bottom-aero1&quot;&gt;						\r\n&lt;div class=&quot;col &quot;&gt;\r\n&lt;div class=&quot;image&quot;&gt;\r\n&lt;a href=&quot;index.php?route=product/category&amp;amp;path=1&quot;&gt;\r\n&lt;img src=&quot;image/catalog/cmsblock/img1-bottom-aero1.jpg&quot; alt=&quot;image&quot;&gt;\r\n&lt;span class=&quot;text&quot;&gt;\r\n&lt;span class=&quot;text1&quot;&gt;new arivals&lt;/span&gt;\r\n&lt;span class=&quot;text2&quot;&gt;Good help &amp;amp; Guard&lt;/span&gt;\r\n&lt;span class=&quot;text3&quot;&gt;Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros&lt;/span&gt;\r\n&lt;span class=&quot;link&quot;&gt;view collection&lt;/span&gt;\r\n&lt;/span&gt;\r\n&lt;/a&gt;\r\n&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;div class=&quot;col&quot;&gt;\r\n&lt;div class=&quot;image&quot;&gt;\r\n&lt;a href=&quot;index.php?route=product/category&amp;amp;path=17&quot;&gt;\r\n&lt;img src=&quot;image/catalog/cmsblock/img2-bottom-aero1.jpg&quot; alt=&quot;image&quot;&gt;\r\n&lt;span class=&quot;text&quot;&gt;\r\n&lt;span class=&quot;text1&quot;&gt;bestseller products&lt;/span&gt;\r\n&lt;span class=&quot;text2&quot;&gt;Top 10 vehicles off week&lt;/span&gt;\r\n&lt;span class=&quot;text3&quot;&gt;Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros&lt;/span&gt;\r\n&lt;span class=&quot;link&quot;&gt;view collection&lt;/span&gt;\r\n&lt;/span&gt;\r\n&lt;/a&gt;\r\n&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;/div&gt;\r\n\r\n\r\n						\r\n																																																																																																																																																																				  									 									 									 									 									 									 									'),
(264, 1, 51, 'Static Welcome   ', NULL, '										  										  										  &lt;div class=&quot;static-welcome&quot;&gt;\r\n&lt;div class=&quot;title&quot;&gt;\r\n&lt;h2&gt;History&lt;/h2&gt;\r\n&lt;h3&gt;welcome to &lt;strong&gt;Aero car store&lt;/strong&gt;&lt;/h3&gt;\r\n&lt;/div&gt;\r\n&lt;div class=&quot;content&quot;&gt;\r\n&lt;div class=&quot;image&quot;&gt;\r\n&lt;a href=&quot;#&quot;&gt;&lt;img src=&quot;image/catalog/cmsblock/img1.png&quot; alt=&quot;img&quot;&gt;&lt;/a&gt;\r\n&lt;/div&gt;\r\n&lt;div class=&quot;text&quot;&gt;\r\n&lt;h3&gt;&lt;span class=&quot;word1&quot;&gt;meet our&lt;/span&gt;&lt;span class=&quot;word2&quot;&gt;car club since 1892&lt;/span&gt;&lt;span class=&quot;word3&quot;&gt;custome and club&lt;/span&gt;&lt;/h3&gt;\r\n&lt;p&gt;\r\nClaritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram, anteposuerit litterarum formas humanitatis per seacula quarta decima et quinta decima. \r\n&lt;/p&gt;\r\n&lt;p&gt;\r\nEodem modo typi, qui nunc nobis videntur parum clari, fiant sollemnes in futurum.\r\n&lt;/p&gt;\r\n&lt;div class=&quot;author&quot;&gt;\r\n&lt;h4 class=&quot;name&quot;&gt;John doe - CEo aero&lt;/h4&gt;\r\n&lt;img class=&quot;signature&quot; src=&quot;image/catalog/cmsblock/img2.png&quot; alt=&quot;signature&quot;&gt;\r\n&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;/div&gt; 									 									 									');
-- --------------------------------------------------------
--
-- Table structure for table `oc_cmsblock_to_store`
--
DROP TABLE IF EXISTS `oc_cmsblock_to_store`;
CREATE TABLE `oc_cmsblock_to_store` (
  `cmsblock_id` int(11) DEFAULT NULL,
  `store_id` tinyint(4) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
-- --------------------------------------------------------
--
-- Table structure for table `oc_extension`
--
DROP TABLE IF EXISTS `oc_extension`;
CREATE TABLE `oc_extension` (
  `extension_id` int(11) NOT NULL,
  `type` varchar(32) NOT NULL,
  `code` varchar(32) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
-- Dumping data for table `oc_extension`
--
INSERT INTO `oc_extension` (`extension_id`, `type`, `code`) VALUES
(1, 'payment', 'cod'),
(2, 'total', 'shipping'),
(3, 'total', 'sub_total'),
(4, 'total', 'tax'),
(5, 'total', 'total'),
(8, 'total', 'credit'),
(9, 'shipping', 'flat'),
(10, 'total', 'handling'),
(11, 'total', 'low_order_fee'),
(12, 'total', 'coupon'),
(55, 'module', 'banner'),
(15, 'total', 'reward'),
(16, 'total', 'voucher'),
(17, 'payment', 'free_checkout'),
(20, 'theme', 'default'),
(21, 'dashboard', 'activity'),
(22, 'dashboard', 'sale'),
(23, 'dashboard', 'recent'),
(24, 'dashboard', 'order'),
(25, 'dashboard', 'online'),
(26, 'dashboard', 'map'),
(27, 'dashboard', 'customer'),
(28, 'dashboard', 'chart'),
(29, 'report', 'sale_coupon'),
(31, 'report', 'customer_search'),
(32, 'report', 'customer_transaction'),
(33, 'report', 'product_purchased'),
(34, 'report', 'product_viewed'),
(35, 'report', 'sale_return'),
(36, 'report', 'sale_order'),
(37, 'report', 'sale_shipping'),
(38, 'report', 'sale_tax'),
(39, 'report', 'customer_activity'),
(40, 'report', 'customer_order'),
(41, 'report', 'customer_reward'),
(42, 'module', 'oc_page_builder'),
(43, 'module', 'ocajaxlogin'),
(45, 'module', 'occmsblock'),
(46, 'module', 'oclayerednavigation'),
(47, 'module', 'newslettersubscribe'),
(48, 'module', 'ocslideshow'),
(50, 'module', 'octhemeoption'),
(51, 'module', 'ocproduct'),
(52, 'module', 'octabproducts'),
(53, 'module', 'ochozmegamenu'),
(56, 'module', 'ocvermegamenu'),
(58, 'module', 'ocfeaturedcategory'),
(59, 'module', 'ocblog'),
(61, 'module', 'octestimonial');
-- --------------------------------------------------------
--
-- Table structure for table `oc_extension_install`
--
DROP TABLE IF EXISTS `oc_extension_install`;
CREATE TABLE `oc_extension_install` (
  `extension_install_id` int(11) NOT NULL,
  `extension_download_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
-- Dumping data for table `oc_extension_install`
--
INSERT INTO `oc_extension_install` (`extension_install_id`, `extension_download_id`, `filename`, `date_added`) VALUES
(1, 0, 'octhemeoption.ocmod.zip', '2017-08-09 09:37:18'),
(2, 0, 'customizetemplate.ocmod.zip', '2017-08-10 09:08:15'),
(3, 0, 'customizetemplate.ocmod.zip', '2017-08-10 17:17:46'),
(4, 0, 'customizetemplate.ocmod.zip', '2017-08-10 17:19:27'),
(5, 0, 'oclayerednavigation.ocmod.zip', '2017-08-11 15:37:04'),
(6, 0, 'occustomizetemplate.ocmod.zip', '2017-08-11 16:07:58'),
(7, 0, 'oclayerednavigation.ocmod.zip', '2017-08-11 16:08:14'),
(8, 0, 'oclayerednavigation.ocmod.zip', '2017-08-14 15:15:18'),
(9, 0, 'oclayerednavigation.ocmod.zip', '2017-08-14 15:17:42'),
(10, 0, 'oclayerednavigation.ocmod.zip', '2017-08-14 15:21:10'),
(11, 0, 'oclayerednavigation.ocmod.zip', '2017-08-14 15:34:32'),
(12, 0, 'oclayerednavigation.ocmod.zip', '2017-08-14 16:04:49'),
(13, 0, 'oclayerednavigation.ocmod.zip', '2017-08-14 16:06:23'),
(14, 0, 'occustomizetemplate.ocmod.zip', '2017-08-14 16:46:14'),
(15, 0, 'oclayerednavigation.ocmod.zip', '2017-08-15 14:53:09'),
(16, 0, 'occustomizetemplate.ocmod.zip', '2017-08-16 14:30:50'),
(17, 0, 'occustomizetemplate.ocmod.zip', '2017-08-29 16:36:30'),
(18, 0, 'occustomizetemplate.ocmod.zip', '2017-09-08 09:30:20'),
(19, 0, 'occustomizetemplate.ocmod.zip', '2017-09-08 09:52:35'),
(20, 0, 'occustomizetemplate.ocmod.zip', '2017-09-21 16:53:03'),
(21, 0, 'occustomizetemplate.ocmod.zip', '2017-10-26 10:56:16'),
(22, 0, 'occustomizetemplate.ocmod.zip', '2017-11-21 15:10:42');
-- --------------------------------------------------------
--
-- Table structure for table `oc_extension_path`
--
DROP TABLE IF EXISTS `oc_extension_path`;
CREATE TABLE `oc_extension_path` (
  `extension_path_id` int(11) NOT NULL,
  `extension_install_id` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- --------------------------------------------------------
--
-- Table structure for table `oc_layout`
--
DROP TABLE IF EXISTS `oc_layout`;
CREATE TABLE `oc_layout` (
  `layout_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
-- Dumping data for table `oc_layout`
--
INSERT INTO `oc_layout` (`layout_id`, `name`) VALUES
(7, 'Layout Aero 03: Default'),
(10, 'Layout Aero 03: Category'),
(11, 'Layout Aero 03: Product'),
(12, 'Layout Aero 03: Home');
-- --------------------------------------------------------
--
-- Table structure for table `oc_layout_module`
--
DROP TABLE IF EXISTS `oc_layout_module`;
CREATE TABLE `oc_layout_module` (
  `layout_module_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL,
  `code` varchar(64) NOT NULL,
  `position` varchar(14) NOT NULL,
  `sort_order` int(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
-- Dumping data for table `oc_layout_module`
--
INSERT INTO `oc_layout_module` (`layout_module_id`, `layout_id`, `code`, `position`, `sort_order`) VALUES
(1269, 11, 'occmsblock.118', 'block7', 0),
(1265, 12, 'occmsblock.118', 'block7', 0),
(1264, 12, 'occmsblock.36', 'block6', 0),
(1312, 10, 'occmsblock.61', 'block5', 0),
(1313, 10, 'occmsblock.36', 'block6', 0),
(1254, 7, 'occmsblock.36', 'block6', 0),
(1255, 7, 'occmsblock.118', 'block7', 0),
(1268, 11, 'occmsblock.36', 'block6', 0),
(1311, 10, 'ochozmegamenu.43', 'block1', 0),
(1253, 7, 'occmsblock.61', 'block5', 0),
(1262, 12, 'oc_page_builder.53', 'content_top', 0),
(1267, 11, 'occmsblock.61', 'block5', 0),
(1263, 12, 'occmsblock.61', 'block5', 0),
(1310, 10, 'occmsblock.123', 'column_left', 1),
(1314, 10, 'occmsblock.118', 'block7', 0),
(1309, 10, 'oclayerednavigation', 'column_left', 0),
(1252, 7, 'ochozmegamenu.43', 'block1', 0),
(1261, 12, 'ochozmegamenu.43', 'block1', 0),
(1266, 11, 'ochozmegamenu.43', 'block1', 0);
-- --------------------------------------------------------
--
-- Table structure for table `oc_layout_route`
--
DROP TABLE IF EXISTS `oc_layout_route`;
CREATE TABLE `oc_layout_route` (
  `layout_route_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `route` varchar(64) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
-- Dumping data for table `oc_layout_route`
--
INSERT INTO `oc_layout_route` (`layout_route_id`, `layout_id`, `store_id`, `route`) VALUES
(353, 10, 0, 'product/category%'),
(344, 11, 0, 'product/product'),
(341, 7, 0, ''),
(343, 12, 0, 'common/home');
-- --------------------------------------------------------
--
-- Table structure for table `oc_modification`
--
DROP TABLE IF EXISTS `oc_modification`;
CREATE TABLE `oc_modification` (
  `modification_id` int(11) NOT NULL,
  `extension_install_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `code` varchar(64) NOT NULL,
  `author` varchar(64) NOT NULL,
  `version` varchar(32) NOT NULL,
  `link` varchar(255) NOT NULL,
  `xml` mediumtext NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
-- Dumping data for table `oc_modification`
--
INSERT INTO `oc_modification` (`modification_id`, `extension_install_id`, `name`, `code`, `author`, `version`, `link`, `xml`, `status`, `date_added`) VALUES
(1, 1, 'OC Theme Option', 'oc_theme_option', 'Plaza Theme', '3.x', '', '<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n<modification>\r\n    <code>oc_theme_option</code>\r\n    <name>OC Theme Option</name>\r\n    <version>3.x</version>\r\n    <author>Plaza Theme</author>\r\n\r\n    <!-- Menu Left in Admin -->\r\n    <file path=\"admin/controller/common/column_left.php\">\r\n        <operation>\r\n            <search ><![CDATA[$sale = array();]]></search>\r\n            <add position=\"before\"><![CDATA[\r\n            // OC Menu Items\r\n            $this->load->language(\'ocadminmenu/ocadminmenu\');\r\n\r\n            $ocadmin_menu = array();\r\n\r\n            if($this->user->hasPermission(\'access\', \'extension/module/octhemeoption\')) {\r\n                $ocadmin_menu[] = array(\r\n                    \'name\'     => $this->language->get(\'text_theme_config\'),\r\n                    \'href\'     => $this->url->link(\'extension/module/octhemeoption\', \'user_token=\' . $this->session->data[\'user_token\'], true),\r\n                    \'children\' => array()\r\n                );\r\n            }\r\n\r\n            if($this->user->hasPermission(\'access\', \'catalog/occategorythumbnail\')) {\r\n                $ocadmin_menu[] = array(\r\n                    \'name\'     => $this->language->get(\'text_category_thumbnail\'),\r\n                    \'href\'     => $this->url->link(\'catalog/occategorythumbnail\', \'user_token=\' . $this->session->data[\'user_token\'], true),\r\n                    \'children\' => array()\r\n                );\r\n            }\r\n\r\n            // Blog\r\n            $blog = array();\r\n\r\n            if ($this->user->hasPermission(\'access\', \'blog/article\')) {\r\n                $blog[] = array(\r\n                    \'name\'     => $this->language->get(\'text_article\'),\r\n                    \'href\'     => $this->url->link(\'blog/article\', \'user_token=\' . $this->session->data[\'user_token\'], true),\r\n                    \'children\' => array()\r\n                );\r\n            }\r\n\r\n            if ($this->user->hasPermission(\'access\', \'blog/articlelist\')) {\r\n                $blog[] = array(\r\n                    \'name\'     => $this->language->get(\'text_article_list\'),\r\n                    \'href\'     => $this->url->link(\'blog/articlelist\', \'user_token=\' . $this->session->data[\'user_token\'], true),\r\n                    \'children\' => array()\r\n                );\r\n            }\r\n\r\n            if ($this->user->hasPermission(\'access\', \'blog/config\')) {\r\n                $blog[] = array(\r\n                    \'name\'     => $this->language->get(\'text_blog_config\'),\r\n                    \'href\'     => $this->url->link(\'blog/config\', \'user_token=\' . $this->session->data[\'user_token\'], true),\r\n                    \'children\' => array()\r\n                );\r\n            }\r\n\r\n            if($blog) {\r\n                $ocadmin_menu[] = array(\r\n                    \'name\'      => $this->language->get(\'text_blog\'),\r\n                    \'href\'      => \'\',\r\n                    \'children\'  => $blog\r\n                );\r\n            }\r\n\r\n            if($this->user->hasPermission(\'access\', \'extension/module/occmsblock\')) {\r\n                $ocadmin_menu[] = array(\r\n                    \'name\'     => $this->language->get(\'text_cms_block\'),\r\n                    \'href\'     => $this->url->link(\'extension/module/occmsblock/cmslist\', \'user_token=\' . $this->session->data[\'user_token\'], true),\r\n                    \'children\' => array()\r\n                );\r\n            }\r\n\r\n            if($this->user->hasPermission(\'access\', \'extension/module/ocslideshow\')) {\r\n                $ocadmin_menu[] = array(\r\n                    \'name\'     => $this->language->get(\'text_slideshow\'),\r\n                    \'href\'     => $this->url->link(\'extension/module/ocslideshow/form\', \'user_token=\' . $this->session->data[\'user_token\'], true),\r\n                    \'children\' => array()\r\n                );\r\n            }\r\n\r\n            if($this->user->hasPermission(\'access\', \'catalog/octestimonial\')) {\r\n                $ocadmin_menu[] = array(\r\n                    \'name\'     => $this->language->get(\'text_testimonial\'),\r\n                    \'href\'     => $this->url->link(\'catalog/octestimonial\', \'user_token=\' . $this->session->data[\'user_token\'], true),\r\n                    \'children\' => array()\r\n                );\r\n            }\r\n            \r\n            $data[\'menus\'][] = array(\r\n                \'id\'       => \'menu-panel\',\r\n                \'icon\'     => \'fa-empire\',\r\n                \'name\'     => $this->language->get(\'text_theme_menu\'),\r\n                \'href\'     => \'\',\r\n                \'children\' => $ocadmin_menu\r\n            );\r\n            ]]></add>\r\n        </operation>\r\n    </file>\r\n\r\n    <!-- PRODUCT ROTATOR IMAGE -->\r\n    <file path=\"admin/controller/catalog/product.php\">\r\n        <operation>\r\n            <search ><![CDATA[public function index() {]]></search>\r\n            <add position=\"after\"><![CDATA[\r\n        /* Product Rotator */\r\n        $this->load->model(\'catalog/ocproductrotator\');\r\n        \r\n        $this->model_catalog_ocproductrotator->installProductRotator();\r\n        /* End Product Rotator */\r\n            ]]></add>\r\n        </operation>\r\n        <operation>\r\n            <search ><![CDATA[$this->load->language(\'catalog/product\');]]></search>\r\n            <add position=\"before\"><![CDATA[\r\n                /* Product Rotator */\r\n                $this->load->language(\'extension/module/ocproductrotator\');\r\n            ]]></add>\r\n        </operation>\r\n        <operation>\r\n            <search ><![CDATA[\'sort_order\' => $product_image[\'sort_order\']]]></search>\r\n            <add position=\"before\"><![CDATA[\r\n                /* Product Rotator */\r\n                \'is_rotator\' => $product_image[\'is_rotator\'],\r\n            ]]></add>\r\n        </operation>\r\n    </file>\r\n\r\n    <file path=\"admin/model/catalog/product.php\">\r\n        <operation>\r\n            <search><![CDATA[$this->db->query(\"INSERT INTO \" . DB_PREFIX . \"product_image SET product_id = \'\" . (int)$product_id . \"\', image = \'\" . $this->db->escape($product_image[\'image\']) . \"\', sort_order = \'\" . (int)$product_image[\'sort_order\'] . \"\'\");]]></search>\r\n            <add position=\"replace\">\r\n                <![CDATA[\r\n                    /* Product Rotator */\r\n                    $this->db->query(\"INSERT INTO \" . DB_PREFIX . \"product_image SET product_id = \'\" . (int)$product_id . \"\', image = \'\" . $this->db->escape($product_image[\'image\']) . \"\', sort_order = \'\" . (int)$product_image[\'sort_order\'] . \"\', is_rotator = \'\" . (int)$product_image[\'is_rotator\'] . \"\'\");\r\n                ]]>\r\n            </add>\r\n        </operation>\r\n    </file>\r\n\r\n    <file path=\"admin/view/template/catalog/product_form.twig\">\r\n        <operation>\r\n            <search><![CDATA[<td class=\"text-right\">{{ entry_sort_order }}</td>]]></search>\r\n            <add position=\"after\">\r\n                <![CDATA[\r\n                    <!-- Product Rotator -->\r\n                    <td class=\"text-center\">{{ entry_is_rotator }}</td>\r\n                ]]>\r\n            </add>\r\n        </operation>\r\n        <operation>\r\n            <search><![CDATA[<td class=\"text-left\"><button type=\"button\" onclick=\"$(\'#image-row{{ image_row }}\').remove();\" data-toggle=\"tooltip\" title=\"{{ button_remove }}\" class=\"btn btn-danger\"><i class=\"fa fa-minus-circle\"></i></button></td>]]></search>\r\n            <add position=\"before\">\r\n                <![CDATA[\r\n                    <!-- Product Rotator -->\r\n                    <td class=\"text-center\">\r\n                      <select name=\"product_image[{{ image_row }}][is_rotator]\" class=\"form-control rotator-select\">\r\n                        {% if product_image.is_rotator and (product_image.is_rotator == 1) %}\r\n                        <option value=\"1\" selected=\"selected\">Yes</option>\r\n                        <option value=\"0\">No</option>\r\n                        {% else %}\r\n                        <option value=\"1\">Yes</option>\r\n                        <option value=\"0\" selected=\"selected\">No</option>\r\n                        {% endif %}\r\n                      </select>\r\n                    </td>\r\n                    <!-- End -->\r\n                ]]>\r\n            </add>\r\n        </operation>\r\n        <operation>\r\n            <search><![CDATA[function addImage() {]]></search>\r\n            <add position=\"before\">\r\n                <![CDATA[\r\n                    //Product Rotator\r\n                    $(\'#tab-image tfoot td:first\').attr(\'colspan\', 3);\r\n                    $(\'.rotator-select\').change(function() {\r\n                      var value = $(this).val();\r\n                      if(value == 1) {\r\n                        $(\'.rotator-select\').val(0);\r\n                        $(this).val(1);\r\n                      }\r\n                    });\r\n                    //End\r\n                ]]>\r\n            </add>\r\n        </operation>\r\n        <operation>\r\n            <search><![CDATA[$(\'#images tbody\').append(html);]]></search>\r\n            <add position=\"after\">\r\n                <![CDATA[\r\n                    //Product Rotator\r\n                    $(\'.rotator-select\').change(function() {\r\n                      var value = $(this).val();\r\n                      if(value == 1) {\r\n                        $(\'.rotator-select\').val(0);\r\n                        $(this).val(1);\r\n                      }\r\n                    });\r\n                    //End\r\n                ]]>\r\n            </add>\r\n        </operation>\r\n        <operation>\r\n            <search><![CDATA[html += \'  <td class=\"text-left\"><button type=\"button\" onclick=\"$(\\\'#image-row\' + image_row  + \'\\\').remove();\" data-toggle=\"tooltip\" title=\"{{ button_remove }}\" class=\"btn btn-danger\"><i class=\"fa fa-minus-circle\"></i></button></td>\';]]></search>\r\n            <add position=\"before\">\r\n                <![CDATA[\r\n                    //Product Rotator\r\n                    html += \' <td class=\"text-center\">\';\r\n                    html += \'   <select name=\"product_image[\' + image_row + \'][is_rotator]\" class=\"form-control rotator-select\">\';\r\n                    html += \'     <option value=\"1\">{{ text_yes }}</option>\';\r\n                    html += \'     <option value=\"0\" selected=\"selected\">{{ text_no }}</option>\';\r\n                    html += \'   </select>\';\r\n                    html += \' </td>\';\r\n                    //End\r\n                ]]>\r\n            </add>\r\n        </operation>\r\n    </file>\r\n    \r\n    <!-- THEME OPTION -->\r\n    <file path=\"catalog/controller/common/header.php\">\r\n        <operation>\r\n            <search><![CDATA[return $this->load->view(\'common/header\', $data);]]></search>\r\n            <add position=\"before\"><![CDATA[\r\n        $data[\'store_id\'] = $this->config->get(\'config_store_id\');\r\n        $data[\'theme_option_status\'] = $this->config->get(\'module_octhemeoption_status\');\r\n        $data[\'a_tag\'] = $this->config->get(\'module_octhemeoption_a_tag\');\r\n        $data[\'header_tag\'] = $this->config->get(\'module_octhemeoption_header_tag\');\r\n        $data[\'body_css\'] = $this->config->get(\'module_octhemeoption_body\');\r\n            ]]></add>\r\n        </operation>\r\n    </file>\r\n\r\n    <!-- Layouts Position -->\r\n    <file path=\"admin/controller/design/layout.php\">\r\n        <operation>\r\n            <search><![CDATA[$this->response->setOutput($this->load->view(\'design/layout_form\', $data));]]></search>\r\n            <add position=\"replace\"><![CDATA[\r\n                $this->response->setOutput($this->load->view(\'design/layout_plaza_form\', $data));\r\n            ]]></add>\r\n        </operation>\r\n    </file>\r\n</modification>\r\n', 1, '2017-08-09 09:37:22'),
(15, 15, 'OC Layered Navigation Ajax', 'layered_navigation', 'Plaza Theme', '3.x', 'http://www.plazathemes.com/', '<modification>\r\n    <name>OC Layered Navigation Ajax</name>\r\n	<version>3.x</version>\r\n	<link>http://www.plazathemes.com/</link>\r\n	<author>Plaza Theme</author>\r\n	<code>layered_navigation</code>\r\n\r\n	<file path=\"catalog/controller/product/category.php\">\r\n        <operation>\r\n            <search><![CDATA[$category_info = $this->model_catalog_category->getCategory($category_id);]]></search>\r\n            <add position=\"before\"><![CDATA[\r\n        /* Edit for Layered Navigation */\r\n        if (!empty($_SERVER[\'HTTPS\']) && $_SERVER[\'HTTPS\'] != \'off\') {\r\n            // SSL connection\r\n            $base_url = str_replace(\'http\', \'https\', $this->config->get(\'config_url\'));\r\n        } else {\r\n            $base_url = $this->config->get(\'config_url\');\r\n        }\r\n            ]]></add>\r\n        </operation>\r\n\r\n        <operation>\r\n            <search><![CDATA[\'href\' => $this->url->link(\'product/category\', \'path=\' . $this->request->get[\'path\'] . \'_\' . $result[\'category_id\'] . $url)]]></search>\r\n            <add position=\"replace\"><![CDATA[\'href\'  => $base_url . \'index.php?route=product/category&path=\' . $result[\'category_id\'] . $url]]></add>\r\n        </operation>\r\n\r\n        <operation>\r\n            <search><![CDATA[$this->url->link(\'product/category\', \'path=\' . $this->request->get[\'path\'] . \'&sort=p.sort_order&order=ASC\' . $url)]]></search>\r\n            <add position=\"replace\"><![CDATA[$base_url . \'index.php?route=product/category&path=\' . $category_id . \'&sort=p.sort_order&order=ASC\' . $url]]></add>\r\n        </operation>\r\n\r\n        <operation>\r\n            <search><![CDATA[$this->url->link(\'product/category\', \'path=\' . $this->request->get[\'path\'] . \'&sort=pd.name&order=ASC\' . $url)]]></search>\r\n            <add position=\"replace\"><![CDATA[$base_url . \'index.php?route=product/category&path=\' . $category_id . \'&sort=pd.name&order=ASC\' . $url]]></add>\r\n        </operation>\r\n\r\n        <operation>\r\n            <search><![CDATA[$this->url->link(\'product/category\', \'path=\' . $this->request->get[\'path\'] . \'&sort=pd.name&order=DESC\' . $url)]]></search>\r\n            <add position=\"replace\"><![CDATA[$base_url . \'index.php?route=product/category&path=\' . $category_id . \'&sort=pd.name&order=DESC\' . $url]]></add>\r\n        </operation>\r\n\r\n        <operation>\r\n            <search><![CDATA[$this->url->link(\'product/category\', \'path=\' . $this->request->get[\'path\'] . \'&sort=p.price&order=ASC\' . $url)]]></search>\r\n            <add position=\"replace\"><![CDATA[$base_url . \'index.php?route=product/category&path=\' . $category_id . \'&sort=p.price&order=ASC\' . $url]]></add>\r\n        </operation>\r\n\r\n        <operation>\r\n            <search><![CDATA[$this->url->link(\'product/category\', \'path=\' . $this->request->get[\'path\'] . \'&sort=p.price&order=DESC\' . $url)]]></search>\r\n            <add position=\"replace\"><![CDATA[$base_url . \'index.php?route=product/category&path=\' . $category_id . \'&sort=p.price&order=DESC\' . $url]]></add>\r\n        </operation>\r\n\r\n        <operation>\r\n            <search><![CDATA[$this->url->link(\'product/category\', \'path=\' . $this->request->get[\'path\'] . \'&sort=rating&order=DESC\' . $url)]]></search>\r\n            <add position=\"replace\"><![CDATA[$base_url . \'index.php?route=product/category&path=\' . $category_id . \'&sort=rating&order=DESC\' . $url]]></add>\r\n        </operation>\r\n\r\n        <operation>\r\n            <search><![CDATA[$this->url->link(\'product/category\', \'path=\' . $this->request->get[\'path\'] . \'&sort=rating&order=ASC\' . $url)]]></search>\r\n            <add position=\"replace\"><![CDATA[$base_url . \'index.php?route=product/category&path=\' . $category_id . \'&sort=rating&order=ASC\' . $url]]></add>\r\n        </operation>\r\n\r\n        <operation>\r\n            <search><![CDATA[$this->url->link(\'product/category\', \'path=\' . $this->request->get[\'path\'] . \'&sort=p.model&order=ASC\' . $url)]]></search>\r\n            <add position=\"replace\"><![CDATA[$base_url . \'index.php?route=product/category&path=\' . $category_id . \'&sort=p.model&order=ASC\' . $url]]></add>\r\n        </operation>\r\n\r\n        <operation>\r\n            <search><![CDATA[$this->url->link(\'product/category\', \'path=\' . $this->request->get[\'path\'] . \'&sort=p.model&order=DESC\' . $url)]]></search>\r\n            <add position=\"replace\"><![CDATA[$base_url . \'index.php?route=product/category&path=\' . $category_id . \'&sort=p.model&order=DESC\' . $url]]></add>\r\n        </operation>\r\n\r\n        <operation>\r\n            <search><![CDATA[$this->url->link(\'product/category\', \'path=\' . $this->request->get[\'path\'] . $url . \'&limit=\' . $value)]]></search>\r\n            <add position=\"replace\"><![CDATA[$base_url . \'index.php?route=product/category&path=\' . $category_id . $url . \'&limit=\' . $value]]></add>\r\n        </operation>\r\n\r\n        <operation>\r\n            <search><![CDATA[$pagination->url = $this->url->link(\'product/category\', \'path=\' . $this->request->get[\'path\'] . $url . \'&page={page}\');]]></search>\r\n            <add position=\"replace\"><![CDATA[$pagination->url = $base_url . \'index.php?route=extension/module/oclayerednavigation/category&path=\' . $category_id . $url . \'&page={page}\';]]></add>\r\n        </operation>\r\n\r\n        <operation>\r\n            <search><![CDATA[$data[\'limit\'] = $limit;]]></search>\r\n            <add position=\"replace\"><![CDATA[\r\n            /* Edit for Layered Navigation Ajax Module */\r\n            $module_status = $this->config->get(\'module_oclayerednavigation_status\');\r\n            if($module_status) {\r\n                // $this->document->addScript(\'catalog/view/javascript/jquery/jquery-ui.min.js\');\r\n                $this->document->addStyle(\'catalog/view/javascript/jquery/css/jquery-ui.min.css\');\r\n \r\n                if (file_exists(DIR_TEMPLATE . $this->config->get(\'theme_\' . $this->config->get(\'config_theme\') . \'_directory\') . \'/stylesheet/opentheme/oclayerednavigation/css/oclayerednavigation.css\')) {\r\n                    $this->document->addStyle(\'catalog/view/theme/\' . $this->config->get(\'theme_\' . $this->config->get(\'config_theme\') . \'_directory\') . \'/stylesheet/opentheme/oclayerednavigation/css/oclayerednavigation.css\');\r\n                } else {\r\n                    $this->document->addStyle(\'catalog/view/theme/default/stylesheet/opentheme/oclayerednavigation/css/oclayerednavigation.css\');\r\n                }\r\n \r\n                $this->document->addScript(\'catalog/view/javascript/opentheme/oclayerednavigation/oclayerednavigation.js\');\r\n            }\r\n\r\n\r\n            ]]></add>\r\n        </operation>\r\n\r\n        <operation>\r\n            <search><![CDATA[$this->response->setOutput($this->load->view(\'product/category\', $data));]]></search>\r\n            <add position=\"replace\"><![CDATA[\r\n            /* Edit for Layered Navigation Ajax Module */\r\n            if($module_status) {\r\n                $data[\'module_oclayerednavigation_loader_img\'] = $base_url . \'image/\' . $this->config->get(\'module_oclayerednavigation_loader_img\');\r\n \r\n                $this->response->setOutput($this->load->view(\'extension/module/oclayerednavigation/occategory\', $data));\r\n            } else {\r\n \r\n                $this->response->setOutput($this->load->view(\'product/category\', $data));\r\n            }\r\n            ]]></add>\r\n        </operation>\r\n	</file>\r\n\r\n    <file path=\"catalog/model/catalog/product.php\">\r\n        <operation>\r\n            <search><![CDATA[if (!empty($data[\'filter_manufacturer_id\'])) {]]></search>\r\n            <add position=\"before\">\r\n                <![CDATA[\r\n        /* Price range */\r\n        if  (!empty($data[\'filter_price\'])) {\r\n            $min_price = $data[\'filter_price\'][\'min_price\'];\r\n            $max_price = $data[\'filter_price\'][\'max_price\'];\r\n            $sql_sl_special = \"(SELECT price FROM \" . DB_PREFIX . \"product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = \'\" . (int)$this->config->get(\'config_customer_group_id\') . \"\' AND ((ps.date_start = \'0000-00-00\' OR ps.date_start < NOW()) AND (ps.date_end = \'0000-00-00\' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1)\";\r\n            $sql_sl_discount = \"(SELECT price FROM \" . DB_PREFIX . \"product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = \'\" . (int)$this->config->get(\'config_customer_group_id\') . \"\' AND pd2.quantity = \'1\' AND ((pd2.date_start = \'0000-00-00\' OR pd2.date_start < NOW()) AND (pd2.date_end = \'0000-00-00\' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1)\";\r\n            $sql .= \" AND (CASE WHEN \" . $sql_sl_special . \" IS NOT NULL THEN \" . $sql_sl_special . \" WHEN \" . $sql_sl_discount . \" IS NOT NULL THEN \". $sql_sl_discount . \" ELSE p.price END) >=\'\". $min_price .\"\'\" ;\r\n            $sql .= \" AND (CASE WHEN \" . $sql_sl_special . \" IS NOT NULL THEN \" . $sql_sl_special . \" WHEN \" . $sql_sl_discount . \" IS NOT NULL THEN \". $sql_sl_discount . \" ELSE p.price END) <=\'\". $max_price .\"\'\";\r\n        }\r\n        /* End */\r\n                ]]>\r\n            </add>\r\n        </operation>\r\n    </file>\r\n</modification>', 1, '2017-08-15 14:53:18');
INSERT INTO `oc_modification` (`modification_id`, `extension_install_id`, `name`, `code`, `author`, `version`, `link`, `xml`, `status`, `date_added`) VALUES
(22, 22, 'OC Customize Template', 'oc_customize_template', 'Plaza Theme', '3.x', '', '<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n<modification>\r\n    <code>oc_customize_template</code>\r\n    <name>OC Customize Template</name>\r\n    <version>3.x</version>\r\n    <author>Plaza Theme</author>\r\n	\r\n	<file path=\"admin/model/localisation/language.php\">\r\n		<operation>\r\n			<search><![CDATA[return $language_id;]]></search>\r\n			<add position=\"before\"><![CDATA[\r\n				$query = $this->db->query(\"SELECT * FROM \" . DB_PREFIX . \"cmsblock_description WHERE language_id = \'\" . (int)$this->config->get(\'config_language_id\') . \"\'\");\r\n\r\n				foreach ($query->rows as $cmsblock_description) {\r\n					$this->db->query(\"INSERT INTO \" . DB_PREFIX . \"cmsblock_description SET cmsblock_des_id = \'\" . (int)$cmsblock_description[\'cmsblock_des_id\'] . \"\', language_id = \'\" . (int)$language_id . \"\', cmsblock_id = \'\" . (int)$cmsblock_description[\'cmsblock_id\'] . \"\', title = \'\" . $this->db->escape($cmsblock_description[\'title\']) . \"\', description = \'\" . $this->db->escape($cmsblock_description[\'description\']) . \"\'\");\r\n				}\r\n				$this->cache->delete(\'cmsblock_description\');\r\n\r\n				$query = $this->db->query(\"SELECT * FROM \" . DB_PREFIX . \"article_description WHERE language_id = \'\" . (int)$this->config->get(\'config_language_id\') . \"\'\");\r\n\r\n				foreach ($query->rows as $article_description) {\r\n					$this->db->query(\"INSERT INTO \" . DB_PREFIX . \"article_description SET article_id = \'\" . (int)$article_description[\'article_id\'] . \"\', language_id = \'\" . (int)$language_id . \"\', name = \'\" . $this->db->escape($article_description[\'name\']) . \"\', description = \'\" . $this->db->escape($article_description[\'description\']) . \"\', intro_text = \'\" . $this->db->escape($article_description[\'intro_text\']) . \"\', meta_title = \'\" . $this->db->escape($article_description[\'meta_title\']) . \"\', meta_description = \'\" . $this->db->escape($article_description[\'meta_description\']) . \"\', meta_keyword = \'\" . $this->db->escape($article_description[\'meta_keyword\']) . \"\'\");\r\n				}\r\n				$this->cache->delete(\'article_description\');\r\n				\r\n				$query = $this->db->query(\"SELECT * FROM \" . DB_PREFIX . \"ocslideshow_image_description WHERE language_id = \'\" . (int)$this->config->get(\'config_language_id\') . \"\'\");\r\n\r\n				foreach ($query->rows as $ocslideshow_image_description) {\r\n					$this->db->query(\"INSERT INTO \" . DB_PREFIX . \"ocslideshow_image_description SET ocslideshow_image_id = \'\" . (int)$ocslideshow_image_description[\'ocslideshow_image_id\'] . \"\', language_id = \'\" . (int)$language_id . \"\', ocslideshow_id = \'\" . (int)$ocslideshow_image_description[\'ocslideshow_id\'] . \"\', description = \'\" . $this->db->escape($ocslideshow_image_description[\'description\']) . \"\', title = \'\" . $this->db->escape($ocslideshow_image_description[\'title\']) . \"\', sub_title = \'\" . $this->db->escape($ocslideshow_image_description[\'sub_title\']) . \"\'\");\r\n				}\r\n				$this->cache->delete(\'ocslideshow_image_description\');\r\n			]]></add>\r\n		</operation>\r\n	</file>\r\n	\r\n	<file path=\"catalog/controller/common/cart.php\">\r\n		<operation>\r\n			<search><![CDATA[$data[\'text_items\'] = sprintf($this->language->get(\'text_items\'), $this->cart->countProducts() + (isset($this->session->data[\'vouchers\']) ? count($this->session->data[\'vouchers\']) : 0), $this->currency->format($total, $this->session->data[\'currency\']));]]></search>\r\n			<add position=\"replace\"><![CDATA[\r\n				$data[\'text_items\'] = sprintf($this->language->get(\'text_items\'), $this->cart->countProducts() + (isset($this->session->data[\'vouchers\']) ? count($this->session->data[\'vouchers\']) : 0), null);\r\n			]]></add>\r\n		</operation>\r\n		<operation>\r\n			<search><![CDATA[$data[\'products\'][] = array(]]></search>\r\n			<add position=\"before\"><![CDATA[\r\n				$c_words = 30;\r\n				$product[\'name\'] = strlen($product[\'name\']) > $c_words ? substr($product[\'name\'],0,$c_words).\"...\" : $product[\'name\'];\r\n			]]></add>\r\n		</operation>\r\n	</file>	\r\n\r\n	<file path=\"catalog/controller/common/header.php\">\r\n		<operation>\r\n			<search><![CDATA[return $this->load->view(\'common/header\', $data);]]></search>\r\n			<add position=\"before\"><![CDATA[\r\n				$data[\'block1\'] = $this->load->controller(\'common/block1\');\r\n				$data[\'block2\'] = $this->load->controller(\'common/block2\');\r\n				$data[\'block3\'] = $this->load->controller(\'common/block3\');\r\n				\r\n				if($this->config->get(\'module_ocajaxlogin_status\')){\r\n					$data[\'use_ajax_login\'] = true;\r\n				}else{\r\n					$data[\'use_ajax_login\'] = false;\r\n				}\r\n				\r\n				// For page specific css\r\n				if (isset($this->request->get[\'route\'])) {\r\n					if (isset($this->request->get[\'product_id\'])) {\r\n						$class = \'-\' . $this->request->get[\'product_id\'];\r\n					} elseif (isset($this->request->get[\'path\'])) {\r\n						$class = \'-\' . $this->request->get[\'path\'];\r\n					} elseif (isset($this->request->get[\'manufacturer_id\'])) {\r\n						$class = \'-\' . $this->request->get[\'manufacturer_id\'];\r\n					} elseif (isset($this->request->get[\'information_id\'])) {\r\n						$class = \'-\' . $this->request->get[\'information_id\'];\r\n					} else {\r\n						$class = \'\';\r\n					}\r\n\r\n					$data[\'class\'] = str_replace(\'/\', \'-\', $this->request->get[\'route\']) . $class;\r\n				} else {\r\n					$data[\'class\'] = \'common-home\';\r\n				}\r\n			]]></add>\r\n		</operation>\r\n	</file>\r\n	\r\n	<file path=\"catalog/controller/common/footer.php\">\r\n		<operation>\r\n			<search><![CDATA[$data[\'newsletter\'] = $this->url->link(\'account/newsletter\', \'\', true);]]></search>\r\n			<add position=\"after\"><![CDATA[						\r\n				$data[\'block4\'] = $this->load->controller(\'common/block4\');			\r\n				$data[\'block5\'] = $this->load->controller(\'common/block5\');				\r\n				$data[\'block6\'] = $this->load->controller(\'common/block6\');				\r\n				$data[\'block7\'] = $this->load->controller(\'common/block7\');				\r\n				$data[\'block8\'] = $this->load->controller(\'common/block8\');				\r\n				$data[\'block9\'] = $this->load->controller(\'common/block9\');				\r\n				$data[\'block10\'] = $this->load->controller(\'common/block10\');				\r\n				if ($this->request->server[\'HTTPS\']) {\r\n					$server = $this->config->get(\'config_ssl\');\r\n				} else {\r\n					$server = $this->config->get(\'config_url\');\r\n				}\r\n			]]></add>\r\n		</operation>\r\n	</file>\r\n	\r\n	<file path=\"catalog/controller/product/category.php\">\r\n		<operation>\r\n			<search><![CDATA[foreach ($results as $result) {]]></search>\r\n			<add position=\"before\"><![CDATA[\r\n				/* Get new product */\r\n				$this->load->model(\'catalog/product\');\r\n		\r\n				$data[\'new_products\'] = array();\r\n		\r\n				$filter_data = array(\r\n					\'sort\'  => \'p.date_added\',\r\n					\'order\' => \'DESC\',\r\n					\'start\' => 0,\r\n					\'limit\' => 10\r\n				);\r\n		\r\n				$new_results = $this->model_catalog_product->getProducts($filter_data);\r\n				/* End */\r\n			]]></add>\r\n		</operation>\r\n		<operation>\r\n			<search><![CDATA[$data[\'products\'][] = array(]]></search>\r\n			<add position=\"before\"><![CDATA[\r\n				$is_new = false;\r\n				if ($new_results) { \r\n					foreach($new_results as $new_r) {\r\n						if($result[\'product_id\'] == $new_r[\'product_id\']) {\r\n							$is_new = true;\r\n						}\r\n					}\r\n				}\r\n				if (($this->config->get(\'config_customer_price\') && $this->customer->isLogged()) || !$this->config->get(\'config_customer_price\')) {\r\n					$price_num = $this->tax->calculate($result[\'price\'], $result[\'tax_class_id\'], $this->config->get(\'config_tax\'));\r\n				} else {\r\n					$price_num = false;\r\n				}\r\n\r\n				if ((float)$result[\'special\']) {\r\n					$special_num = $this->tax->calculate($result[\'special\'], $result[\'tax_class_id\'], $this->config->get(\'config_tax\'));\r\n				} else {\r\n					$special_num = false;\r\n				}\r\n				/// Product Rotator /\r\n				$store_id = $this->config->get(\'config_store_id\');\r\n\r\n				$product_rotator_status = (int) $this->config->get(\'module_octhemeoption_rotator\')[$store_id];\r\n				if($product_rotator_status == 1) {\r\n				 $this->load->model(\'catalog/ocproductrotator\');\r\n				 $this->load->model(\'tool/image\');\r\n			 \r\n				 $product_id = $result[\'product_id\'];\r\n				 $product_rotator_image = $this->model_catalog_ocproductrotator->getProductRotatorImage($product_id);\r\n			 \r\n				 if($product_rotator_image) {\r\n				  $rotator_image = $this->model_tool_image->resize($product_rotator_image, $this->config->get(\'theme_\' . $this->config->get(\'config_theme\') . \'_image_product_width\'), $this->config->get(\'theme_\' . $this->config->get(\'config_theme\') . \'_image_product_height\')); \r\n				 } else {\r\n				  $rotator_image = false;\r\n				 } \r\n				} else {\r\n				 $rotator_image = false;    \r\n				}\r\n				/// End Product Rotator /\r\n								\r\n				$c_words = 50;\r\n				$result[\'name\'] = strlen($result[\'name\']) > $c_words ? substr($result[\'name\'],0,$c_words).\"...\" : $result[\'name\'];\r\n			]]></add>\r\n		</operation>\r\n		<operation>\r\n			<search><![CDATA[\'product_id\'  => $result[\'product_id\'],]]></search>\r\n			<add position=\"before\"><![CDATA[\r\n				\'is_new\'      => $is_new,\r\n				\'rotator_image\' => $rotator_image,\r\n				\'price_num\'       => $price_num,\r\n				\'special_num\'     => $special_num,\r\n				\'manufacturer\' => $result[\'manufacturer\'],\r\n				\'manufacturers\' => $this->url->link(\'product/manufacturer/info\', \'manufacturer_id=\' . $result[\'manufacturer_id\']),\r\n			]]></add>\r\n		</operation>\r\n	</file>\r\n	\r\n	<file path=\"catalog/controller/product/product.php\">\r\n		<operation>\r\n			<search><![CDATA[$results = $this->model_catalog_product->getProductRelated($this->request->get[\'product_id\']);]]></search>\r\n			<add position=\"before\"><![CDATA[\r\n				/* Get new product */\r\n				$this->load->model(\'catalog/product\');\r\n		\r\n				$data[\'new_products\'] = array();\r\n		\r\n				$filter_data = array(\r\n					\'sort\'  => \'p.date_added\',\r\n					\'order\' => \'DESC\',\r\n					\'start\' => 0,\r\n					\'limit\' => 10\r\n				);\r\n		\r\n				$new_results = $this->model_catalog_product->getProducts($filter_data);\r\n				/* End */\r\n			]]></add>\r\n		</operation>\r\n		<operation>\r\n			<search><![CDATA[$data[\'products\'][] = array(]]></search>\r\n			<add position=\"before\"><![CDATA[\r\n				$is_new = false;\r\n				if ($new_results) { \r\n					foreach($new_results as $new_r) {\r\n						if($result[\'product_id\'] == $new_r[\'product_id\']) {\r\n							$is_new = true;\r\n						}\r\n					}\r\n				}\r\n				if (($this->config->get(\'config_customer_price\') && $this->customer->isLogged()) || !$this->config->get(\'config_customer_price\')) {\r\n					$price_num = $this->tax->calculate($result[\'price\'], $result[\'tax_class_id\'], $this->config->get(\'config_tax\'));\r\n				} else {\r\n					$price_num = false;\r\n				}\r\n\r\n				if ((float)$result[\'special\']) {\r\n					$special_num = $this->tax->calculate($result[\'special\'], $result[\'tax_class_id\'], $this->config->get(\'config_tax\'));\r\n				} else {\r\n					$special_num = false;\r\n				}\r\n				/// Product Rotator /\r\n				$store_id = $this->config->get(\'config_store_id\');\r\n\r\n				$product_rotator_status = (int) $this->config->get(\'module_octhemeoption_rotator\')[$store_id];\r\n				if($product_rotator_status == 1) {\r\n				 $this->load->model(\'catalog/ocproductrotator\');\r\n				 $this->load->model(\'tool/image\');\r\n			 \r\n				 $product_id = $result[\'product_id\'];\r\n				 $product_rotator_image = $this->model_catalog_ocproductrotator->getProductRotatorImage($product_id);\r\n			 \r\n				 if($product_rotator_image) {\r\n				  $rotator_image = $this->model_tool_image->resize($product_rotator_image, $this->config->get(\'theme_\' . $this->config->get(\'config_theme\') . \'_image_product_width\'), $this->config->get(\'theme_\' . $this->config->get(\'config_theme\') . \'_image_product_height\')); \r\n				 } else {\r\n				  $rotator_image = false;\r\n				 }\r\n				} else {\r\n				 $rotator_image = false;    \r\n				}\r\n				/// End Product Rotator /	\r\n				\r\n				$data[\'use_quickview\'] = (int) $this->config->get(\'module_octhemeoption_quickview\')[$store_id];\r\n				\r\n				$c_words = 50;\r\n				$result[\'name\'] = strlen($result[\'name\']) > $c_words ? substr($result[\'name\'],0,$c_words).\"...\" : $result[\'name\'];\r\n			]]></add>\r\n		</operation>\r\n		<operation>\r\n			<search><![CDATA[\'rating\'      => $rating,]]></search>\r\n			<add position=\"after\"><![CDATA[\r\n				\'is_new\'      => $is_new,\r\n				\'rotator_image\' => $rotator_image,\r\n				\'price_num\'       => $price_num,\r\n				\'special_num\'     => $special_num,\r\n				\'manufacturer\' => $result[\'manufacturer\'],\r\n				\'manufacturers\' => $this->url->link(\'product/manufacturer/info\', \'manufacturer_id=\' . $result[\'manufacturer_id\']),\r\n			]]></add>\r\n		</operation>\r\n		<operation>\r\n			<search><![CDATA[$data[\'description\'] = html_entity_decode($product_info[\'description\'], ENT_QUOTES, \'UTF-8\');]]></search>\r\n			<add position=\"after\"><![CDATA[\r\n				$data[\'short_description\'] = utf8_substr(strip_tags(html_entity_decode($product_info[\'description\'], ENT_QUOTES, \'UTF-8\')), 0, $this->config->get($this->config->get(\'config_theme\') . \'_product_description_length\')) . \'..\';\r\n			]]></add>\r\n		</operation>\r\n	</file>\r\n	\r\n	<file path=\"catalog/controller/product/special.php\">		\r\n		<operation>\r\n			<search><![CDATA[$results = $this->model_catalog_product->getProductSpecials($filter_data);]]></search>\r\n			<add position=\"after\"><![CDATA[\r\n				/* Get new product */\r\n				$this->load->model(\'catalog/product\');\r\n		\r\n				$data[\'new_products\'] = array();\r\n		\r\n				$filter_data = array(\r\n					\'sort\'  => \'p.date_added\',\r\n					\'order\' => \'DESC\',\r\n					\'start\' => 0,\r\n					\'limit\' => 10\r\n				);\r\n		\r\n				$new_results = $this->model_catalog_product->getProducts($filter_data);\r\n				/* End */\r\n			]]></add>\r\n		</operation>\r\n		\r\n		<operation>\r\n			<search><![CDATA[$data[\'products\'][] = array(]]></search>\r\n			<add position=\"before\"><![CDATA[\r\n				$is_new = false;\r\n				if ($new_results) { \r\n					foreach($new_results as $new_r) {\r\n						if($result[\'product_id\'] == $new_r[\'product_id\']) {\r\n							$is_new = true;\r\n						}\r\n					}\r\n				}\r\n				if (($this->config->get(\'config_customer_price\') && $this->customer->isLogged()) || !$this->config->get(\'config_customer_price\')) {\r\n					$price_num = $this->tax->calculate($result[\'price\'], $result[\'tax_class_id\'], $this->config->get(\'config_tax\'));\r\n				} else {\r\n					$price_num = false;\r\n				}\r\n\r\n				if ((float)$result[\'special\']) {\r\n					$special_num = $this->tax->calculate($result[\'special\'], $result[\'tax_class_id\'], $this->config->get(\'config_tax\'));\r\n				} else {\r\n					$special_num = false;\r\n				}\r\n				/// Product Rotator /\r\n				$store_id = $this->config->get(\'config_store_id\');\r\n				$product_rotator_status = (int) $this->config->get(\'module_octhemeoption_rotator\')[$store_id];\r\n				\r\n				if($product_rotator_status == 1) {\r\n				 $this->load->model(\'catalog/ocproductrotator\');\r\n				 $this->load->model(\'tool/image\');\r\n			 \r\n				 $product_id = $result[\'product_id\'];\r\n				 $product_rotator_image = $this->model_catalog_ocproductrotator->getProductRotatorImage($product_id);\r\n			 \r\n					 if($product_rotator_image) {\r\n							$rotator_image = $this->model_tool_image->resize($product_rotator_image, $this->config->get(\'theme_\' . $this->config->get(\'config_theme\') . \'_image_product_width\'), $this->config->get(\'theme_\' . $this->config->get(\'config_theme\') . \'_image_product_height\')); \r\n					} else {\r\n						$rotator_image = false;\r\n					}\r\n				} else {\r\n				 $rotator_image = false;    \r\n				}\r\n				/// End Product Rotator /\r\n				\r\n				$c_words = 50;\r\n				$result[\'name\'] = strlen($result[\'name\']) > $c_words ? substr($result[\'name\'],0,$c_words).\"...\" : $result[\'name\'];\r\n			]]></add>\r\n		</operation>\r\n		\r\n		<operation>\r\n			<search><![CDATA[\'rating\'      => $result[\'rating\'],]]></search>\r\n			<add position=\"after\"><![CDATA[\r\n				\'is_new\'      => $is_new,\r\n				\'rotator_image\' => $rotator_image,\r\n				\'price_num\'       => $price_num,\r\n				\'special_num\'     => $special_num,\r\n				\'manufacturer\' => $result[\'manufacturer\'],\r\n				\'manufacturers\' => $this->url->link(\'product/manufacturer/info\', \'manufacturer_id=\' . $result[\'manufacturer_id\']),\r\n			]]></add>\r\n		</operation>\r\n	</file>\r\n\r\n	<file path=\"catalog/controller/product/search.php\">\r\n		<operation>\r\n			<search><![CDATA[$results = $this->model_catalog_product->getProducts($filter_data);]]></search>\r\n			<add position=\"after\"><![CDATA[\r\n				/* Get new product */\r\n				$this->load->model(\'catalog/product\');\r\n		\r\n				$data[\'new_products\'] = array();\r\n		\r\n				$filter_data = array(\r\n					\'sort\'  => \'p.date_added\',\r\n					\'order\' => \'DESC\',\r\n					\'start\' => 0,\r\n					\'limit\' => 10\r\n				);\r\n		\r\n				$new_results = $this->model_catalog_product->getProducts($filter_data);\r\n				/* End */\r\n			]]></add>\r\n		</operation>\r\n		\r\n		<operation>\r\n			<search><![CDATA[$data[\'products\'][] = array(]]></search>\r\n			<add position=\"before\"><![CDATA[\r\n				$is_new = false;\r\n				if ($new_results) { \r\n					foreach($new_results as $new_r) {\r\n						if($result[\'product_id\'] == $new_r[\'product_id\']) {\r\n							$is_new = true;\r\n						}\r\n					}\r\n				}\r\n				if (($this->config->get(\'config_customer_price\') && $this->customer->isLogged()) || !$this->config->get(\'config_customer_price\')) {\r\n					$price_num = $this->tax->calculate($result[\'price\'], $result[\'tax_class_id\'], $this->config->get(\'config_tax\'));\r\n				} else {\r\n					$price_num = false;\r\n				}\r\n\r\n				if ((float)$result[\'special\']) {\r\n					$special_num = $this->tax->calculate($result[\'special\'], $result[\'tax_class_id\'], $this->config->get(\'config_tax\'));\r\n				} else {\r\n					$special_num = false;\r\n				}\r\n				/// Product Rotator /\r\n				$store_id = $this->config->get(\'config_store_id\');\r\n				$product_rotator_status = (int) $this->config->get(\'module_octhemeoption_rotator\')[$store_id];\r\n				if($product_rotator_status == 1) {\r\n				 $this->load->model(\'catalog/ocproductrotator\');\r\n				 $this->load->model(\'tool/image\');\r\n			 \r\n				 $product_id = $result[\'product_id\'];\r\n				 $product_rotator_image = $this->model_catalog_ocproductrotator->getProductRotatorImage($product_id);\r\n			 \r\n				 if($product_rotator_image) {\r\n							$rotator_image = $this->model_tool_image->resize($product_rotator_image, $this->config->get(\'theme_\' . $this->config->get(\'config_theme\') . \'_image_product_width\'), $this->config->get(\'theme_\' . $this->config->get(\'config_theme\') . \'_image_product_height\')); \r\n					} else {\r\n						$rotator_image = false;\r\n					} \r\n				} else {\r\n				 $rotator_image = false;    \r\n				}\r\n				/// End Product Rotator /\r\n				\r\n				$c_words = 50;\r\n				$result[\'name\'] = strlen($result[\'name\']) > $c_words ? substr($result[\'name\'],0,$c_words).\"...\" : $result[\'name\'];\r\n			]]></add>\r\n		</operation>\r\n		\r\n		<operation>\r\n			<search><![CDATA[\'rating\'      => $result[\'rating\'],]]></search>\r\n			<add position=\"after\"><![CDATA[\r\n				\'is_new\'      => $is_new,\r\n				\'rotator_image\' => $rotator_image,\r\n				\'price_num\'       => $price_num,\r\n				\'special_num\'     => $special_num,\r\n				\'manufacturer\' => $result[\'manufacturer\'],\r\n				\'manufacturers\' => $this->url->link(\'product/manufacturer/info\', \'manufacturer_id=\' . $result[\'manufacturer_id\']),\r\n			]]></add>\r\n		</operation>\r\n	</file>\r\n\r\n	<file path=\"catalog/controller/product/manufacturer.php\">\r\n		<operation>\r\n			<search><![CDATA[$results = $this->model_catalog_product->getProducts($filter_data);]]></search>\r\n			<add position=\"after\"><![CDATA[\r\n				/* Get new product */\r\n				$this->load->model(\'catalog/product\');\r\n		\r\n				$data[\'new_products\'] = array();\r\n		\r\n				$filter_data = array(\r\n					\'sort\'  => \'p.date_added\',\r\n					\'order\' => \'DESC\',\r\n					\'start\' => 0,\r\n					\'limit\' => 10\r\n				);\r\n		\r\n				$new_results = $this->model_catalog_product->getProducts($filter_data);\r\n				/* End */\r\n			]]></add>\r\n		</operation>\r\n		\r\n		<operation>\r\n			<search><![CDATA[$data[\'products\'][] = array(]]></search>\r\n			<add position=\"before\"><![CDATA[\r\n				$is_new = false;\r\n				if ($new_results) { \r\n					foreach($new_results as $new_r) {\r\n						if($result[\'product_id\'] == $new_r[\'product_id\']) {\r\n							$is_new = true;\r\n						}\r\n					}\r\n				}\r\n				if (($this->config->get(\'config_customer_price\') && $this->customer->isLogged()) || !$this->config->get(\'config_customer_price\')) {\r\n					$price_num = $this->tax->calculate($result[\'price\'], $result[\'tax_class_id\'], $this->config->get(\'config_tax\'));\r\n				} else {\r\n					$price_num = false;\r\n				}\r\n\r\n				if ((float)$result[\'special\']) {\r\n					$special_num = $this->tax->calculate($result[\'special\'], $result[\'tax_class_id\'], $this->config->get(\'config_tax\'));\r\n				} else {\r\n					$special_num = false;\r\n				}\r\n				/// Product Rotator /\r\n				$store_id = $this->config->get(\'config_store_id\');\r\n				$product_rotator_status = (int) $this->config->get(\'module_octhemeoption_rotator\')[$store_id];\r\n				if($product_rotator_status == 1) {\r\n				 $this->load->model(\'catalog/ocproductrotator\');\r\n				 $this->load->model(\'tool/image\');\r\n			 \r\n				 $product_id = $result[\'product_id\'];\r\n				 $product_rotator_image = $this->model_catalog_ocproductrotator->getProductRotatorImage($product_id);\r\n			 \r\n				 if($product_rotator_image) {\r\n							$rotator_image = $this->model_tool_image->resize($product_rotator_image, $this->config->get(\'theme_\' . $this->config->get(\'config_theme\') . \'_image_product_width\'), $this->config->get(\'theme_\' . $this->config->get(\'config_theme\') . \'_image_product_height\')); \r\n					} else {\r\n						$rotator_image = false;\r\n					}\r\n				} else {\r\n				 $rotator_image = false;    \r\n				}\r\n				/// End Product Rotator /\r\n				\r\n				$c_words = 50;\r\n				$result[\'name\'] = strlen($result[\'name\']) > $c_words ? substr($result[\'name\'],0,$c_words).\"...\" : $result[\'name\'];\r\n			]]></add>\r\n		</operation>\r\n		\r\n		<operation>\r\n			<search><![CDATA[\'rating\'      => $result[\'rating\'],]]></search>\r\n			<add position=\"after\"><![CDATA[\r\n				\'is_new\'      => $is_new,\r\n				\'rotator_image\' => $rotator_image,\r\n				\'price_num\'       => $price_num,\r\n				\'special_num\'     => $special_num,\r\n				\'manufacturer\' => $result[\'manufacturer\'],\r\n				\'manufacturers\' => $this->url->link(\'product/manufacturer/info\', \'manufacturer_id=\' . $result[\'manufacturer_id\']),\r\n			]]></add>\r\n		</operation>\r\n	</file>\r\n	<file path=\"catalog/controller/account/{account,address,affiliate,download,edit,forgotten,login,logout,newsletter,order,password,recurring,register,reset,return,reward,success,tracking,transaction,voucher,wishlist}*.php\">\r\n		  <operation>\r\n		   <search><![CDATA[$this->document->setTitle($this->language->get(\'heading_title\'));]]></search>\r\n		   <add position=\"after\"><![CDATA[\r\n			$data[\'heading_title\'] = $this->language->get(\'heading_title\');\r\n		   ]]></add>\r\n		  </operation>\r\n		 </file>\r\n		 \r\n		 <file path=\"catalog/controller/affiliate/{login,register,success,}*.php\">\r\n		  <operation>\r\n		   <search><![CDATA[$this->document->setTitle($this->language->get(\'heading_title\'));]]></search>\r\n		   <add position=\"after\"><![CDATA[\r\n			$data[\'heading_title\'] = $this->language->get(\'heading_title\');\r\n		   ]]></add>\r\n		  </operation>\r\n		 </file>\r\n		 \r\n		 <file path=\"catalog/controller/checkout/{cart,checkout,failure,success}*.php\">\r\n		  <operation>\r\n		   <search><![CDATA[$this->document->setTitle($this->language->get(\'heading_title\'));]]></search>\r\n		   <add position=\"after\"><![CDATA[\r\n			$data[\'heading_title\'] = $this->language->get(\'heading_title\');\r\n		   ]]></add>\r\n		  </operation>\r\n		 </file>\r\n		 \r\n		 <file path=\"catalog/controller/product/{compare,manufacturer,search,special}*.php\">\r\n		  <operation>\r\n		   <search><![CDATA[$this->document->setTitle($this->language->get(\'heading_title\'));]]></search>\r\n		   <add position=\"after\"><![CDATA[\r\n			$data[\'heading_title\'] = $this->language->get(\'heading_title\');\r\n		   ]]></add>\r\n		  </operation>\r\n		 </file>\r\n		 \r\n		 <file path=\"catalog/controller/{common,error}/{maintenance,not_found}*.php\">\r\n		  <operation>\r\n		   <search><![CDATA[$this->document->setTitle($this->language->get(\'heading_title\'));]]></search>\r\n		   <add position=\"after\"><![CDATA[\r\n			$data[\'heading_title\'] = $this->language->get(\'heading_title\');\r\n		   ]]></add>\r\n		  </operation>\r\n		 </file>\r\n		 \r\n		 <file path=\"catalog/controller/extension/{credit_card,payment}/{sagepay_direct,sagepay_server,squareup,amazon_login_pay,klarna_checkout,wechat_pay}*.php\">\r\n		  <operation>\r\n		   <search><![CDATA[$this->document->setTitle($this->language->get(\'heading_title\'));]]></search>\r\n		   <add position=\"after\"><![CDATA[\r\n			$data[\'heading_title\'] = $this->language->get(\'heading_title\');\r\n		   ]]></add>\r\n		  </operation>\r\n	</file>\r\n	<file path=\"catalog/controller/information/{sitemap,contact}*.php\">\r\n	  <operation>\r\n	   <search><![CDATA[$this->document->setTitle($this->language->get(\'heading_title\'));]]></search>\r\n	   <add position=\"after\"><![CDATA[\r\n		$data[\'heading_title\'] = $this->language->get(\'heading_title\');\r\n	   ]]></add>\r\n	  </operation>\r\n	 </file>\r\n	 <file path=\"catalog/controller/account/{address,download,order,recurring,return,reward,transaction,wishlist}*.php\">\r\n		<operation>\r\n			<search><![CDATA[$this->document->setTitle($this->language->get(\'heading_title\'));]]></search>\r\n			<add position=\"after\"><![CDATA[\r\n				$data[\'text_empty\'] = $this->language->get(\'text_empty\');\r\n			]]></add>\r\n		</operation>\r\n	</file>\r\n	<file path=\"catalog/controller/checkout/cart.php\">\r\n		<operation>\r\n			<search><![CDATA[$this->document->setTitle($this->language->get(\'heading_title\'));]]></search>\r\n			<add position=\"after\"><![CDATA[\r\n				$data[\'text_empty\'] = $this->language->get(\'text_empty\');\r\n			]]></add>\r\n		</operation>\r\n	</file>\r\n	<file path=\"catalog/controller/common/cart.php\">\r\n		<operation>\r\n			<search><![CDATA[$this->document->setTitle($this->language->get(\'heading_title\'));]]></search>\r\n			<add position=\"after\"><![CDATA[\r\n				$data[\'text_empty\'] = $this->language->get(\'text_empty\');\r\n			]]></add>\r\n		</operation>\r\n	</file>\r\n	<file path=\"catalog/controller/extension/credit_card/{sagepay_direct,sagepay_server}*.php\">\r\n		<operation>\r\n			<search><![CDATA[$this->document->setTitle($this->language->get(\'heading_title\'));]]></search>\r\n			<add position=\"after\"><![CDATA[\r\n				$data[\'text_empty\'] = $this->language->get(\'text_empty\');\r\n			]]></add>\r\n		</operation>\r\n	</file>\r\n	\r\n	<file path=\"catalog/controller/product/{category,search}*.php\">\r\n		<operation>\r\n			<search><![CDATA[$data[\'breadcrumbs\'] = array();]]></search>\r\n			<add position=\"before\"><![CDATA[\r\n				$data[\'text_empty\'] = $this->language->get(\'text_empty\');\r\n			]]></add>\r\n		</operation>\r\n	</file>\r\n	\r\n	<file path=\"catalog/controller/product/{category,compare,manufacturer,special}*.php\">\r\n		<operation>\r\n			<search><![CDATA[$this->document->setTitle($this->language->get(\'heading_title\'));]]></search>\r\n			<add position=\"after\"><![CDATA[\r\n				$data[\'text_empty\'] = $this->language->get(\'text_empty\');\r\n			]]></add>\r\n		</operation>\r\n	</file>\r\n	<file path=\"admin/controller/design/layout.php\">\r\n		<operation>\r\n			<search><![CDATA[$this->load->language(\'design/layout\');]]></search>\r\n			<add position=\"after\"><![CDATA[\r\n				$data[\'heading_title\'] = $this->language->get(\'heading_title\');\r\n			]]></add>\r\n		</operation>\r\n		<operation>\r\n			<search><![CDATA[protected function getForm() {]]></search>\r\n			<add position=\"after\"><![CDATA[\r\n				$this->load->language(\'design/layout\');\r\n				$data[\'heading_title\'] = $this->language->get(\'heading_title\');\r\n			]]></add>\r\n		</operation>\r\n		\r\n	</file>\r\n	\r\n	<file path=\"catalog/controller/account/voucher.php\">\r\n		<operation>\r\n			<search><![CDATA[$this->load->language(\'account/voucher\');]]></search>\r\n			<add position=\"after\"><![CDATA[\r\n				$data[\'text_agree\'] = $this->language->get(\'text_agree\');\r\n			]]></add>\r\n		</operation>\r\n	</file>\r\n</modification>', 1, '2017-11-21 15:10:46');
-- --------------------------------------------------------
--
-- Table structure for table `oc_module`
--
DROP TABLE IF EXISTS `oc_module`;
CREATE TABLE `oc_module` (
  `module_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `code` varchar(32) NOT NULL,
  `setting` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
-- Dumping data for table `oc_module`
--
INSERT INTO `oc_module` (`module_id`, `name`, `code`, `setting`) VALUES
(32, 'Layout Homepage 01', 'oc_page_builder', '{\"name\":\"Layout Homepage 01\",\"status\":\"1\",\"widget\":[{\"class\":\"full-width\",\"main_cols\":[{\"format\":\"12\",\"sub_rows\":[{\"sub_cols\":[{\"info\":{\"module\":[{\"code\":\"ocajaxlogin\",\"name\":\"OC Ajax Login\",\"url\":\"http:\\/\\/tt_mimosa.com\\/admin\\/index.php?route=extension\\/module\\/ocajaxlogin&amp;user_token=pV5WsoAx9b4nS2pf5IH5tYgum8ZxSYyM\"},{\"code\":\"newslettersubscribe.39\",\"name\":\"OC Newsletter &gt; Newsletter Popup\",\"url\":\"http:\\/\\/digitech1.com\\/admin\\/index.php?route=extension\\/module\\/newslettersubscribe&amp;user_token=hbJ51vlrkvkgYdFHGLssAcLb8A7hOzea&amp;module_id=39\"}]},\"format\":\"12\"}]}]}]},{\"class\":\"full-width\",\"main_cols\":[{\"format\":\"12\",\"sub_rows\":[{\"sub_cols\":[{\"info\":{\"module\":[{\"code\":\"occmsblock.115\",\"name\":\"OC CMS Block &gt; Static Welcome\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/occmsblock&amp;user_token=Aww4gXjrpMX5mfIyvlIs79uCYvBnkZvO&amp;module_id=115\"}]},\"format\":\"12\"}]}]}]},{\"class\":\"\",\"main_cols\":[{\"format\":\"12\",\"sub_rows\":[{\"sub_cols\":[{\"info\":{\"module\":[{\"code\":\"occmsblock.33\",\"name\":\"OC CMS Block &gt; Static Top Aero1\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/occmsblock&amp;user_token=XNteH7q4H3gF4v9hVuwmdBjGz3oJsnGN&amp;module_id=33\"},{\"code\":\"ocproduct.117\",\"name\":\"OC Products &gt; Featured products 1\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/ocproduct&amp;user_token=XNteH7q4H3gF4v9hVuwmdBjGz3oJsnGN&amp;module_id=117\"}]},\"format\":\"12\"}]}]}]},{\"class\":\"full-width\",\"main_cols\":[{\"format\":\"12\",\"sub_rows\":[{\"sub_cols\":[{\"info\":{\"module\":[{\"code\":\"octestimonial.116\",\"name\":\"OC Testimonials &gt; Testimonial\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/octestimonial&amp;user_token=XNteH7q4H3gF4v9hVuwmdBjGz3oJsnGN&amp;module_id=116\"}]},\"format\":\"12\"}]}]}]},{\"class\":\"\",\"main_cols\":[{\"format\":\"12\",\"sub_rows\":[{\"sub_cols\":[{\"info\":{\"module\":[{\"code\":\"octabproducts.45\",\"name\":\"OC Tab Products &gt; Tabs Categories 1\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/octabproducts&amp;user_token=XNteH7q4H3gF4v9hVuwmdBjGz3oJsnGN&amp;module_id=45\"}]},\"format\":\"12\"}]}]}]},{\"class\":\"full-width\",\"main_cols\":[{\"format\":\"12\",\"sub_rows\":[{\"sub_cols\":[{\"info\":{\"module\":[{\"code\":\"occmsblock.79\",\"name\":\"OC CMS Block &gt; Static Bottom Aero1\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/occmsblock&amp;user_token=XNteH7q4H3gF4v9hVuwmdBjGz3oJsnGN&amp;module_id=79\"}]},\"format\":\"12\"}]}]}]},{\"class\":\"corporate-full\",\"main_cols\":[{\"format\":\"12\",\"sub_rows\":[{\"sub_cols\":[{\"info\":{\"module\":[{\"code\":\"occmsblock.62\",\"name\":\"OC CMS Block &gt; Corporate\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/occmsblock&amp;user_token=XNteH7q4H3gF4v9hVuwmdBjGz3oJsnGN&amp;module_id=62\"}]},\"format\":\"12\"}]}]}]},{\"class\":\"\",\"main_cols\":[{\"format\":\"12\",\"sub_rows\":[{\"sub_cols\":[{\"info\":{\"module\":[{\"code\":\"ocblog.89\",\"name\":\"OC Blog &gt; Blog 1\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/ocblog&amp;user_token=XNteH7q4H3gF4v9hVuwmdBjGz3oJsnGN&amp;module_id=89\"}]},\"format\":\"12\"}]}]}]},{\"class\":\"\",\"main_cols\":[{\"format\":\"12\",\"sub_rows\":[{\"sub_cols\":[{\"info\":{\"module\":[{\"code\":\"newslettersubscribe.40\",\"name\":\"OC Newsletter &gt; Newsletter\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/newslettersubscribe&amp;user_token=AxyORO1vkCXSLYUBD1gJLGx0Dv7sL9Ej&amp;module_id=40\"}]},\"format\":\"12\"}]}]}]}]}'),
(118, 'Social', 'occmsblock', '{\"name\":\"Social\",\"cmsblock_id\":\"52\",\"status\":\"1\"}'),
(33, 'Static Top Aero1', 'occmsblock', '{\"name\":\"Static Top Aero1\",\"cmsblock_id\":\"20\",\"status\":\"1\"}'),
(36, 'Payment', 'occmsblock', '{\"name\":\"Payment\",\"cmsblock_id\":\"23\",\"status\":\"1\"}'),
(115, 'Static Welcome', 'occmsblock', '{\"name\":\"Static Welcome\",\"cmsblock_id\":\"51\",\"status\":\"1\"}'),
(39, 'Newsletter Popup', 'newslettersubscribe', '{\"name\":\"Newsletter Popup\",\"popup\":\"1\",\"status\":\"1\",\"newslettersubscribe_unsubscribe\":\"1\",\"newslettersubscribe_mail_status\":\"1\",\"newslettersubscribe_thickbox\":\"1\",\"newslettersubscribe_registered\":\"1\",\"store_id\":\"0\",\"to\":\"sendall\",\"customer_group_id\":\"1\",\"customers\":\"\",\"affiliates\":\"\",\"products\":\"\",\"subject\":\"\",\"message\":\"\"}'),
(40, 'Newsletter', 'newslettersubscribe', '{\"name\":\"Newsletter\",\"popup\":\"0\",\"status\":\"1\",\"newslettersubscribe_unsubscribe\":\"1\",\"newslettersubscribe_mail_status\":\"1\",\"newslettersubscribe_thickbox\":\"1\",\"newslettersubscribe_registered\":\"1\",\"store_id\":\"0\",\"to\":\"sendall\",\"customer_group_id\":\"1\",\"customers\":\"\",\"affiliates\":\"\",\"products\":\"\",\"subject\":\"\",\"message\":\"\"}'),
(41, 'Home slideshow 1', 'ocslideshow', '{\"name\":\"Home slideshow 1\",\"status\":\"1\",\"banner\":\"18\",\"width\":\"1920\",\"height\":\"846\"}'),
(43, 'Horizontal Mega Menu 1', 'ochozmegamenu', '{\"name\":\"Horizontal Mega Menu 1\",\"status\":\"1\",\"hhome\":\"1\",\"sticky\":\"1\",\"hdepth\":\" 5 \",\"hlevel\":\" 4 \",\"hactive\":\"CAT251,CMS3,CMS4,LINK42\",\"mobile\":\"1\"}'),
(45, 'Tabs Categories 1', 'octabproducts', '{\"name\":\"Tabs Categories 1\",\"status\":\"1\",\"title_lang\":{\"en-gb\":{\"title\":\"our products\"},\"de-de\":{\"title\":\"our products\"}},\"class\":\"tabs-category-slider\",\"module_description\":{\"1\":{\"description\":\"&lt;p&gt;Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum.&lt;\\/p&gt;\"},\"5\":{\"description\":\"\"}},\"type\":\"0\",\"slider\":\"1\",\"items\":\"4\",\"row\":\"1\",\"loop\":\"0\",\"margin\":\"30\",\"auto\":\"0\",\"time\":\"1000\",\"speed\":\"1000\",\"navigation\":\"1\",\"pagination\":\"0\",\"desktop\":\"4\",\"tablet\":\"3\",\"mobile\":\"2\",\"smobile\":\"2\",\"nrow\":\"1\",\"showcart\":\"1\",\"showwishlist\":\"1\",\"showcompare\":\"1\",\"showquickview\":\"1\",\"description\":\"0\",\"countdown\":\"0\",\"rotator\":\"1\",\"newlabel\":\"0\",\"salelabel\":\"1\",\"limit\":\"16\",\"width\":\"600\",\"height\":\"600\",\"octab\":[{\"tab_name\":{\"en-gb\":{\"title\":\"Mirrors\"},\"de-de\":{\"title\":\"Mirrors\"}},\"option\":\"1\",\"productall\":\"\",\"cate_name\":\"Shop\\u00a0\\u00a0&gt;\\u00a0\\u00a0Mirrors\",\"cate_id\":\"41\",\"productfrom\":\"1\",\"productcate\":\"\",\"input_specific_product\":\"0\",\"autoproduct\":\"0\"},{\"tab_name\":{\"en-gb\":{\"title\":\"Sounds\"},\"de-de\":{\"title\":\"Sounds\"}},\"option\":\"1\",\"productall\":\"\",\"cate_name\":\"Shop\\u00a0\\u00a0&gt;\\u00a0\\u00a0Sounds\",\"cate_id\":\"30\",\"productfrom\":\"1\",\"productcate\":\"\",\"input_specific_product\":\"0\",\"autoproduct\":\"0\"},{\"tab_name\":{\"en-gb\":{\"title\":\"Tires\"},\"de-de\":{\"title\":\"Tires\"}},\"option\":\"1\",\"productall\":\"\",\"cate_name\":\"Shop\\u00a0\\u00a0&gt;\\u00a0\\u00a0Tires\",\"cate_id\":\"1\",\"productfrom\":\"1\",\"productcate\":\"\",\"input_specific_product\":\"0\",\"autoproduct\":\"0\"},{\"tab_name\":{\"en-gb\":{\"title\":\"Steering wheels\"},\"de-de\":{\"title\":\"Steering wheels\"}},\"option\":\"1\",\"productall\":\"\",\"cate_name\":\"Shop\\u00a0\\u00a0&gt;\\u00a0\\u00a0Steering wheels\",\"cate_id\":\"17\",\"productfrom\":\"1\",\"productcate\":\"\",\"input_specific_product\":\"0\",\"autoproduct\":\"0\"}]}'),
(117, 'Featured products 1', 'ocproduct', '{\"name\":\"Featured products 1\",\"status\":\"1\",\"option\":\"0\",\"product\":\"\",\"cate_name\":\"Biker Jackets\",\"cate_id\":\"20\",\"productfrom\":\"0\",\"productcate\":\"\",\"input_specific_product\":\"0\",\"autoproduct\":\"0\",\"title_lang\":{\"en-gb\":{\"title\":\"Featured products\"},\"de-de\":{\"title\":\"Featured products\"}},\"class\":\"large-product\",\"module_description\":{\"1\":{\"description\":\"&lt;p&gt;Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum.&lt;\\/p&gt;\"},\"5\":{\"description\":\"\"}},\"type\":\"0\",\"slider\":\"1\",\"items\":\"3\",\"auto\":\"0\",\"time\":\"1000\",\"speed\":\"1000\",\"row\":\"1\",\"loop\":\"0\",\"margin\":\"30\",\"navigation\":\"0\",\"pagination\":\"0\",\"desktop\":\"3\",\"tablet\":\"2\",\"mobile\":\"2\",\"smobile\":\"2\",\"nrow\":\"1\",\"showcart\":\"1\",\"showwishlist\":\"1\",\"showcompare\":\"1\",\"showquickview\":\"1\",\"description\":\"1\",\"countdown\":\"0\",\"rotator\":\"1\",\"newlabel\":\"0\",\"salelabel\":\"1\",\"limit\":\"12\",\"width\":\"600\",\"height\":\"600\"}'),
(48, 'Layout Homepage 02', 'oc_page_builder', '{\"name\":\"Layout Homepage 02\",\"status\":\"1\",\"widget\":[{\"class\":\"full-width\",\"main_cols\":[{\"format\":\"12\",\"sub_rows\":[{\"sub_cols\":[{\"info\":{\"module\":[{\"code\":\"ocajaxlogin\",\"name\":\"OC Ajax Login\",\"url\":\"http:\\/\\/tt_mimosa.com\\/admin\\/index.php?route=extension\\/module\\/ocajaxlogin&amp;user_token=JTpdZxghacDqM3vT9ZusZ55msurHFXmp\"},{\"code\":\"newslettersubscribe.39\",\"name\":\"OC Newsletter &gt; Newsletter Popup\",\"url\":\"http:\\/\\/tt_mimosa.com\\/admin\\/index.php?route=extension\\/module\\/newslettersubscribe&amp;user_token=JTpdZxghacDqM3vT9ZusZ55msurHFXmp&amp;module_id=39\"},{\"code\":\"ocslideshow.49\",\"name\":\"OC Slide show &gt; Home slideshow 2\",\"url\":\"http:\\/\\/tt_mimosa.com\\/admin\\/index.php?route=extension\\/module\\/ocslideshow&amp;user_token=JTpdZxghacDqM3vT9ZusZ55msurHFXmp&amp;module_id=49\"}]},\"format\":\"12\"}]}]}]},{\"class\":\"\",\"main_cols\":[{\"format\":\"12\",\"sub_rows\":[{\"sub_cols\":[{\"info\":{\"module\":[{\"code\":\"occmsblock.33\",\"name\":\"OC CMS Block &gt; Static Top Aero1\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/occmsblock&amp;user_token=1e7QrgpGh6baywWBBabbswG1VzVNP419&amp;module_id=33\"},{\"code\":\"octabproducts.45\",\"name\":\"OC Tab Products &gt; Tabs Categories 1\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/octabproducts&amp;user_token=1e7QrgpGh6baywWBBabbswG1VzVNP419&amp;module_id=45\"}]},\"format\":\"12\"}]}]}]},{\"class\":\"full-width\",\"main_cols\":[{\"format\":\"12\",\"sub_rows\":[{\"sub_cols\":[{\"info\":{\"module\":[{\"code\":\"octestimonial.116\",\"name\":\"OC Testimonials &gt; Testimonial\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/octestimonial&amp;user_token=1e7QrgpGh6baywWBBabbswG1VzVNP419&amp;module_id=116\"}]},\"format\":\"12\"}]}]}]},{\"class\":\"fluid-width\",\"main_cols\":[{\"format\":\"12\",\"sub_rows\":[{\"sub_cols\":[{\"info\":{\"module\":[{\"code\":\"ocproduct.119\",\"name\":\"OC Products &gt; New products 2\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/ocproduct&amp;user_token=1e7QrgpGh6baywWBBabbswG1VzVNP419&amp;module_id=119\"}]},\"format\":\"12\"}]}]}]},{\"class\":\"full-width\",\"main_cols\":[{\"format\":\"12\",\"sub_rows\":[{\"sub_cols\":[{\"info\":{\"module\":[{\"code\":\"occmsblock.79\",\"name\":\"OC CMS Block &gt; Static Bottom Aero1\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/occmsblock&amp;user_token=1e7QrgpGh6baywWBBabbswG1VzVNP419&amp;module_id=79\"}]},\"format\":\"12\"}]}]}]},{\"class\":\"corporate-full\",\"main_cols\":[{\"format\":\"12\",\"sub_rows\":[{\"sub_cols\":[{\"info\":{\"module\":[{\"code\":\"occmsblock.62\",\"name\":\"OC CMS Block &gt; Corporate\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/occmsblock&amp;user_token=1e7QrgpGh6baywWBBabbswG1VzVNP419&amp;module_id=62\"}]},\"format\":\"12\"}]}]}]},{\"class\":\"\",\"main_cols\":[{\"format\":\"12\",\"sub_rows\":[{\"sub_cols\":[{\"info\":{\"module\":[{\"code\":\"ocblog.89\",\"name\":\"OC Blog &gt; Blog 1\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/ocblog&amp;user_token=1e7QrgpGh6baywWBBabbswG1VzVNP419&amp;module_id=89\"},{\"code\":\"newslettersubscribe.40\",\"name\":\"OC Newsletter &gt; Newsletter\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/newslettersubscribe&amp;user_token=1e7QrgpGh6baywWBBabbswG1VzVNP419&amp;module_id=40\"}]},\"format\":\"12\"}]}]}]}]}'),
(49, 'Home slideshow 2', 'ocslideshow', '{\"name\":\"Home slideshow 2\",\"status\":\"1\",\"banner\":\"19\",\"width\":\"1920\",\"height\":\"933\"}'),
(53, 'Layout Homepage 03', 'oc_page_builder', '{\"name\":\"Layout Homepage 03\",\"status\":\"1\",\"widget\":[{\"class\":\"slider-group full-width\",\"main_cols\":[{\"format\":\"12\",\"sub_rows\":[{\"sub_cols\":[{\"info\":{\"module\":[{\"code\":\"ocajaxlogin\",\"name\":\"OC Ajax Login\",\"url\":\"http:\\/\\/tt_mimosa.com\\/admin\\/index.php?route=extension\\/module\\/ocajaxlogin&amp;user_token=d01XJrsDs0kRU4rN0MQ3LdjJUxHkQcS4\"},{\"code\":\"newslettersubscribe.39\",\"name\":\"OC Newsletter &gt; Newsletter Popup\",\"url\":\"http:\\/\\/tt_mimosa.com\\/admin\\/index.php?route=extension\\/module\\/newslettersubscribe&amp;user_token=d01XJrsDs0kRU4rN0MQ3LdjJUxHkQcS4&amp;module_id=39\"},{\"code\":\"ocslideshow.55\",\"name\":\"OC Slide show &gt; Home slideshow 3\",\"url\":\"http:\\/\\/tt_mimosa.com\\/admin\\/index.php?route=extension\\/module\\/ocslideshow&amp;user_token=d01XJrsDs0kRU4rN0MQ3LdjJUxHkQcS4&amp;module_id=55\"},{\"code\":\"occmsblock.62\",\"name\":\"OC CMS Block &gt; Corporate\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/occmsblock&amp;user_token=60jB5sIKuKeYptm0rHCEtGZPQbWz0kTw&amp;module_id=62\"}]},\"format\":\"12\"}]}]}]},{\"class\":\"\",\"main_cols\":[{\"format\":\"12\",\"sub_rows\":[{\"sub_cols\":[{\"info\":{\"module\":[{\"code\":\"occmsblock.33\",\"name\":\"OC CMS Block &gt; Static Top Aero1\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/occmsblock&amp;user_token=60jB5sIKuKeYptm0rHCEtGZPQbWz0kTw&amp;module_id=33\"},{\"code\":\"octabproducts.45\",\"name\":\"OC Tab Products &gt; Tabs Categories 1\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/octabproducts&amp;user_token=60jB5sIKuKeYptm0rHCEtGZPQbWz0kTw&amp;module_id=45\"}]},\"format\":\"12\"}]}]}]},{\"class\":\"bestseller-full\",\"main_cols\":[{\"format\":\"12\",\"sub_rows\":[{\"sub_cols\":[{\"info\":{\"module\":[{\"code\":\"ocproduct.120\",\"name\":\"OC Products &gt; Bestseller Products 2\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/ocproduct&amp;user_token=60jB5sIKuKeYptm0rHCEtGZPQbWz0kTw&amp;module_id=120\"}]},\"format\":\"12\"}]}]}]},{\"class\":\"fluid-width\",\"main_cols\":[{\"format\":\"12\",\"sub_rows\":[{\"sub_cols\":[{\"info\":{\"module\":[{\"code\":\"ocproduct.119\",\"name\":\"OC Products &gt; New products 2\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/ocproduct&amp;user_token=60jB5sIKuKeYptm0rHCEtGZPQbWz0kTw&amp;module_id=119\"}]},\"format\":\"12\"}]}]}]},{\"class\":\"\",\"main_cols\":[{\"format\":\"12\",\"sub_rows\":[{\"sub_cols\":[{\"info\":{\"module\":[{\"code\":\"occmsblock.121\",\"name\":\"OC CMS Block &gt; Static Middle Aero3\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/occmsblock&amp;user_token=60jB5sIKuKeYptm0rHCEtGZPQbWz0kTw&amp;module_id=121\"},{\"code\":\"ocblog.89\",\"name\":\"OC Blog &gt; Blog 1\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/ocblog&amp;user_token=60jB5sIKuKeYptm0rHCEtGZPQbWz0kTw&amp;module_id=89\"},{\"code\":\"newslettersubscribe.40\",\"name\":\"OC Newsletter &gt; Newsletter\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/newslettersubscribe&amp;user_token=60jB5sIKuKeYptm0rHCEtGZPQbWz0kTw&amp;module_id=40\"}]},\"format\":\"12\"}]}]}]}]}'),
(55, 'Home slideshow 3', 'ocslideshow', '{\"name\":\"Home slideshow 3\",\"status\":\"1\",\"banner\":\"20\",\"width\":\"1920\",\"height\":\"943\"}'),
(56, 'Home slideshow 4', 'ocslideshow', '{\"name\":\"Home slideshow 4\",\"status\":\"1\",\"banner\":\"21\",\"width\":\"1920\",\"height\":\"943\"}'),
(58, 'Layout Homepage 04', 'oc_page_builder', '{\"name\":\"Layout Homepage 04\",\"status\":\"1\",\"widget\":[{\"class\":\"slider-group full-width\",\"main_cols\":[{\"format\":\"12\",\"sub_rows\":[{\"sub_cols\":[{\"info\":{\"module\":[{\"code\":\"ocajaxlogin\",\"name\":\"OC Ajax Login\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/ocajaxlogin&amp;user_token=O8HwYLvbTubkbUY1ZUiZHXWS6QJvO1TF\"},{\"code\":\"newslettersubscribe.39\",\"name\":\"OC Newsletter &gt; Newsletter Popup\",\"url\":\"http:\\/\\/tt_mimosa.com\\/admin\\/index.php?route=extension\\/module\\/newslettersubscribe&amp;user_token=B8NA7TZvGegvK5ov6i4gI7nxLfxuUO0Z&amp;module_id=39\"},{\"code\":\"ocslideshow.56\",\"name\":\"OC Slide show &gt; Home slideshow 4\",\"url\":\"http:\\/\\/nego1.com\\/admin\\/index.php?route=extension\\/module\\/ocslideshow&amp;user_token=liC4JKKvD3lBhQzfgzUbrtMgk2gQr1JF&amp;module_id=56\"},{\"code\":\"occmsblock.62\",\"name\":\"OC CMS Block &gt; Corporate\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/occmsblock&amp;user_token=O8HwYLvbTubkbUY1ZUiZHXWS6QJvO1TF&amp;module_id=62\"}]},\"format\":\"12\"}]}]}]},{\"class\":\"\",\"main_cols\":[{\"format\":\"12\",\"sub_rows\":[{\"sub_cols\":[{\"info\":{\"module\":[{\"code\":\"occmsblock.122\",\"name\":\"OC CMS Block &gt; Static Top Aero4\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/occmsblock&amp;user_token=O8HwYLvbTubkbUY1ZUiZHXWS6QJvO1TF&amp;module_id=122\"},{\"code\":\"octabproducts.45\",\"name\":\"OC Tab Products &gt; Tabs Categories 1\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/octabproducts&amp;user_token=O8HwYLvbTubkbUY1ZUiZHXWS6QJvO1TF&amp;module_id=45\"},{\"code\":\"occmsblock.121\",\"name\":\"OC CMS Block &gt; Static Middle Aero3\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/occmsblock&amp;user_token=O8HwYLvbTubkbUY1ZUiZHXWS6QJvO1TF&amp;module_id=121\"}]},\"format\":\"12\"}]}]}]},{\"class\":\"fluid-width\",\"main_cols\":[{\"format\":\"12\",\"sub_rows\":[{\"sub_cols\":[{\"info\":{\"module\":[{\"code\":\"ocproduct.119\",\"name\":\"OC Products &gt; New products 2\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/ocproduct&amp;user_token=O8HwYLvbTubkbUY1ZUiZHXWS6QJvO1TF&amp;module_id=119\"}]},\"format\":\"12\"}]}]}]},{\"class\":\"\",\"main_cols\":[{\"format\":\"12\",\"sub_rows\":[{\"sub_cols\":[{\"info\":{\"module\":[{\"code\":\"ocblog.89\",\"name\":\"OC Blog &gt; Blog 1\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/ocblog&amp;user_token=O8HwYLvbTubkbUY1ZUiZHXWS6QJvO1TF&amp;module_id=89\"},{\"code\":\"newslettersubscribe.40\",\"name\":\"OC Newsletter &gt; Newsletter\",\"url\":\"http:\\/\\/aero1.com\\/admin\\/index.php?route=extension\\/module\\/newslettersubscribe&amp;user_token=O8HwYLvbTubkbUY1ZUiZHXWS6QJvO1TF&amp;module_id=40\"}]},\"format\":\"12\"}]}]}]}]}'),
(61, 'Footer Static', 'occmsblock', '{\"name\":\"Footer Static\",\"cmsblock_id\":\"30\",\"status\":\"1\"}'),
(62, 'Corporate', 'occmsblock', '{\"name\":\"Corporate\",\"cmsblock_id\":\"32\",\"status\":\"1\"}'),
(120, 'Bestseller Products 2', 'ocproduct', '{\"name\":\"Bestseller Products 2\",\"status\":\"1\",\"option\":\"2\",\"product\":\"\",\"cate_name\":\"Biker Jackets\",\"cate_id\":\"20\",\"productfrom\":\"0\",\"productcate\":\"\",\"input_specific_product\":\"0\",\"autoproduct\":\"2\",\"title_lang\":{\"en-gb\":{\"title\":\"Best seller products\"},\"de-de\":{\"title\":\"Best seller products\"}},\"class\":\"ocbestsellerproducts\",\"module_description\":{\"1\":{\"description\":\"&lt;p&gt;Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum.&lt;\\/p&gt;\"},\"5\":{\"description\":\"\"}},\"type\":\"1\",\"slider\":\"1\",\"items\":\"3\",\"auto\":\"0\",\"time\":\"1000\",\"speed\":\"1000\",\"row\":\"2\",\"loop\":\"0\",\"margin\":\"30\",\"navigation\":\"0\",\"pagination\":\"1\",\"desktop\":\"3\",\"tablet\":\"2\",\"mobile\":\"2\",\"smobile\":\"2\",\"nrow\":\"1\",\"showcart\":\"0\",\"showwishlist\":\"0\",\"showcompare\":\"0\",\"showquickview\":\"0\",\"description\":\"0\",\"countdown\":\"0\",\"rotator\":\"0\",\"newlabel\":\"0\",\"salelabel\":\"0\",\"limit\":\"12\",\"width\":\"600\",\"height\":\"600\"}'),
(121, 'Static Middle Aero3', 'occmsblock', '{\"name\":\"Static Middle Aero3\",\"cmsblock_id\":\"53\",\"status\":\"1\"}'),
(122, 'Static Top Aero4', 'occmsblock', '{\"name\":\"Static Top Aero4\",\"cmsblock_id\":\"54\",\"status\":\"1\"}'),
(116, 'Testimonial', 'octestimonial', '{\"name\":\"Testimonial\",\"status\":\"1\",\"width\":\"64\",\"height\":\"64\",\"auto\":\"1\",\"items\":\"1\",\"limit\":\"10\",\"speed\":\"1000\",\"rows\":\"1\",\"navigation\":\"0\",\"pagination\":\"1\"}'),
(79, 'Static Bottom Aero1', 'occmsblock', '{\"name\":\"Static Bottom Aero1\",\"cmsblock_id\":\"36\",\"status\":\"1\"}'),
(119, 'New products 2', 'ocproduct', '{\"name\":\"New products 2\",\"status\":\"1\",\"option\":\"2\",\"product\":\"\",\"cate_name\":\"Biker Jackets\",\"cate_id\":\"20\",\"productfrom\":\"0\",\"productcate\":\"\",\"input_specific_product\":\"0\",\"autoproduct\":\"0\",\"title_lang\":{\"en-gb\":{\"title\":\"New arrivals\"},\"de-de\":{\"title\":\"New arrivals\"}},\"class\":\"\",\"module_description\":{\"1\":{\"description\":\"&lt;p&gt;Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum.&lt;\\/p&gt;\"},\"5\":{\"description\":\"\"}},\"type\":\"0\",\"slider\":\"1\",\"items\":\"6\",\"auto\":\"0\",\"time\":\"1000\",\"speed\":\"1000\",\"row\":\"1\",\"loop\":\"0\",\"margin\":\"30\",\"navigation\":\"0\",\"pagination\":\"0\",\"desktop\":\"5\",\"tablet\":\"4\",\"mobile\":\"3\",\"smobile\":\"2\",\"nrow\":\"1\",\"showcart\":\"1\",\"showwishlist\":\"1\",\"showcompare\":\"1\",\"showquickview\":\"1\",\"description\":\"0\",\"countdown\":\"0\",\"rotator\":\"1\",\"newlabel\":\"0\",\"salelabel\":\"1\",\"limit\":\"12\",\"width\":\"600\",\"height\":\"600\"}'),
(89, 'Blog 1', 'ocblog', '{\"name\":\"Blog 1\",\"status\":\"1\",\"list\":\"1\",\"width\":\"270\",\"height\":\"334\",\"auto\":\"0\",\"items\":\"2\",\"speed\":\"1000\",\"rows\":\"1\",\"navigation\":\"0\",\"pagination\":\"0\"}'),
(123, 'Static SideBar', 'occmsblock', '{\"name\":\"Static SideBar\",\"cmsblock_id\":\"55\",\"status\":\"1\"}');
-- --------------------------------------------------------
--
-- Table structure for table `oc_ocslideshow`
--
DROP TABLE IF EXISTS `oc_ocslideshow`;
CREATE TABLE `oc_ocslideshow` (
  `ocslideshow_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `auto` tinyint(1) DEFAULT NULL,
  `delay` int(11) DEFAULT NULL,
  `hover` tinyint(1) DEFAULT NULL,
  `nextback` tinyint(1) DEFAULT NULL,
  `contrl` tinyint(1) DEFAULT NULL,
  `effect` varchar(64) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
-- Dumping data for table `oc_ocslideshow`
--
INSERT INTO `oc_ocslideshow` (`ocslideshow_id`, `name`, `status`, `auto`, `delay`, `hover`, `nextback`, `contrl`, `effect`) VALUES
(18, 'Home slideshow 1', 1, 1, 5000, 1, 1, 1, '  random  '),
(19, 'Home slideshow 2', 1, 1, 5000, 1, 1, 1, '  random  '),
(20, 'Home slideshow 3', 1, 1, 5000, 1, 1, 1, '  random  '),
(21, 'Home slideshow 4', 1, 1, 5000, 1, 1, 1, '  random  ');
-- --------------------------------------------------------
--
-- Table structure for table `oc_ocslideshow_image`
--
DROP TABLE IF EXISTS `oc_ocslideshow_image`;
CREATE TABLE `oc_ocslideshow_image` (
  `ocslideshow_image_id` int(11) NOT NULL,
  `ocslideshow_id` int(11) NOT NULL,
  `link` varchar(255) NOT NULL,
  `type` int(11) NOT NULL,
  `banner_store` varchar(110) DEFAULT '0',
  `image` varchar(255) NOT NULL,
  `small_image` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
-- Dumping data for table `oc_ocslideshow_image`
--
INSERT INTO `oc_ocslideshow_image` (`ocslideshow_image_id`, `ocslideshow_id`, `link`, `type`, `banner_store`, `image`, `small_image`) VALUES
(362, 18, 'http://www.plazathemes.com/', 3, '0,0,0', 'catalog/slideshow/slider3-aero1.jpg', 'no_image.png'),
(361, 18, 'http://www.plazathemes.com/', 2, '0,0,0', 'catalog/slideshow/slider2-aero1.jpg', 'no_image.png'),
(364, 19, 'http://www.plazathemes.com/', 2, '1,0,1,0,1,0', 'catalog/slideshow/slider2-aero2.jpg', 'no_image.png'),
(365, 19, 'http://www.plazathemes.com/', 1, '1,0,1,0,1,0', 'catalog/slideshow/slider1-aero2.jpg', 'no_image.png'),
(371, 21, '#', 3, '3,0,3,0,3,0', 'catalog/slideshow/slider3-aero4.jpg', 'no_image.png'),
(370, 21, '#', 2, '3,0,3,0,3,0', 'catalog/slideshow/slider2-aero4.jpg', 'no_image.png'),
(369, 21, '#', 1, '3,0,3,0,3,0', 'catalog/slideshow/slider1-aero4.jpg', 'no_image.png'),
(363, 19, 'http://www.plazathemes.com/', 3, '1,0,1,0,1,0', 'catalog/slideshow/slider3-aero2.jpg', 'no_image.png'),
(360, 18, 'http://www.plazathemes.com/', 1, '0,0,0', 'catalog/slideshow/slider1-aero1.jpg', 'no_image.png'),
(366, 20, 'http://www.plazathemes.com/', 1, '2,0,2,0,2,0', 'catalog/slideshow/slider1-aero3.jpg', 'no_image.png'),
(367, 20, 'http://www.plazathemes.com/', 2, '2,0,2,0,2,0', 'catalog/slideshow/slider2-aero3.jpg', 'no_image.png'),
(368, 20, 'http://www.plazathemes.com/', 3, '2,0,2,0,2,0', 'catalog/slideshow/slider3-aero3.jpg', 'no_image.png');
-- --------------------------------------------------------
--
-- Table structure for table `oc_ocslideshow_image_description`
--
DROP TABLE IF EXISTS `oc_ocslideshow_image_description`;
CREATE TABLE `oc_ocslideshow_image_description` (
  `ocslideshow_image_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `ocslideshow_id` int(11) NOT NULL,
  `title` varchar(64) NOT NULL,
  `sub_title` varchar(64) DEFAULT NULL,
  `description` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
-- Dumping data for table `oc_ocslideshow_image_description`
--
INSERT INTO `oc_ocslideshow_image_description` (`ocslideshow_image_id`, `language_id`, `ocslideshow_id`, `title`, `sub_title`, `description`) VALUES
(368, 5, 20, 'Typi non habent claritatem insitam', 'World leading technology', '&lt;p&gt;Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium\r\nlectorum. Mirum est notare quam littera gothica.&lt;/p&gt;        '),
(362, 5, 18, '', '', '&lt;p&gt;&lt;br&gt;&lt;/p&gt;'),
(367, 5, 20, 'Typi non habent claritatem insitam', 'World leading technology', '&lt;p&gt;Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium\r\nlectorum. Mirum est notare quam littera gothica.&lt;/p&gt;        '),
(368, 1, 20, 'Typi non habent claritatem insitam', 'World leading technology', '&lt;p&gt;Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium\r\nlectorum. Mirum est notare quam littera gothica.&lt;/p&gt;        '),
(364, 5, 19, 'Typi non habent claritatem insitam', 'Explore the range', '&lt;p&gt;Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram.&lt;/p&gt;'),
(365, 5, 19, 'Typi non habent claritatem insitam', 'Speed up your car', '&lt;p&gt;Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram.&lt;/p&gt;'),
(365, 1, 19, 'Typi non habent claritatem insitam', 'Speed up your car', '&lt;p&gt;Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram.&lt;/p&gt;'),
(364, 1, 19, 'Typi non habent claritatem insitam', 'Explore the range', '&lt;p&gt;Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram.&lt;/p&gt;'),
(371, 5, 21, 'Typi non habent claritatem insitam', 'experience the power within', '&lt;p&gt;Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium\r\nlectorum. Mirum est notare quam littera gothica.&lt;/p&gt;'),
(370, 5, 21, 'Typi non habent claritatem insitam', 'World leading technology', '&lt;p&gt;Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium\r\nlectorum. Mirum est notare quam littera gothica.&lt;/p&gt;'),
(371, 1, 21, 'Typi non habent claritatem insitam', 'experience the power within', '&lt;p&gt;Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium\r\nlectorum. Mirum est notare quam littera gothica.&lt;/p&gt;'),
(363, 1, 19, 'Typi non habent claritatem insitam', 'Explore the range', '&lt;p&gt;Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram.&lt;/p&gt;'),
(363, 5, 19, 'Typi non habent claritatem insitam', 'Explore the range', '&lt;p&gt;Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram.&lt;/p&gt;'),
(367, 1, 20, 'Typi non habent claritatem insitam', 'World leading technology', '&lt;p&gt;Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium\r\nlectorum. Mirum est notare quam littera gothica.&lt;/p&gt;        '),
(362, 1, 18, '', '', '&lt;p&gt;&lt;br&gt;&lt;/p&gt;'),
(361, 5, 18, '', '', '&lt;p&gt;&lt;br&gt;&lt;/p&gt;'),
(360, 1, 18, '', '', '&lt;p&gt;&lt;br&gt;&lt;/p&gt;'),
(360, 5, 18, '', '', '&lt;p&gt;&lt;br&gt;&lt;/p&gt;'),
(361, 1, 18, '', '', '&lt;p&gt;&lt;br&gt;&lt;/p&gt;'),
(366, 1, 20, 'Typi non habent claritatem insitam', 'experience the power within', '&lt;p&gt;Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram.&lt;/p&gt;        '),
(366, 5, 20, 'Typi non habent claritatem insitam', 'experience the power within', '&lt;p&gt;Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram.&lt;/p&gt;        '),
(369, 1, 21, 'Typi non habent claritatem insitam', 'World leading technology', '&lt;p&gt;Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium\r\nlectorum. Mirum est notare quam littera gothica.&lt;/p&gt;'),
(370, 1, 21, 'Typi non habent claritatem insitam', 'World leading technology', '&lt;p&gt;Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium\r\nlectorum. Mirum est notare quam littera gothica.&lt;/p&gt;'),
(369, 5, 21, 'Typi non habent claritatem insitam', 'World leading technology', '&lt;p&gt;Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium\r\nlectorum. Mirum est notare quam littera gothica.&lt;/p&gt;');
-- --------------------------------------------------------
--
-- Table structure for table `oc_user_group`
--
DROP TABLE IF EXISTS `oc_user_group`;
CREATE TABLE `oc_user_group` (
  `user_group_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `permission` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
-- Dumping data for table `oc_user_group`
--
INSERT INTO `oc_user_group` (`user_group_id`, `name`, `permission`) VALUES
(1, 'Administrator', '{\"access\":[\"catalog\\/attribute\",\"catalog\\/attribute_group\",\"catalog\\/category\",\"catalog\\/download\",\"catalog\\/filter\",\"catalog\\/information\",\"catalog\\/manufacturer\",\"catalog\\/occategorythumbnail\",\"catalog\\/octestimonial\",\"catalog\\/option\",\"catalog\\/product\",\"catalog\\/recurring\",\"catalog\\/review\",\"common\\/column_left\",\"common\\/developer\",\"common\\/filemanager\",\"common\\/profile\",\"common\\/security\",\"customer\\/custom_field\",\"customer\\/customer\",\"customer\\/customer_approval\",\"customer\\/customer_group\",\"design\\/banner\",\"design\\/layout\",\"design\\/seo_url\",\"design\\/theme\",\"design\\/translation\",\"event\\/language\",\"event\\/statistics\",\"event\\/theme\",\"extension\\/analytics\\/google\",\"extension\\/captcha\\/basic\",\"extension\\/captcha\\/google\",\"extension\\/dashboard\\/activity\",\"extension\\/dashboard\\/chart\",\"extension\\/dashboard\\/customer\",\"extension\\/dashboard\\/map\",\"extension\\/dashboard\\/online\",\"extension\\/dashboard\\/order\",\"extension\\/dashboard\\/recent\",\"extension\\/dashboard\\/sale\",\"extension\\/extension\\/analytics\",\"extension\\/extension\\/captcha\",\"extension\\/extension\\/dashboard\",\"extension\\/extension\\/feed\",\"extension\\/extension\\/fraud\",\"extension\\/extension\\/menu\",\"extension\\/extension\\/module\",\"extension\\/extension\\/payment\",\"extension\\/extension\\/report\",\"extension\\/extension\\/shipping\",\"extension\\/extension\\/theme\",\"extension\\/extension\\/total\",\"extension\\/feed\\/google_base\",\"extension\\/feed\\/google_sitemap\",\"extension\\/feed\\/openbaypro\",\"extension\\/fraud\\/fraudlabspro\",\"extension\\/fraud\\/ip\",\"extension\\/fraud\\/maxmind\",\"extension\\/module\\/account\",\"extension\\/module\\/amazon_login\",\"extension\\/module\\/amazon_pay\",\"extension\\/module\\/banner\",\"extension\\/module\\/bestseller\",\"extension\\/module\\/carousel\",\"extension\\/module\\/category\",\"extension\\/module\\/divido_calculator\",\"extension\\/module\\/ebay_listing\",\"extension\\/module\\/featured\",\"extension\\/module\\/filter\",\"extension\\/module\\/google_hangouts\",\"extension\\/module\\/html\",\"extension\\/module\\/information\",\"extension\\/module\\/klarna_checkout_module\",\"extension\\/module\\/latest\",\"extension\\/module\\/laybuy_layout\",\"extension\\/module\\/newslettersubscribe\",\"extension\\/module\\/oc_page_builder\",\"extension\\/module\\/ocajaxlogin\",\"extension\\/module\\/ocblog\",\"extension\\/module\\/occmsblock\",\"extension\\/module\\/ocfeaturedcategory\",\"extension\\/module\\/ocfeaturedcategorytab\",\"extension\\/module\\/ochozmegamenu\",\"extension\\/module\\/oclayerednavigation\",\"extension\\/module\\/ocproduct\",\"extension\\/module\\/ocslideshow\",\"extension\\/module\\/octabproducts\",\"extension\\/module\\/octestimonial\",\"extension\\/module\\/octhemeoption\",\"extension\\/module\\/ocvermegamenu\",\"extension\\/module\\/pilibaba_button\",\"extension\\/module\\/pp_braintree_button\",\"extension\\/module\\/pp_button\",\"extension\\/module\\/pp_login\",\"extension\\/module\\/sagepay_direct_cards\",\"extension\\/module\\/sagepay_server_cards\",\"extension\\/module\\/slideshow\",\"extension\\/module\\/special\",\"extension\\/module\\/store\",\"extension\\/openbay\\/amazon\",\"extension\\/openbay\\/amazon_listing\",\"extension\\/openbay\\/amazon_product\",\"extension\\/openbay\\/amazonus\",\"extension\\/openbay\\/amazonus_listing\",\"extension\\/openbay\\/amazonus_product\",\"extension\\/openbay\\/ebay\",\"extension\\/openbay\\/ebay_profile\",\"extension\\/openbay\\/ebay_template\",\"extension\\/openbay\\/etsy\",\"extension\\/openbay\\/etsy_product\",\"extension\\/openbay\\/etsy_shipping\",\"extension\\/openbay\\/etsy_shop\",\"extension\\/openbay\\/fba\",\"extension\\/payment\\/alipay\",\"extension\\/payment\\/alipay_cross\",\"extension\\/payment\\/amazon_login_pay\",\"extension\\/payment\\/authorizenet_aim\",\"extension\\/payment\\/authorizenet_sim\",\"extension\\/payment\\/bank_transfer\",\"extension\\/payment\\/bluepay_hosted\",\"extension\\/payment\\/bluepay_redirect\",\"extension\\/payment\\/cardconnect\",\"extension\\/payment\\/cardinity\",\"extension\\/payment\\/cheque\",\"extension\\/payment\\/cod\",\"extension\\/payment\\/divido\",\"extension\\/payment\\/eway\",\"extension\\/payment\\/firstdata\",\"extension\\/payment\\/firstdata_remote\",\"extension\\/payment\\/free_checkout\",\"extension\\/payment\\/g2apay\",\"extension\\/payment\\/globalpay\",\"extension\\/payment\\/globalpay_remote\",\"extension\\/payment\\/klarna_account\",\"extension\\/payment\\/klarna_checkout\",\"extension\\/payment\\/klarna_invoice\",\"extension\\/payment\\/laybuy\",\"extension\\/payment\\/liqpay\",\"extension\\/payment\\/nochex\",\"extension\\/payment\\/paymate\",\"extension\\/payment\\/paypoint\",\"extension\\/payment\\/payza\",\"extension\\/payment\\/perpetual_payments\",\"extension\\/payment\\/pilibaba\",\"extension\\/payment\\/pp_braintree\",\"extension\\/payment\\/pp_express\",\"extension\\/payment\\/pp_payflow\",\"extension\\/payment\\/pp_payflow_iframe\",\"extension\\/payment\\/pp_pro\",\"extension\\/payment\\/pp_pro_iframe\",\"extension\\/payment\\/pp_standard\",\"extension\\/payment\\/realex\",\"extension\\/payment\\/realex_remote\",\"extension\\/payment\\/sagepay_direct\",\"extension\\/payment\\/sagepay_server\",\"extension\\/payment\\/sagepay_us\",\"extension\\/payment\\/securetrading_pp\",\"extension\\/payment\\/securetrading_ws\",\"extension\\/payment\\/skrill\",\"extension\\/payment\\/squareup\",\"extension\\/payment\\/twocheckout\",\"extension\\/payment\\/web_payment_software\",\"extension\\/payment\\/wechat_pay\",\"extension\\/payment\\/worldpay\",\"extension\\/report\\/customer_activity\",\"extension\\/report\\/customer_order\",\"extension\\/report\\/customer_reward\",\"extension\\/report\\/customer_search\",\"extension\\/report\\/customer_transaction\",\"extension\\/report\\/marketing\",\"extension\\/report\\/product_purchased\",\"extension\\/report\\/product_viewed\",\"extension\\/report\\/sale_coupon\",\"extension\\/report\\/sale_order\",\"extension\\/report\\/sale_return\",\"extension\\/report\\/sale_shipping\",\"extension\\/report\\/sale_tax\",\"extension\\/shipping\\/auspost\",\"extension\\/shipping\\/citylink\",\"extension\\/shipping\\/ec_ship\",\"extension\\/shipping\\/fedex\",\"extension\\/shipping\\/flat\",\"extension\\/shipping\\/free\",\"extension\\/shipping\\/item\",\"extension\\/shipping\\/parcelforce_48\",\"extension\\/shipping\\/pickup\",\"extension\\/shipping\\/royal_mail\",\"extension\\/shipping\\/ups\",\"extension\\/shipping\\/usps\",\"extension\\/shipping\\/weight\",\"extension\\/theme\\/default\",\"extension\\/total\\/coupon\",\"extension\\/total\\/credit\",\"extension\\/total\\/handling\",\"extension\\/total\\/klarna_fee\",\"extension\\/total\\/low_order_fee\",\"extension\\/total\\/reward\",\"extension\\/total\\/shipping\",\"extension\\/total\\/sub_total\",\"extension\\/total\\/tax\",\"extension\\/total\\/total\",\"extension\\/total\\/voucher\",\"localisation\\/country\",\"localisation\\/currency\",\"localisation\\/geo_zone\",\"localisation\\/language\",\"localisation\\/length_class\",\"localisation\\/location\",\"localisation\\/order_status\",\"localisation\\/return_action\",\"localisation\\/return_reason\",\"localisation\\/return_status\",\"localisation\\/stock_status\",\"localisation\\/tax_class\",\"localisation\\/tax_rate\",\"localisation\\/weight_class\",\"localisation\\/zone\",\"mail\\/affiliate\",\"mail\\/customer\",\"mail\\/forgotten\",\"mail\\/return\",\"mail\\/reward\",\"mail\\/transaction\",\"marketing\\/contact\",\"marketing\\/coupon\",\"marketing\\/marketing\",\"marketplace\\/api\",\"marketplace\\/event\",\"marketplace\\/extension\",\"marketplace\\/install\",\"marketplace\\/installer\",\"marketplace\\/marketplace\",\"marketplace\\/modification\",\"marketplace\\/openbay\",\"report\\/online\",\"report\\/report\",\"report\\/statistics\",\"sale\\/order\",\"sale\\/recurring\",\"sale\\/return\",\"sale\\/voucher\",\"sale\\/voucher_theme\",\"setting\\/setting\",\"setting\\/store\",\"startup\\/error\",\"startup\\/event\",\"startup\\/login\",\"startup\\/permission\",\"startup\\/router\",\"startup\\/sass\",\"startup\\/startup\",\"tool\\/backup\",\"tool\\/log\",\"tool\\/upload\",\"user\\/api\",\"user\\/user\",\"user\\/user_permission\",\"extension\\/module\\/ocblog\",\"blog\\/article\",\"blog\\/articlelist\",\"blog\\/config\",\"extension\\/module\\/ocinstagram\",\"extension\\/module\\/octestimonial\",\"catalog\\/octestimonial\"],\"modify\":[\"catalog\\/attribute\",\"catalog\\/attribute_group\",\"catalog\\/category\",\"catalog\\/download\",\"catalog\\/filter\",\"catalog\\/information\",\"catalog\\/manufacturer\",\"catalog\\/occategorythumbnail\",\"catalog\\/octestimonial\",\"catalog\\/option\",\"catalog\\/product\",\"catalog\\/recurring\",\"catalog\\/review\",\"common\\/column_left\",\"common\\/developer\",\"common\\/filemanager\",\"common\\/profile\",\"common\\/security\",\"customer\\/custom_field\",\"customer\\/customer\",\"customer\\/customer_approval\",\"customer\\/customer_group\",\"design\\/banner\",\"design\\/layout\",\"design\\/seo_url\",\"design\\/theme\",\"design\\/translation\",\"event\\/language\",\"event\\/statistics\",\"event\\/theme\",\"extension\\/analytics\\/google\",\"extension\\/captcha\\/basic\",\"extension\\/captcha\\/google\",\"extension\\/dashboard\\/activity\",\"extension\\/dashboard\\/chart\",\"extension\\/dashboard\\/customer\",\"extension\\/dashboard\\/map\",\"extension\\/dashboard\\/online\",\"extension\\/dashboard\\/order\",\"extension\\/dashboard\\/recent\",\"extension\\/dashboard\\/sale\",\"extension\\/extension\\/analytics\",\"extension\\/extension\\/captcha\",\"extension\\/extension\\/dashboard\",\"extension\\/extension\\/feed\",\"extension\\/extension\\/fraud\",\"extension\\/extension\\/menu\",\"extension\\/extension\\/module\",\"extension\\/extension\\/payment\",\"extension\\/extension\\/report\",\"extension\\/extension\\/shipping\",\"extension\\/extension\\/theme\",\"extension\\/extension\\/total\",\"extension\\/feed\\/google_base\",\"extension\\/feed\\/google_sitemap\",\"extension\\/feed\\/openbaypro\",\"extension\\/fraud\\/fraudlabspro\",\"extension\\/fraud\\/ip\",\"extension\\/fraud\\/maxmind\",\"extension\\/module\\/account\",\"extension\\/module\\/amazon_login\",\"extension\\/module\\/amazon_pay\",\"extension\\/module\\/banner\",\"extension\\/module\\/bestseller\",\"extension\\/module\\/carousel\",\"extension\\/module\\/category\",\"extension\\/module\\/divido_calculator\",\"extension\\/module\\/ebay_listing\",\"extension\\/module\\/featured\",\"extension\\/module\\/filter\",\"extension\\/module\\/google_hangouts\",\"extension\\/module\\/html\",\"extension\\/module\\/information\",\"extension\\/module\\/klarna_checkout_module\",\"extension\\/module\\/latest\",\"extension\\/module\\/laybuy_layout\",\"extension\\/module\\/newslettersubscribe\",\"extension\\/module\\/oc_page_builder\",\"extension\\/module\\/ocajaxlogin\",\"extension\\/module\\/ocblog\",\"extension\\/module\\/occmsblock\",\"extension\\/module\\/ocfeaturedcategory\",\"extension\\/module\\/ocfeaturedcategorytab\",\"extension\\/module\\/ochozmegamenu\",\"extension\\/module\\/oclayerednavigation\",\"extension\\/module\\/ocproduct\",\"extension\\/module\\/ocslideshow\",\"extension\\/module\\/octabproducts\",\"extension\\/module\\/octestimonial\",\"extension\\/module\\/octhemeoption\",\"extension\\/module\\/ocvermegamenu\",\"extension\\/module\\/pilibaba_button\",\"extension\\/module\\/pp_braintree_button\",\"extension\\/module\\/pp_button\",\"extension\\/module\\/pp_login\",\"extension\\/module\\/sagepay_direct_cards\",\"extension\\/module\\/sagepay_server_cards\",\"extension\\/module\\/slideshow\",\"extension\\/module\\/special\",\"extension\\/module\\/store\",\"extension\\/openbay\\/amazon\",\"extension\\/openbay\\/amazon_listing\",\"extension\\/openbay\\/amazon_product\",\"extension\\/openbay\\/amazonus\",\"extension\\/openbay\\/amazonus_listing\",\"extension\\/openbay\\/amazonus_product\",\"extension\\/openbay\\/ebay\",\"extension\\/openbay\\/ebay_profile\",\"extension\\/openbay\\/ebay_template\",\"extension\\/openbay\\/etsy\",\"extension\\/openbay\\/etsy_product\",\"extension\\/openbay\\/etsy_shipping\",\"extension\\/openbay\\/etsy_shop\",\"extension\\/openbay\\/fba\",\"extension\\/payment\\/alipay\",\"extension\\/payment\\/alipay_cross\",\"extension\\/payment\\/amazon_login_pay\",\"extension\\/payment\\/authorizenet_aim\",\"extension\\/payment\\/authorizenet_sim\",\"extension\\/payment\\/bank_transfer\",\"extension\\/payment\\/bluepay_hosted\",\"extension\\/payment\\/bluepay_redirect\",\"extension\\/payment\\/cardconnect\",\"extension\\/payment\\/cardinity\",\"extension\\/payment\\/cheque\",\"extension\\/payment\\/cod\",\"extension\\/payment\\/divido\",\"extension\\/payment\\/eway\",\"extension\\/payment\\/firstdata\",\"extension\\/payment\\/firstdata_remote\",\"extension\\/payment\\/free_checkout\",\"extension\\/payment\\/g2apay\",\"extension\\/payment\\/globalpay\",\"extension\\/payment\\/globalpay_remote\",\"extension\\/payment\\/klarna_account\",\"extension\\/payment\\/klarna_checkout\",\"extension\\/payment\\/klarna_invoice\",\"extension\\/payment\\/laybuy\",\"extension\\/payment\\/liqpay\",\"extension\\/payment\\/nochex\",\"extension\\/payment\\/paymate\",\"extension\\/payment\\/paypoint\",\"extension\\/payment\\/payza\",\"extension\\/payment\\/perpetual_payments\",\"extension\\/payment\\/pilibaba\",\"extension\\/payment\\/pp_braintree\",\"extension\\/payment\\/pp_express\",\"extension\\/payment\\/pp_payflow\",\"extension\\/payment\\/pp_payflow_iframe\",\"extension\\/payment\\/pp_pro\",\"extension\\/payment\\/pp_pro_iframe\",\"extension\\/payment\\/pp_standard\",\"extension\\/payment\\/realex\",\"extension\\/payment\\/realex_remote\",\"extension\\/payment\\/sagepay_direct\",\"extension\\/payment\\/sagepay_server\",\"extension\\/payment\\/sagepay_us\",\"extension\\/payment\\/securetrading_pp\",\"extension\\/payment\\/securetrading_ws\",\"extension\\/payment\\/skrill\",\"extension\\/payment\\/squareup\",\"extension\\/payment\\/twocheckout\",\"extension\\/payment\\/web_payment_software\",\"extension\\/payment\\/wechat_pay\",\"extension\\/payment\\/worldpay\",\"extension\\/report\\/customer_activity\",\"extension\\/report\\/customer_order\",\"extension\\/report\\/customer_reward\",\"extension\\/report\\/customer_search\",\"extension\\/report\\/customer_transaction\",\"extension\\/report\\/marketing\",\"extension\\/report\\/product_purchased\",\"extension\\/report\\/product_viewed\",\"extension\\/report\\/sale_coupon\",\"extension\\/report\\/sale_order\",\"extension\\/report\\/sale_return\",\"extension\\/report\\/sale_shipping\",\"extension\\/report\\/sale_tax\",\"extension\\/shipping\\/auspost\",\"extension\\/shipping\\/citylink\",\"extension\\/shipping\\/ec_ship\",\"extension\\/shipping\\/fedex\",\"extension\\/shipping\\/flat\",\"extension\\/shipping\\/free\",\"extension\\/shipping\\/item\",\"extension\\/shipping\\/parcelforce_48\",\"extension\\/shipping\\/pickup\",\"extension\\/shipping\\/royal_mail\",\"extension\\/shipping\\/ups\",\"extension\\/shipping\\/usps\",\"extension\\/shipping\\/weight\",\"extension\\/theme\\/default\",\"extension\\/total\\/coupon\",\"extension\\/total\\/credit\",\"extension\\/total\\/handling\",\"extension\\/total\\/klarna_fee\",\"extension\\/total\\/low_order_fee\",\"extension\\/total\\/reward\",\"extension\\/total\\/shipping\",\"extension\\/total\\/sub_total\",\"extension\\/total\\/tax\",\"extension\\/total\\/total\",\"extension\\/total\\/voucher\",\"localisation\\/country\",\"localisation\\/currency\",\"localisation\\/geo_zone\",\"localisation\\/language\",\"localisation\\/length_class\",\"localisation\\/location\",\"localisation\\/order_status\",\"localisation\\/return_action\",\"localisation\\/return_reason\",\"localisation\\/return_status\",\"localisation\\/stock_status\",\"localisation\\/tax_class\",\"localisation\\/tax_rate\",\"localisation\\/weight_class\",\"localisation\\/zone\",\"mail\\/affiliate\",\"mail\\/customer\",\"mail\\/forgotten\",\"mail\\/return\",\"mail\\/reward\",\"mail\\/transaction\",\"marketing\\/contact\",\"marketing\\/coupon\",\"marketing\\/marketing\",\"marketplace\\/api\",\"marketplace\\/event\",\"marketplace\\/extension\",\"marketplace\\/install\",\"marketplace\\/installer\",\"marketplace\\/marketplace\",\"marketplace\\/modification\",\"marketplace\\/openbay\",\"report\\/online\",\"report\\/report\",\"report\\/statistics\",\"sale\\/order\",\"sale\\/recurring\",\"sale\\/return\",\"sale\\/voucher\",\"sale\\/voucher_theme\",\"setting\\/setting\",\"setting\\/store\",\"startup\\/error\",\"startup\\/event\",\"startup\\/login\",\"startup\\/permission\",\"startup\\/router\",\"startup\\/sass\",\"startup\\/startup\",\"tool\\/backup\",\"tool\\/log\",\"tool\\/upload\",\"user\\/api\",\"user\\/user\",\"user\\/user_permission\",\"extension\\/module\\/ocblog\",\"blog\\/article\",\"blog\\/articlelist\",\"blog\\/config\",\"extension\\/module\\/ocinstagram\",\"extension\\/module\\/octestimonial\",\"catalog\\/octestimonial\"]}'),
(10, 'Demonstration', '');
INSERT INTO `oc_setting` (`store_id`, `code`, `key`, `value`, `serialized`) VALUES
(0, 'module_ocajaxlogin', 'module_ocajaxlogin_loader_img', 'catalog/AjaxLoader.gif', 0),
(0, 'module_ocajaxlogin', 'module_ocajaxlogin_redirect_status', '0', 0),
(0, 'module_ocajaxlogin', 'module_ocajaxlogin_status', '1', 0),
(0, 'module_ocblog', 'module_ocblog_article_height', '334', 0),
(0, 'module_ocblog', 'module_ocblog_article_limit', '10', 0),
(0, 'module_ocblog', 'module_ocblog_article_width', '270', 0),
(0, 'module_ocblog', 'module_ocblog_blog_height', '334', 0),
(0, 'module_ocblog', 'module_ocblog_blog_width', '270', 0),
(0, 'module_ocblog', 'module_ocblog_meta_description', 'Blog Description', 0),
(0, 'module_ocblog', 'module_ocblog_meta_keyword', 'Blog Keyword', 0),
(0, 'module_ocblog', 'module_ocblog_meta_title', 'Blog', 0),
(0, 'module_oclayerednavigation', 'module_oclayerednavigation_loader_img', 'catalog/AjaxLoader.gif', 0),
(0, 'module_oclayerednavigation', 'module_oclayerednavigation_status', '1', 0),
(0, 'module_octhemeoption', 'module_octhemeoption_a_tag', '[{\"color\":\"292929\",\"hover_color\":\"EE3333\"},{\"color\":\"FFFFFF\",\"hover_color\":\"FFFFFF\"},{\"color\":\"FFFFFF\",\"hover_color\":\"FFFFFF\"},{\"color\":\"FFFFFF\",\"hover_color\":\"FFFFFF\"}]', 1),
(0, 'module_octhemeoption', 'module_octhemeoption_body', '[{\"color\":\"707070\",\"font_family\":\"Poppins\",\"font_size\":\"12px\",\"font_weight\":\"400\",\"line_height\":\"20px\"},{\"color\":\"FFFFFF\",\"font_family\":\"\",\"font_size\":\"\",\"font_weight\":\"\",\"line_height\":\"\"},{\"color\":\"FFFFFF\",\"font_family\":\"\",\"font_size\":\"\",\"font_weight\":\"\",\"line_height\":\"\"},{\"color\":\"FFFFFF\",\"font_family\":\"\",\"font_size\":\"\",\"font_weight\":\"\",\"line_height\":\"\"}]', 1),
(0, 'module_octhemeoption', 'module_octhemeoption_catalog', '[\"1\",\"1\",\"1\",\"1\"]', 1),
(0, 'module_octhemeoption', 'module_octhemeoption_header_tag', '[{\"color\":\"292929\",\"font_family\":\"Poppins\",\"font_weight\":\"400\",\"h1\":{\"font_size\":\"33px\"},\"h2\":{\"font_size\":\"27px\"},\"h3\":{\"font_size\":\"21px\"},\"h4\":{\"font_size\":\"15px\"},\"h5\":{\"font_size\":\"12px\"},\"h6\":{\"font_size\":\"10.2px\"}},{\"color\":\"FFFFFF\",\"font_family\":\"\",\"font_weight\":\"\",\"h1\":{\"font_size\":\"\"},\"h2\":{\"font_size\":\"\"},\"h3\":{\"font_size\":\"\"},\"h4\":{\"font_size\":\"\"},\"h5\":{\"font_size\":\"\"},\"h6\":{\"font_size\":\"\"}},{\"color\":\"FFFFFF\",\"font_family\":\"\",\"font_weight\":\"\",\"h1\":{\"font_size\":\"\"},\"h2\":{\"font_size\":\"\"},\"h3\":{\"font_size\":\"\"},\"h4\":{\"font_size\":\"\"},\"h5\":{\"font_size\":\"\"},\"h6\":{\"font_size\":\"\"}},{\"color\":\"FFFFFF\",\"font_family\":\"\",\"font_weight\":\"\",\"h1\":{\"font_size\":\"\"},\"h2\":{\"font_size\":\"\"},\"h3\":{\"font_size\":\"\"},\"h4\":{\"font_size\":\"\"},\"h5\":{\"font_size\":\"\"},\"h6\":{\"font_size\":\"\"}}]', 1),
(0, 'module_octhemeoption', 'module_octhemeoption_loader_img', 'catalog/AjaxLoader.gif', 0),
(0, 'module_octhemeoption', 'module_octhemeoption_quickview', '[\"1\",\"1\",\"1\",\"1\"]', 1),
(0, 'module_octhemeoption', 'module_octhemeoption_rotator', '[\"1\",\"1\",\"1\",\"1\"]', 1),
(0, 'module_octhemeoption', 'module_octhemeoption_status', '[\"0\",\"0\",\"0\",\"0\"]', 1);
UPDATE `oc_setting` SET `value` = '7' WHERE `oc_setting`.`key` = 'config_layout_id';
UPDATE `oc_setting` SET `value` = '7' WHERE `oc_setting`.`key` = 'config_compression';
UPDATE `oc_setting` SET `value` = '0' WHERE `oc_setting`.`key` = 'developer_sass';
UPDATE `oc_setting` SET `value` = '0' WHERE `oc_setting`.`key` = 'developer_theme';
UPDATE `oc_setting` SET `value` = 'tt_aero3' WHERE `oc_setting`.`key` = 'theme_default_directory';
UPDATE `oc_setting` SET `value` = '600' WHERE `oc_setting`.`key` = 'theme_default_image_additional_height';
UPDATE `oc_setting` SET `value` = '600' WHERE `oc_setting`.`key` = 'theme_default_image_additional_width';
UPDATE `oc_setting` SET `value` = '80' WHERE `oc_setting`.`key` = 'theme_default_image_cart_height';
UPDATE `oc_setting` SET `value` = '80' WHERE `oc_setting`.`key` = 'theme_default_image_cart_width';
UPDATE `oc_setting` SET `value` = '256' WHERE `oc_setting`.`key` = 'theme_default_image_category_height';
UPDATE `oc_setting` SET `value` = '1170' WHERE `oc_setting`.`key` = 'theme_default_image_category_width';
UPDATE `oc_setting` SET `value` = '80' WHERE `oc_setting`.`key` = 'theme_default_image_compare_height';
UPDATE `oc_setting` SET `value` = '80' WHERE `oc_setting`.`key` = 'theme_default_image_compare_width';
UPDATE `oc_setting` SET `value` = '600' WHERE `oc_setting`.`key` = 'theme_default_image_popup_height';
UPDATE `oc_setting` SET `value` = '600' WHERE `oc_setting`.`key` = 'theme_default_image_popup_width';
UPDATE `oc_setting` SET `value` = '600' WHERE `oc_setting`.`key` = 'theme_default_image_product_height';
UPDATE `oc_setting` SET `value` = '600' WHERE `oc_setting`.`key` = 'theme_default_image_product_width';
UPDATE `oc_setting` SET `value` = '600' WHERE `oc_setting`.`key` = 'theme_default_image_related_height';
UPDATE `oc_setting` SET `value` = '600' WHERE `oc_setting`.`key` = 'theme_default_image_related_width';
UPDATE `oc_setting` SET `value` = '600' WHERE `oc_setting`.`key` = 'theme_default_image_thumb_height';
UPDATE `oc_setting` SET `value` = '600' WHERE `oc_setting`.`key` = 'theme_default_image_thumb_width';
UPDATE `oc_setting` SET `value` = '80' WHERE `oc_setting`.`key` = 'theme_default_image_wishlist_height';
UPDATE `oc_setting` SET `value` = '80' WHERE `oc_setting`.`key` = 'theme_default_image_wishlist_width';
UPDATE `oc_setting` SET `value` = '300' WHERE `oc_setting`.`key` = 'theme_default_product_description_length';
UPDATE `oc_setting` SET `value` = '9' WHERE `oc_setting`.`key` = 'theme_default_product_limit';
--
-- Indexes for dumped tables
--
--
-- Indexes for table `oc_article`
--
ALTER TABLE `oc_article`
  ADD PRIMARY KEY (`article_id`);
--
-- Indexes for table `oc_article_description`
--
ALTER TABLE `oc_article_description`
  ADD PRIMARY KEY (`article_id`,`language_id`),
  ADD KEY `name` (`name`);
--
-- Indexes for table `oc_article_list`
--
ALTER TABLE `oc_article_list`
  ADD PRIMARY KEY (`article_list_id`),
  ADD KEY `name` (`name`);
--
-- Indexes for table `oc_testimonial`
--
ALTER TABLE `oc_testimonial`
  ADD PRIMARY KEY (`testimonial_id`);
--
-- Indexes for table `oc_testimonial_description`
--
ALTER TABLE `oc_testimonial_description`
  ADD PRIMARY KEY (`testimonial_id`,`language_id`);
--
-- Indexes for table `oc_cmsblock`
--
ALTER TABLE `oc_cmsblock`
  ADD PRIMARY KEY (`cmsblock_id`);
--
-- Indexes for table `oc_cmsblock_description`
--
ALTER TABLE `oc_cmsblock_description`
  ADD PRIMARY KEY (`cmsblock_des_id`,`language_id`);
--
-- Indexes for table `oc_extension`
--
ALTER TABLE `oc_extension`
  ADD PRIMARY KEY (`extension_id`);
--
-- Indexes for table `oc_extension_install`
--
ALTER TABLE `oc_extension_install`
  ADD PRIMARY KEY (`extension_install_id`);
--
-- Indexes for table `oc_extension_path`
--
ALTER TABLE `oc_extension_path`
  ADD PRIMARY KEY (`extension_path_id`);
--
-- Indexes for table `oc_layout`
--
ALTER TABLE `oc_layout`
  ADD PRIMARY KEY (`layout_id`);
--
-- Indexes for table `oc_layout_module`
--
ALTER TABLE `oc_layout_module`
  ADD PRIMARY KEY (`layout_module_id`);
--
-- Indexes for table `oc_layout_route`
--
ALTER TABLE `oc_layout_route`
  ADD PRIMARY KEY (`layout_route_id`);
--
-- Indexes for table `oc_modification`
--
ALTER TABLE `oc_modification`
  ADD PRIMARY KEY (`modification_id`);
--
-- Indexes for table `oc_module`
--
ALTER TABLE `oc_module`
  ADD PRIMARY KEY (`module_id`);
--
-- Indexes for table `oc_ocslideshow`
--
ALTER TABLE `oc_ocslideshow`
  ADD PRIMARY KEY (`ocslideshow_id`);
--
-- Indexes for table `oc_ocslideshow_image`
--
ALTER TABLE `oc_ocslideshow_image`
  ADD PRIMARY KEY (`ocslideshow_image_id`);
--
-- Indexes for table `oc_ocslideshow_image_description`
--
ALTER TABLE `oc_ocslideshow_image_description`
  ADD PRIMARY KEY (`ocslideshow_image_id`,`language_id`);
--
-- Indexes for table `oc_user_group`
--
ALTER TABLE `oc_user_group`
  ADD PRIMARY KEY (`user_group_id`);
--
-- AUTO_INCREMENT for dumped tables
--
--
-- AUTO_INCREMENT for table `oc_article`
--
ALTER TABLE `oc_article`
  MODIFY `article_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `oc_article_list`
--
ALTER TABLE `oc_article_list`
  MODIFY `article_list_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `oc_testimonial`
--
ALTER TABLE `oc_testimonial`
  MODIFY `testimonial_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `oc_cmsblock`
--
ALTER TABLE `oc_cmsblock`
  MODIFY `cmsblock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;
--
-- AUTO_INCREMENT for table `oc_cmsblock_description`
--
ALTER TABLE `oc_cmsblock_description`
  MODIFY `cmsblock_des_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=283;
--
-- AUTO_INCREMENT for table `oc_extension`
--
ALTER TABLE `oc_extension`
  MODIFY `extension_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;
--
-- AUTO_INCREMENT for table `oc_extension_install`
--
ALTER TABLE `oc_extension_install`
  MODIFY `extension_install_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `oc_extension_path`
--
ALTER TABLE `oc_extension_path`
  MODIFY `extension_path_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `oc_layout`
--
ALTER TABLE `oc_layout`
  MODIFY `layout_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `oc_layout_module`
--
ALTER TABLE `oc_layout_module`
  MODIFY `layout_module_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1321;
--
-- AUTO_INCREMENT for table `oc_layout_route`
--
ALTER TABLE `oc_layout_route`
  MODIFY `layout_route_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=355;
--
-- AUTO_INCREMENT for table `oc_modification`
--
ALTER TABLE `oc_modification`
  MODIFY `modification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `oc_module`
--
ALTER TABLE `oc_module`
  MODIFY `module_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;
--
-- AUTO_INCREMENT for table `oc_ocslideshow`
--
ALTER TABLE `oc_ocslideshow`
  MODIFY `ocslideshow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `oc_ocslideshow_image`
--
ALTER TABLE `oc_ocslideshow_image`
  MODIFY `ocslideshow_image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=372;
--
-- AUTO_INCREMENT for table `oc_user_group`
--
ALTER TABLE `oc_user_group`
  MODIFY `user_group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;