@extends('layouts.master', [
    'datatable' => true, 
    'confirm_safeable' => true,
    'modals' => ['employee'],
    'title' => __('accounting::global.salaries'),
    'crumbs' => [
        ['#', __('accounting::global.salaries')],
    ]
])

@push('head')
    
@endpush


@section('content')
        <!-- Content Header (Page header) -->
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title float-left">@lang('accounting::salaries.list')</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-extra">
                        <form action="" method="GET" class="form-inline guide-advanced-search">
                            @csrf
                            <div class="form-group mr-2">
                                <label>
                                    <i class="fa fa-cogs"></i>
                                    <span>@lang('accounting::global.search_advanced')</span>
                                </label>
                            </div>
                            <div class="form-group mr-2">
                                <label for="from-date">@lang('accounting::global.from')</label>
                                <input type="date" name="from_date" id="from-date" value="{{ $from_date }}" class="form-control">
                            </div>
                            <div class="form-group mr-2">
                                <label for="to-date">@lang('accounting::global.to')</label>
                                <input type="date" name="to_date" id="to-date" value="{{ $to_date }}" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <span>@lang('accounting::global.search')</span>
                                <i class="fa fa-search"></i>
                            </button>
                        </form>
                        </div>
                        <div class="card-body text-center">
                            <table id="datatable" class="datatable table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>@lang('accounting::global.id')</th>
                                        <th>@lang('accounting::global.month')</th>
                                        <th>@lang('accounting::global.employee')</th>
                                        <th>@lang('accounting::global.salary')</th>
                                        {{--  <th>@lang('accounting::global.status')</th>  --}}
                                        <th>@lang('accounting::global.user')</th>
                                        <th>@lang('accounting::global.date')</th>
                                        <th>@lang('accounting::global.options')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($salaries as $salary)
                                    <tr>
                                        <td>{{ $salary->id }}</td>
                                        <td>
                                            @if (auth()->user()->isAbleTo('employees-read'))
                                            <button class="btn btn-info btn-xs employees preview"
                                                data-action="{{ route('employees.update', $salary->employee_id) }}"
                                                data-id="{{ $salary->employee->id }}" data-id="{{ $salary->employee->id }}"
                                                data-name="{{ $salary->employee->name }}" data-line="{{ $salary->employee->line }}"
                                                data-public-line="{{ $salary->employee->public_line }}"
                                                data-started="{{ $salary->employee->started_at }}" data-salary="{{ $salary->employee->salary }}"
                                                data-position="{{ $salary->employee->position->title }}"
                                                data-department="{{ $salary->employee->department->title }}" data-toggle="modal"
                                                data-target="#employeeModal">
                                                <i class="fa fa-eye"></i>
                                                <span>{{ $salary->employee->name }}</span>
                                            </button>
                                            @else
                                            {{ $salary->employee->name }}
                                            @endif
                                        </td>
                                        <td>{{ $salary->month }}</td>
                                        <td>{{ $salary->displayAmount() }}</td>
                                        {{--  <td>{{ $salary->money('bonus') }}</td>
                                        <td>{{ $salary->money('debts') }}</td>
                                        <td>{{ $salary->money('deducations') }}</td>
                                        <td>{{ $salary->money('total') }}</td>
                                        <td>{{ $salary->money('net') }}</td>  --}}
                                        <td>{{ $salary->auth()->name }}</td>
                                        <td>{{ $salary->created_at->format('Y/m/d') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                @if (auth()->user()->isAbleTo('salaries-update'))
                                                <a href="{{ route('accounting.salaries.confirm', $salary) }}" class="btn btn-success">
                                                    <i class="fa fa-check"></i>
                                                    <span>@lang('accounting::global.confirm_title')</span>
                                                </a>
                                                @else
                                                <span>
                                                    <i class="fa fa-time"></i>
                                                    <span>@lang('accounting::global.confirming')</span>
                                                </span>
                                                @endif
                                                @permission('salaries-read')
                                                <a href="{{ route('accounting.salary', ['salary' => $salary, 'layout' => 'print']) }}"
                                                    class="btn btn-default">
                                                    <i class="fa fa-print"></i>
                                                    <span>طباعة</span>
                                                </a></li>
                                                @endpermission
                                                @permission('salaries-read')
                                                <a href="{{ route('accounting.salary', $salary) }}" class="btn btn-info">
                                                    <i class="fa fa-eye"></i>
                                                    <span>@lang('global.show')</span>
                                                </a></li>
                                                @endpermission
                                                @permission('salaries-delete')
                                                <a href="#" class="btn btn-danger delete" data-form="#deleteForm-{{ $salary->id }}">
                                                    <i class="fa fa-trash"></i>
                                                    <span>حذف</span>
                                                </a>
                                                @endpermission
                                            </div>
                                            @permission('salaries-delete')
                                            <form id="deleteForm-{{ $salary->id }}" action="{{ route('salaries.destroy', $salary) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                            </form>
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
            </div>
        </section>
        <!-- /.content -->    
@endsection


@push('foot')
   
@endpush
