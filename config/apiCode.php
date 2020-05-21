<?php

// api code

return [
    /**
     * HTTP STATUS CODE : ２００
     */
    // 執行成功
    'success'             => 200001,

    /**
     * HTTP STATUS CODE    : ４０１
     */
    // 登入失敗
    'invalidCredentials'  => 401001,

    /**
     * HTTP STATUS CODE    : ４０３
     */
    // 無該功能權限
    'invalidPermission'  => 403001,

    /**
     * HTTP STATUS CODE    : ４２２
     */
    // 驗證失敗
    'validateFail'        => 422001,

    /**
     * HTTP STATUS CODE    : ５００
     */
    // 未傳遞 API Code
    'notAPICode'          => 500001,
    // 產生 Token 異常
    'couldNotCreateToken' => 500002,
];
