<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
            'category_id',
            'first_name',
            'last_name',
            'gender',
            'email',
            'tel',
            'address',
            'building',
            'detail',
        ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }    


    // ===============================
    // 名前検索（姓・名・メールアドレス）
    // ===============================
    public function scopeKeywordSearch($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {

                // 名前（姓・名）
                $q->where('first_name', 'like', "%{$keyword}%")
                ->orWhere('last_name', 'like', "%{$keyword}%")

                // フルネーム検索
                ->orWhereRaw("CONCAT(last_name, first_name) LIKE ?", ["%{$keyword}%"])
                ->orWhereRaw("CONCAT(last_name, ' ', first_name) LIKE ?", ["%{$keyword}%"])

                // メール
                ->orWhere('email', 'like', "%{$keyword}%");
            });
        }
    }

    // ===============================
    // 性別検索
    // ===============================
    public function scopeGenderSearch($query, $gender)
    {   
        //「全て」を選んだ場合は検索しないようにする
        if (!empty($gender) && $gender !== 'all') {
            $query->where('gender', $gender);
        }
    }

    // ===============================
    // カテゴリ検索
    // ===============================
    public function scopeCategorySearch(Builder $query, $category_id)
    {
        //「全て」を選んだ場合は検索しないようにする
        if (!empty($category_id) && $category_id !== 'all') {
            $query->where('category_id', $category_id);
        }
    }

    // ===============================
    // 日付検索
    // ===============================
    public function scopeDateSearch(Builder $query, $date)
    {
        if (!empty($date)) {
            $query->whereDate('created_at', $date);
        }
    }
      
}
