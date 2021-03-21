@extends('layouts.master')

@section('title', $news->title)

@section('content')

  <div id="breadcrumb">
    <div class="container">
      <div  aria-label="Breadcrumbs" class="breadcrumbs breadcrumb-trail">
        <ul class="trail-items">
          <li class="trail-item trail-begin"><a href="/" rel="home"><span>Home</span></a></li>
          <li class="trail-item trail-end"><span>Blog Detail</span></li>
        </ul>
      </div> <!-- .breadcrumbs -->
    </div><!-- .container -->
  </div> <!-- #breadcrumb -->

  <div id="content" class="site-content blog">
    <div class="container">
      <div class="inner-wrapper">
        <div id="primary" class="content-area">
          <main id="main" class="site-main">
            <article class="hentry">
              <div class="entry-thumb">
                <img class="aligncenter" src="{{ $news->image }}" alt="{{ $news->title }}">
              </div>
              <div class="entry-content-wrapper">
                <header class="entry-header">
                  <h2 class="entry-title"><a rel="bookmark">{{ $news->title }}</a></h2>
                </header><!-- .entry-header -->

                <div class="entry-content">
                  {!! $news->description !!}
                </div><!-- .entry-content -->
              </div><!-- .entry-content-wrapper -->
            </article>
          </main> <!-- #main -->

        </div><!-- #primary -->

        @include('includes.sidebar')
      </div> <!-- #inner-wrapper -->
    </div><!-- .container -->
  </div> <!-- #content-->

@endsection
