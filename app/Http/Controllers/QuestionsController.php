<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionRequest;
use App\Question;
use App\Repositories\QuestionRepository;
use App\Topic;
use Auth;
use Illuminate\Http\Request;

class QuestionsController extends Controller
{

    protected $questionRepository;

    public function __construct(QuestionRepository $questionRepository)
    {
        $this->middleware('auth')->except(['index', 'show']);

        $this->questionRepository = $questionRepository;
    }

    //所有问题
    public function index()
    {
        $questions = $this->questionRepository->getQuestionsFeed();
        return view('questions.index', compact('questions'));
    }

    //创建问题页面
    public function create()
    {
        return view('questions.create');
    }

    //创建问题
    public function store(StoreQuestionRequest $request)
    {
        // 获取问题标签id数组
        $topics = $this->questionRepository->normalizeTopics($request->get('topics'));
        $data = [
            'title' => $request->get('title'),
            'body' => $request->get('body'),
            'user_id' => Auth::id(),
        ];
        // 提交新增问题
        $question = $this->questionRepository->create($data);
        // 更新用户提问数量
        Auth::user()->increment('questions_count');
        // 新增question与topics关联数据，已在模型中声明关联
        $question->topics()->attach($topics);
        // 跳转至问题详情页面
        return redirect()->route('questions.show', [$question->id]);
    }

    //显示问题
    public function show($id)
    {
        // 获取问题详情、标签和回答
        $question = $this->questionRepository->byIdWithTopicsAndAnswers($id);
        return view('questions.show', compact('question'));
    }

    //修改问题
    public function edit($id)
    {
        // 获取问题详情
        $question = $this->questionRepository->byId($id);
        if (Auth::user()->owns($question)) {
            return view('questions.edit', compact('question'));
        }
        return back();
    }

    //问题更新
    public function update(StoreQuestionRequest $request, $id)
    {
        // 获取问题详情
        $question = $this->questionRepository->byId($id);
        $topics = $this->questionRepository->normalizeTopics($request->get('topics'));
        $question->update([
            'title' => $request->get('title'),
            'body' => $request->get('body'),
        ]);

        $question->topics()->sync($topics);
        return redirect()->route('questions.show', [$question->id]);
    }

    //删除问题
    public function destroy($id)
    {
        $question = $this->questionRepository->byId($id);

        if (Auth::user()->owns($question)) {
            $question->delete();
            Auth::user()->decrement('questions_count');
            return redirect('/');
        }
        abort(403, 'Forbidden');
    }

}
