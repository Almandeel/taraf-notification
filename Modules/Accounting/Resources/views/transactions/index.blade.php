@extends('layouts.master', [
    'datatable' => true, 
    'confirm_safeable' => true,
    'modals' => ['employee'],
    'title' => __('accounting::global.transactions'),
    'crumbs' => [
        ['#', __('accounting::global.transactions')],
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
                            <h3 class="card-title float-left">@lang('accounting::transactions.list')</h3>
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
                                <label for="type">@lang('accounting::global.type')</label>
                                <select class="form-control type" name="type" id="type">
                                    <option value="all" {{ ($type == 'all') ? 'selected' : '' }}>@lang('global.all')</option>
                                    @foreach ($types as $t)
                                    <option value="{{ $t }}" {{ ($type != 'all' && $t == $type) ? 'selected' : '' }}>
                                        @lang('transactions.types.' . $t)
                                    </option>
                                    @endforeach
                                </select>
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
                                        <th>@lang('accounting::global.type')</th>
                                        <th>@lang('accounting::global.employee')</th>
                                        <th>@lang('accounting::global.amount')</th>
                                        {{--  <th>@lang('accounting::global.status')</th>  --}}
                                        <th>@lang('accounting::global.user')</th>
                                        <th>@lang('accounting::global.date')</th>
                                        <th>@lang('accounting::global.options')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->id }}</td>
                                        <td>{{ $transaction->displayType() }}</td>
                                        <td>{{ $transaction->employee->name }}</td>
                                        <td>{{ number_format($transaction->amount, 2) }}</td>
                                        {{--  <td>{{ $transaction->displayStatus() }}</td>  --}}
                                        <td>{{ $transaction->auth()->name }}</td>
                                        <td>{{ $transaction->created_at->format('Y/m/d') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                @if (auth()->user()->isAbleTo('transactions-update'))
                                                <a href="#" class="btn btn-success" data-confirm="safeable" data-modal="safeable"
                                                    data-safeable-type="{{ get_class($transaction) }}" data-safeable-id="{{ $transaction->id }}"
                                                    data-amount="{{ $transaction->amount }}" data-type="{{ $transaction->getType() }}"
                                                    data-account-id="0" >
                                                    <i class="fa fa-check"></i>
                                                    <span>@lang('accounting::global.confirm_title')</span>
                                                </a>
                                                @else
                                                <span>
                                                    <i class="fa fa-time"></i>
                                                    <span>@lang('accounting::global.confirming')</span>
                                                </span>
                                                @endif
                                                @permission('transactions-read')
                                                <a href="{{ route('accounting.transaction', $transaction) }}" class="btn btn-info">
                                                    <i class="fa fa-eye"></i>
                                                    <span>@lang('global.show')</span>
                                                </a></li>
                                                @endpermission
                                                @permission('transactions-delete')
                                                <a href="#" class="btn btn-danger delete" data-form="#deleteForm-{{ $transaction->id }}">
                                                    <i class="fa fa-trash"></i>
                                                    <span>حذف</span>
                                                </a>
                                                @endpermission
                                            </div>
                                            @permission('transactions-delete')
                                            <form id="deleteForm-{{ $transaction->id }}" action="{{ route('transactions.destroy', $transaction) }}" method="POST">
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
