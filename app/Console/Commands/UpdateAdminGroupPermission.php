<?php

namespace App\Console\Commands;

use App\Entities\Group\Groups;
use Illuminate\Console\Command;
use App\Entities\Permission\Permission;

class UpdateAdminGroupPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file-download:updatePermission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新管理者權限群組(用於新增功能)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $adminGroupName = config('default.adminGroupName');
        $group = Groups::where('name', $adminGroupName)->first();
        if ($group == null) {
            $this->error('找不到權限管理名稱為「' . $adminGroupName . '」的資料!!!');
            exit;
        }
        $groupID = $group->id;
        Permission::where('group_id', $groupID)->delete();
        // 新增
        foreach (config('permission.permission') as $cateValue) {
            // 分類
            if ($cateValue['permission']) {
                $this->createPermission($groupID, $cateValue['func_key']);
            }
            foreach ($cateValue['menu'] as $menuValue) {
                // 功能
                if ($menuValue['permission']) {
                    $this->createPermission($groupID, $menuValue['func_key']);
                }
                // 操作
                foreach ($menuValue['action'] as $actionValue) {
                    $this->createPermission($groupID, $actionValue['func_key']);
                }
            }
        }
        $this->info('更新「' . $adminGroupName . '」的權限結束。');
    }

    /**
     * 新增功能權限
     *
     * @param  integer $groupID
     * @param  string  $funcKey
     */
    private function createPermission($groupID, $funcKey)
    {
        Permission::create([
            'group_id'      => $groupID,
            'func_key'      => $funcKey,
        ]);
    }
}
