@extends('layouts.master', 
[
    'datatable' => true, 
    'confirm_status' => true, 
    'title' => 'عقد رقم: ' . $contract->id, 
    'modals' => ['position', 'employee', 'attachment'],
    'crumbs' => [
        [route('contracts.index'), 'العقود'],
        ['#', 'عقد رقم: ' . $contract->id],
    ],
])

@push('head')
    
@endpush


@section('content')
    <section class="content">
        @component('components.tabs')
            @slot('items')
                @component('components.tab-item')
                    @if (session('active_tab') != 'vouchers')
                        @slot('active', true)
                    @endif
                    @slot('id', 'details')
                    @slot('title', 'بيانات العقد')
                @endcomponent
                @component('components.tab-item')
                    @slot('id', 'attachments')
                    @slot('title', 'المرفقات')
                @endcomponent
                @component('components.tab-item')
                    @if (session('active_tab') == 'vouchers')
                        @slot('active', true)
                    @endif
                    @slot('id', 'vouchers')
                    @slot('title', 'السندات')
                @endcomponent
            @endslot
            @slot('contents')
                @component('components.tab-content')
                    @if (session('active_tab') != 'vouchers')
                        @slot('active', true)
                    @endif
                    @slot('id', 'details')
                    @slot('content')
                        <table class="table table-bordered table-striped text-center">
                            <thead>
                                @if ($contract->getApplicationDays(false, true) > 0)
                                    <tr>
                                        <th colspan="4">
                                            <span>مدة التقديم {{ $contract->getApplicationDays(true) }}</span>
                                            <span>-</span>
                                            <span>المتبقي {{ $contract->getApplicationDays(true, true) }}</span>
                                        </th>
                                    </tr>
                                @elseif ($contract->getApplicationDays(false, true) < 0)
                                    <tr>
                                        <th colspan="4">
                                            <span>مدة التقديم {{ $contract->getApplicationDays(true) }}</span>
                                            <span>-</span>
                                            <span>{{ $contract->getApplicationDays(true, true) }}</span>
                                        </th>
                                    </tr>
                                @endif
                                <tr>
                                    <th>تاريخ العقد</th>
                                    <td>{{ $contract->created_at->format('Y-m-d') }}</td>
                                    <th>المهنة</th>
                                    <td>{{ $contract->profession->name }}</td>
                                </tr>
                                <tr>
                                    <th>
                                        رقم العقد
                                    </th>
                                    <td>{{ $contract->id }}</td>
                                    <th>
                                        العامل \ العاملة
                                    </th>
                                    <td>{{ $contract->cv()->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>
                                        رقم التأشيرة
                                    </th>
                                    <td>{{ $contract->visa }}</td>
                                    <th>
                                        الحالة الإجتماعية 
                                    </th>
                                    <td>{{ $contract->cv()->nationality ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>
                                        اسم العميل
                                    </th>
                                    <td>{{ $contract->customer->name }}</td>
                                    <th>
                                        رقم الهوية
                                    </th>
                                    <td>{{ $contract->customer->id_number }}</td>
                                </tr>
                                <tr>
                                    <th>
                                        قيمة العقد
                                    </th>
                                    <td>{{ $contract->amount }}</td>
                                    <th>
                                     الديانة
                                    </th>
                                    <td>{{ $contract->cv()->religion ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>
                                        المسوق
                                    </th>
                                    <td>{{ $contract->marketer->name ?? '' }}</td>
                                    <th>
                                        جهة القدوم
                                    </th>
                                    <td>{{ $contract->destination  }}</td>
                                </tr>
                                <tr>
                                    <th>
                                        نسبة المسوق
                                    </th>
                                    <td>{{ $contract->marketing_ratio }}</td>
                                    <th>
                                        مطار القدوم
                                    </th>
                                    <td>{{ $contract->arrival_airport  }}</td>
                                </tr>
                                <tr>
                                    <th>
                                        الدولة
                                    </th>
                                    <td>{{ $contract->country->name }}</td>
                                    <th>
                                        تاريخ الوصول
                                    </th>
                                    <td>{{ $contract->date_arrival  }}</td>
                                </tr>
                                <tr>
                                    <th>
                                        تاريخ التقديم
                                    </th>
                                    <td>{{ $contract->start_date }}</td>
                                    <th>
                                        مدة التقديم
                                    </th>
                                    <td>{{ $contract->getApplicationDays(true)  }}</td>
                                </tr>
                                {{--  <tr>
                                    <th>
                                        ملاحظات العقد
                                    </th>
                                    <td colspan="3">{{ $contract->details  }}</td>
                                </tr>  --}}
                                {{-- 
                                    <tr>
                                        <tr>
                                            <th>تاريخ العقد</th>
                                            <td>{{ $contract->created_at->format('Y-m-d') }}</td>
                                        </tr>
                                        <tr>
                                            <th>العميل</th>
                                            <td>{{ $contract->customer->name }}</td>
                                        </tr>
                                    </tr>
                                    <tr>
                                        <th>المكتب الخارجي</th>
                                        <td>{{ $contract->office->name ?? '' }}  {{ $contract->country->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>العامل \ العاملة</th>
                                        <td>{{ $contract->cv()->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>رقم التأشيرة</th>
                                        <td>{{ $contract->visa }}</td>
                                    </tr>
                                    <tr>
                                        <th>المهنة</th>
                                        <td>{{ $contract->profession->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>قيمة العقد</th>
                                        <td>{{ $contract->amount }}</td>
                                    </tr>
                                    <tr>
                                        <th>المسوق</th>
                                        <td>{{ $contract->marketer->name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>نسبة المسوق</th>
                                        <td>{{ $contract->marketing_ratio  }}</td>
                                    </tr>
                                    <tr>
                                        <th>جهة الوصول</th>
                                        <td>{{ $contract->destination  }}</td>
                                    </tr>
                                    <tr>
                                        <th>مطار الوصول</th>
                                        <td>{{ $contract->arrival_airport  }}</td>
                                    </tr>
                                    <tr>
                                        <th>تاريخ الوصول</th>
                                        <td>{{ $contract->date_arrival  }}</td>
                                    </tr>
                                    <tr>
                                        <th>خيارات</th>
                                        <td>
                                            <a class="btn btn-warning btn-sm contracts update"
                                                href="{{ route('contracts.edit', $contract->id) }}"><i class="fa fa-edit"></i> تعديل</a>
                                        </td>
                                    </tr> --}}
                            </thead>
                        </table>
                    @endslot
                @endcomponent
                @component('components.tab-content')
                    @slot('id', 'attachments')
                    @slot('content')
                        @component('components.attachments-viewer')
                            @slot('attachable', $contract)
                            @slot('canAdd', true)
                            @slot('view', 'timeline')
                        @endcomponent
                    @endslot
                @endcomponent
                @component('components.tab-content')
                    @if (session('active_tab') == 'vouchers')
                        @slot('active', true)
                    @endif
                    @slot('id', 'vouchers')
                    @slot('content')
                        @component('accounting::components.vouchers')
                            @slot('voucherable', $contract)
                        @endcomponent
                    @endslot
                @endcomponent
            @endslot
        @endcomponent
    </section>
    <!-- /.content -->    
@endsection


@push('foot')

@endpush
