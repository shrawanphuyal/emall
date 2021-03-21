<style>
  .category-widget ul li.active > a {
    color: #3399ff;
  }
</style>

<div id="sidebar-primary"
     class="sidebar widget-area">
  <div class="sidebar-widget-wrapper">
    <aside class="widget category-widget">
      <h3 class="widget-title">All Categories</h3>
      @include('includes.cat_subcats')
    </aside> <!-- .widget -->

  {{--<aside class="widget widget-archive">
    <h3 class="widget-title"><span class="widget-title-wrapper">Price</span></h3>

  </aside>--}} <!-- .widget-archive -->

  </div> <!-- .sidebar-widget-wrapper -->
</div> <!-- .sidebar -->