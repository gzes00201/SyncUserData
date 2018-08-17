<?php

namespace App\Console\Commands;

use ErrorException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Services\UserService;

class SyncUserDataFromUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'syncUser:fromUrl {--url= : 要同步的url位址}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '從url同步User資料';

    /**
     *
     * @var DripEmailer
     */
    protected $drip;

    /**
     *
     * @var _userService
     */
    protected $_userService;

    /**
     * Create a new command instance.
     *
     * @param  UserService $_userService
     * @return void
     */
    public function __construct(UserService $_userService)
    {
        parent::__construct();
        $this->_userService = $_userService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $url = $this->option('url');

        if (!$this->CheckOptionHasUrl($url)) {
            return false;
        }

        $userData = $this->getUserFromUrl($url);
        $this->syncUserData($userData);
    }

    /**
     * 執行會員資料更新
     *
     * @param  Object $userData
     * @return void
     */
    private function syncUserData($userData)
    {

        if (count($userData) > 0) {
            foreach ($userData as $user) {
                $this->_userService->syncUserData($user);
            }
        }
    }


    /**
     * 取得使用資料
     *
     * @param  String $url
     * @return userDatas
     */
    private function getUserFromUrl($url)
    {
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $url);

        if ($res->getStatusCode() !== 200) {
            throw new ErrorException('取得資料異常，不執行此次User資料同步');
        }
        $userData = \GuzzleHttp\json_decode($res->getBody(), true);

        if (!$userData) {
            throw new ErrorException('User資料解析異常');
        }

        return $userData;
    }


    /**
     * 確認有傳入網址參數
     *
     * @param  String $url
     * @return boolean
     */
    public function CheckOptionHasUrl($url)
    {
        if (empty($url) || $url === '') {

            Log::error('未輸入外部網址');
            return false;
        }

        Log::info('進行資料同步，同步目標', ['url' => $url]);
        return true;
    }


}
