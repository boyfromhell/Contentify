<?php namespace App\Modules\News\Controllers;

use App\Modules\News\Models\News as News;
use URL, HTML, FrontController;

class NewsController extends FrontController {

	public function __construct()
	{
		$this->model = 'News';

		parent::__construct();
	}

    public function index()
    {
        $this->buildIndexForm(array(
            'buttons'   => NULL,
            'tableHead' => [t('ID') => 'id', t('Title') => 'title', t('Category') => NULL, t('Date') => 'created_at'],
            'tableRow'  => function($news)
            {
                return array(
                    $news->id,
                    HTML::link(URL::route('news.show', [$news->id]), $news->title),
                    $news->newscat->title,
                    $news->created_at->toDateString()
                    );
            },
            'actions'   => []
            ), 'front');
    }

    public function showOverview()
    {
        $hasAccess = (user() and user()->hasAccess('internal')); // Internal news are protected and require the "internal" permission
        $allNews = News::wherePublished(true)->where('internal', '<=', $hasAccess)->orderBy('created_at', 'DESC')->take(5)->get();

        $this->pageView('news::show_overview', compact('allNews'));
    }

    public function show($id)
    {
        $news = News::whereId($id)->wherePublished(true)->first();

        $hasAccess = (user() and user()->hasAccess('internal'));
        if ($news->internal and ! $hasAccess) {
            return $this->message(trans('app.access_denied'));
        }

        $news->access_counter++;
        $news->save();

        $this->pageView('news::show', compact('news'));
    }

    public function globalSearch($subject)
    {
        $allNews = News::where('title', 'LIKE', '%'.$subject.'%')->get();

        $results = array();
        foreach ($allNews as $news) {
            $results[$news->title] = URL::to('news/'.$news->id.'/show');
        }

        return $results;
    }

}