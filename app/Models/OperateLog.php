<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperateLog extends Model
{
    protected $table = 'operate_log';
    protected $guarded = ['id'];

    public static function getOperaLog($request)
    {
        $data = $request->all(['created_at_start', 'created_at_end']);

        $result = OperateLog::with('user:id,username')
            ->when($data['created_at_start'] && !$data['created_at_end'], function ($query) use ($data) {
                return $query->where('created_at', '>=', $data['created_at_start']);
            })->when(!$data['created_at_start'] && $data['created_at_end'], function ($query) use ($data) {
                return $query->where('created_at', '<=', $data['created_at_end']);
            })->when($data['created_at_start'] && $data['created_at_end'], function ($query) use ($data) {
                return $query->whereBetween('created_at', [$data['created_at_start'], $data['created_at_end']]);
            })->orderBy('id', 'desc')->paginate($request->get('limit', 30));

        return $result;
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
