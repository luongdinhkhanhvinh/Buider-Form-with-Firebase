<!-- Footer -->
<!--<footer class="footer pt-0">
    <div class="row align-items-center justify-content-lg-between">
        <div class="col-lg-6">
            <div class="copyright text-center text-lg-left text-muted">
                &copy; 2019 <a href="https://www.creative-tim.com" class="font-weight-bold ml-1" target="_blank">Creative Tim</a>
            </div>
        </div>
        <div class="col-lg-6">
            <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                <li class="nav-item">
                    <a href="https://www.creative-tim.com" class="nav-link" target="_blank">Creative Tim</a>
                </li>
                <li class="nav-item">
                    <a href="https://www.creative-tim.com/presentation" class="nav-link" target="_blank">About Us</a>
                </li>
                <li class="nav-item">
                    <a href="http://blog.creative-tim.com" class="nav-link" target="_blank">Blog</a>
                </li>
                <li class="nav-item">
                    <a href="https://www.creative-tim.com/license" class="nav-link" target="_blank">License</a>
                </li>
            </ul>
        </div>
    </div>
</footer>-->
</div>
</div>
<!-- Argon Scripts -->
<!-- Core -->
<script src="assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/js-cookie/js.cookie.js"></script>
<script src="assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
<script src="assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
<!-- Optional JS -->
<script src="assets/vendor/chart.js/dist/Chart.min.js"></script>
<script src="assets/vendor/chart.js/dist/Chart.extension.js"></script>
<!-- Argon JS -->
<script src="assets/js/argon.js?v=1.1.0"></script>
<!-- Demo JS - remove this in your project -->
<script src="assets/js/demo.min.js"></script>

<script src="assets/js/jquery-ui.min.js"></script>
<script src="assets/js/form-builder.min.js"></script>
<script src="assets/js/form-render.min.js"></script>
<script src="assets/vendor/sweetalert2/dist/sweetalert2.min.js"></script>;
<script src="assets/vendor/blockUI/main.js"></script>
<script>
    function showPageLoader() {
        var button = "<button class='btn btn-white text-success'>Please wait...<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span></button>";
        $.blockUI({
            overlayColor: "#000000",
            type: "loader",
            state: "success",
            css: {
                padding:        0,
                margin:         0,
                width:          '20%',
                top:            '40%',
                left:           '40%',
                textAlign:      'center',
                color:          '#000',
                border:         'none',
                backgroundColor:'transparent',
                cursor:         'wait'
            },
            baseZ: 100000000,
            message: button
        });
    }
    function closePageLoader() {
        $.unblockUI();
    }


    $(document).ajaxStart(function(){
        showPageLoader();
    });
    $(document).ajaxComplete(function( event, xhr, settings ) {
        closePageLoader();
    });
    $(document).ajaxError(function( event, xhr, settings ) {
        closePageLoader();
    });
/*

    $(document).ajax({
        beforeSend: function(){
            showPageLoader();
        },
        complete: function(){
            closePageLoader();
        },
        ajaxSend: function(){
            showPageLoader();
        },
        success: function(){
            closePageLoader();
        },
        ajaxSuccess: function(){
            closePageLoader();
        },
        error: function(){
            closePageLoader();
        },
        ajaxError: function(){
            closePageLoader();
        },
        ajaxComplete: function(){
            closePageLoader();
        }
        // ......
    });*/
</script>