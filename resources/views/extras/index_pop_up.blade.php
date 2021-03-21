<!-- Popup  -->
@push('style')
    <style>
        .content {
            width: 50%;
            border: none;
            margin: auto;
            padding: 15px;
            border-radius: 10px;
            overflow: hidden;
            text-align: center;
        }

        .close {
            color: #FFFFFF;
            float: right;
            font-size: 30px;
            font-weight: 700
        }

        .close:focus,
        .close:hover {
            color: #000;
            text-decoration: none;
            cursor: pointer
        }

        .modal {
            position: fixed;
            z-index: 9999;
            padding: 50px 0 50px 0;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, .4);
            display: none; /*added property */
        }
    </style>
@endpush
<div>
    <!--MODAL -->
    <div class='modal' id='modal'>
        <!-- MODAL CONTENT -->
        <div class='content'>
            <!-- For the close button -->
            <span class="close" id='close'>&times;</span>
            <!-- Actual content -->
                <img src="{{asset('holi.jpeg')}}" alt="image"/>
        </div>
    </div>
</div>

@push('script')
    <script>
        $(document).ready(() => {
            $(".close").on("click", function () {
                $("#modal").css("display", "none");
                sessionStorage["PopupShown"] = 'yes';
            });
            if (sessionStorage["PopupShown"] != 'yes')
            {
                $('#modal').css('display', 'block');
            }
        })
    </script>
@endpush