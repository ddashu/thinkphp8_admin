/*
 Navicat Premium Dump SQL

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80012 (8.0.12)
 Source Host           : localhost:3306
 Source Schema         : thinkphp8_admin

 Target Server Type    : MySQL
 Target Server Version : 80012 (8.0.12)
 File Encoding         : 65001

 Date: 10/06/2026 21:52:22
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_alipay_config
-- ----------------------------
DROP TABLE IF EXISTS `admin_alipay_config`;
CREATE TABLE `admin_alipay_config`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `app_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '支付宝应用ID',
  `private_key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '应用私钥',
  `public_key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '支付宝公钥',
  `notify_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '异步通知地址',
  `return_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '同步跳转地址',
  `sign_type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'RSA2' COMMENT '签名方式(RSA2/RSA)',
  `charset` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'utf-8' COMMENT '字符编码',
  `format` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'json' COMMENT '返回格式',
  `sandbox` tinyint(4) NOT NULL DEFAULT 0 COMMENT '沙箱模式(0否 1是)',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '状态(1启用 0禁用)',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` datetime NULL DEFAULT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '支付宝配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_alipay_config
-- ----------------------------
INSERT INTO `admin_alipay_config` VALUES (1, '', '', '', '/api/payment/alipay/notify', '/api/payment/alipay/return', 'RSA2', 'utf-8', 'json', 0, 0, '支付宝支付配置', NULL, NULL);

-- ----------------------------
-- Table structure for admin_api_key
-- ----------------------------
DROP TABLE IF EXISTS `admin_api_key`;
CREATE TABLE `admin_api_key`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所属用户ID',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密钥名称',
  `api_key` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'API Key',
  `api_secret` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'API Secret',
  `quota_limit` bigint(20) NOT NULL DEFAULT -1 COMMENT '调用限额(-1无限)',
  `quota_used` bigint(20) NOT NULL DEFAULT 0 COMMENT '已使用量',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '状态',
  `expires_at` datetime NULL DEFAULT NULL COMMENT '过期时间',
  `last_used_at` datetime NULL DEFAULT NULL COMMENT '最后使用时间',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_api_key`(`api_key` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'API密钥表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_api_key
-- ----------------------------

-- ----------------------------
-- Table structure for admin_article
-- ----------------------------
DROP TABLE IF EXISTS `admin_article`;
CREATE TABLE `admin_article`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分类ID',
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `summary` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '摘要',
  `cover_image` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '封面图',
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '内容(Markdown/HTML)',
  `author` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '作者',
  `source` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '来源',
  `view_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '阅读量',
  `like_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '点赞数',
  `comment_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '评论数',
  `is_top` tinyint(4) NOT NULL DEFAULT 0 COMMENT '置顶(0否 1是)',
  `is_recommend` tinyint(4) NOT NULL DEFAULT 0 COMMENT '推荐(0否 1是)',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '状态(0草稿 1已发布 2已下架)',
  `published_at` datetime NULL DEFAULT NULL COMMENT '发布时间',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '发布人ID',
  `create_time` datetime NULL DEFAULT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_category_id`(`category_id` ASC) USING BTREE,
  INDEX `idx_status`(`status` ASC) USING BTREE,
  INDEX `idx_published_at`(`published_at` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '文章表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_article
-- ----------------------------

-- ----------------------------
-- Table structure for admin_article_category
-- ----------------------------
DROP TABLE IF EXISTS `admin_article_category`;
CREATE TABLE `admin_article_category`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级ID',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分类名称',
  `slug` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '别名',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图标',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '状态',
  `create_time` datetime NULL DEFAULT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '文章分类表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_article_category
-- ----------------------------
INSERT INTO `admin_article_category` VALUES (1, 0, '公告通知', 'notice', '', '系统公告和通知', 1, 1, NULL, NULL);
INSERT INTO `admin_article_category` VALUES (2, 0, '帮助文档', 'help', '', '使用帮助和文档', 2, 1, NULL, NULL);
INSERT INTO `admin_article_category` VALUES (3, 0, '新闻动态', 'news', '', '平台新闻动态', 3, 1, NULL, NULL);

-- ----------------------------
-- Table structure for admin_config
-- ----------------------------
DROP TABLE IF EXISTS `admin_config`;
CREATE TABLE `admin_config`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `group` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'basic' COMMENT '配置分组',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '配置名称',
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '配置编码',
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '配置值',
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string' COMMENT '值类型',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_code`(`code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '系统配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_config
-- ----------------------------
INSERT INTO `admin_config` VALUES (1, 'basic', '站点名称', 'site_name', 'OpenClaw AI', 'string', '网站名称', 1, NULL, NULL);
INSERT INTO `admin_config` VALUES (2, 'basic', '站点描述', 'site_description', '智能AI平台管理系统', 'string', '网站描述', 2, NULL, NULL);
INSERT INTO `admin_config` VALUES (3, 'basic', '站点Logo', 'site_logo', '', 'string', '网站Logo URL', 3, NULL, NULL);
INSERT INTO `admin_config` VALUES (4, 'email', 'SMTP主机', 'smtp_host', '', 'string', 'SMTP服务器地址', 1, NULL, NULL);
INSERT INTO `admin_config` VALUES (5, 'email', 'SMTP端口', 'smtp_port', '465', 'number', 'SMTP端口', 2, NULL, NULL);
INSERT INTO `admin_config` VALUES (6, 'email', 'SMTP用户', 'smtp_user', '', 'string', 'SMTP用户名', 3, NULL, NULL);
INSERT INTO `admin_config` VALUES (7, 'email', 'SMTP密码', 'smtp_pass', '', 'string', 'SMTP密码', 4, NULL, NULL);
INSERT INTO `admin_config` VALUES (8, 'storage', '存储方式', 'storage_driver', 'local', 'string', '存储方式(local/oss/s3)', 1, NULL, NULL);
INSERT INTO `admin_config` VALUES (9, 'storage', '上传大小限制', 'upload_max_size', '10', 'number', 'MB', 2, NULL, NULL);

-- ----------------------------
-- Table structure for admin_file
-- ----------------------------
DROP TABLE IF EXISTS `admin_file`;
CREATE TABLE `admin_file`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上传用户ID',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件名',
  `path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '存储路径',
  `url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '访问URL',
  `mime_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'MIME类型',
  `size` bigint(20) NOT NULL DEFAULT 0 COMMENT '文件大小',
  `extension` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '扩展名',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '文件表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_file
-- ----------------------------

-- ----------------------------
-- Table structure for admin_log
-- ----------------------------
DROP TABLE IF EXISTS `admin_log`;
CREATE TABLE `admin_log`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '操作用户ID',
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '操作用户名',
  `module` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '模块',
  `action` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '操作',
  `method` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求URL',
  `params` json NULL COMMENT '请求参数',
  `ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '操作IP',
  `user_agent` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户代理',
  `create_time` datetime NULL DEFAULT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_user_id`(`user_id` ASC) USING BTREE,
  INDEX `idx_create_time`(`create_time` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '操作日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_log
-- ----------------------------
INSERT INTO `admin_log` VALUES (1, 1, 'admin', 'admin.User', 'edit', 'PUT', '/api/admin/user/update', '{\"id\": 1, \"email\": \"\", \"phone\": \"\", \"roles\": [{\"id\": 1, \"code\": \"super_admin\", \"name\": \"超级管理员\", \"sort\": 1, \"pivot\": {\"role_id\": 1, \"user_id\": 1}, \"status\": 1, \"create_time\": null, \"description\": \"拥有所有权限\", \"update_time\": null}], \"avatar\": \"\", \"status\": 1, \"role_id\": 1, \"nickname\": \"超级管理员\", \"username\": \"admin\", \"create_time\": null, \"delete_time\": null, \"update_time\": \"2026-06-10 20:59:55\", \"last_login_ip\": \"127.0.0.1\", \"last_login_time\": \"2026-06-10 20:59:54\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) TRAESOLOCN/1.107.1 Chrome/142.0.7444.235 Electron/39.2.7 Safari/537.36', '2026-06-10 21:00:38');

-- ----------------------------
-- Table structure for admin_login_log
-- ----------------------------
DROP TABLE IF EXISTS `admin_login_log`;
CREATE TABLE `admin_login_log`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户ID',
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '登录IP',
  `location` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '登录地点',
  `browser` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '浏览器',
  `os` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '操作系统',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '状态(1成功 0失败)',
  `message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '提示信息',
  `create_time` datetime NULL DEFAULT NULL COMMENT '登录时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_user_id`(`user_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '登录日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_login_log
-- ----------------------------
INSERT INTO `admin_login_log` VALUES (1, 1, 'admin', '127.0.0.1', '', 'Unknown', 'Windows', 0, '密码错误', '2026-06-10 18:14:31');
INSERT INTO `admin_login_log` VALUES (2, 1, 'admin', '127.0.0.1', '', 'Unknown', 'Windows', 0, '密码错误', '2026-06-10 18:14:45');
INSERT INTO `admin_login_log` VALUES (3, 1, 'admin', '127.0.0.1', '', 'Unknown', 'Windows', 0, '密码错误', '2026-06-10 18:15:20');
INSERT INTO `admin_login_log` VALUES (4, 1, 'admin', '127.0.0.1', '', 'Unknown', 'Windows', 1, '登录成功', '2026-06-10 18:16:19');
INSERT INTO `admin_login_log` VALUES (5, 1, 'admin', '127.0.0.1', '', 'Unknown', 'Windows', 1, '登录成功', '2026-06-10 18:16:47');
INSERT INTO `admin_login_log` VALUES (6, 1, 'admin', '127.0.0.1', '', 'Chrome', 'Windows', 0, '密码错误', '2026-06-10 20:43:24');
INSERT INTO `admin_login_log` VALUES (7, 1, 'admin', '127.0.0.1', '', 'Chrome', 'Windows', 1, '登录成功', '2026-06-10 20:45:28');
INSERT INTO `admin_login_log` VALUES (8, 1, 'admin', '127.0.0.1', '', 'Chrome', 'Windows', 1, '登录成功', '2026-06-10 20:45:38');
INSERT INTO `admin_login_log` VALUES (9, 1, 'admin', '127.0.0.1', '', 'Unknown', 'Windows', 1, '登录成功', '2026-06-10 20:51:42');
INSERT INTO `admin_login_log` VALUES (10, 1, 'admin', '127.0.0.1', '', 'Unknown', 'Windows', 1, '登录成功', '2026-06-10 20:52:03');
INSERT INTO `admin_login_log` VALUES (11, 1, 'admin', '127.0.0.1', '', 'Unknown', 'Windows', 1, '登录成功', '2026-06-10 20:53:35');
INSERT INTO `admin_login_log` VALUES (12, 1, 'admin', '127.0.0.1', '', 'Chrome', 'Windows', 1, '登录成功', '2026-06-10 20:59:55');

-- ----------------------------
-- Table structure for admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `admin_menu`;
CREATE TABLE `admin_menu`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级ID',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单名称',
  `type` tinyint(4) NOT NULL DEFAULT 2 COMMENT '类型(1目录 2菜单 3按钮)',
  `path` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '路由路径',
  `component` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '组件路径',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图标',
  `permission` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '权限标识',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `visible` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否显示',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '状态',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 29 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '菜单表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_menu
-- ----------------------------
INSERT INTO `admin_menu` VALUES (1, 0, '数据看板', 2, '/dashboard', 'dashboard/index', 'dashboard', '', 1, 1, 1, NULL, NULL);
INSERT INTO `admin_menu` VALUES (2, 0, '系统管理', 1, '/system', '', 'setting', '', 2, 1, 1, NULL, NULL);
INSERT INTO `admin_menu` VALUES (3, 0, 'AI管理', 1, '/ai', '', 'cpu', '', 3, 1, 1, NULL, NULL);
INSERT INTO `admin_menu` VALUES (4, 0, '日志管理', 1, '/log', '', 'document', '', 4, 1, 1, NULL, NULL);
INSERT INTO `admin_menu` VALUES (5, 0, '文件管理', 2, '/file', 'file/index', 'folder', '', 5, 1, 1, NULL, NULL);
INSERT INTO `admin_menu` VALUES (6, 2, '用户管理', 2, '/system/user', 'system/user/index', 'user', 'system:user:list', 1, 1, 1, NULL, NULL);
INSERT INTO `admin_menu` VALUES (7, 2, '角色管理', 2, '/system/role', 'system/role/index', 'peoples', 'system:role:list', 2, 1, 1, NULL, NULL);
INSERT INTO `admin_menu` VALUES (8, 2, '菜单管理', 2, '/system/menu', 'system/menu/index', 'tree', 'system:menu:list', 3, 1, 1, NULL, NULL);
INSERT INTO `admin_menu` VALUES (9, 2, '系统配置', 2, '/system/config', 'system/config/index', 'tool', 'system:config:list', 4, 1, 1, NULL, NULL);
INSERT INTO `admin_menu` VALUES (10, 3, 'API密钥', 2, '/ai/apikey', 'ai/apikey/index', 'key', 'ai:apikey:list', 1, 1, 1, NULL, NULL);
INSERT INTO `admin_menu` VALUES (11, 3, '模型管理', 2, '/ai/model', 'ai/model/index', 'component', 'ai:model:list', 2, 1, 1, NULL, NULL);
INSERT INTO `admin_menu` VALUES (12, 4, '操作日志', 2, '/log/operation', 'log/operation/index', 'log', 'log:operation:list', 1, 1, 1, NULL, NULL);
INSERT INTO `admin_menu` VALUES (13, 6, '用户新增', 3, '', '', '', 'system:user:add', 1, 1, 1, NULL, NULL);
INSERT INTO `admin_menu` VALUES (14, 6, '用户编辑', 3, '', '', '', 'system:user:edit', 2, 1, 1, NULL, NULL);
INSERT INTO `admin_menu` VALUES (15, 6, '用户删除', 3, '', '', '', 'system:user:delete', 3, 1, 1, NULL, NULL);
INSERT INTO `admin_menu` VALUES (16, 7, '角色新增', 3, '', '', '', 'system:role:add', 1, 1, 1, NULL, NULL);
INSERT INTO `admin_menu` VALUES (17, 7, '角色编辑', 3, '', '', '', 'system:role:edit', 2, 1, 1, NULL, NULL);
INSERT INTO `admin_menu` VALUES (18, 7, '角色删除', 3, '', '', '', 'system:role:delete', 3, 1, 1, NULL, NULL);
INSERT INTO `admin_menu` VALUES (19, 0, '内容管理', 1, '/content', '', 'document', '', 6, 1, 1, NULL, NULL);
INSERT INTO `admin_menu` VALUES (20, 19, '文章管理', 2, '/content/article', 'content/article/index', 'document', 'content:article:list', 1, 1, 1, NULL, NULL);
INSERT INTO `admin_menu` VALUES (21, 19, '支付配置', 2, '/content/payment', 'content/payment/index', 'money', 'content:payment:list', 2, 1, 1, NULL, NULL);
INSERT INTO `admin_menu` VALUES (22, 19, '短信配置', 2, '/content/sms', 'content/sms/index', 'chat-dot-round', 'content:sms:list', 3, 1, 1, NULL, NULL);
INSERT INTO `admin_menu` VALUES (23, 20, '新增文章', 3, '', '', '', 'content:article:add', 1, 1, 1, NULL, NULL);
INSERT INTO `admin_menu` VALUES (24, 20, '编辑文章', 3, '', '', '', 'content:article:edit', 2, 1, 1, NULL, NULL);
INSERT INTO `admin_menu` VALUES (25, 20, '删除文章', 3, '', '', '', 'content:article:delete', 3, 1, 1, NULL, NULL);
INSERT INTO `admin_menu` VALUES (26, 20, '新增文章', 3, '', '', '', 'content:article:add', 1, 1, 1, NULL, NULL);
INSERT INTO `admin_menu` VALUES (27, 20, '编辑文章', 3, '', '', '', 'content:article:edit', 2, 1, 1, NULL, NULL);
INSERT INTO `admin_menu` VALUES (28, 20, '删除文章', 3, '', '', '', 'content:article:delete', 3, 1, 1, NULL, NULL);

-- ----------------------------
-- Table structure for admin_model
-- ----------------------------
DROP TABLE IF EXISTS `admin_model`;
CREATE TABLE `admin_model`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '模型名称',
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '模型编码',
  `category` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'chat' COMMENT '分类',
  `provider` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '供应商',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '描述',
  `config` json NULL COMMENT '模型参数配置',
  `pricing` json NULL COMMENT '定价配置',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '状态(1上线 0下线 2维护)',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_code`(`code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'AI模型表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_model
-- ----------------------------

-- ----------------------------
-- Table structure for admin_payment_config
-- ----------------------------
DROP TABLE IF EXISTS `admin_payment_config`;
CREATE TABLE `admin_payment_config`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `channel` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'wechat' COMMENT '支付渠道(wechat/alipay)',
  `mch_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商户号',
  `app_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '应用ID',
  `api_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'API密钥',
  `cert_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '证书路径',
  `key_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密钥路径',
  `notify_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '回调地址',
  `sandbox` tinyint(4) NOT NULL DEFAULT 0 COMMENT '沙箱模式(0否 1是)',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '状态(1启用 0禁用)',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` datetime NULL DEFAULT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_channel`(`channel` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '支付配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_payment_config
-- ----------------------------
INSERT INTO `admin_payment_config` VALUES (1, 'wechat', '', '', '', '', '', '/api/payment/wechat/notify', 0, 0, '微信支付', NULL, NULL);

-- ----------------------------
-- Table structure for admin_role
-- ----------------------------
DROP TABLE IF EXISTS `admin_role`;
CREATE TABLE `admin_role`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '角色名称',
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '角色编码',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '状态',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_code`(`code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '角色表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_role
-- ----------------------------
INSERT INTO `admin_role` VALUES (1, '超级管理员', 'super_admin', '拥有所有权限', 1, 1, NULL, NULL);
INSERT INTO `admin_role` VALUES (2, '普通管理员', 'admin', '普通管理权限', 2, 1, NULL, NULL);

-- ----------------------------
-- Table structure for admin_role_menu
-- ----------------------------
DROP TABLE IF EXISTS `admin_role_menu`;
CREATE TABLE `admin_role_menu`  (
  `role_id` int(10) UNSIGNED NOT NULL COMMENT '角色ID',
  `menu_id` int(10) UNSIGNED NOT NULL COMMENT '菜单ID',
  PRIMARY KEY (`role_id`, `menu_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '角色菜单关联表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_role_menu
-- ----------------------------
INSERT INTO `admin_role_menu` VALUES (1, 1);
INSERT INTO `admin_role_menu` VALUES (1, 2);
INSERT INTO `admin_role_menu` VALUES (1, 3);
INSERT INTO `admin_role_menu` VALUES (1, 4);
INSERT INTO `admin_role_menu` VALUES (1, 5);
INSERT INTO `admin_role_menu` VALUES (1, 6);
INSERT INTO `admin_role_menu` VALUES (1, 7);
INSERT INTO `admin_role_menu` VALUES (1, 8);
INSERT INTO `admin_role_menu` VALUES (1, 9);
INSERT INTO `admin_role_menu` VALUES (1, 10);
INSERT INTO `admin_role_menu` VALUES (1, 11);
INSERT INTO `admin_role_menu` VALUES (1, 12);
INSERT INTO `admin_role_menu` VALUES (1, 13);
INSERT INTO `admin_role_menu` VALUES (1, 14);
INSERT INTO `admin_role_menu` VALUES (1, 15);
INSERT INTO `admin_role_menu` VALUES (1, 16);
INSERT INTO `admin_role_menu` VALUES (1, 17);
INSERT INTO `admin_role_menu` VALUES (1, 18);
INSERT INTO `admin_role_menu` VALUES (1, 19);
INSERT INTO `admin_role_menu` VALUES (1, 20);
INSERT INTO `admin_role_menu` VALUES (1, 21);
INSERT INTO `admin_role_menu` VALUES (1, 22);
INSERT INTO `admin_role_menu` VALUES (1, 23);
INSERT INTO `admin_role_menu` VALUES (1, 24);
INSERT INTO `admin_role_menu` VALUES (1, 25);
INSERT INTO `admin_role_menu` VALUES (1, 26);
INSERT INTO `admin_role_menu` VALUES (1, 27);
INSERT INTO `admin_role_menu` VALUES (1, 28);

-- ----------------------------
-- Table structure for admin_sms_config
-- ----------------------------
DROP TABLE IF EXISTS `admin_sms_config`;
CREATE TABLE `admin_sms_config`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '渠道名称',
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '渠道编码(alibaba/tencent)',
  `access_key_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'AccessKey ID',
  `access_key_secret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'AccessKey Secret',
  `sign_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '短信签名',
  `template_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '模板编码',
  `endpoint` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '接口地址',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '状态(1启用 0禁用)',
  `is_default` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否默认(0否 1是)',
  `daily_limit` int(11) NOT NULL DEFAULT 100 COMMENT '每日发送上限(-1不限)',
  `used_today` int(11) NOT NULL DEFAULT 0 COMMENT '今日已发数',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` datetime NULL DEFAULT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_code`(`code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '短信渠道配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_sms_config
-- ----------------------------
INSERT INTO `admin_sms_config` VALUES (1, '阿里云短信', 'alibaba', '', '', '', '', '', 0, 1, 100, 0, '', NULL, NULL);
INSERT INTO `admin_sms_config` VALUES (2, '腾讯云短信', 'tencent', '', '', '', '', '', 0, 0, 100, 0, '', NULL, NULL);

-- ----------------------------
-- Table structure for admin_user
-- ----------------------------
DROP TABLE IF EXISTS `admin_user`;
CREATE TABLE `admin_user`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密码',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '状态(1正常 0禁用)',
  `last_login_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '最后登录IP',
  `last_login_time` datetime NULL DEFAULT NULL COMMENT '最后登录时间',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '软删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_username`(`username` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '管理员用户表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_user
-- ----------------------------
INSERT INTO `admin_user` VALUES (1, 'admin', '$2y$10$rcZ/c9V3l/qMCWZIOOQfSei27g0yxAb89uZewQAJu6SpFRwHMULbC', '超级管理员', '', '', '', 1, '127.0.0.1', '2026-06-10 20:59:54', NULL, '2026-06-10 20:59:55', NULL);

-- ----------------------------
-- Table structure for admin_user_role
-- ----------------------------
DROP TABLE IF EXISTS `admin_user_role`;
CREATE TABLE `admin_user_role`  (
  `user_id` int(10) UNSIGNED NOT NULL COMMENT '用户ID',
  `role_id` int(10) UNSIGNED NOT NULL COMMENT '角色ID',
  PRIMARY KEY (`user_id`, `role_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户角色关联表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_user_role
-- ----------------------------
INSERT INTO `admin_user_role` VALUES (1, 1);

SET FOREIGN_KEY_CHECKS = 1;
