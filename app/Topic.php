<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = [
        'name', 'questions_count','bio'
    ];

    /*
     * 多对多关系，声明关联
     * 问题 - 标签
     */
    public function questions()
    {
        return $this->belongsToMany(Question::class)->withTimestamps();
    }
}
