-- phpMyAdmin SQL Dump
-- version 3.3.2deb1ubuntu1
-- http://www.phpmyadmin.net
--
-- ����: localhost
-- ��������: 2014 �� 07 �� 08 �� 13:34
-- �������汾: 5.1.41
-- PHP �汾: 5.3.2-1ubuntu4.24

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- ���ݿ�: `db_mobilepay`
--

-- --------------------------------------------------------

--
-- ��Ľṹ `tb_gamerechargelist`
--

CREATE TABLE IF NOT EXISTS `tb_gamerechargelist` (
  `fd_grclist_id` int(11) NOT NULL AUTO_INCREMENT,
  `fd_grclist_paycardid` varchar(100) DEFAULT NULL,
  `fd_grclist_authorid` int(11) DEFAULT NULL,
  `fd_grclist_paydate` date DEFAULT NULL,
  `fd_grclist_bkntno` varchar(100) DEFAULT NULL,
  `fd_grclist_bkordernumber` varchar(200) DEFAULT NULL,
  `fd_grclist_sdcrid` int(11) DEFAULT NULL,
  `fd_grclist_rechamoney` double(12,2) DEFAULT NULL COMMENT '��ֵ���',
  `fd_grclist_bkmoney` double(12,2) DEFAULT NULL COMMENT '�������׽��',
  `fd_grclist_paymoney` double(12,2) DEFAULT NULL COMMENT 'ʵ��֧�����',
  `fd_grclist_gamecardid` varchar(200) DEFAULT NULL,
  `fd_grclist_gamename` varchar(200) DEFAULT NULL,
  `fd_grclist_gamecardnum` int(11) DEFAULT NULL,
  `fd_grclist_gameuserid` varchar(200) DEFAULT NULL,
  `fd_grclist_gamearea` varchar(200) DEFAULT NULL,
  `fd_grclist_gamesrv` varchar(200) DEFAULT NULL,
  `fd_grclist_bankcardno` varchar(30) DEFAULT NULL,
  `fd_grclist_state` varchar(100) DEFAULT '0',
  `fd_grclist_ofreqcontent` text COMMENT 'ŷ�ɽӿ���������',
  `fd_grclist_ofanscontent` text COMMENT 'ŷ�ɽӿڷ�������',
  `fd_grclist_datetime` datetime DEFAULT NULL,
  `fd_grclist_payfee` double(12,2) DEFAULT NULL,
  `fd_grclist_cusid` int(11) DEFAULT NULL,
  PRIMARY KEY (`fd_grclist_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='��Ϸ��ֵ����';
