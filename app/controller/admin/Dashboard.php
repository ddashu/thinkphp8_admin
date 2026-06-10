<?php
namespace app\controller\admin;

use app\model\AdminUser;
use app\model\AdminApiKey;
use app\model\AdminModel;
use app\model\AdminLog;
use app\model\AdminLoginLog;
use think\facade\Db;

class Dashboard extends BaseAdmin
{
    /**
     * 统计概览
     */
    public function stats()
    {
        $userCount    = AdminUser::count();
        $apiKeyCount  = AdminApiKey::where('status', 1)->count();
        $modelCount   = AdminModel::where('status', 1)->count();
        $todayLogCount = AdminLog::whereDay('create_time')->count();

        return success([
            'user_count'     => $userCount,
            'api_key_count'  => $apiKeyCount,
            'model_count'    => $modelCount,
            'today_log_count' => $todayLogCount,
        ]);
    }

    /**
     * 趋势数据（近7天）
     */
    public function trend()
    {
        $days = [];
        $loginCounts = [];
        $operationCounts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $days[] = $date;

            $loginCounts[] = AdminLoginLog::whereDay('create_time', $date)
                ->where('status', 1)
                ->count();

            $operationCounts[] = AdminLog::whereDay('create_time', $date)
                ->count();
        }

        return success([
            'dates'            => $days,
            'login_counts'     => $loginCounts,
            'operation_counts' => $operationCounts,
        ]);
    }

    /**
     * API调用统计
     */
    public function apiStats()
    {
        $totalKeys    = AdminApiKey::count();
        $activeKeys   = AdminApiKey::where('status', 1)->count();
        $totalUsed    = AdminApiKey::sum('quota_used');
        $expiredKeys  = AdminApiKey::where('expires_at', '<', date('Y-m-d H:i:s'))->count();

        return success([
            'total_keys'  => $totalKeys,
            'active_keys' => $activeKeys,
            'total_used'  => $totalUsed,
            'expired_keys' => $expiredKeys,
        ]);
    }

    /**
     * 模型分布
     */
    public function modelDistribution()
    {
        $distribution = AdminModel::where('status', 1)
            ->group('category')
            ->field('category, count(*) as count')
            ->select()
            ->toArray();

        $providerDistribution = AdminModel::where('status', 1)
            ->group('provider')
            ->field('provider, count(*) as count')
            ->select()
            ->toArray();

        return success([
            'category' => $distribution,
            'provider' => $providerDistribution,
        ]);
    }
}
