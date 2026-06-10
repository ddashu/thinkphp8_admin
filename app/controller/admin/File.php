<?php
namespace app\controller\admin;

use app\model\AdminFile;

class File extends BaseAdmin
{
    /**
     * 文件列表
     */
    public function index()
    {
        $params = $this->getPagination();
        $query  = AdminFile::order('id', 'desc');

        $name = $this->request->param('name', '');
        if (!empty($name)) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        $extension = $this->request->param('extension', '');
        if (!empty($extension)) {
            $query->where('extension', $extension);
        }

        $list = $query->paginate([
            'page'      => $params['page'],
            'list_rows' => $params['page_size'],
        ]);

        return success([
            'list'      => $list->items(),
            'total'     => $list->total(),
            'page'      => $params['page'],
            'page_size' => $params['page_size'],
        ]);
    }

    /**
     * 上传文件
     */
    public function upload()
    {
        $file = $this->request->file('file');
        if (!$file) {
            return error('请选择要上传的文件');
        }

        try {
            $uploadDir = public_path() . 'uploads';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $dateDir = $uploadDir . '/' . date('Ymd');
            if (!is_dir($dateDir)) {
                mkdir($dateDir, 0755, true);
            }

            $originalName = $file->getOriginalName();
            $extension    = $file->getOriginalExtension();
            $mimeType     = $file->getMime();
            $size         = $file->getSize();

            $fileName = md5(uniqid()) . '.' . $extension;
            $filePath = $dateDir . '/' . $fileName;

            move_uploaded_file($file->getPathname(), $filePath);

            $url = '/uploads/' . date('Ymd') . '/' . $fileName;

            $fileRecord = AdminFile::create([
                'user_id'    => $this->userId,
                'name'       => $originalName,
                'path'       => $filePath,
                'url'        => $url,
                'mime_type'  => $mimeType,
                'size'       => $size,
                'extension'  => $extension,
            ]);

            return success($fileRecord, '上传成功');
        } catch (\Exception $e) {
            return error('上传失败: ' . $e->getMessage());
        }
    }

    /**
     * 删除文件
     */
    public function delete($id)
    {
        $file = AdminFile::find(intval($id));
        if (!$file) {
            return error('文件不存在', 404);
        }

        try {
            // 删除物理文件
            if (file_exists($file->path)) {
                unlink($file->path);
            }
            $file->delete();
            return success(null, '删除成功');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }
}
