<?php
namespace App\Repositories;


use App\Question;
use App\Topic;
class QuestionRepository
{
    //拿到所有问题
    public function getQuestionsFeed()
    {
        // Eloquent 查询 Scopes
        // published() -> scopePublished()
        return Question::published()->orderBy('is_first','desc')->latest('updated_at')->with('user')->get();
    }

    // 只获取问题详情
    public function byId($id)
    {
        return Question::find($id);
    }

    // 获取问题的详情、标签和回答
    public function byIdWithTopicsAndAnswers($id)
    {
        // with() 预加载多种关联
        // select * from `questions` where `id` = '4' limit 1
        // select `topics`.*, `question_topic`.`question_id` as `pivot_question_id`, `question_topic`.`topic_id` as `pivot_topic_id`, `question_topic`.`created_at` as `pivot_created_at`, `question_topic`.`updated_at` as `pivot_updated_at` from `topics` inner join `question_topic` on `topics`.`id` = `question_topic`.`topic_id` where `question_topic`.`question_id` in ('4')
        // select * from `answers` where `answers`.`question_id` in ('4')
        return Question::where('id', $id)->with(['topics','answers'])->first();
    }

    /*
     * 更新问题的标签
     * param $topics 标签id
     */
    public function normalizeTopics(array $topics)
    {
        // collect() 创建集合 map() 遍历集合 increment() 自增
        return collect($topics)->map(function ($topic) {
            // 判断标签是否存在，存在则自增数量，不存在则创建标签
            if (is_numeric($topic)) {
                Topic::find($topic)->increment('questions_count');
                return (int)$topic;
            }
            $newTopic = Topic::create(['name' => $topic, 'questions_count' => 1]);
            return $newTopic->id;
        })->toArray();
    }

    // 提交新增问题
    public function create(array $attributes)
    {
        return Question::create($attributes);
    }

    //指定问题的评论
    public function getQuestionCommentsById($id)
    {
        $question = Question::with('comments', 'comments.user')->where('id', $id)->first();

        return $question->comments;
    }

    //增问题的评论数
    public function addCommentsCount($id)
    {
        $question = Question::find($id);
        $question->increment('comments_count');
    }
}