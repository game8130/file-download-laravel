<?php

namespace App\Services\User;

use Jenssegers\Agent\Agent;
use App\Services\Common\IpToAreaServices;
use App\Repositories\User\UsersLoginRepository;

class UsersLoginServices
{
    private $ipToAreaServices;
    private $usersLoginRepository;

    public function __construct(
        IpToAreaServices $ipToAreaServices,
        UsersLoginRepository $usersLoginRepository

    ) {
        $this->ipToAreaServices = $ipToAreaServices;
        $this->usersLoginRepository = $usersLoginRepository;
    }

    /**
     * 整理要新增登入日誌資訊
     *
     * @param array    $user    [需有id、account兩個欄位資料]
     * @param string   $ip      [目前IP]
     * @param integer  $active  [登入狀態]
     *
     * @return mixed|string
     */
    public function storeLogin($user, $ip = '', $active = 1)
    {
        try {
            $ip = get_real_ip($ip);
            $agent = new Agent();
            $browser = $agent->browser();
            $platform = $agent->platform();
            $deviceInfo = [
                'device'           => $agent->device(),
                'browser'          => $browser,
                'platform'         => $platform,
                'browser_version'  => $agent->version($browser),
                'platform_version' => $agent->version($platform)
            ];

            return $this->usersLoginRepository->store([
                'user_id'      => $user['id'],
                'user_account' => $user['account'],
                'login_ip'     => $ip,
                'device'       => $agent->isMobile() ? 2 : 1,
                'device_info'  => $deviceInfo,
                'area'         => $this->ipToAreaServices->ipToArea($ip),
                'active'       => $active,
            ]);
        } catch (\Exception $e) {
            // 記錄錯誤
            return $e->getMessage();
        }
    }
}
