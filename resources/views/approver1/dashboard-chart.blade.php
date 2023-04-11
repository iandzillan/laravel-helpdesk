<script>
    $(document).ready(function(){
        // Category chart
        let baroptions = {
            series: [], 
            chart: {
                type: 'bar',
                height: 350
            }, 
            plotOptions: {
                bar: {
                    horizontal: false, 
                    columnWidth: '55%',
                    endingShape: 'rounded',
                    borderRadius: 5,
                }
            }, 
            dataLabels: {
                enabled: false
            }, 
            stroke: {
                show: true, 
                width: 2,
                curve: 'smooth',
                colors: ['transparent']
            }, 
            xaxis: {
                type: 'category',
                categories: [],
            }, 
            yaxis: {
                title: {
                    text: 'total'
                }
            }, 
            legend: {
                show: false,
            },
            fill: {
                opacity: 1
            }, 
            tooltip: {
                y: {
                    formatter: function(val){
                        return val; 
                    }
                }
            }, 
            noData: {
                text: 'No data...'
            }
        };
        let barchart = new ApexCharts(document.querySelector('#category'), baroptions);
        barchart.render();
    
        let url = "{{ route('dept.categorychart.year') }}";
        $.getJSON(url, function(response){
            let series     = [];
            for (let i = 0; i < response.name.length; i++) {
                series.push({
                    name: response.name[i],
                    data: response.data[i]
                });
            }
            barchart.updateOptions({
                xaxis: {
                    categories: response.month
                },
                series: series
            })
        })
    
        $('#filter-bar').on('change', function(){
            let value = $('#filter-bar').val();
            switch (value) {
                case 'year':
                    let url1 = "{{ route('dept.categorychart.year') }}";
                    $.getJSON(url1, function(response){
                        let series = [];
                        for (let i = 0; i < response.name.length; i++) {
                            series.push({
                                name: response.name[i],
                                data: response.data[i]
                            });
                        }
                        barchart.updateOptions({
                            xaxis: {
                                categories: response.month
                            },
                            series: series
                        })
                    })
                    break;
    
                    case 'month':
                        let url2 = "{{ route('dept.categorychart.month') }}";
                        $.getJSON(url2, function(response){
                            let series2 = [];
                            for (let i = 0; i < response.name.length; i++) {
                                series2.push({
                                    name: response.name[i],
                                    data: response.data[i]
                                });
                            }
                            barchart.updateOptions({
                                xaxis:{
                                    categories: response.month
                                },
                                series: series2
                            });
                        })
                    break;
    
                case 'week':
                    let url3 = "{{ route('dept.categorychart.week') }}";
                        $.getJSON(url3, function(response){
                            let series2 = [];
                            for (let i = 0; i < response.name.length; i++) {
                                series2.push({
                                    name: response.name[i],
                                    data: response.data[i]
                                });
                            }
                            barchart.updateOptions({
                                xaxis:{
                                    categories: response.week
                                },
                                series: series2
                            });
                        })
                    break;
            
                default:
                    break;
            }
        })



        // Sub category chart
        let donutoptions = {
            series: [],
            chart: {
                type: 'donut'
            },
            responsive: [{
                options: {
                    chart: {
                        width: 200
                    }
                }
            }],
            legend: {
                show: false,
            },
            noData: {
                text: 'No data...'
            }
        };
        let donutchart = new ApexCharts(document.querySelector('#sub-category'), donutoptions);
        donutchart.render();

        let url4 = "{{ route('dept.subcategorychart.year') }}";
        $.getJSON(url4, function(response){
            donutchart.updateOptions({
                series: response.data,
                labels: response.name
            });
        });

        $('#filter-donut').on('change', function(){
            let value = $('#filter-donut').val()
            switch (value) {
                case 'year':
                    let url5 = "{{ route('dept.subcategorychart.year') }}";
                    $.getJSON(url5, function(response){
                        donutchart.updateOptions({
                            series: response.data,
                            labels: response.name
                        });
                    });
                    break;
                
                case 'month':
                    let url6 = "{{ route('dept.subcategorychart.month') }}";
                    $.getJSON(url6, function(response){
                        donutchart.updateOptions({
                            series: response.data,
                            labels: response.name
                        });
                    });
                    break;
                
                case 'week':
                    let url7 = "{{ route('dept.subcategorychart.week') }}";
                    $.getJSON(url7, function(response){
                        donutchart.updateOptions({
                            series: response.data,
                            labels: response.name
                        });
                    });
                    break;
            
                default:
                    break;
            }
        })
    });
</script>