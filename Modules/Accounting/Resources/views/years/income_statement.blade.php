@extends('layouts.print', [
    'title', __('accounting::lists.income_statement') . ': ' . $year->id,
    'heading', __('accounting::lists.income_statement') . ': ' . $year->id,
])
@push('content')
    <h1>
        <i class="fa fa-list"></i>
        <span>@lang('accounting::lists.income_statement'): {{ $year->id }}</span>
    </h1>
    <div class="table-wrapper">
        <table class="table table-bordered table-condensed table-striped">
            <tbody>
                <tr>
                    <td class="col-md-2">
                        <div>-</div>
                        @component('accounting::years.income_amounts')
                            @slot('year', $year)
                            @slot('type', 'amounts')
                            @slot('account', revenuesAccount())
                        @endcomponent
                        <div><strong style="">{{ number_format($revenues_amount, 2) }}</strong></div>
                    </td>
                    <td class="col-md-2">-</td>
                    <td class="col-md-8">
                        <div><strong style="text-decoration: underline">{{ revenuesAccount()->name }}</strong></div>
                        @component('accounting::years.income_amounts')
                            @slot('year', $year)
                            @slot('type', 'names')
                            @slot('side', 'revenues')
                            @slot('account', revenuesAccount())
                        @endcomponent
                        <div><strong style="">@lang('accounting::global.total_revenues')</strong></div>

                    </td>
                </tr>
                
                <tr>
                    <td>-</td>
                    <td>
                        <div>-</div>
                        @component('accounting::years.income_amounts')
                            @slot('year', $year)
                            @slot('type', 'amounts')
                            @slot('account', expensesAccount())
                        @endcomponent
                        <div><strong style="">{{ number_format($expenses_amount, 2) }}</strong></div>
                    </td>
                    <td>
                        <div><strong style="text-decoration: underline">{{ expensesAccount()->name }}</strong></div>
                        @component('accounting::years.income_amounts')
                            @slot('year', $year)
                            @slot('type', 'names')
                            @slot('side', 'expenses')
                            @slot('account', expensesAccount())
                        @endcomponent
                        <div><strong style="">@lang('accounting::global.total_expenses')</strong></div>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td class="revenues">
                        @if($revenues_amount > $expenses_amount)
                            <span class="success">{{ number_format(($revenues_amount - $expenses_amount), 2) }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="expenses">
                        @if($expenses_amount > $revenues_amount)
                            <span class="error">{{ number_format(($expenses_amount - $revenues_amount), 2) }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="title">
                        @if($revenues_amount > $expenses_amount)
                            <strong style="text-decoration: underline" class="success">@lang('accounting::global.net_profit')</strong>
                        @elseif($expenses_amount > $revenues_amount)
                            <strong style="text-decoration: underline" class="error">@lang('accounting::global.net_lost')</strong>
                        @else
                            لا يوجد ربح او خسارة
                        @endif
                    </strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
@endpush
@push('foot')
<script>
    $(document).ready(function(){
        let revenues_accounts = $('.revenues-account-balance');
        let expenses_accounts = $('.expenses-account-balance');
    });
</script>
@endpush