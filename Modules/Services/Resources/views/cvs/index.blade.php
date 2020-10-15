@extends('layouts.master', [
    'title' => 'cvs',
    'modals' => ['customer'],
    'datatable' => true,
    'crumbs' => [
        ['#', 'cvs'],
    ]
])

@section('content')
    <section class="content">
        {{--  <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"> قائمة السير الذاتية</h3>
                        <a class=" btn btn-primary btn-sm float-right" href="{{ route('servicescvs.create') }}"><i class="fa fa-plus"></i> اضافة</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-filters">
                            <div class="row">
                                <div class="col col-md-7">
                                    <div class="form-group form-inline">
                                        <div class="form-group mr-2">
                                            <label>متوسط العمر</label>
                                        </div>
                                        <div class="form-group mr-2">
                                            <label>اقل</label>
                                            <input type="number" class="form-control min" name="min" value="{{ $cvs->count() ? $cvs->sortBy(function($c){return $c->age();})->first()->age() : 20 }}">
                                        </div>
                                        <div class="form-group mr-2">
                                            <label>اكثر</label>
                                            <input type="number" class="form-control max" name="max" value="{{ $cvs->count() ? $cvs->sortBy(function($c){return $c->age();})->last()->age() : 50 }}">
                                        </div>
                                    </div>
                                    <div class="form-group form-inline">
                                        <div class="form-group mr-2">
                                            <label>بين تاريخين</label>
                                        </div>
                                        <div class="form-group mr-2">
                                            <label>من</label>
                                            <input type="date" name="from_date" class="form-control" />
                                        </div>
                                        <div class="form-group mr-2">
                                            <label>الى</label>
                                            <input type="date" name="to_date" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group form-inline">
                                        <div class="form-group mr-2">
                                            <label>الحقل</label>
                                            <div class="input-group">
                                                <select name="column" class="column form-control">
                                                    <option value="0">#</option>
                                                    <option value="1">العمال \ العاملة</option>
                                                    <option value="2">رقم الجواز</option>
                                                    <option value="3">النوع</option>
                                                    <option value="4">العمر</option>
                                                    <option value="5">الدولة</option>
                                                    <option value="6">المكتب الخارجي</option>
                                                    <option value="7">الحالة</option>
                                                    <option value="8">الاجراء الحالى</option>
                                                    <option value="9">تاريخ الانشاء</option>
                                                </select>
                                                <select name="column-value" class="column form-control editable"></select>
                                                <input type="text" class="column-value form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table id="table-filtered" class="table table-bordered table-striped table-filtered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>العمال \ العاملة</th>
                                    <th>رقم الجواز</th>
                                    <th>النوع</th>
                                    <th>العمر</th>
                                    <th>الدولة</th>
                                    <th>المكتب الخارجي</th>
                                    <th>الحالة</th>
                                    <th>الاجراء الحالى</th>
                                    <th>تاريخ الانشاء</th>
                                    <th>خيارات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cvs as $index=>$cv)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $cv->name }}</td>
                                        <td>{{ $cv->passport }}</td>
                                        <td>{{ $cv->gender == 1 ? 'ذكر' : 'انثى' }}</td>
                                        <td>{{ $cv->age() }}</td>
                                        <td>{{ $cv->country->name ?? '' }}</td>
                                        <td>{{ $cv->office->name ?? '' }}</td>
                                        <td>
                                            @if (!$cv->pull)
                                                @if($cv->accepted && $cv->contract_id)
                                                    <span class="text-success">
                                                        تم عمل عقد
                                                    </span>
                                                @endif
                                                @if($cv->accepted && $cv->contract_id == null)
                                                    <span class="text-success">
                                                        تمت الموافقة
                                                    </span>
                                                @endif
                                                @if(!$cv->accepted)
                                                    <span class="text-warning">
                                                        في الانتظار
                                                    </span>
                                                @endif

                                            @elseif ($cv->pull && ! $cv->pull->confirmed)
                                                <p class="text-info">تم تقديم طلب سحب</p>
                                            @elseif ($cv->pull && $cv->pull->confirmed)
                                                <p class="text-danger">تم السحب</p>
                                            @endif
                                        </td>
                                        <td>{{ $cv->procedure }}</td>
                                        <td>{{ $cv->created_at->format('Y/m/d') }}</td>
                                        <td>
                                            <a class="btn btn-info btn-sm" href="{{ route('servicescvs.show', $cv->id) }}"><i class="fa fa-eye"></i> عرض</a>
                                            <a class="btn btn-warning btn-sm cvs update" href="{{ route('servicescvs.edit', $cv->id) }}"><i class="fa fa-edit"></i> تعديل </a>
                                            @if(!$cv->accepted)
                                            <form class="d-inline-block" action="{{ route('servicescvs.update', $cv->id) }}?type=accept" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button class="btn btn-success btn-sm" type="submit"><i class="fa fa-check"></i> موافقة</button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->  --}}
        @component('components.tabs')
            @slot('items')
                @component('components.tab-item')
                    @slot('active', true)
                    @slot('id', 'active')
                    @slot('title', 'السير الذاتية الجديدة')
                @endcomponent
                @component('components.tab-item')
                    @slot('id', 'deactive')
                    @slot('title', 'سير ذاتية تم  اختيارها')
                @endcomponent
            @endslot
            @slot('contents')
                @component('components.tab-content')
                    @slot('active', true)
                    @slot('id', 'active')
                    @slot('content')
                        <table class="table table-bordered table-striped datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>العمال \ العاملة</th>
                                    <th>رقم الجواز</th>
                                    <th>النوع</th>
                                    <th>المكتب الخارجي</th>
                                    <th>الحالة</th>
                                    <th>الاجراء الحالى</th>
                                    <th>تاريخ الانشاء</th>
                                    <th>خيارات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cvs->whereIn('status', [Modules\ExternalOffice\Models\Cv::STATUS_WAITING, Modules\ExternalOffice\Models\Cv::STATUS_ACCEPTED]) as $index=>$cv)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $cv->name }}</td>
                                        <td>{{ $cv->passport }}</td>
                                        <td>{{ $cv->gender == 1 ? 'ذكر' : 'انثى' }}</td>
                                        <td>{{ $cv->office->name ?? '' }}</td>
                                        <td>
                                            @if (!$cv->pull)
                                                @if($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_CONTRACTED)
                                                    <span class="text-success">
                                                        تم عمل عقد
                                                    </span>
                                                @endif
                                                @if($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_ACCEPTED)
                                                    <span class="text-success">
                                                        تمت الموافقة
                                                    </span>
                                                @endif
                                                @if($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_WAITING)
                                                    <span class="text-warning">
                                                        في الانتظار
                                                    </span>
                                                @endif
                                            @elseif ($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_PULLED)
                                                <p class="text-info">تم تقديم طلب سحب</p>
                                            @elseif ($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_PULLED)
                                                <p class="text-danger">تم السحب</p>
                                            @endif
                                        </td>
                                        <td>{{ \Str::limit($cv->procedure, 30) }}</td>
                                        <td>{{ $cv->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            @permission('cv-read')
                                            <a class="btn btn-info btn-sm" href="{{ route('servicescvs.show', $cv->id) }}"><i class="fa fa-eye"></i> عرض</a>
                                            @endpermission

                                            @permission('cv-update')
                                            <a class="btn btn-warning btn-sm cvs update" href="{{ route('servicescvs.edit', $cv->id) }}"><i class="fa fa-edit"></i> تعديل </a>
                                            @endpermission

                                            @permission('cv-update')
                                            @if($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_WAITING)
                                            <form class="d-inline-block" action="{{ route('servicescvs.update', $cv->id) }}?type=accept" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button class="btn btn-success btn-sm" type="submit"><i class="fa fa-check"></i> موافقة</button>
                                            </form>
                                            @endif
                                            @endpermission
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endslot
                @endcomponent
                @component('components.tab-content')
                    @slot('id', 'deactive')
                    @slot('content')
                        <table class="table table-bordered table-striped datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>العمال \ العاملة</th>
                                    <th>رقم الجواز</th>
                                    <th>النوع</th>
                                    <th>المكتب الخارجي</th>
                                    <th>الحالة</th>
                                    <th>الاجراء الحالى</th>
                                    <th>تاريخ الانشاء</th>
                                    <th>خيارات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cvs->whereNotIn('status', [Modules\ExternalOffice\Models\Cv::STATUS_WAITING, Modules\ExternalOffice\Models\Cv::STATUS_ACCEPTED]) as $index=>$cv)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $cv->name }}</td>
                                        <td>{{ $cv->passport }}</td>
                                        <td>{{ $cv->gender == 1 ? 'ذكر' : 'انثى' }}</td>
                                        <td>{{ $cv->office->name ?? '' }}</td>
                                        <td>
                                            @if (!$cv->pull)
                                            @if($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_CONTRACTED)
                                                <span class="text-success">
                                                    تم عمل عقد
                                                </span>
                                            @endif
                                            @if($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_ACCEPTED)
                                                <span class="text-success">
                                                    تمت الموافقة
                                                </span>
                                            @endif
                                            @if($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_WAITING)
                                                <span class="text-warning">
                                                    في الانتظار
                                                </span>
                                            @endif
                                        @elseif ($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_PULLED)
                                            <p class="text-info">تم تقديم طلب سحب</p>
                                        @elseif ($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_PULLED)
                                            <p class="text-danger">تم السحب</p>
                                        @endif
                                        </td>
                                        <td>{{ $cv->procedure }}</td>
                                        <td>{{ $cv->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            @permission('cv-read')
                                            <a class="btn btn-info btn-sm" href="{{ route('servicescvs.show', $cv->id) }}"><i class="fa fa-eye"></i> عرض</a>
                                            @endpermission

                                            @permission('cv-update')
                                            <a class="btn btn-warning btn-sm cvs update" href="{{ route('servicescvs.edit', $cv->id) }}"><i class="fa fa-edit"></i> تعديل </a>
                                            @if($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_WAITING)
                                            <form class="d-inline-block" action="{{ route('servicescvs.update', $cv->id) }}?type=accept" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button class="btn btn-success btn-sm" type="submit"><i class="fa fa-check"></i> موافقة</button>
                                            </form>
                                            @endif
                                            @endpermission
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endslot
                @endcomponent
            @endslot
        @endcomponent
    </section>
@endsection

@push('foot')
    <script>
        $.fn.dataTable.ext.search.push(
            function( settings, data, dataIndex ) {
                var min = parseInt( $('.table-filters input.min[name=min]').val(), 10 );
                var max = parseInt( $('.table-filters input.max[name=max]').val(), 10 );
                var age = parseFloat( data[4] ) || 0; // use data for the age column
        
                if ( ( isNaN( min ) && isNaN( max ) ) ||
                    ( isNaN( min ) && age <= max ) ||
                    ( min <= age   && isNaN( max ) ) ||
                    ( min <= age   && age <= max ) )
                {
                    return true;
                }
                return false;
            }
        );
        $(document).ready(function() {
            const table_filtered = $('#table-filtered').DataTable({
                'ordering': true,
            });
            $('.table-filtered').dataTable().find("thead th").off("click.DT");
            
            // Event listener to the two range filtering inputs to redraw on input
            // $('input.min, input.max').keyup( function() {
            //     table_filtered.draw();
            // } );
            $(document).on('change, keyup', '.table-filters input.min[name=min], .table-filters input.max[name=max]', function(){
                table_filtered
                .columns(4)
                .order( 'asc')
                .draw();
            });
            /*
            $('.table-filters select.column').on( 'change', function () {
                var column = table_filtered.column( i );
                table_filtered.search( this.value ).draw();
            } );
            */
            $('.table-filters input.column-value').on( 'keyup', function () {
                var value = $(this).val();
                if(value.length > 0){
                    table_filtered
                        .columns($('.table-filters select.column').val())
                        .search(value)
                        .order( 'asc')
                        .draw();
                }else{
                    // table_filtered.fnFilterClear()
                    $('.table-filtered').dataTable().fnFilterClear()
                }
            } );
            /*
            table_filtered.columns().indexes().flatten().each( function ( i ) {
                var column = table_filtered.column( i );
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        // Escape the expression so we can perform a regex match
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
            
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
            
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
            */

            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var from_date = $('.table-filters input[name=from_date]').val();
                    var to_date = $('.table-filters input[name=to_date]').val();
                    var min = (from_date ? new Date(from_date) : new Date()).getTime();
                    var max = (to_date ? new Date(to_date) : new Date()).getTime();
                    var startDate = (new Date(data[9])).getTime();
                    if(from_date){
                        if (min == null && max == null) return true;
                        if (min == null && startDate <= max) return true;
                        if (max == null && startDate >= min) return true;
                        if (startDate <= max && startDate >= min) return true;
                        return false;
                    }
                    return true;
                }
            );
            
            $(document).on('keyup, change', '.table-filters input[name=from_date], .table-filters input[name=to_date]', function() {
                table_filtered
                // .column( '9:visible' )
                .columns(9)
                .order( 'asc' ).draw();
            });
        } );
    </script>
@endpush
