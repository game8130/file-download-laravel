<?php

/**
 * 功能權限設置相關
 * 資料格式說明：
 *     func_key：分類的 Key 值
 *     name：分類名稱
 *     permission：是否有功能權限設置(true：有,false：無)
 *     menu：[ 所屬該分類的功能清單
 *         [
 *             func_key：該功能的 Key 值
 *             name：功能名稱
 *             permission：是否有功能權限設置(true：有,false：無)
 *             operation: 是否有操作日誌設置(true：有,false：無)
 *             action：[ 該功能對應操作權限設定(Ex.刪除注單)
 *                 [
 *                     func_key：操作 Key 值
 *                     name：操作名稱
 *                 ],
 *                 ...
 *             ],
 *         ],
 *         ...
 *     ],
 *
 */

return [
    'permission' => [
        // 後台
        [
            'func_key'   => 10,
            'name'       => '人事管理系統',
            'permission' => true,
            'action'     => [],
            'menu'       => [
                [
                    'func_key'   => 1001,
                    'name'       => '人員管理',
                    'route'      => 'user',
                    'permission' => true,
                    'operation'  => true,
                    'action'     => [],
                ],
                [
                    'func_key'   => 1002,
                    'name'       => '權限管理',
                    'route'      => 'group',
                    'permission' => true,
                    'operation'  => true,
                    'action'     => [],
                ],
                [
                    'func_key'   => 1003,
                    'name'       => '操作日誌',
                    'route'      => 'user_operation',
                    'permission' => true,
                    'operation'  => false,
                    'action'     => [],
                ],
                [
                    'func_key'   => 1004,
                    'name'       => '登入日誌',
                    'route'      => 'user_login',
                    'permission' => true,
                    'operation'  => false,
                    'action'     => [],
                ],
                [
                    'func_key'   => 1005,
                    'name'       => '人員特休管理',
                    'route'      => 'user_information',
                    'permission' => true,
                    'operation'  => false,
                    'action'     => [],
                ],
            ],
        ],
        [
            'func_key'   => 11,
            'name'       => '檔案管理',
            'permission' => true,
            'action'     => [],
            'menu'       => [
                [
                    'func_key'   => 1101,
                    'name'       => '檔案管理',
                    'route'      => 'file',
                    'permission' => true,
                    'operation'  => true,
                    'action'     => [],
                ],
            ],
        ],
    ],
];
