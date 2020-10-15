@extends('layouts.master', [
    'title' => 'العملاء',
    'datatable' => true, 
    'modals' => ['customer', 'complaint'], 
    'crumbs' => [
        ['#', 'العملاء'],
    ]
])
@push('head')
    
@endpush


@section('content')
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="card text-right">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="card-title float-left">قائمة العملاء</h3>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="card-title float-right">
                                        @permission('contracts-create')
                                        <button class="btn btn-primary btn-sm customers" data-toggle="modal" data-target="#customerModal"><i class="fa fa-plus"></i> إضافة</button>
                                        @endpermission
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body text-center">
                            <table id="datatable" class="datatable table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الاسم</th>
                                        <th>العنوان</th>
                                        <th>رقم الهاتف</th>
                                        <th>رقم الهوية</th>
                                        <th>الخيارات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customers as $index=>$customer)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $customer->name }}</td>
                                                <td>{{ $customer->address }}</td>
                                                <td>{{ $customer->phones }}</td>
                                                <td>{{ $customer->id_number }}</td>
                                                <td>
                                                    @permission('contracts-read')
                                                    <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-default btn-sm"><i class="fa fa-eye"></i> عرض</a>
                                                    @endpermission

                                                    @permission('contracts-create')
                                                    <a href="{{ route('customers.addcontract', $customer->id) }}" class="btn btn-info btn-sm"><i class="fa fa-plus"></i>  انشاء طلب  توظيف </a>
                                                    @endpermission
                                                    
                                                    @permission('contracts-update')
                                                    <button class="btn btn-warning btn-sm customer update" data-description="{{ $customer->description }}"  data-number="{{ $customer->id_number }}" data-action="{{ route('customers.update', $customer->id) }}"  data-name="{{ $customer->name }}" data-address="{{ $customer->address }}" data-phones="{{ $customer->phones }}"  data-toggle="modal" data-target="#warehouseModal"><i class="fa fa-edit"></i> تعديل</button>
                                                    @endpermission
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
            <!-- /.row -->
        </section>
        <!-- /.content -->    
@endsection


@push('foot')


@endpush
