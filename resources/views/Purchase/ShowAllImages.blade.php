<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css">
<style>
    body {
        background-color: #fafafa;
    }
</style>
<?php

?>

<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
// if($accType == 'client'){
//     $m = $_GET['m'];
// }else{
//     $m = Auth::user()->company_id;
// }
$m = Input::get('m');


use App\Helpers\PurchaseHelper;
?>
<div class="well">
    <div class="panel">
        <div class="panel-body">
            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="stage2">  </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <span class="subHeadingLabelClass">ALL IMAGES </span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="loader">
                            </div>
                        </div>

                        <div class="row" id="showhide">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="container mt-5 mb-4">
                                            <div class="row popup-gallery">
                                                <?php
                                                foreach ($jobOrderDocs as $row){?>
                                                <div class="col-lg-4 col-md-6 mb-4">
                                                    <a href="{{ url('storage/app/'.$row->image_file) }}">
                                                        <img src="{{ url('storage/app/'.$row->image_file) }}" class="img-fluid" style="width: 80%;    height: 200px !important;">

                                                    </a>
                                                </div>
                                                <?php }?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Optional JavaScript -->
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>

<script>
    $(document).ready(function() {
        $('.popup-gallery').magnificPopup({
            delegate: 'a',
            type: 'image',
            tLoading: 'Loading image #%curr%...',
            mainClass: 'mfp-img-mobile',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0,1] // Will preload 0 - before current, and 1 after the current image
            },
            image: {
                tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
                titleSrc: function(item) {
                    //return item.el.attr('title') + '<small>by Marsel Van Oosten</small>';
                }
            }
        });
    });
</script>
{{--@endsection--}}