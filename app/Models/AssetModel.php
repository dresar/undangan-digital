<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetModel extends Model
{
    protected $table = 'cdn_assets';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'name',
        'type',
        'url',
        'version',
        'description',
        'is_active',
        'load_order',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]',
        'type' => 'required|in_list[css,js,font,image]',
        'url' => 'required',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function getActiveAssets($type = null)
    {
        $builder = $this->where('is_active', 1);
        if ($type) {
            $builder->where('type', $type);
        }
        return $builder->orderBy('load_order', 'ASC')->findAll();
    }

    public function getByType($type)
    {
        return $this->where('type', $type)
            ->orderBy('load_order', 'ASC')
            ->findAll();
    }
}

