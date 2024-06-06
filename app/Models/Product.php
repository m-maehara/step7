<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    use HasFactory;

    protected $fillable = [
        'product_name',
        'price',
        'stock',
        'company_id',
        'comment',
        'img_path',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function search($searchTerm)
    {
        return $this->where('product_name', 'LIKE', "%{$searchTerm}%")->get();
    }

    public function registerProduct($data)
    {
        // データのバリデーション
        $validator = Validator::make($data, [
            'product_name' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'company_id' => 'required',
            'comment' => 'nullable',
            'img_path' => 'nullable|image|max:2048',
        ]);

        // バリデーションに失敗した場合は例外をスロー
        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        // 新しい製品を作成して保存
        return $this->create($data);
    }
}


