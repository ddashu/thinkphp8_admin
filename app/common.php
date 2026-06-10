<?php
// 应用公共文件

// 统一成功响应
function success($data = null, $message = 'success', $code = 200)
{
    return json(['code' => $code, 'message' => $message, 'data' => $data]);
}

// 统一错误响应
function error($message = 'error', $code = 400, $data = null)
{
    return json(['code' => $code, 'message' => $message, 'data' => $data]);
}
