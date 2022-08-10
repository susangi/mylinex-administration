@extends('Administration::layouts.app')
@section('title', 'Dashboard')
@section('content')
    <!-- Container -->
    <div class="container mt-xl-50 mt-sm-30 mt-15">
        <!-- Title -->
        <div class="hk-pg-header align-items-top">
            <div>
                <h2 class="hk-pg-title font-weight-600 mb-10">Welcome Back {{Auth::user()->name}}</h2>
            </div>
        </div>
        <!-- /Title -->
        <!-- Row -->
        <div class="row">
            <div class="col-xl-12">
                <div class="hk-row">
                    <div class="col-lg-4 col-md-6">
                        <div class="card card-sm">
                            <div class="card-body">
                                <span class="d-block font-14 font-weight-500 text-dark text-center text-uppercase">customer satisfaction</span>
                                <div class="font-35 font-weight-500 text-dark text-center mt-5">
                                    <span class="counter-anim">95</span><span>%</span>
                                </div>
                                <div class="progress-wrap">
                                    <div class="progress progress-bar-xs rounded-bottom-left rounded-bottom-right">
                                        <div class="progress-bar progress-bar-xs bg-red w-15" role="progressbar" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                                        <div class="progress-bar bg-red-light-3 w-35" role="progressbar" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                                        <div class="progress-bar bg-red-light-4 w-50" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-4">
                                        <span class="d-block text-capitalize">desktop</span>
                                        <span class="d-block text-dark font-weight-500 font-20">15%</span>
                                        <span class="d-block font-weight-600 font-13">201,434</span>
                                    </div>
                                    <div class="col-4">
                                        <span class="d-block text-capitalize">mobile</span>
                                        <span class="d-block text-dark font-weight-500 font-20">34.5%</span>
                                        <span class="d-block font-weight-600 font-13">101,434</span>
                                    </div>
                                    <div class="col-4">
                                        <span class="d-block text-capitalize">tablet</span>
                                        <span class="d-block text-dark font-weight-500 font-20">60.8%</span>
                                        <span class="d-block font-weight-600 font-13">101,434</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header card-header-action">
                                <h6>Lead Stats</h6>
                                <div class="d-flex align-items-center card-action-wrap">
                                    <div class="inline-block dropdown">
                                        <a class="dropdown-toggle no-caret" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="ion ion-ios-more"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Separated link</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="hk-legend-wrap mb-20">
                                    <div class="hk-legend">
                                        <span class="d-10 bg-red-light-3 rounded-circle d-inline-block"></span><span>Won Leads</span>
                                    </div>
                                    <div class="hk-legend">
                                        <span class="d-10 bg-red rounded-circle d-inline-block"></span><span>Lost Leads</span>
                                    </div>
                                </div>
                                <div id="e_chart_10" class="echart" style="height:291px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-6">
                        <div class="hk-row">
                            <div class="col-lg-4">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <span class="d-block font-11 font-weight-500 text-dark text-uppercase mb-10">Budget</span>
                                        <div class="d-flex align-items-center justify-content-between position-relative">
                                            <div>
														<span class="d-block">
															<span class="display-5 font-weight-400 text-dark">$74,260</span>
														</span>
                                            </div>
                                            <div class="position-absolute r-0">
														<span id="pie_chart_2" class="d-flex easy-pie-chart" data-percent="75">
															<span class="percent head-font">75</span>
														</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <span class="d-block font-11 font-weight-500 text-dark text-uppercase mb-10">Revenue</span>
                                        <div class="d-flex align-items-end justify-content-between">
                                            <div>
														<span class="d-block">
															<span class="display-5 font-weight-400 text-dark">$28,725</span>
															<small>excl tax</small>
														</span>
                                            </div>
                                            <div>
                                                <span class="text-success font-12 font-weight-600">+5%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <span class="d-block font-11 font-weight-500 text-dark text-uppercase mb-10">Genrated Invoices</span>
                                        <div class="d-flex align-items-end justify-content-between">
                                            <div>
														<span class="d-block">
															<span class="display-5 font-weight-400 text-dark">187</span>
														</span>
                                            </div>
                                            <div>
                                                <span class="text-danger font-12 font-weight-600">-12%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header card-header-action">
                                <h6>Campaigning Stats</h6>
                                <div class="d-flex align-items-center card-action-wrap">
                                    <div class="d-flex align-items-center card-action-wrap">
                                        <a href="#" class="inline-block refresh mr-15">
                                            <i class="ion ion-md-arrow-down"></i>
                                        </a>
                                        <a class="inline-block card-close" href="#" data-effect="fadeOut">
                                            <i class="ion ion-md-close"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="hk-legend-wrap mb-20">
                                    <div class="hk-legend">
                                        <span class="d-10 bg-red-light-3 rounded-circle d-inline-block"></span><span>Click Rate</span>
                                    </div>
                                    <div class="hk-legend">
                                        <span class="d-10 bg-red-light-2 rounded-circle d-inline-block"></span><span>Impressions</span>
                                    </div>
                                </div>
                                <!--<div id="flot_line_chart_moving" class="" style="height:234px;"></div>-->
                                <div id="area_chart" class="morris-chart" style="height:345px;"></div>
                                <div class="row mt-20 text-center">
                                    <div class="col-4">
                                        <span class="d-block text-capitalize">Weekly Users</span>
                                        <span class="d-block text-dark font-weight-500 font-20">324,222</span>
                                    </div>
                                    <div class="col-4">
                                        <span class="d-block text-capitalize">Monthly Users</span>
                                        <span class="d-block text-dark font-weight-500 font-20">123,432</span>
                                    </div>
                                    <div class="col-4">
                                        <span class="d-block text-capitalize">Trend</span>
                                        <span class="d-block">
												<i class="zmdi zmdi-trending-up text-success font-24"></i>
											</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body pa-0">
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover mb-0">
                                    <thead>
                                    <tr>
                                        <th>Project</th>
                                        <th>Company</th>
                                        <th>Update</th>
                                        <th>Status</th>
                                        <th>Tasks</th>
                                        <th>Deadline</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Branding</td>
                                        <td>Pineapple Inc</td>
                                        <td>13 Nov 2018</td>
                                        <td><span class="badge badge-soft-success">Completed</span></td>
                                        <td><span class="d-flex align-items-center"><i class="zmdi zmdi-time-restore font-25 mr-10 text-danger"></i><span>0</span></span></td>
                                        <td>10 Nov 2018</td>
                                    </tr>
                                    <tr>
                                        <td>Website</td>
                                        <td>Gooole co.</td>
                                        <td>30 Nov 2018</td>
                                        <td><span class="badge badge-soft-primary">In Process</span></td>
                                        <td><span class="d-flex align-items-center"><i class="zmdi zmdi-time-restore font-25 mr-10 text-danger"></i><span>3</span></span></td>
                                        <td>13 Dec 2018</td>
                                    </tr>
                                    <tr>
                                        <td>Collaterals</td>
                                        <td>Big Energy</td>
                                        <td>12 Nov 2018</td>
                                        <td><span class="badge badge-soft-danger">Behind</span></td>
                                        <td><span class="d-flex align-items-center"><i class="zmdi zmdi-time-restore font-25 mr-10 text-danger"></i><span>14</span></span></td>
                                        <td>21 Oct 2018</td>
                                    </tr>
                                    <tr>
                                        <td>Branding, Print</td>
                                        <td>Novotel</td>
                                        <td>10 Nov 2018</td>
                                        <td><span class="badge badge-soft-primary">In process</span></td>
                                        <td><span class="d-flex align-items-center"><i class="zmdi zmdi-time-restore font-25 mr-10 text-danger"></i><span>6</span></span></td>
                                        <td>14 Nov 2018</td>
                                    </tr>
                                    <tr>
                                        <td>Web Application</td>
                                        <td>Folkswagan</td>
                                        <td>12 Nov 2018</td>
                                        <td><span class="badge badge-soft-danger">Behind</span></td>
                                        <td><span class="d-flex align-items-center"><i class="zmdi zmdi-time-restore font-25 mr-10 text-danger"></i><span>9</span></span></td>
                                        <td>15 Oct 2018</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Row -->
    </div>
    <!-- /Container -->
@endsection
@push('styles')
    @include('Administration::layouts.includes.styles.fonts')
    @include('Administration::layouts.includes.styles.charts')
    @include('Administration::layouts.includes.styles.interface')
@endpush
@push('scripts')
    @include('Administration::layouts.includes.scripts.fonts')
    @include('Administration::layouts.includes.scripts.charts')
    @include('Administration::layouts.includes.scripts.interface')
    <script src="{{asset('js/dashboard2-data.js')}}"></script>
    <script>
        /*Counter Animation*/
        var counterAnim = $('.counter-anim');
        if( counterAnim.length > 0 ){
            counterAnim.counterUp({ delay: 10,
                time: 1000});
        }
    </script>
@endpush
