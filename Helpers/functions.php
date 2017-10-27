<?php
/**
 * @param int $code 返回信息代码
 * @param string $msg   返回信息类型，error，success
 * @param null $paras   返回得到的数据
 * @return \Illuminate\Http\JsonResponse
 */
function responseToJson($code = 0, $msg = '', $paras = null)
{
    $res["code"] = $code;
    $res["msg"] = $msg;
    // if (!empty($paras)) {
    $res["result"] = $paras;
    // }
    return response()->json($res);
}

function get_session_user()
{
    return session('user');
}

function get_session_user_id()
{
    $user = session("user");
    return $user ? $user->id : 0;
}