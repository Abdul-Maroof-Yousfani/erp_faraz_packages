<?php use App\Helpers\HrHelper;?>
@include('includes._normalUserNavigation')
<style>
    .site-header {
        padding-top: 150px;
    }
    .site-header {
        margin: 0 auto;
        padding: 80px 0 0;
        max-width: 820px;
    }
    .site-header__title {
        font-size: 7.25rem;
    }
    .site-header__title {
        margin: 0;
        font-family: Montserrat,sans-serif;

        font-weight: 700;
        line-height: 1.1;
        text-transform: uppercase;
        -webkit-hyphens: auto;
        -moz-hyphens: auto;
        -ms-hyphens: auto;
        hyphens: auto;
    }

    .main-content__checkmark {
        font-size: 8.0625rem;
        line-height: 1;
        color: #24b663;
    }
    .main-content__body {
        font-size: 1.25rem;
    }
    .main-content__body {
        margin: 20px 0 0;
        font-size: 2em;
        line-height: 1.4;
    }
    .site-footer {
        padding: 145px 0 25px;
    }
    .site-footer {
        margin: 150px auto;

        padding: 0;
        max-width: 820px;
    }
    #fineprint
    {
        font-weight: bold;
    }
</style>
<div class="well">
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                    <header class="site-header" id="header">
                        <h1 class="site-header__title" data-lead-id="site-header-title">THANK YOU!</h1>
                    </header>
                    <div class="main-content">
                        <span class="glyphicon glyphicon-ok main-content__checkmark"></span>
                        <p class="main-content__body" data-lead-id="main-content-body">Thanks a bunch for filling that out. It means a lot to us, just like you do! We really appreciate you giving us a moment of your time today. Thanks for being you.</p>
                    </div>

                    <footer class="site-footer" id="footer">
                        <p class="site-footer__fineprint" id="fineprint">Copyright ©2014 | All Rights Reserved</p>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

