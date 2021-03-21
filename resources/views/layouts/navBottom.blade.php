<header id="masthead"
        class="site-header">
  <div class="container">
    <div class="site-branding">
      <div id="site-identity">
        <h1 class="site-title">
          <a href="{{ route('home') }}"
             rel="home">
            <img alt="logo"
                 src="{{ $company->image(130,43,'logo') }}">
          </a>
        </h1>
      </div><!-- #site-identity -->
    </div><!-- .site-branding -->
    <div id="right-head">
      <div class="header-search-wrapper">
        <div class="advance-product-search">
          <div class="advance-search-wrap">
            <span class="categories-list"
                  id="selected-category">
              {{ request()->query('selected_category') ?? 'Select Categories' }}
            </span>
            @include('includes.cat_subcats', ['default' => true])
          </div><!-- .advance-search-wrap -->
          <form role="search"
                method="get"
                class="product-search"
                action="{{ route('search') }}">
            <input type="hidden"
                   name="selected_category"
                   id="selected-category-name"
                   value="{{ request()->query('selected_category') }}">
            <input type="hidden"
                   name="category"
                   value="{{ request()->query('category') }}"
                   id="selected-category-id">
            <input type="hidden"
                   name="level"
                   value="{{ request()->query('level') }}"
                   id="selected-category-level">
            <input type="search"
                   class="search-field"
                   placeholder="Search for products, brands & more."
                   value="{{ request()->query('keyword') }}"
                   required
                   name="keyword">
            <input class="submit"
                   type="submit"
                   value="Search">
          </form><!-- .product-search -->
        </div><!-- .advance-product-search -->
      </div><!-- .header-search-wrapper -->
    </div><!-- #right-head -->
  </div> <!-- .container -->
</header> <!-- .site-header -->

@push('script')
  <script>
    $(document).ready(function () {
      var $selectedCategory = $('#selected-category');
      var $selectedCategoryId = $('#selected-category-id');
      // var $selectedCategorySlug = $('#selected-category-slug');
      var $selectedCategoryName = $('#selected-category-name');
      var $selectedCategoryLevel = $('#selected-category-level');
      var $searchCategoryIndividual = $('.search-category-individual');

      $searchCategoryIndividual.on('click', function (event) {
        event.preventDefault();

        $selectedCategory.text($(this).text());
        $selectedCategoryId.val($(this).data('id'));
        $selectedCategoryName.val($(this).text());
        $selectedCategoryLevel.val($(this).data('level'));
      })
    })
  </script>
@endpush