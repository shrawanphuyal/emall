@extends('layouts.master')

@section('title', 'Cart items')

@push('style')
  <style>
    [v-cloak] {
      display : none;
    }

    #processing-cover {
      position   : fixed;
      left       : 0;
      right      : 0;
      top        : 0;
      bottom     : 0;
      background : rgba(0, 0, 0, 0.9);
      z-index    : 999;
      display    : none;
    }

    #processing {
      position      : fixed;
      top           : 50%;
      left          : 50%;
      background    : #555;
      color         : #fff;
      padding       : 10px 15px;
      border-radius : 25px;
    }
  </style>

  @if($cartCount && auth()->check())
    <script src="https://www.paypalobjects.com/api/checkout.js"></script>
  @endif
@endpush

@section('content')

  <div id="breadcrumb">
    <div class="container">
      <div aria-label="Breadcrumbs"
           class="breadcrumbs breadcrumb-trail">
        <ul class="trail-items">
          <li class="trail-item trail-begin"><a href="/"
                                                rel="home"><span>Home</span></a></li>
          <li class="trail-item trail-end"><span>Cart</span></li>
        </ul>
      </div> <!-- .breadcrumbs -->
    </div><!-- .container -->
  </div> <!-- #breadcrumb -->

  <div id="content"
       v-cloak
       class="site-content full-width-template">
    <div class="container">
      <div class="inner-wrapper">
        <div id="primary"
             class="content-area">
          <main id="main">
            <div class="view-cart-all">
              <h3 class="cart-title">Your Cart (
                <span class="toal-cart">{{ $cartCount }}</span> {{ str_plural('Product', $cartCount) }} )</h3>

              <div class="cart-item"
                   v-for="product in products">
                <div class="cart-item-thumb">
                  <img :src="product.image">
                </div><!-- .cart-item-thumb -->
                <div class="cart-item-info">
                  <h4>@{{ product.title }}</h4>
                  <ul class="cart-details">
                    <li class="view-cost">Rate: Rs @{{ product.sellingPrice.toFixed(2) }}</li>
                    <li class="view-size">Quantity:
                      <input type="number"
                             v-model.number="product.quantity"
                             min="1"
                             :max="product.stock"
                             class="input-text qty text"></li>
                    {{--<li class="view-size">Size:<span>15</span></li>--}}

                  </ul>
                </div><!-- .cart-item-info -->
                <a href="#"
                   @click.prevent="removeFromCart(product)"
                   class="custom-button remove-button">Remove</a>
              </div> <!-- .cart-item -->

              @if($cartCount)
                <div class="total-cart-wrap"
                     v-if="products.length > 0">
                  @auth
                    <a href="#"
                       @click.prevent="normalCheckout"
                       class="custom-button">Normal Checkout</a>
                    <div id="paypal-button"
                         style="background: none;"
                         class="custom-button"></div>
                  @else
                    <h3 class="alignleft">You must login to place your order
                      <button type="submit" class="custom-button login-trigger">Login</button>
                    </h3>
                  @endauth
                  <div class="total-cart-amount alignright">
                    <p>
                      <span>Total :</span>
                      <span class="currency">Rs. </span>@{{ totalPrice.toFixed(2) }}
                      (<span class="currency">$</span> @{{ totalPriceDollar.toFixed(2) }})
                    </p>
                  </div><!-- .total-cart-amount -->
                </div><!-- .total-cart-wrap" -->
              @endif
            </div><!-- .view-cart-all -->
          </main><!-- #main -->
        </div><!-- #primary -->
      </div> <!-- #inner-wrapper -->
    </div><!-- .container -->
  </div> <!-- #content-->
  <div id="processing-cover">
    <div id="processing"><i class="fa fa-spinner fa-spin"></i> Processing</div>
  </div>

@endsection

@push('script')
  <script src="https://cdn.jsdelivr.net/npm/vue@2.5.17/dist/vue.min.js"></script>
  <script>
    function showProcessing() {
      document.getElementById('processing-cover').style.display = 'block';
    }

    function hideProcessing() {
      document.getElementById('processing-cover').style.display = 'none';
    }

    var vueInstance = new Vue({
      el: '#content',
      data: {
        rawProducts: [],
        products: [],
        shippingAddress: {},
        NprToDollar: parseFloat('{{ $company->conversion_rate }}')
      },
      methods: {
        removeFromCart: function (product) {
          if (confirm("Are you sure?")) {
            location = '/remove-from-cart/' + product.slug;
          }
        },
        normalCheckout: function () {
          if (confirm("Are you sure?")) {
            checkout('normal');
          }
        }
      },

      computed: {
        totalPrice: function () {
          return this.products.reduce(function (sum, product) {
            return sum + product.sellingPrice * product.quantity;
          }, 0);
        },
        totalPriceDollar: function () {
          return this.products.reduce(function (sum, product) {
            var sp = parseFloat((product.sellingPrice / this.NprToDollar).toFixed(2));
            return sum + sp * product.quantity;
          }.bind(this), 0);
        },
        cartProducts: function () {
          return this.products.map(function (product) {
            return {
              name: product.title,
              price: (product.sellingPrice / this.NprToDollar).toFixed(2),
              quantity: product.quantity,
              currency: 'USD'
            };
          }.bind(this));
        }
      },

      created: function () {
        this.rawProducts = @json($cartItems);

        this.products = this.rawProducts.map(function (product) {
          var discountAmount = product.discount_type === 1
            ? product.discount
            : product.price * product.discount / 100;

          return {
            id: product.id,
            title: product.title,
            slug: product.slug,
            image: product.firstImage,
            sellingPrice: product.price - discountAmount,
            quantity: 1,
            stock: product.quantity
          }
        });
      }
    });

    @if($cartCount && auth()->check())
    $(document).ready(function () {
      paypal.Button.render({
        env: 'sandbox', // Or 'production',

        commit: true, // Show a 'Pay Now' button

        style: {
          color: 'gold',
          size: 'small'
        },

        client: {
          sandbox: 'AYOmm6NgzQ4ra2fLyPIrg9JgrY6pAj0KtBr2CRAaFzVaCtXy6sUXbzXR3V7VRxj1enDMB8UR1h48J81z',
          production: 'AYOmm6NgzQ4ra2fLyPIrg9JgrY6pAj0KtBr2CRAaFzVaCtXy6sUXbzXR3V7VRxj1enDMB8UR1h48J81z'
        },

        payment: function (data, actions) {
          /*
           * Make a call to the REST api to create the payment
           */
          console.log("onPayment");

          return actions.payment.create({
            payment: {
              transactions: [
                {
                  amount: {
                    total: vueInstance.totalPriceDollar.toFixed(2),
                    currency: 'USD',
                    details: {
                      subtotal: vueInstance.totalPriceDollar.toFixed(2),
                      shipping: '0.00'
                      // tax: '0.07',
                      // handling_fee: '1.00',
                      // shipping_discount: '-1.00',
                      // insurance: '0.01'
                    }
                  },

                  item_list: {
                    items: vueInstance.cartProducts
                    // shipping_address: vueInstance.shippingAddress
                  }
                }
              ]
            }
          });
        },

        onAuthorize: function (data, actions) {
          /*
           * Execute the payment here
           */
          console.log("onAuthorize");
          return actions.payment.get().then(function (data) {
            vueInstance.shippingAddress = data.payer.payer_info.shipping_address;

            return actions.payment.execute().then(function (res) {
              checkout('paypal', res.id);
            });
          });

        },

        onCancel: function (data, actions) {
          /*
           * Buyer cancelled the payment
           */
          console.log("onCancel");
        },

        onError: function (err) {
          /*
           * An error occurred during the transaction
           */
          console.log("onError:", err);
          // showAlertMessageDanger("An error occured", err.response);
        }
      }, '#paypal-button');
    });

    @endif

    function checkout(paymentType, paymentId) {
      showProcessing();
      $.ajax({
        type: "post",
        url: "{{ route('payment.process') }}",
        data: {
          products: vueInstance.products,
          paymentId: paymentId || "",
          paymentType: paymentType,
          shippingAddress: vueInstance.shippingAddress
        },
        success: function (data) {
          if (data.status) {
            vueInstance.rawProducts = [];
            vueInstance.products = [];
            $('.toal-cart').text(0);
            showAlertMessage("Your order has been placed successfully. We will contact you soon.", 'success', 8);
          } else {
            showAlertMessage(data.message, 'failure', 5);
          }
          hideProcessing();
        },
        error: function (error) {
          console.log(paymentType + ' Error:', error);
          hideProcessing();
        }
      })
    }
  </script>
@endpush