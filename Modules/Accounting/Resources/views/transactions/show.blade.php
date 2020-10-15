@extends('layouts.master', [
    'modals' => ['employee', 'attachment'],
    'datatable' => true, 
    'lightbox' => true, 
    'confirm_safeable' => true,
    'title' => $transaction->displayType() . ': ' . $transaction->id,
    'crumbs' => [
        [route('accounting.transactions'), __('accounting::global.transactions')],
        ['#', $transaction->displayType() . ': ' . $transaction->id],
    ]
])
@section('content')
    <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" id="tabs-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tabs-details-tab" data-toggle="pill" href="#tabs-details" role="tab"
                        aria-controls="tabs-details" aria-selected="true">@lang('accounting::global.details')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tabs-attachments-tab" data-toggle="pill" href="#tabs-attachments" role="tab"
                        aria-controls="tabs-attachments" aria-selected="false">@lang('accounting::global.attachments')</a>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        <span>@lang('accounting::global.options')</span>
                        <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu">
                        @if (auth()->user()->isAbleTo('transactions-update') && !$transaction->entry)
                        <a href="#" class="dropdown-item" data-confirm="safeable" data-modal="safeable"
                            data-safeable-type="{{ get_class($transaction) }}" data-safeable-id="{{ $transaction->id }}"
                            data-amount="{{ $transaction->amount }}" data-type="{{ $transaction->getType() }}" data-account-id="0">
                            <i class="fa fa-check"></i>
                            <span>@lang('accounting::global.confirm_title')</span>
                        </a>
                        @endif
                        @permission('transactions-delete')
                        <a href="#" class="dropdown-item delete" data-form="#deleteForm-{{ $transaction->id }}">
                            <i class="fa fa-trash"></i>
                            <span>@lang('accounting::global.delete')</span>
                        </a>
                        <form id="deleteForm-{{ $transaction->id }}" style="display:none;"
                            action="{{ route('transactions.destroy', $transaction->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                        </form>
                        @endpermission
                    </div>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="tabs-tabContent">
                <div class="tab-pane fade active show" id="tabs-details" role="tabpanel" aria-labelledby="tabs-details-tab">
                    <table class="table table-bordered table-hover text-center">
                        <thead>
                            <tr>
                                <th>@lang('accounting::global.id')</th>
                                <th>@lang('accounting::global.type')</th>
                                <th>@lang('accounting::global.employee')</th>
                                <th>@lang('accounting::global.amount')</th>
                                <th>@lang('accounting::global.status')</th>
                                <th>@lang('accounting::global.user')</th>
                                <th>@lang('accounting::global.date')</th>
                                <th>@lang('accounting::global.options')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $transaction->id }}</td>
                                <td>{{ $transaction->displayType() }}</td>
                                <td>{{ $transaction->employee->name }}</td>
                                <td>{{ number_format($transaction->amount, 2) }}</td>
                                <td>{{ $transaction->displayStatus() }}</td>
                                <td>{{ $transaction->auth()->name }}</td>
                                <td>{{ $transaction->created_at->format('Y/m/d') }}</td>
                                <td>
                                    <div class="btn-group">
                                        @if (auth()->user()->isAbleTo('transactions-update') && !$transaction->entry)
                                        <a href="#" class="btn btn-success" data-confirm="safeable" data-modal="safeable"
                                            data-safeable-type="{{ get_class($transaction) }}" data-safeable-id="{{ $transaction->id }}"
                                            data-amount="{{ $transaction->amount }}" data-type="{{ $transaction->getType() }}" data-account-id="0">
                                            <i class="fa fa-check"></i>
                                            <span>@lang('accounting::global.confirm_title')</span>
                                        </a>
                                        @endif
                                        @permission('transactions-delete')
                                        <a href="#" class="btn btn-danger delete" data-form="#deleteForm-{{ $transaction->id }}">
                                            <i class="fa fa-trash"></i>
                                            <span>حذف</span>
                                        </a>
                                        @endpermission
                                    </div>
                                    @permission('transactions-delete')
                                    <form id="deleteForm-{{ $transaction->id }}" action="{{ route('transactions.destroy', $transaction) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    @endpermission
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="tabs-attachments" role="tabpanel" aria-labelledby="tabs-attachments-tab">
                    @component('components.attachments-viewer')
                        @slot('attachments', $transaction->attachments)
                        @slot('canAdd', true)
                        @slot('view', 'timeline')
                        @slot('attachableType', 'Modules\Transaction\Models\Transaction')
                        @slot('attachableId', $transaction->id)
                    @endcomponent
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
@endsection
