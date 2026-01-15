<?php
use App\Helpers\CommonHelper;
?>

@extends('layouts.default')
@section('content')
    <div class="well_N">
        <div class="dp_sdw">
            <div class="panel">
                <div class="panel-body">
                    <div class="row align-items-center head-bott">
                        <div class="col-md-5 head-h2">
                            <h2 class="subHeadingLabelClass">View Master List</h2>
                        </div>
                        <div class="col-md-7 text-right">
                            <input class="fom2 hidden-print" id="searchInput" type="text" placeholder="Search Master Records">&nbsp;&nbsp;
                            <a id="openModalLink" href="#" onclick="showModal('masterItemsList','','Master Items','')"><i class="glyphicon glyphicon-solid glyphicon-filter"></i> Select Master Item</a>
                        </div>
                    </div>
                    <div id="printList">
                        <div class="panel">
                            <div>
                                <span id="response"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Wait for the page to fully load
        document.addEventListener("DOMContentLoaded", function() {
            // Find the link element by its ID
            var openModalLink = document.getElementById('openModalLink');

            // Trigger the click event on the link
            openModalLink.click();
        });
    </script>
@endsection