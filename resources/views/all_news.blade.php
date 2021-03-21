@extends('layouts.master')

@section('title', 'All News')

@section('content')

  <div id="breadcrumb">
    <div class="container">
      <div aria-label="Breadcrumbs"
           class="breadcrumbs breadcrumb-trail">
        <ul class="trail-items">
          <li class="trail-item trail-begin"><a href="/"
                                                rel="home"><span>Home</span></a></li>
          <li class="trail-item trail-end"><span>Blog</span></li>
        </ul>
      </div> <!-- .breadcrumbs -->
    </div><!-- .container -->
  </div> <!-- #breadcrumb -->

  <div id="content"
       class="site-content blog">
    <div class="container">
      <div class="inner-wrapper">
        <div id="primary"
             class="content-area">
          <main id="main"
                class="site-main">
            @foreach($allNews as $news)
              <article class="hentry post">
                <div class="entry-thumb aligncenter">
                  <a href="{{ route('news.detail', $news->slug) }}">
                    <img src="{{ $news->image(840,440) }}"
                         alt="{{ $news->title }}">
                  </a>
                </div> <!-- .entry-thumb -->
                <div class="entry-content-wrapper">
                  <header class="entry-header">
                    <h2 class="entry-title"><a rel="bookmark">{{ $news->title }}</a></h2>
                  </header><!-- .entry-header -->
                  <div class="entry-meta">
                    <span class="posted-on">{{ $news->created_at->format('M d, Y') }}</span>
                    {{--<span class="cat-links"><a href="#" rel="category tag">Agency</a></span>--}}
                  </div><!-- .entry-meta -->
                  <div class="entry-content">
                    <p>{{ $news->textStriped(208, 'description') }}</p>
                    <a href="{{ route('news.detail', $news->slug) }}"
                       class="custom-button">Read More</a>
                  </div><!-- .entry-content -->
                </div><!-- .entry-content-wrapper -->
              </article><!-- .post -->
            @endforeach
          </main> <!-- #main -->
          {{ $allNews->links('vendor.pagination.frontend') }}
        </div><!-- #primary -->

        @include('includes.sidebar')
      </div> <!-- #inner-wrapper -->
    </div><!-- .container -->
  </div> <!-- #content-->

@endsection
