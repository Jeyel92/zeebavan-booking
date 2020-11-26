<?
    /**
     * @var bool $selected
     * @var array $rate
     */
?>

<div class="zeeba_form step" id="step2" style="display: none;">
    <div class="select-vehicle my-4" id="vehicles">
        <div class="row offer-bar-container">
            <div class="col-md-4 col-xs-12">
                <div title="Pick up info" class="pick-info-bar mx-5">
                    <div class="pick-info-bar-content">
                        <div class="mr-3"><i data-feather="map-pin"></i></div>
                        <div>
                            <div id="get-location" class="text-gray-500 text-sm"></div>
                            <div id="pickup_date" class="text-gray-500 text-sm"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xs-12">
                <div title="Drop off info" class="dropoff-info-bar mx-5">
                    <div class="dropoff-info-bar-content">
                        <div class="mr-3"><i data-feather="map-pin"></i></div>
                        <div>
                            <div id="get-return-location" class="text-gray-500 text-sm"></div>
                            <div id="dropoff_date" class="text-gray-500 text-sm"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xs-12">
                <div class="rental-period-info-bar">
                    <div class="rental-period-info-bar-content mx-5">
                        <div class="mr-3"><i data-feather="calendar"></i></div>
                        <div>
                            <div class="text-gray-500 text-sm">Rental Period</div>
                            <div id="days-count" class="text-gray-500 text-sm"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="vehicle-content">
            <ul class="row" id="all-vehicles"></ul>
        </div>
    </div>
</div>
<!-- end step 02 -->