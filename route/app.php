<?php
use think\facade\Route;

// 无需认证的路由
Route::group('api/admin', function () {
    Route::post('auth/login', 'admin.Auth/login');
    Route::post('auth/refresh', 'admin.Auth/refresh');
})->allowCrossDomain();

// 需要认证的路由
Route::group('api/admin', function () {
    // 认证相关
    Route::post('auth/logout', 'admin.Auth/logout');
    Route::get('auth/info', 'admin.Auth/info');

    // 数据看板
    Route::get('dashboard/stats', 'admin.Dashboard/stats');
    Route::get('dashboard/trend', 'admin.Dashboard/trend');
    Route::get('dashboard/api-stats', 'admin.Dashboard/apiStats');
    Route::get('dashboard/model-distribution', 'admin.Dashboard/modelDistribution');

    // 用户管理
    Route::get('user', 'admin.User/index');
    Route::get('user/:id', 'admin.User/read');
    Route::post('user', 'admin.User/add');
    Route::put('user/:id', 'admin.User/edit');
    Route::delete('user/:id', 'admin.User/delete');
    Route::put('user/toggle-status/:id', 'admin.User/toggleStatus');
    Route::post('user/reset-password/:id', 'admin.User/resetPassword');

    // 角色管理
    Route::get('role', 'admin.Role/index');
    Route::get('role/all', 'admin.Role/all');
    Route::get('role/:id', 'admin.Role/read');
    Route::post('role', 'admin.Role/add');
    Route::put('role/:id', 'admin.Role/edit');
    Route::delete('role/:id', 'admin.Role/delete');
    Route::post('role/assign-menus/:id', 'admin.Role/assignMenus');
    Route::put('role/toggle-status/:id', 'admin.Role/toggleStatus');

    // 菜单管理
    Route::get('menu', 'admin.Menu/index');
    Route::get('menu/tree', 'admin.Menu/tree');
    Route::get('menu/user-tree', 'admin.Menu/userMenuTree');
    Route::get('menu/:id', 'admin.Menu/read');
    Route::post('menu', 'admin.Menu/add');
    Route::put('menu/:id', 'admin.Menu/edit');
    Route::delete('menu/:id', 'admin.Menu/delete');

    // API密钥管理
    Route::get('apikey', 'admin.ApiKey/index');
    Route::get('apikey/:id', 'admin.ApiKey/read');
    Route::post('apikey', 'admin.ApiKey/add');
    Route::put('apikey/:id', 'admin.ApiKey/edit');
    Route::delete('apikey/:id', 'admin.ApiKey/delete');
    Route::put('apikey/toggle-status/:id', 'admin.ApiKey/toggleStatus');

    // AI模型管理
    Route::get('model', 'admin.Model/index');
    Route::get('model/:id', 'admin.Model/read');
    Route::post('model', 'admin.Model/add');
    Route::put('model/:id', 'admin.Model/edit');
    Route::delete('model/:id', 'admin.Model/delete');
    Route::put('model/toggle-status/:id', 'admin.Model/toggleStatus');

    // 系统配置
    Route::get('config', 'admin.Config/index');
    Route::post('config/batch-update', 'admin.Config/batchUpdate');

    // 操作日志
    Route::get('log', 'admin.Log/index');
    Route::get('log/:id', 'admin.Log/read');

    // 文件管理
    Route::get('file', 'admin.File/index');
    Route::post('file/upload', 'admin.File/upload');
    Route::delete('file/:id', 'admin.File/delete');

    // 个人信息
    Route::get('profile', 'admin.Profile/info');
    Route::put('profile', 'admin.Profile/update');
    Route::post('profile/change-password', 'admin.Profile/changePassword');
    Route::post('profile/upload-avatar', 'admin.Profile/uploadAvatar');

    // 支付配置管理
    Route::get('payment/config', 'admin.PaymentConfig/index');
    Route::put('payment/config/save', 'admin.PaymentConfig/save');
    Route::post('payment/test', 'admin.PaymentConfig/test');

    // 支付宝配置管理
    Route::get('alipay/config', 'admin.AlipayConfig/index');
    Route::put('alipay/config', 'admin.AlipayConfig/save');
    Route::post('alipay/test', 'admin.AlipayConfig/test');

    // 短信配置管理
    Route::get('sms/config', 'admin.SmsConfig/index');
    Route::get('sms/config/:id', 'admin.SmsConfig/read');
    Route::post('sms/config', 'admin.SmsConfig/add');
    Route::put('sms/config/:id', 'admin.SmsConfig/edit');
    Route::delete('sms/config/:id', 'admin.SmsConfig/delete');
    Route::put('sms/config/toggle-status/:id', 'admin.SmsConfig/toggleStatus');
    Route::put('sms/config/set-default/:id', 'admin.SmsConfig/setDefault');

    // 文章分类管理
    Route::get('article/category/tree', 'admin.ArticleCategory/index');
    Route::get('article/category/all', 'admin.ArticleCategory/all');
    Route::post('article/category', 'admin.ArticleCategory/add');
    Route::put('article/category/:id', 'admin.ArticleCategory/edit');
    Route::delete('article/category/:id', 'admin.ArticleCategory/delete');

    // 文章管理
    Route::get('article', 'admin.Article/index');
    Route::get('article/:id', 'admin.Article/read');
    Route::post('article', 'admin.Article/add');
    Route::put('article/:id', 'admin.Article/edit');
    Route::delete('article/:id', 'admin.Article/delete');
    Route::put('article/toggle-status/:id', 'admin.Article/toggleStatus');
    Route::put('article/toggle-top/:id', 'admin.Article/toggleTop');
    Route::put('article/toggle-recommend/:id', 'admin.Article/toggleRecommend');
})->middleware([\app\middleware\Auth::class, \app\middleware\Permission::class, \app\middleware\OperationLog::class]);
