<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    protected $table = 'login_log';
    protected $guarded = ['id'];

    public static function getLoginLog($request)
    {
        $data = $request->all(['created_at_start', 'created_at_end', 'username']);

        $result = self::when($data['username'], function ($query) use ($data) {
            return $query->where('username', 'like', '%' . $data['username'] . '%');
        })->when($data['created_at_start'] && !$data['created_at_end'], function ($query) use ($data) {
            return $query->where('created_at', '>=', $data['created_at_start']);
        })->when(!$data['created_at_start'] && $data['created_at_end'], function ($query) use ($data) {
            return $query->where('created_at', '<=', $data['created_at_end']);
        })->when($data['created_at_start'] && $data['created_at_end'], function ($query) use ($data) {
            return $query->whereBetween('created_at', [$data['created_at_start'], $data['created_at_end']]);
        })->orderBy('id', 'desc')->paginate($request->get('limit', 30));

        return $result;
    }


}
