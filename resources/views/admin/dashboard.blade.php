
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box back bg-info">
            <a href="admin/users">
                <span class="info-box-icon bg-aqua"><i class="fa fa-users text-white"></i></span>
            </a>
            <div class="info-box-content">
                <span class="info-box-text text-white">Пользователи</span>
                <span class="info-box-number text-white">{{\App\User::where('role', 2)->count()}}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box  bg-success">
            <a href="/admin/mail_orders">
                <span class="info-box-icon bg-red"><i class="fa fa-envelope text-white"></i></span>
            </a>
            <div class="info-box-content">
                <span class="info-box-text text-white">Заказы на Email</span>
                <span class="info-box-number text-white">{{\App\Order::where('type', 1)->count()}}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->
    <div class="clearfix visible-sm-block"></div>

    <div class="col-md-3 col-sm-6 col-xs-12">

        <div class="info-box bg-warning">
            <a href="/admin/kurs">
            <span class="info-box-icon bg-green"><i class="fa fa-credit-card text-white" aria-hidden="true"></i></span>
            </a>
            <div class="info-box-content">
                <span class="info-box-text">Курс доллара</span>
                <span class="info-box-text"><b>на {{date('d.m.Y')}}</b></span>
                <span class="info-box-number text-danger">{{number_format(\App\Kurs::orderBy('date', 'desc')->value('USD'), 2, '.', ' ')}}</span>
            </div>

            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-danger">
            <a href="/admin/kurs">
            <span class="info-box-icon bg-yellow"><i class="fa fa-credit-card-alt"></i></span>
            </a>
            <div class="info-box-content">
                <span class="info-box-text text-white">Курс йены</span>
                <span class="info-box-text text-white"><b>на {{date('d.m.Y')}}</b></span>
                <span class="info-box-number text-warning">{{number_format(\App\Kurs::orderBy('date', 'desc')->value('JPY'), 2, '.', ' ')}}</span>
            </div>

            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
</div>

<!-- MAP & BOX PANE -->
<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">Информация</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                <i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
            </button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body no-padding">
        <div class="row">
            <div class="col-md-9 col-sm-8">
                <div class="pad">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Пользователи</div>
                        <div class="panel-body">
                            <table class="table table-responsive table-hover dashboard-user">
                                <thead class="table-info">
                                <tr>
                                    <th>#</th>
                                    <th>Пользователь</th>
                                   {{-- <th>Баланс</th>--}}
                                   {{-- <th>Email</th>--}}
                                    <th>Дата регистрации</th>
                                    <th>Действие</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $i=1;
                                    $query = \App\User::where('role', 2)->orderBy('created_at', 'desc')->limit(10)->get();
                                @endphp
                                @if($query)
                                    @foreach($query as $row)
                                        <tr>
                                            <th scope="row">{{$i}}</th>
                                            <td><a href="admin/users/{{$row->id }}/edit">{{$row->name}}</a></td>
                                            {{--<td>{{$row->account? $row->account: 0}}&nbsp;<i class="fa fa-ruble"></i></td>
                                            <td>{{$row->email}}</td>--}}
                                            <td>{{date('d.m.Y',strtotime($row->created_at))}}</td>
                                            <td class="user-admin-dashboard">
                                                <a href="admin/users/{{$row->id }}/edit"  data-toggle="tooltip" data-placement="bottom" title="редактировать" style="float: left">
                                                    <i class="fa fa-pencil fa-lg text-warning"></i>
                                                </a>
                                                &nbsp;{{--<form action="admin/users/{{$row->id }}/edit" method="post">
                                                        {{ csrf_field() }}
                                                        <input type="hidden" name="id" value="{{$row->id}}">
                                                        <button class="btn btn-xs " title="Редактировать" data-toggle="tooltip">
                                                            <i class="fa fa-pencil"></i>

                                                        </button>
                                                    </form>--}}
                                                <form action="{{route('admin.users.ban')}}" method="post" class="user-bun" style="float: left;padding-left: 5px">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="id" value="{{$row->id}}">
                                                    <button class="btn btn-xs btn-danger" title="Забанить" data-toggle="tooltip">
                                                        <i class="fa fa-ban"></i>

                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                @else
                                    <tr><td colspan="5">Нет пользователей.</td></tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Map will be created here
                    <div id="world-map-markers" style="height: 325px;">
                        <div class="jvectormap-container" style="width: 100%; height: 100%; position: relative; overflow: hidden; background-color: transparent;">
                            <svg width="778" height="325">
                            </svg>
                            <div class="jvectormap-zoomin">+</div>
                            <div class="jvectormap-zoomout">−</div>
                        </div>
                    </div>-->
                </div>
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-4">
                <div class="pad box-pane-right bg-gray" style="min-height: 280px">
                    @php
                        $day_now = date('Y-m-d 00:00:00');
                        $users_today = \App\User::where('created_at', '>', $day_now)->count();
                        $email_ord_today = \App\Order::where([['datus', '>', strtotime($day_now)], 'type' => 1])->count();
                    @endphp
                    <div class="description-block margin-bottom bg-danger" style="padding: 5px 0 7px 0">
                        {{--<div class="sparkbar pad" data-color="#fff">
                            <canvas width="34" height="30" style="display: inline-block; width: 34px; height: 30px; vertical-align: top;"></canvas>
                        </div>--}}
                        <a href="/admin/mail_orders">
                            <h1 style="color:#ffffff;font-weight: 700">{{$email_ord_today}}</h1>
                        </a>
                        <span class="description-text text-white">Заказов на Email сегодня</span>
                    </div>
                    <!-- /.description-block -->
                    <div class="description-block margin-bottom bg-info" style="padding: 5px 0 7px 0">
                        {{--<div class="sparkbar pad" data-color="#fff">
                            <canvas width="34" height="30" style="display: inline-block; width: 34px; height: 30px; vertical-align: top;"></canvas>
                        </div>--}}
                        <a href="/admin/users">
                            <h1 style="color:#ffffff;font-weight: 700">{{$users_today}}</h1>
                        </a>
                        <span class="description-text text-white">Пользователи сегодня</span>
                    </div>
                    <!-- /.description-block -->
                    <div class="description-block margin-bottom bg-success" style="padding: 5px 0 7px 0">
                        <a href="/admin/tickets">
                            @php
                                $count = \App\Ticket::where('status', 0)->count();
                                if ($count > 0){
                                    $color = 'red';
                                }else{
                                    $color = '#ffffff';
                                }
                            @endphp
                            <h1 style="color:{{$color}};font-weight: 700">
                                {{$count}}
                            </h1>
                        </a>
                        <span class="description-text text-white" style="font-size: 16px;font-weight: 600">Тикеты без ответа</span>
                    </div>

                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.box-body -->
</div>


