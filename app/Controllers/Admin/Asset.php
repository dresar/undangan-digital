<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AssetModel;

class Asset extends BaseController
{
    protected $assetModel;
    protected $assetBasePath;

    public function __construct()
    {
        $this->assetModel = new AssetModel();
        $this->assetBasePath = FCPATH . 'assets' . DIRECTORY_SEPARATOR . 'cdn' . DIRECTORY_SEPARATOR;
        
        if (!is_dir($this->assetBasePath)) {
            mkdir($this->assetBasePath, 0755, true);
        }
        
        $subfolders = ['image', 'css', 'js', 'font'];
        foreach ($subfolders as $folder) {
            $folderPath = $this->assetBasePath . $folder . DIRECTORY_SEPARATOR;
            if (!is_dir($folderPath)) {
                mkdir($folderPath, 0755, true);
            }
        }
    }

    public function index()
    {
        $type = $this->request->getGet('type');
        $search = $this->request->getGet('search');

        $builder = $this->assetModel;
        
        if ($type) {
            $builder->where('type', $type);
        }
        
        if ($search) {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('description', $search)
                ->orLike('url', $search)
                ->groupEnd();
        }

        $assets = $builder->orderBy('load_order', 'ASC')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $data = [
            'assets' => $assets,
            'type' => $type,
            'search' => $search,
            'stats' => [
                'total' => $this->assetModel->countAllResults(false),
                'css' => $this->assetModel->where('type', 'css')->countAllResults(false),
                'js' => $this->assetModel->where('type', 'js')->countAllResults(false),
                'active' => $this->assetModel->where('is_active', 1)->countAllResults(false),
            ],
        ];

        return view('admin/asset/index', $data);
    }

    public function create()
    {
        return view('admin/asset/form', ['asset' => null]);
    }

    public function store()
    {
        $type = $this->request->getPost('type');
        $url = $this->request->getPost('url');
        $file = $this->request->getFile('asset_file');

        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
            'type' => 'required|in_list[css,js,font,image]',
            'version' => 'permit_empty|max_length[50]',
            'description' => 'permit_empty',
            'is_active' => 'in_list[0,1]',
            'load_order' => 'permit_empty|integer',
        ];

        if ($file && $file->isValid()) {
            $allowedExts = [];
            $maxSize = 5120;
            
            switch ($type) {
                case 'image':
                    $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
                    break;
                case 'css':
                    $allowedExts = ['css'];
                    break;
                case 'js':
                    $allowedExts = ['js'];
                    break;
                case 'font':
                    $allowedExts = ['woff', 'woff2', 'ttf', 'otf', 'eot'];
                    break;
            }
            
            $rules['asset_file'] = 'uploaded[asset_file]|max_size[asset_file,' . $maxSize . ']|ext_in[asset_file,' . implode(',', $allowedExts) . ']';
        } else {
            $rules['url'] = 'required';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $finalUrl = $url;

        if ($file && $file->isValid()) {
            $typeFolder = $this->assetBasePath . $type . DIRECTORY_SEPARATOR;
            if (!is_dir($typeFolder)) {
                mkdir($typeFolder, 0755, true);
            }

            $newName = time() . '_' . $file->getName();
            $file->move($typeFolder, $newName);
            
            $finalUrl = base_url('assets/cdn/' . $type . '/' . $newName);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'type' => $type,
            'url' => $finalUrl,
            'version' => $this->request->getPost('version'),
            'description' => $this->request->getPost('description'),
            'is_active' => (int)($this->request->getPost('is_active') ?? 1),
            'load_order' => (int)($this->request->getPost('load_order') ?? 0),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($this->assetModel->insert($data)) {
            return redirect()->to('/admin/asset')->with('success', 'CDN Asset berhasil ditambahkan');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan CDN Asset');
    }

    public function edit($id)
    {
        $asset = $this->assetModel->find($id);
        if (!$asset) {
            return redirect()->to('/admin/asset')->with('error', 'Asset tidak ditemukan');
        }

        return view('admin/asset/form', ['asset' => $asset]);
    }

    public function update($id)
    {
        $asset = $this->assetModel->find($id);
        if (!$asset) {
            return redirect()->to('/admin/asset')->with('error', 'Asset tidak ditemukan');
        }

        $type = $this->request->getPost('type');
        $url = $this->request->getPost('url');
        $file = $this->request->getFile('asset_file');

        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
            'type' => 'required|in_list[css,js,font,image]',
            'version' => 'permit_empty|max_length[50]',
            'description' => 'permit_empty',
            'is_active' => 'in_list[0,1]',
            'load_order' => 'permit_empty|integer',
        ];

        if ($file && $file->isValid()) {
            $allowedExts = [];
            $maxSize = 5120;
            
            switch ($type) {
                case 'image':
                    $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
                    break;
                case 'css':
                    $allowedExts = ['css'];
                    break;
                case 'js':
                    $allowedExts = ['js'];
                    break;
                case 'font':
                    $allowedExts = ['woff', 'woff2', 'ttf', 'otf', 'eot'];
                    break;
            }
            
            $rules['asset_file'] = 'uploaded[asset_file]|max_size[asset_file,' . $maxSize . ']|ext_in[asset_file,' . implode(',', $allowedExts) . ']';
        } else {
            $rules['url'] = 'required';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $finalUrl = $url;

        if ($file && $file->isValid()) {
            if (!empty($asset['url']) && strpos($asset['url'], base_url('assets/cdn/')) !== false) {
                $oldFile = str_replace(base_url('assets/cdn/'), $this->assetBasePath, $asset['url']);
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            $typeFolder = $this->assetBasePath . $type . DIRECTORY_SEPARATOR;
            if (!is_dir($typeFolder)) {
                mkdir($typeFolder, 0755, true);
            }

            $newName = time() . '_' . $file->getName();
            $file->move($typeFolder, $newName);
            
            $finalUrl = base_url('assets/cdn/' . $type . '/' . $newName);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'type' => $type,
            'url' => $finalUrl,
            'version' => $this->request->getPost('version'),
            'description' => $this->request->getPost('description'),
            'is_active' => (int)($this->request->getPost('is_active') ?? 1),
            'load_order' => (int)($this->request->getPost('load_order') ?? 0),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($this->assetModel->update($id, $data)) {
            return redirect()->to('/admin/asset')->with('success', 'CDN Asset berhasil diperbarui');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui CDN Asset');
    }

    public function delete($id)
    {
        $asset = $this->assetModel->find($id);
        if (!$asset) {
            return redirect()->to('/admin/asset')->with('error', 'Asset tidak ditemukan');
        }

        if (!empty($asset['url']) && strpos($asset['url'], base_url('assets/cdn/')) !== false) {
            $filePath = str_replace(base_url('assets/cdn/'), $this->assetBasePath, $asset['url']);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        if ($this->assetModel->delete($id)) {
            return redirect()->to('/admin/asset')->with('success', 'CDN Asset berhasil dihapus');
        }

        return redirect()->to('/admin/asset')->with('error', 'Gagal menghapus CDN Asset');
    }

    public function toggleStatus($id)
    {
        $asset = $this->assetModel->find($id);
        if (!$asset) {
            return $this->response->setJSON(['success' => false, 'message' => 'Asset tidak ditemukan']);
        }

        $newStatus = $asset['is_active'] == 1 ? 0 : 1;
        $this->assetModel->update($id, ['is_active' => $newStatus, 'updated_at' => date('Y-m-d H:i:s')]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Status berhasil diubah',
            'is_active' => $newStatus
        ]);
    }
}

