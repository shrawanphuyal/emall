<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsRequest;
use App\News;
use Illuminate\Http\Request;

class NewsController extends AsdhController
{
  private $prefix = 'news';

  public function __construct()
  {
    parent::__construct();
    $this->website['routeType'] = 'news';
  }

  public function index()
  {
    $this->website['newss'] = News::latest()->paginate($this->default_pagination_limit);

    return view('admin.news.index', $this->website);
  }

  public function create()
  {
    $this->website['edit'] = false;

    return view('admin.news.create', $this->website);
  }

  public function store(NewsRequest $request)
  {
    $image_name = null;
    if (!is_null($request->image)) {
      $image_name = upload_image_modified($request->image, $this->prefix);
    }

    return News::create([
      'title'       => $request->title,
      'slug'        => asdh_str_slug($request->title),
      'image'       => $image_name,
      'description' => $request->description,
    ])
      ? back()->with('success_message', 'News successfully added.')
      : back()->with('failure_message', 'News could not be added. Please try again later.');
  }

  public function edit(News $news)
  {
    $this->website['edit'] = true;
    $this->website['news'] = $news;

    return view('admin.news.create', $this->website);
  }

  public function update(NewsRequest $request, News $news)
  {
    $image_name = $news->getOriginal('image');
    if (!is_null($request->image)) {
      $news->delete_image();
      $image_name = upload_image_modified($request->image, $this->prefix);
    }

    return $news->update([
      'title'       => $request->title,
      'slug'        => asdh_str_slug($request->title),
      'image'       => $image_name,
      'description' => $request->description,
    ])
      ? back()->with('success_message', 'News successfully updated.')
      : back()->with('failure_message', 'News could not be updated. Please try again later.');
  }

  public function destroy(News $news)
  {
    if ($news->delete()) {
      $news->delete_image();

      return back()->with('success_message', 'News successfully deleted.');
    }

    return back()->with('failure_message', 'News could not be deleted. Please try again later.');
  }
}
