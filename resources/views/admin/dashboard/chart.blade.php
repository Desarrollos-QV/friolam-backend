<div class="row">
    <div class="col-12 col-lg-7">
        <div class="card radius-10" style="height: 490px;">
            <div class="card-body">
                <div class="chart-container-1">
                    <div id="chart4"></div>
                </div>
            </div>
            
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-2 g-0 row-group text-center ">
                <div class="col">
                    <div class="p-3">
                        <h5 class="mb-0">{{ $overview['order'] }}</h5>
                        <small class="mb-0">Viajes Completos</small>
                    </div>
                </div>
                <div class="col">
                    <div class="p-3">
                        <h5 class="mb-0">{{ $overview['cancel'] }}</h5>
                        <small class="mb-0">Viajes cancelados</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-5">
        <div class="card radius-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <h6 class="mb-0">Rating de conductores</h6>
                    </div>
                </div>        
            </div>
            <div class="card-body">
                
                <?php
                    for ($i=0; $i < count($dboysRate); $i++) { 
                        if ($i <= 4) {
                            ?>
                            <div class="mb-4">
                                <p class="mb-2">{{$dboysRate[$i]['name']}} <span class="float-end">{{$dboysRate[$i]['rating']}}%</span></p>
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar bg-gradient-bloody" role="progressbar" style="width: {{$dboysRate[$i]['rating']}}%"></div>
                                </div>
                            </div>
                            <?php
                        } 
                    }
                ?>
               
            </div>
        </div>
    </div>
</div>
<!--end row-->
