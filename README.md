# OpenClaw AI Admin

基于 **ThinkPHP 8.0 + Vue 2.0 + ElementUI** 的后台管理系统，采用前后端分离架构，支持 RBAC 权限管理、JWT 认证、多支付渠道配置等功能。

## 技术栈

| 层级 | 技术 | 版本 |
|------|------|------|
| 后端框架 | ThinkPHP | 8.1 |
| 前端框架 | Vue | 2.x |
| UI 组件库 | ElementUI | 2.15 |
| 数据库 | MySQL | 8.0+ |
| 认证方式 | JWT (firebase/php-jwt) | 7.x |
| 状态管理 | Vuex | 3.x |
| HTTP 客户端 | Axios | - |
| 图表库 | ECharts | 5.x |

## 功能模块

### 系统管理
- **用户管理** - 管理员账号的增删改查、状态切换、密码重置
- **角色管理** - 角色分配、权限授权（树形菜单勾选）
- **菜单管理** - 动态菜单配置，支持目录/菜单/按钮三级
- **系统配置** - 分组式系统参数管理

### AI 管理
- **API 密钥** - AI 服务 API Key 的管理与状态控制
- **AI 模型** - 模型列表管理、启用/禁用切换

### 内容管理
- **文章管理** - 文章 CRUD、分类选择、封面上传、置顶/推荐、发布/下架
- **支付配置**
  - 微信支付：商户号、AppID、API密钥、证书路径、回调地址、沙箱模式
  - 支付宝：AppID、应用私钥、支付宝公钥、签名方式、异步/同步回调地址
- **短信配置** - 多短信渠道管理（阿里云/腾讯云等）、每日用量统计

### 其他功能
- **数据看板** - ECharts 图表展示（用户趋势、API 调用分布）
- **操作日志** - 用户操作记录查询与详情查看
- **文件管理** - 文件上传与管理
- **个人中心** - 修改个人信息、头像上传、密码修改

## 项目结构

```
thinkphp8_admin/
├── app/                          # 后端应用目录
│   ├── controller/admin/         # 控制器（16个）
│   │   ├── Auth.php              #   登录认证
│   │   ├── Dashboard.php         #   数据看板
│   │   ├── User.php              #   用户管理
│   │   ├── Role.php              #   角色管理
│   │   ├── Menu.php              #   菜单管理
│   │   ├── ApiKey.php            #   API密钥管理
│   │   ├── Model.php             #   AI模型管理
│   │   ├── Config.php            #   系统配置
│   │   ├── Log.php               #   操作日志
│   │   ├── File.php              #   文件管理
│   │   ├── Profile.php           #   个人中心
│   │   ├── PaymentConfig.php     #   微信支付配置
│   │   ├── AlipayConfig.php      #   支付宝配置
│   │   ├── SmsConfig.php         #   短信渠道配置
│   │   ├── ArticleCategory.php   #   文章分类
│   │   └── Article.php           #   文章管理
│   ├── model/                    # 模型层（18个）
│   ├── middleware/               # 中间件（CORS / Auth / Permission / OperationLog）
│   ├── service/                  # 服务层（Token / Auth / User / Role / Menu）
│   └── common.php                # 全局响应函数 success() / error()
├── config/                       # 配置文件
│   ├── database.php              #   数据库连接
│   ├── cors.php                  #   跨域配置
│   └── jwt.php                   #   JWT 配置
├── route/app.php                 # API 路由定义
├── database/                     # SQL 脚本
│   ├── install.sql               #   主数据库（11张基础表 + 种子数据）
│   ├── add_features.sql          #   扩展功能表（支付/短信/文章/分类）
│   └── alipay.sql                #   支付宝配置表
├── frontend/                     # 前端 SPA 目录
│   ├── src/
│   │   ├── api/                  #   API 接口定义（15个模块）
│   │   ├── views/                #   页面组件
│   │   │   ├── login/            #     登录页
│   │   │   ├── dashboard/        #     数据看板
│   │   │   ├── system/           #     系统（用户/角色/菜单/配置）
│   │   │   ├── ai/               #     AI（密钥/模型）
│   │   │   ├── content/          #     内容（文章/支付/短信）
│   │   │   ├── log/              #     日志
│   │   │   ├── file/             #     文件
│   │   │   └── profile/          #     个人中心
│   │   ├── layout/               #   布局组件（侧边栏/导航栏/标签栏）
│   │   ├── router/index.js       #   路由配置
│   │   ├── store/modules/        #   Vuex 模块（user/permission/app/tagsView/settings）
│   │   ├── styles/               #   SCSS 样式（变量/全局/侧边栏/覆盖）
│   │   └── utils/                #   工具函数（request/auth）
│   └── vue.config.js             #   Webpack 配置
├── public/                       # 入口目录
│   ├── index.php                 #   后端入口
│   └── dist/                     #   前端构建产物
├── .env                          # 环境变量
├── composer.json                 # PHP 依赖
└── frontend/package.json         # Node.js 依赖
```

## 快速开始

### 环境要求

- PHP >= 8.0
- MySQL >= 8.0
- Node.js >= 14.0
- Composer
- npm / yarn

### 1. 克隆项目

```bash
git clone https://github.com/ddashu/thinkphp8_admin.git
cd thinkphp8_admin
```

### 2. 后端配置

```bash
# 安装 PHP 依赖
composer install

# 复制环境变量文件
cp .env.example .env

# 编辑 .env，配置数据库连接信息
```

`.env` 关键配置项：

```env
DB_HOST = 127.0.0.1
DB_NAME = thinkphp8_admin
DB_USER = root
DB_PASS = root
DB_PORT = 3306
JWT_SECRET = your_jwt_secret_key_here
```

### 3. 导入数据库

```bash
mysql -u root -p your_database < database/install.sql
mysql -u root -p your_database < database/add_features.sql
mysql -u root -p your_database < database/alipay.sql
```

或使用 Navicat / phpMyAdmin 等工具依次导入三个 SQL 文件。

### 4. 前端安装与构建

```bash
cd frontend

# 安装依赖
npm install

# 开发模式运行（端口 8080，代理到后端 8000）
npm run serve

# 生产构建
npm run build
```

### 5. 启动服务

- 后端：通过小皮面板 / phpStudy 配置站点，指向 `public` 目录
- 前端开发：`npm run serve` 启动在 `http://localhost:8080`
- 生产部署：前端构建产物输出到 `public/dist`，直接访问后端地址即可

## 默认账号

| 角色 | 用户名 | 密码 |
|------|--------|------|
| 超级管理员 | admin | admin123 |

> 首次登录后请及时修改默认密码

## API 接口

所有接口统一前缀：`/api/admin`

| 模块 | 方法 | 路径 | 说明 |
|------|------|------|------|
| 认证 | POST | `/auth/login` | 登录获取 Token |
| 认证 | POST | `/auth/refresh` | 刷新 Token |
| 认证 | GET | `/auth/info` | 获取当前用户信息 |
| 看板 | GET | `/dashboard/stats` | 统计概览 |
| 用户 | GET | `/user` | 用户列表 |
| 用户 | POST | `/user` | 新增用户 |
| 用户 | PUT | `/user/:id` | 编辑用户 |
| 角色 | GET | `/role` | 角色列表 |
| 角色 | POST | `/role/assign-menus/:id` | 分配权限 |
| 菜单 | GET | `/menu/tree` | 菜单树 |
| 支付 | GET | `/payment/config` | 微信支付配置 |
| 支付 | GET | `/alipay/config` | 支付宝配置 |
| 短信 | GET | `/sms/config` | 短信渠道列表 |
| 文章 | GET | `/article` | 文章列表 |
| 文章 | GET | `/article/category/tree` | 分类树 |
| ... | ... | ... | 更多路由见 `route/app.php` |

## 核心特性

- **RBAC 权限系统**：用户 → 角色 → 菜单（按钮）三级权限模型
- **JWT 双 Token**：Access Token（2h）+ Refresh Token（7d）无感刷新
- **统一响应格式**：`{ code, message, data }`
- **操作日志中间件**：自动记录所有写操作
- **动态路由**：根据角色权限动态生成侧边栏菜单
- **深色侧边栏 + 浅色内容区** UI 设计风格

## License

MIT
