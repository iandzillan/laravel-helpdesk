<script>
    $(document).ready(function(){
        // generate report button action 
        $('#generate-report').on('click', function(){
            $('#modal-report').modal('show');
        });

        // generate button action
        $('body').on('click', '#generate', function(e){
            e.preventDefault();

            // define variable
            let from  = $('#from').val();
            let to    = $('#to').val();
            let url   = "{{ route('admin.sla.report') }}";
            let token = $('meta[name="csrf-token"]').attr('content');

            // show loading
            swal.fire({
                title: 'Please wait',
                text: 'Sending request...',
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEnterKey: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // ajax report
            $.ajax({
                url: url,
                type: 'post',
                cache: false,
                data: {
                    'from': from,
                    'to': to,
                    '_token': token
                }, 
                success:function(response){
                    if (response.success = false) {
                        // show response
                        swal.fire({
                            icon: 'warning',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        });
                    } else {
                        // show response
                        swal.fire({
                            icon: 'success',
                            title: 'SLA Report has been generated',
                            showConfirmButton: false,
                            timer: 2000
                        });

                        window.location = url;
                    }

                    // clear alert
                    $('#form-report').trigger('reset');
                    $('input').removeClass('is-invalid');
                    $('.invalid-feedback').removeClass('d-block');
                    // close modal
                    $('#modal-report').modal('hide');
                }, 
                error:function(error){
                    console.log(error.responseJSON.message);
                    // show message
                    swal.fire({
                        icon: 'error',
                        title: 'Something wrong',
                        text: 'Please check again',
                        showConfirmButton: false,
                        timer: 2000
                    });

                    // check if from field has error
                    if (error.responseJSON.from) {
                        $('#from').addClass('is-invalid');
                        $('#alert-from').addClass('d-block');
                        $('#alert-from').removeClass('d-none');
                        $('#alert-from').html(error.responseJSON.from);
                    } else {
                        $('#from').removeClass('is-invalid');
                        $('#alert-from').removeClass('d-block');
                        $('#alert-from').addClass('d-none');
                    }

                    // check if to field has error
                    if (error.responseJSON.to) {
                        $('#to').addClass('is-invalid');
                        $('#alert-to').addClass('d-block');
                        $('#alert-to').removeClass('d-none');
                        $('#alert-to').html(error.responseJSON.to);
                    } else {
                        $('#to').removeClass('is-invalid');
                        $('#alert-to').removeClass('d-block');
                        $('#alert-to').addClass('d-none');
                    }
                }
            });
        });

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
    
        let url = "{{ route('admin.categorychart.year') }}";
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
                    let url1 = "{{ route('admin.categorychart.year') }}";
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
                        let url2 = "{{ route('admin.categorychart.month') }}";
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
                    let url3 = "{{ route('admin.categorychart.week') }}";
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

        let url4 = "{{ route('admin.subcategorychart.year') }}";
        $.getJSON(url4, function(response){
            let coloR = [];
            let dynamicColors = function() {
                let r = Math.floor(Math.random() * 255);
                let g = Math.floor(Math.random() * 255);
                let b = Math.floor(Math.random() * 255);
                return "rgb(" + r + "," + g + "," + b + ")";
            };
            for (let i = 0; i < response.data.length; i++) {
                coloR.push(dynamicColors());
            }

            donutchart.updateOptions({
                series: response.data,
                labels: response.name,
                colors: coloR
            });
        });

        $('#filter-donut').on('change', function(){
            let value = $('#filter-donut').val()
            switch (value) {
                case 'year':
                    let url5 = "{{ route('admin.subcategorychart.year') }}";
                    $.getJSON(url5, function(response){
                        let coloR = [];
                        let dynamicColors = function() {
                            let r = Math.floor(Math.random() * 255);
                            let g = Math.floor(Math.random() * 255);
                            let b = Math.floor(Math.random() * 255);
                            return "rgb(" + r + "," + g + "," + b + ")";
                        };
                        for (let i = 0; i < response.data.length; i++) {
                            coloR.push(dynamicColors());
                        }

                        donutchart.updateOptions({
                            series: response.data,
                            labels: response.name,
                            colors: coloR
                        });
                    });
                    break;
                
                case 'month':
                    let url6 = "{{ route('admin.subcategorychart.month') }}";
                    $.getJSON(url6, function(response){
                        let coloR = [];
                        let dynamicColors = function() {
                            let r = Math.floor(Math.random() * 255);
                            let g = Math.floor(Math.random() * 255);
                            let b = Math.floor(Math.random() * 255);
                            return "rgb(" + r + "," + g + "," + b + ")";
                        };
                        for (let i = 0; i < response.data.length; i++) {
                            coloR.push(dynamicColors());
                        }

                        donutchart.updateOptions({
                            series: response.data,
                            labels: response.name,
                            colors: coloR
                        });
                    });
                    break;
                
                case 'week':
                    let url7 = "{{ route('admin.subcategorychart.week') }}";
                    $.getJSON(url7, function(response){
                        let coloR = [];
                        let dynamicColors = function() {
                            let r = Math.floor(Math.random() * 255);
                            let g = Math.floor(Math.random() * 255);
                            let b = Math.floor(Math.random() * 255);
                            return "rgb(" + r + "," + g + "," + b + ")";
                        };
                        for (let i = 0; i < response.data.length; i++) {
                            coloR.push(dynamicColors());
                        }

                        donutchart.updateOptions({
                            series: response.data,
                            labels: response.name,
                            colors: coloR
                        });
                    });
                    break;
            
                default:
                    break;
            }
        })

        // rate percentage chart
        let donutRateoptions = {
            series: [],
            labels: ['Above SLA Time', 'Under SLA Time'],
            colors: ['#D4526E', '#33B2DF'],
            chart: {
                type: 'donut',
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
        let donutRatechart = new ApexCharts(document.querySelector('#success-rate'), donutRateoptions);
        donutRatechart.render();

        let url8 = "{{ route('admin.solvePercentage.year') }}";
        $.getJSON(url8, function(response){
            donutRatechart.updateOptions({
                series: response.data
            });
        });

        $('#filter-percentage').on('change', function(){
            let value = $('#filter-percentage').val();
            switch (value) {
                case 'year':
                    let url9 = "{{ route('admin.solvePercentage.year') }}";
                    $.getJSON(url9, function(response){
                        donutRatechart.updateOptions({
                            series: response.data
                        });
                    });
                    break;
                
                case 'month':
                    let url10 = "{{ route('admin.solvePercentage.month') }}";
                    $.getJSON(url10, function(response){
                        donutRatechart.updateOptions({
                            series: response.data
                        });
                    });
                    break;

                case 'week':
                    let url11 = "{{ route('admin.solvePercentage.week') }}";
                    $.getJSON(url11, function(response){
                        donutRatechart.updateOptions({
                            series: response.data
                        });
                    });
                    break;
            
                default:
                    break;
            }
        });
    });
</script>