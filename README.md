# 量子Admin - 智能AI平台管理系统

基于 **ThinkPHP 8 + Vue 2 + ElementUI** 的企业级后台管理系统，采用前后端分离架构，集成 RBAC 权限管理、JWT 认证、多支付渠道配置、富文本编辑器等功能。

> **开发团队**：量子软件工作室 | **官网**：[www.dglzsoft.com](http://www.dglzsoft.com) | **技术支持微信**：yework2016

## 技术栈

| 层级 | 技术 | 版本 | 说明 |
|------|------|------|------|
| 后端框架 | ThinkPHP | 8.1 | PHP 全栈框架 |
| 前端框架 | Vue | 2.x | 渐进式 SPA 框架 |
| UI 组件库 | ElementUI | 2.15 | 企业级组件库 |
| 富文本编辑器 | Quill | 1.x | 文章内容编辑（vue-quill-editor） |
| 图表库 | ECharts | 5.x | 数据可视化看板 |
| 数据库 | MySQL | 8.0+ | 关系型数据库 |
| 认证方式 | JWT | 7.x | firebase/php-jwt 双令牌 |
| 状态管理 | Vuex | 3.x | 集中式状态管理 |
| HTTP 客户端 | Axios | - | 请求拦截与响应处理 |

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
- **文章管理** - Quill 富文本编辑器、分类选择、封面上传、置顶/推荐、发布/下架
- **支付配置**
  - 微信支付：商户号、AppID、API密钥、证书路径、回调地址、沙箱模式
  - 支付宝：AppID、应用私钥(PKCS8)、支付宝公钥、签名方式(RSA2/RSA)、异步/同步回调地址
- **短信配置** - 多短信渠道管理（阿里云/腾讯云等）、每日用量统计

### 其他功能
- **数据看板** - ECharts 图表展示（用户趋势、API 调用分布、模型使用情况）
- **操作日志** - 用户操作记录查询与详情查看
- **文件管理** - 文件上传与管理
- **个人中心** - 修改个人信息、头像上传、密码修改

## 项目结构

```
thinkphp8_admin/
├── html/                         # 功能介绍页面
│   ├── index.html                #   单页介绍页（响应式）
│   └── images/                   #   后台功能截图（8张）
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
│   │   ├── SmsConfig.py          #   短信渠道配置
│   │   ├── ArticleCategory.py    #   文章分类
│   │   └── Article.py            #   文章管理（含富文本）
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
│   │   │   ├── login/            #     登录页（含公司信息底部）
│   │   │   ├── dashboard/        #     数据看板
│   │   │   ├── system/           #     系统（用户/角色/菜单/配置）
│   │   │   ├── ai/               #     AI（密钥/模型）
│   │   │   ├── content/          #     内容（文章/支付/短信）
│   │   │   ├── log/              #     日志
│   │   │   ├── file/             #     文件
│   │   │   └── profile/          #     个人中心
│   │   ├── layout/               #   布局组件（侧边栏含公司信息底部）
│   │   ├── router/index.js       #   路由配置
│   │   ├── store/modules/        #   Vuex 模块
│   │   ├── styles/               #   SCSS 样式
│   │   └── utils/                #   工具函数
│   └── vue.config.js             #   Webpack 配置
├── public/                       # 入口目录
│   ├── index.php                 #   后端入口
│   └── dist/                     #   前端构建产物
├── .env                          # 环境变量
├── .gitignore                    # Git 忽略规则
├── composer.json                 # PHP 依赖
├── README.md                     # 项目文档
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
- 功能介绍页面：直接浏览器打开 `html/index.html` 即可预览

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
| 支付 | PUT | `/payment/config/save` | 保存微信支付配置 |
| 支付 | GET | `/alipay/config` | 支付宝配置 |
| 支付 | PUT | `/alipay/config` | 保存支付宝配置 |
| 短信 | GET | `/sms/config` | 短信渠道列表 |
| 短信 | POST | `/sms/config` | 新增短信渠道 |
| 文章 | GET | `/article` | 文章列表 |
| 文章 | POST | `/article` | 新增文章 |
| 文章 | PUT | `/article/:id` | 编辑文章 |
| 文章 | GET | `/article/category/tree` | 分类树 |
| ... | ... | ... | 更多路由见 `route/app.php` |

## 核心特性

- **RBAC 权限系统**：用户 → 角色 → 菜单（按钮）三级权限模型
- **JWT 双 Token**：Access Token（2h）+ Refresh Token（7d）无感刷新
- **统一响应格式**：`{ code, message, data }`
- **操作日志中间件**：自动记录所有写操作
- **动态路由**：根据角色权限动态生成侧边栏菜单
- **富文本编辑器**：Quill 集成，支持加粗/斜体/标题/列表/对齐/颜色/链接/图片等
- **多渠道支付**：微信支付 + 支付宝双 Tab 配置界面
- **深色侧边栏 + 浅色内容区** UI 设计风格
- **品牌定制**：登录页/侧边栏底部展示公司信息（量子软件工作室）

## License

MIT

---

本程序由 [量子软件工作室](http://www.dglzsoft.com) 开发  
技术支持微信：yework2016
