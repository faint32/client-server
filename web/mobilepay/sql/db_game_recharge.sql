-- phpMyAdmin SQL Dump
-- version 3.3.2deb1ubuntu1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014 年 07 月 08 日 13:34
-- 服务器版本: 5.1.41
-- PHP 版本: 5.3.2-1ubuntu4.24

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 数据库: `db_mobilepay`
--

-- --------------------------------------------------------

--
-- 表的结构 `tb_gamerechargelist`
--

CREATE TABLE IF NOT EXISTS `tb_gamerechargelist` (
  `fd_grclist_id` int(11) NOT NULL AUTO_INCREMENT,
  `fd_grclist_paycardid` varchar(100) DEFAULT NULL,
  `fd_grclist_authorid` int(11) DEFAULT NULL,
  `fd_grclist_paydate` date DEFAULT NULL,
  `fd_grclist_bkntno` varchar(100) DEFAULT NULL,
  `fd_grclist_bkordernumber` varchar(200) DEFAULT NULL,
  `fd_grclist_sdcrid` int(11) DEFAULT NULL,
  `fd_grclist_rechamoney` double(12,2) DEFAULT NULL COMMENT '充值金额',
  `fd_grclist_bkmoney` double(12,2) DEFAULT NULL COMMENT '银联交易金额',
  `fd_grclist_paymoney` double(12,2) DEFAULT NULL COMMENT '实际支付金额',
  `fd_grclist_gamecardid` varchar(200) DEFAULT NULL,
  `fd_grclist_gamename` varchar(200) DEFAULT NULL,
  `fd_grclist_gamecardnum` int(11) DEFAULT NULL,
  `fd_grclist_gameuserid` varchar(200) DEFAULT NULL,
  `fd_grclist_gamearea` varchar(200) DEFAULT NULL,
  `fd_grclist_gamesrv` varchar(200) DEFAULT NULL,
  `fd_grclist_bankcardno` varchar(30) DEFAULT NULL,
  `fd_grclist_state` varchar(100) DEFAULT '0',
  `fd_grclist_ofreqcontent` text COMMENT '欧飞接口请求内容',
  `fd_grclist_ofanscontent` text COMMENT '欧飞接口返回内容',
  `fd_grclist_datetime` datetime DEFAULT NULL,
  `fd_grclist_payfee` double(12,2) DEFAULT NULL,
  `fd_grclist_cusid` int(11) DEFAULT NULL,
  PRIMARY KEY (`fd_grclist_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='游戏充值功能';
