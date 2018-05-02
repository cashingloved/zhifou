<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['title', 'body', 'user_id','is_first'];


    // 设置问题显示
    public function isHidden()
    {
        return $this->is_hidden === 'T';
    }

    /*
     * 多对多关系，声明关联
     * 标签 - 问题
     */
    public function topics()
    {
        return $this->belongsToMany(Topic::class)->withTimestamps();
    }

    /*
     * 一对多关系，声明关联
     * 用户 - 问题
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /*
     * 一对多关系，声明关联
     * 问题 - 答案
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    /*
     * 多对多关系，声明关联
     * 用户 - 问题
     */
    public function followers()
    {
        return $this->belongsToMany(User::class,'user_question')->withTimestamps();
    }

    /*
     * 多态关联
     * 问题 - 答案
     */
    public function comments()
    {
        return $this->morphMany('App\Comment','commentable');
    }

    // where 条件
    public function scopePublished($query)
    {
        return $query->where('is_hidden','F');
    }


}
