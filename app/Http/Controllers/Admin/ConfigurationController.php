<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use App\Service\Contract\ConfigGroupServiceInterface;

class ConfigurationController extends Controller
{
    protected $configService;

    public function __construct(ConfigGroupServiceInterface $configGroupService)
    {
        $this->configService = $configGroupService;
    }

    /**
     * 配置主页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Swoft\Http\Message\Response|\think\response\View
     * @throws \Throwable
     */
    public function index()
    {
        $groups = $this->configService->getManyConfigWithConfigurations();
        return view('admin.configuration.index', compact('groups'));
    }

    /**
     * 添加配置
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Swoft\Http\Message\Response|\think\response\View
     * @throws \Throwable
     */
    public function create()
    {
        $groups = $this->configService->getConfigs();
        return view('admin.configuration.create', compact('groups'));
    }

    /**
     * 添加配置
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all(['group_id', 'label', 'key', 'val', 'type', 'tips', 'sort']);
            $this->configService->storeConfiguration($data);
        } catch (\Exception $exception) {
            return Redirect::back()->withErrors('添加失败');
        }
        //缓存配置信息
        $configuration = $this->configService->cacheConfiguration();
        $request->session()->put('configuration', $configuration);
        return Redirect::to(URL::route('admin.configuration'))->with(['success' => '添加成功']);
    }

    /**
     * 更新配置
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {

        DB::beginTransaction();
        try {
            $data = $request->except(['_token', 'id']);
            foreach ($data as $k => $v) {
                $this->configService->updateConfiguration($k, $v);
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            return Response::json(['code' => 1, 'msg' => '更新失败']);
        }
        //缓存配置信息
        $configuration = $this->configService->cacheConfiguration();
        $request->session()->put('configuration', $configuration);
        return Response::json(['code' => 0, 'msg' => '更新成功']);
    }

}
