@extends("layouts.app")
@section("style")
<link href="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css">

@endsection

@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        @include('admin.dashboard.overview')
        {{-- < row --> --}}
        @include('admin.dashboard.chart')
		{{-- < Live Tracking --> --}}
        @include('admin.dashboard.google')
</div>
</div>
@endsection

@section("script")

<script src="{{ asset('assets/plugins/apexcharts-bundle/js/apexcharts.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
<script src="{{ asset('assets/plugins/chartjs/js/Chart.min.js') }}"></script>
<script src="{{ asset('assets/plugins/chartjs/js/Chart.extension.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery.easy-pie-chart/jquery.easypiechart.min.js') }}"></script>


<script>
    $(function () {
		"use strict";
        var options = {
			series: [{
				name: 'Cancelados',
				data: [<?php echo $admin->chart(6)['cancel']; ?>, <?php echo $admin->chart(5)['cancel']; ?>, <?php echo $admin->chart(4)['cancel']; ?>, <?php echo $admin->chart(3)['cancel']; ?>, <?php echo $admin->chart(2)['cancel']; ?>, <?php echo $admin->chart(1)['cancel']; ?>, <?php echo $admin->chart(0)['cancel']; ?>]},
				{
				name: 'Completos',
				data: [<?php echo $admin->chart(6)['order']; ?>, <?php echo $admin->chart(5)['order']; ?>, <?php echo $admin->chart(4)['order']; ?>, <?php echo $admin->chart(3)['order']; ?>, <?php echo $admin->chart(2)['order']; ?>, <?php echo $admin->chart(1)['order']; ?>, <?php echo $admin->chart(0)['order']; ?>]
			},],
			chart: {
				foreColor: '#9ba7b2',
				type: 'bar',
				height: 360
			},
			plotOptions: {
				bar: {
					horizontal: false,
					columnWidth: '45%',
					endingShape: 'rounded'
				},
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				show: true,
				width: 2,
				colors: ['transparent']
			},
			title: {
				text: 'Total de Viajes',
				align: 'left',
				style: {
					fontSize: '14px',
					color:"#000"
				}
			},
			xaxis: {
				categories: ['<?php echo $admin->getMonthName(6); ?>', '<?php echo $admin->getMonthName(5); ?>', '<?php echo $admin->getMonthName(4); ?>', '<?php echo $admin->getMonthName(3); ?>', '<?php echo $admin->getMonthName(2); ?>', '<?php echo $admin->getMonthName(1); ?>', '<?php echo $admin->getMonthName(0); ?>'],
			},
			yaxis: {
				title: {
					text: ''
				}
			},
			fill: {
				opacity: 1
			},
			tooltip: {
				y: {
					formatter: function (val) {
						return val
					}
				}
			}
		};
	
		var chart = new ApexCharts(document.querySelector("#chart4"), options);
		chart.render();	

	});

	$(document).ready(function() {
		$('#viajes').DataTable();
	});
</script>
@endsection
