-- OpenClaw AI Admin System Database Schema

CREATE TABLE IF NOT EXISTS `admin_user` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '邮箱',
  `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  `status` tinyint NOT NULL DEFAULT 1 COMMENT '状态(1正常 0禁用)',
  `last_login_ip` varchar(45) NOT NULL DEFAULT '' COMMENT '最后登录IP',
  `last_login_time` datetime DEFAULT NULL COMMENT '最后登录时间',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `delete_time` datetime DEFAULT NULL COMMENT '软删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='管理员用户表';

CREATE TABLE IF NOT EXISTS `admin_role` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '角色名称',
  `code` varchar(50) NOT NULL DEFAULT '' COMMENT '角色编码',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `sort` int NOT NULL DEFAULT 0 COMMENT '排序',
  `status` tinyint NOT NULL DEFAULT 1 COMMENT '状态',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='角色表';

CREATE TABLE IF NOT EXISTS `admin_menu` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int unsigned NOT NULL DEFAULT 0 COMMENT '父级ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `type` tinyint NOT NULL DEFAULT 2 COMMENT '类型(1目录 2菜单 3按钮)',
  `path` varchar(200) NOT NULL DEFAULT '' COMMENT '路由路径',
  `component` varchar(200) NOT NULL DEFAULT '' COMMENT '组件路径',
  `icon` varchar(50) NOT NULL DEFAULT '' COMMENT '图标',
  `permission` varchar(100) NOT NULL DEFAULT '' COMMENT '权限标识',
  `sort` int NOT NULL DEFAULT 0 COMMENT '排序',
  `visible` tinyint NOT NULL DEFAULT 1 COMMENT '是否显示',
  `status` tinyint NOT NULL DEFAULT 1 COMMENT '状态',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='菜单表';

CREATE TABLE IF NOT EXISTS `admin_user_role` (
  `user_id` int unsigned NOT NULL COMMENT '用户ID',
  `role_id` int unsigned NOT NULL COMMENT '角色ID',
  PRIMARY KEY (`user_id`, `role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户角色关联表';

CREATE TABLE IF NOT EXISTS `admin_role_menu` (
  `role_id` int unsigned NOT NULL COMMENT '角色ID',
  `menu_id` int unsigned NOT NULL COMMENT '菜单ID',
  PRIMARY KEY (`role_id`, `menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='角色菜单关联表';

CREATE TABLE IF NOT EXISTS `admin_api_key` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT 0 COMMENT '所属用户ID',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '密钥名称',
  `api_key` varchar(64) NOT NULL DEFAULT '' COMMENT 'API Key',
  `api_secret` varchar(128) NOT NULL DEFAULT '' COMMENT 'API Secret',
  `quota_limit` bigint NOT NULL DEFAULT -1 COMMENT '调用限额(-1无限)',
  `quota_used` bigint NOT NULL DEFAULT 0 COMMENT '已使用量',
  `status` tinyint NOT NULL DEFAULT 1 COMMENT '状态',
  `expires_at` datetime DEFAULT NULL COMMENT '过期时间',
  `last_used_at` datetime DEFAULT NULL COMMENT '最后使用时间',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_api_key` (`api_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='API密钥表';

CREATE TABLE IF NOT EXISTS `admin_model` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '模型名称',
  `code` varchar(50) NOT NULL DEFAULT '' COMMENT '模型编码',
  `category` varchar(30) NOT NULL DEFAULT 'chat' COMMENT '分类',
  `provider` varchar(50) NOT NULL DEFAULT '' COMMENT '供应商',
  `description` text COMMENT '描述',
  `config` json DEFAULT NULL COMMENT '模型参数配置',
  `pricing` json DEFAULT NULL COMMENT '定价配置',
  `status` tinyint NOT NULL DEFAULT 1 COMMENT '状态(1上线 0下线 2维护)',
  `sort` int NOT NULL DEFAULT 0 COMMENT '排序',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='AI模型表';

CREATE TABLE IF NOT EXISTS `admin_config` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `group` varchar(30) NOT NULL DEFAULT 'basic' COMMENT '配置分组',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '配置名称',
  `code` varchar(50) NOT NULL DEFAULT '' COMMENT '配置编码',
  `value` text COMMENT '配置值',
  `type` varchar(20) NOT NULL DEFAULT 'string' COMMENT '值类型',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `sort` int NOT NULL DEFAULT 0 COMMENT '排序',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统配置表';

CREATE TABLE IF NOT EXISTS `admin_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT 0 COMMENT '操作用户ID',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '操作用户名',
  `module` varchar(50) NOT NULL DEFAULT '' COMMENT '模块',
  `action` varchar(50) NOT NULL DEFAULT '' COMMENT '操作',
  `method` varchar(10) NOT NULL DEFAULT '' COMMENT '请求方法',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '请求URL',
  `params` json DEFAULT NULL COMMENT '请求参数',
  `ip` varchar(45) NOT NULL DEFAULT '' COMMENT '操作IP',
  `user_agent` varchar(500) NOT NULL DEFAULT '' COMMENT '用户代理',
  `create_time` datetime DEFAULT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='操作日志表';

CREATE TABLE IF NOT EXISTS `admin_file` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT 0 COMMENT '上传用户ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '文件名',
  `path` varchar(500) NOT NULL DEFAULT '' COMMENT '存储路径',
  `url` varchar(500) NOT NULL DEFAULT '' COMMENT '访问URL',
  `mime_type` varchar(100) NOT NULL DEFAULT '' COMMENT 'MIME类型',
  `size` bigint NOT NULL DEFAULT 0 COMMENT '文件大小',
  `extension` varchar(20) NOT NULL DEFAULT '' COMMENT '扩展名',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='文件表';

CREATE TABLE IF NOT EXISTS `admin_login_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT 0 COMMENT '用户ID',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `ip` varchar(45) NOT NULL DEFAULT '' COMMENT '登录IP',
  `location` varchar(100) NOT NULL DEFAULT '' COMMENT '登录地点',
  `browser` varchar(100) NOT NULL DEFAULT '' COMMENT '浏览器',
  `os` varchar(100) NOT NULL DEFAULT '' COMMENT '操作系统',
  `status` tinyint NOT NULL DEFAULT 1 COMMENT '状态(1成功 0失败)',
  `message` varchar(255) NOT NULL DEFAULT '' COMMENT '提示信息',
  `create_time` datetime DEFAULT NULL COMMENT '登录时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='登录日志表';

-- 初始管理员 (密码: admin123, bcrypt hash)
INSERT INTO `admin_user` (`username`, `password`, `nickname`, `status`) VALUES
('admin', '$2y$10$7kL5N8mP2qR4sT6uV8wX0yZ2aB4cD6eF8gH0iJ2kL4mN6oP8qR0S', '超级管理员', 1);

-- 初始角色
INSERT INTO `admin_role` (`name`, `code`, `description`, `sort`, `status`) VALUES
('超级管理员', 'super_admin', '拥有所有权限', 1, 1),
('普通管理员', 'admin', '普通管理权限', 2, 1);

-- 初始菜单
INSERT INTO `admin_menu` (`parent_id`, `name`, `type`, `path`, `component`, `icon`, `permission`, `sort`, `visible`, `status`) VALUES
(0, '数据看板', 2, '/dashboard', 'dashboard/index', 'dashboard', '', 1, 1, 1),
(0, '系统管理', 1, '/system', '', 'setting', '', 2, 1, 1),
(0, 'AI管理', 1, '/ai', '', 'cpu', '', 3, 1, 1),
(0, '日志管理', 1, '/log', '', 'document', '', 4, 1, 1),
(0, '文件管理', 2, '/file', 'file/index', 'folder', '', 5, 1, 1);

-- 系统管理子菜单
INSERT INTO `admin_menu` (`parent_id`, `name`, `type`, `path`, `component`, `icon`, `permission`, `sort`, `visible`, `status`) VALUES
(2, '用户管理', 2, '/system/user', 'system/user/index', 'user', 'system:user:list', 1, 1, 1),
(2, '角色管理', 2, '/system/role', 'system/role/index', 'peoples', 'system:role:list', 2, 1, 1),
(2, '菜单管理', 2, '/system/menu', 'system/menu/index', 'tree', 'system:menu:list', 3, 1, 1),
(2, '系统配置', 2, '/system/config', 'system/config/index', 'tool', 'system:config:list', 4, 1, 1);

-- AI管理子菜单
INSERT INTO `admin_menu` (`parent_id`, `name`, `type`, `path`, `component`, `icon`, `permission`, `sort`, `visible`, `status`) VALUES
(3, 'API密钥', 2, '/ai/apikey', 'ai/apikey/index', 'key', 'ai:apikey:list', 1, 1, 1),
(3, '模型管理', 2, '/ai/model', 'ai/model/index', 'component', 'ai:model:list', 2, 1, 1);

-- 日志管理子菜单
INSERT INTO `admin_menu` (`parent_id`, `name`, `type`, `path`, `component`, `icon`, `permission`, `sort`, `visible`, `status`) VALUES
(4, '操作日志', 2, '/log/operation', 'log/operation/index', 'log', 'log:operation:list', 1, 1, 1);

-- 用户管理按钮权限
INSERT INTO `admin_menu` (`parent_id`, `name`, `type`, `path`, `component`, `icon`, `permission`, `sort`, `visible`, `status`) VALUES
(6, '用户新增', 3, '', '', '', 'system:user:add', 1, 1, 1),
(6, '用户编辑', 3, '', '', '', 'system:user:edit', 2, 1, 1),
(6, '用户删除', 3, '', '', '', 'system:user:delete', 3, 1, 1),
(7, '角色新增', 3, '', '', '', 'system:role:add', 1, 1, 1),
(7, '角色编辑', 3, '', '', '', 'system:role:edit', 2, 1, 1),
(7, '角色删除', 3, '', '', '', 'system:role:delete', 3, 1, 1);

-- 超级管理员拥有所有菜单
INSERT INTO `admin_role_menu` (`role_id`, `menu_id`)
SELECT 1, `id` FROM `admin_menu`;

-- 管理员关联角色
INSERT INTO `admin_user_role` (`user_id`, `role_id`) VALUES (1, 1);

-- 系统配置
INSERT INTO `admin_config` (`group`, `name`, `code`, `value`, `type`, `description`, `sort`) VALUES
('basic', '站点名称', 'site_name', 'OpenClaw AI', 'string', '网站名称', 1),
('basic', '站点描述', 'site_description', '智能AI平台管理系统', 'string', '网站描述', 2),
('basic', '站点Logo', 'site_logo', '', 'string', '网站Logo URL', 3),
('email', 'SMTP主机', 'smtp_host', '', 'string', 'SMTP服务器地址', 1),
('email', 'SMTP端口', 'smtp_port', '465', 'number', 'SMTP端口', 2),
('email', 'SMTP用户', 'smtp_user', '', 'string', 'SMTP用户名', 3),
('email', 'SMTP密码', 'smtp_pass', '', 'string', 'SMTP密码', 4),
('storage', '存储方式', 'storage_driver', 'local', 'string', '存储方式(local/oss/s3)', 1),
('storage', '上传大小限制', 'upload_max_size', '10', 'number', 'MB', 2);
