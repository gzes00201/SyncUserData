<?php

namespace App\Services;

use App\User;
use ErrorException;
use Illuminate\Support\Facades\Log;

class UserService
{

    /**
     * 同步使用者資料
     *
     * @param  Object $userData
     * @return void
     */
    public function syncUserData($userData)
    {
        if (!$this->CheckOptionHasIdAndUsername($userData))
        {
          return false;
        }
        $this->syncUser($userData);
    }

    /**
     * 同步使用者資料(新增/更新)
     *
     * @param  Object $userData
     * @return void
     */
    private function syncUser($userData)
    {
        $targetUser = User::find($userData['id']);
        if (empty($targetUser)) {
            $this->createNewUser($userData);
            return;
        }

        $this->updateNewUser($targetUser, $userData);
    }

    /**
     * 新增使用者
     *
     * @param  Object $userData
     * @return void
     */
    private function createNewUser($userData)
    {
        Log::info('新使用者,進行使用者新增');
        User::create([
            'name' => array_get($userData, 'name'),
            'username' => array_get($userData, 'username'),
            'email' => array_get($userData, 'email'),
            'phone' => array_get($userData, 'phone'),
            'website' => array_get($userData, 'website'),
            'password' => bcrypt('testtest'),
        ]);
    }

    /**
     * 更新使用者資料
     *
     * @param  User $targetUser
     * @param  Object $userData
     * @return void
     */
    private function updateNewUser(User $targetUser, $userData)
    {
        Log::info('更新User資料,進行使用者新增', ['data' => $userData]);
        $targetUser->update([
            'name' => array_get($userData, 'name'),
            'username' => array_get($userData, 'username'),
            'email' => array_get($userData, 'email'),
            'phone' => array_get($userData, 'phone'),
            'website' => array_get($userData, 'website'),
        ]);
    }

    /**
     * 檢查是否有IP與Username的參數
     *
     * @param  Object $userData
     * @return boolean
     */
    private function CheckOptionHasIdAndUsername($userData)
    {
        if (empty($userData['id'])) {
            Log::error('缺少User id無法處理', ['data' => $userData]);
            return false;

        }

        if (empty($userData['username'])) {
            Log::info('缺少User username無法處理', ['data' => $userData]);
            return false;
        }
        return true;
    }
}

?>