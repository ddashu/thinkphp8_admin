-- 支付宝配置表
CREATE TABLE IF NOT EXISTS `admin_alipay_config` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `app_id` varchar(100) NOT NULL DEFAULT '' COMMENT '支付宝应用ID',
  `private_key` text NOT NULL COMMENT '应用私钥',
  `public_key` text NOT NULL COMMENT '支付宝公钥',
  `notify_url` varchar(500) NOT NULL DEFAULT '' COMMENT '异步通知地址',
  `return_url` varchar(500) NOT NULL DEFAULT '' COMMENT '同步跳转地址',
  `sign_type` varchar(10) NOT NULL DEFAULT 'RSA2' COMMENT '签名方式(RSA2/RSA)',
  `charset` varchar(20) NOT NULL DEFAULT 'utf-8' COMMENT '字符编码',
  `format` varchar(10) NOT NULL DEFAULT 'json' COMMENT '返回格式',
  `sandbox` tinyint NOT NULL DEFAULT 0 COMMENT '沙箱模式(0否 1是)',
  `status` tinyint NOT NULL DEFAULT 1 COMMENT '状态(1启用 0禁用)',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='支付宝配置表';

-- 初始数据
INSERT INTO `admin_alipay_config` (`app_id`, `private_key`, `public_key`, `notify_url`, `return_url`, `sign_type`, `status`, `remark`) VALUES
('', '', '', '/api/payment/alipay/notify', '/api/payment/alipay/return', 'RSA2', 0, '支付宝支付配置');
