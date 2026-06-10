<?php
namespace app\controller\admin;

use app\model\AdminUser;
use app\model\AdminFile;

class Profile extends BaseAdmin
{
    /**
     * 获取个人信息
     */
    public function info()
    {
        $user = AdminUser::with('roles')->find($this->userId);
        if (!$user) {
            return error('用户不存在', 404);
        }
        $user->hidden(['password']);
        return success($user);
    }

    /**
     * 更新个人信息
     */
    public function update()
    {
        $data = $this->request->put();
        $user = AdminUser::find($this->userId);
        if (!$user) {
            return error('用户不存在', 404);
        }

        // 只允许修改以下字段
        $allowFields = ['nickname', 'email', 'phone'];
        $updateData  = [];
        foreach ($allowFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }

        if (!empty($updateData)) {
            $user->save($updateData);
        }

        $user->hidden(['password']);
        return success($user, '更新成功');
    }

    /**
     * 修改密码
     */
    public function changePassword()
    {
        $oldPassword = $this->request->post('old_password', '');
        $newPassword = $this->request->post('new_password', '');

        if (empty($oldPassword) || empty($newPassword)) {
            return error('旧密码和新密码不能为空');
        }

        $user = AdminUser::find($this->userId);
        if (!$user) {
            return error('用户不存在', 404);
        }

        if (!password_verify($oldPassword, $user->password)) {
            return error('旧密码错误');
        }

        $user->password = password_hash($newPassword, PASSWORD_BCRYPT);
        $user->save();

        return success(null, '密码修改成功');
    }

    /**
     * 上传头像
     */
    public function uploadAvatar()
    {
        $file = $this->request->file('avatar');
        if (!$file) {
            return error('请选择要上传的头像');
        }

        try {
            $uploadDir = public_path() . 'uploads/avatars';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $extension = $file->getOriginalExtension();
            $fileName  = $this->userId . '_' . md5(uniqid()) . '.' . $extension;
            $filePath  = $uploadDir . '/' . $fileName;

            move_uploaded_file($file->getPathname(), $filePath);

            $url = '/uploads/avatars/' . $fileName;

            $user = AdminUser::find($this->userId);
            $user->avatar = $url;
            $user->save();

            return success(['avatar' => $url], '上传成功');
        } catch (\Exception $e) {
            return error('上传失败: ' . $e->getMessage());
        }
    }
}
