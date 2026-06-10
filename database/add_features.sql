-- 微信支付配置表
CREATE TABLE IF NOT EXISTS `admin_payment_config` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `channel` varchar(30) NOT NULL DEFAULT 'wechat' COMMENT '支付渠道(wechat/alipay)',
  `mch_id` varchar(64) NOT NULL DEFAULT '' COMMENT '商户号',
  `app_id` varchar(100) NOT NULL DEFAULT '' COMMENT '应用ID',
  `api_key` varchar(255) NOT NULL DEFAULT '' COMMENT 'API密钥',
  `cert_path` varchar(500) NOT NULL DEFAULT '' COMMENT '证书路径',
  `key_path` varchar(500) NOT NULL DEFAULT '' COMMENT '密钥路径',
  `notify_url` varchar(500) NOT NULL DEFAULT '' COMMENT '回调地址',
  `sandbox` tinyint NOT NULL DEFAULT 0 COMMENT '沙箱模式(0否 1是)',
  `status` tinyint NOT NULL DEFAULT 1 COMMENT '状态(1启用 0禁用)',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_channel` (`channel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='支付配置表';

-- 短信渠道配置表
CREATE TABLE IF NOT EXISTS `admin_sms_config` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '渠道名称',
  `code` varchar(30) NOT NULL DEFAULT '' COMMENT '渠道编码(alibaba/tencent)',
  `access_key_id` varchar(128) NOT NULL DEFAULT '' COMMENT 'AccessKey ID',
  `access_key_secret` varchar(255) NOT NULL DEFAULT '' COMMENT 'AccessKey Secret',
  `sign_name` varchar(50) NOT NULL DEFAULT '' COMMENT '短信签名',
  `template_code` varchar(100) NOT NULL DEFAULT '' COMMENT '模板编码',
  `endpoint` varchar(200) NOT NULL DEFAULT '' COMMENT '接口地址',
  `status` tinyint NOT NULL DEFAULT 1 COMMENT '状态(1启用 0禁用)',
  `is_default` tinyint NOT NULL DEFAULT 0 COMMENT '是否默认(0否 1是)',
  `daily_limit` int NOT NULL DEFAULT 100 COMMENT '每日发送上限(-1不限)',
  `used_today` int NOT NULL DEFAULT 0 COMMENT '今日已发数',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='短信渠道配置表';

-- 文章分类表
CREATE TABLE IF NOT EXISTS `admin_article_category` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int unsigned NOT NULL DEFAULT 0 COMMENT '父级ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '分类名称',
  `slug` varchar(50) NOT NULL DEFAULT '' COMMENT '别名',
  `icon` varchar(50) NOT NULL DEFAULT '' COMMENT '图标',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `sort` int NOT NULL DEFAULT 0 COMMENT '排序',
  `status` tinyint NOT NULL DEFAULT 1 COMMENT '状态',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='文章分类表';

-- 文章表
CREATE TABLE IF NOT EXISTS `admin_article` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int unsigned NOT NULL DEFAULT 0 COMMENT '分类ID',
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '标题',
  `summary` varchar(500) NOT NULL DEFAULT '' COMMENT '摘要',
  `cover_image` varchar(500) NOT NULL DEFAULT '' COMMENT '封面图',
  `content` longtext COMMENT '内容(Markdown/HTML)',
  `author` varchar(50) NOT NULL DEFAULT '' COMMENT '作者',
  `source` varchar(100) NOT NULL DEFAULT '' COMMENT '来源',
  `view_count` int unsigned NOT NULL DEFAULT 0 COMMENT '阅读量',
  `like_count` int unsigned NOT NULL DEFAULT 0 COMMENT '点赞数',
  `comment_count` int unsigned NOT NULL DEFAULT 0 COMMENT '评论数',
  `is_top` tinyint NOT NULL DEFAULT 0 COMMENT '置顶(0否 1是)',
  `is_recommend` tinyint NOT NULL DEFAULT 0 COMMENT '推荐(0否 1是)',
  `status` tinyint NOT NULL DEFAULT 0 COMMENT '状态(0草稿 1已发布 2已下架)',
  `published_at` datetime DEFAULT NULL COMMENT '发布时间',
  `user_id` int unsigned NOT NULL DEFAULT 0 COMMENT '发布人ID',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_category_id` (`category_id`),
  KEY `idx_status` (`status`),
  KEY `idx_published_at` (`published_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='文章表';

-- 初始数据：微信支付配置
INSERT INTO `admin_payment_config` (`channel`, `mch_id`, `app_id`, `api_key`, `notify_url`, `status`, `remark`) VALUES
('wechat', '', '', '', '/api/payment/wechat/notify', 0, '微信支付');

-- 初始数据：短信渠道配置
INSERT INTO `admin_sms_config` (`name`, `code`, `sign_name`, `template_code`, `status`, `is_default`) VALUES
('阿里云短信', 'alibaba', '', '', 0, 1),
('腾讯云短信', 'tencent', '', '', 0, 0);

-- 初始数据：文章分类
INSERT INTO `admin_article_category` (`parent_id`, `name`, `slug`, `description`, `sort`, `status`) VALUES
(0, '公告通知', 'notice', '系统公告和通知', 1, 1),
(0, '帮助文档', 'help', '使用帮助和文档', 2, 1),
(0, '新闻动态', 'news', '平台新闻动态', 3, 1);

-- 初始数据：菜单（内容管理 + 子菜单）
SET @parent_menu = (SELECT MAX(id) FROM admin_menu);
INSERT INTO `admin_menu` (`parent_id`, `name`, `type`, `path`, `component`, `icon`, `permission`, `sort`, `visible`, `status`) VALUES
(0, '内容管理', 1, '/content', '', 'document', '', 6, 1, 1);

SET @content_menu = (SELECT id FROM admin_menu WHERE name='内容管理' ORDER BY id DESC LIMIT 1);
INSERT INTO `admin_menu` (`parent_id`, `name`, `type`, `path`, `component`, `icon`, `permission`, `sort`, `visible`, `status`) VALUES
(@content_menu, '文章管理', 2, '/content/article', 'content/article/index', 'document', 'content:article:list', 1, 1, 1),
(@content_menu, '支付配置', 2, '/content/payment', 'content/payment/index', 'money', 'content:payment:list', 2, 1, 1),
(@content_menu, '短信配置', 2, '/content/sms', 'content/sms/index', 'chat-dot-round', 'content:sms:list', 3, 1, 1);

-- 文章管理按钮权限
SET @article_menu = (SELECT id FROM admin_menu WHERE path='/content/article');
INSERT INTO `admin_menu` (`parent_id`, `name`, `type`, `path`, `component`, `icon`, `permission`, `sort`, `visible`, `status`) VALUES
(@article_menu, '新增文章', 3, '', '', '', 'content:article:add', 1, 1, 1),
(@article_menu, '编辑文章', 3, '', '', '', 'content:article:edit', 2, 1, 1),
(@article_menu, '删除文章', 3, '', '', '', 'content:article:delete', 3, 1, 1);

-- 超级管理员拥有新菜单
INSERT INTO `admin_role_menu` (`role_id`, `menu_id`)
SELECT 1, `id` FROM `admin_menu` WHERE `id` > 20;
