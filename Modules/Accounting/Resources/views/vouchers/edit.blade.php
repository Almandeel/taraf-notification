@extends('accounting::layouts.master', [
    'datatable' => true, 
    'title' => $title,
    'datatable' => true,
    'modals' => ['attachment'],
    'summernote' => true,
    'crumbs' => [
        [route('vouchers.index'), __('accounting::global.vouchers')],
        [route('vouchers.show', $voucher), $voucher->getType() . ': ' . $voucher->id],
        ['#', $title],
    ]
])
@push('content')
    <section class="content">
        @if (request()->has('check'))
            <form class="form" action="{{ route('vouchers.update', $voucher) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="check" value="true">
                <div class="row">
                    <div class="col">
                        @component('components.widget')
                            @slot('title')
                                <i class="fas fa-list"></i>
                                <span>@lang('accounting::global.details')</span>
                            @endslot
                            @slot('body')
                                <div class="form-group row">
                                    @if (!$voucher->benefitIsModel() && is_null($voucher->bill()) && is_null($voucher->advance))
                                    <div class="col">
                                        <label>@lang('accounting::global.benefit')</label>
                                        <input class="form-control" autocomplete="off" type="text" name="voucherable_type" value="{{ $voucher->voucherable_type }}" placeholder="@lang('accounting::global.benefit')" />
                                    </div>
                                    @endif
                                    {{--  <div class="col">
                                        <label>@lang('accounting::global.number')</label>
                                        <input class="form-control" autocomplete="off" type="number" name="number" value="{{ $voucher->number ? $voucher->number : $voucher->id }}" placeholder="@lang('accounting::global.number')" />
                                    </div>  --}}
                                    @if (!$voucher->benefitIsModel() && is_null($voucher->bill()) && is_null($voucher->advance))
                                        <div class="col">
                                            <label>@lang('accounting::global.type')</label>
                                            <select class="form-control type" name="type" id="type" required>
                                                @foreach (\Modules\Accounting\Models\Voucher::TYPES as $type)
                                                <option value="{{ $type }}" {{ $type == $voucher->type ? 'selected' : '' }}>@lang('accounting::vouchers.types.' . $type)</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <label>@lang('accounting::global.amount')</label>
                                        <div class="input-group">
                                            <input type="number" id="amount" name="amount" class="form-control" value="{{ $voucher->amount }}" required>
                                            <select name="currency" id="currency" class="form-control" required>
                                                <option value="ريال" {{ $voucher->currency == 'ريال' ? 'selected' : ''}}>ريال</option>
                                                <option value="دولار" {{ $voucher->currency == 'دولار' ? 'selected' : ''}}>دولار</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label>@lang('accounting::global.date')</label>
                                        <input class="form-control" autocomplete="off" type="date" name="voucher_date" value="{{ $voucher->voucher_date ? $voucher->voucher_date : date('Y-m-d') }}" placeholder="@lang('accounting::global.date')" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>@lang('accounting::global.details')</label>
                                    <textarea class="form-control" name="details" placeholder="@lang('accounting::global.details')">{{ $voucher->details }}</textarea>
                                </div>
                            @endslot
                            @slot('footer')
                                <button type="submit" class="btn btn-primary">@lang('accounting::global.submit')</button>
                            @endslot
                        @endcomponent
                    </div>
                    <div class="col">
                        @component('components.widget')
                            @slot('title')
                                <i class="fas fa-list"></i>
                                <span>@lang('accounting::global.entry')</span>
                            @endslot
                            @slot('body')
                                <div class="form-group row">
                                    <div class="col">
                                        <label>@lang('accounting::global.safe')</label>
                                        <select name="safe_id" class="form-control safes" required></select>
                                    </div>
                                    <div class="col">
                                        <label>@lang('accounting::global.account')</label>
                                        <select name="account_id" class="form-control accounts" required></select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <label>@lang('accounting::global.amount')</label>
                                        <div class="input-group">
                                            <input type="number" id="amount" name="entry_amount" class="form-control" value="{{ $voucher->amount }}" required>
                                            <label class="form-control">ريال</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label>@lang('accounting::global.date')</label>
                                        <input class="form-control" autocomplete="off" type="date" name="entry_date" value="{{ $voucher->voucher_date ? $voucher->voucher_date : date('Y-m-d') }}" placeholder="@lang('accounting::global.date')" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>@lang('accounting::entries.details')</label>
                                    <textarea class="form-control" name="entry_details" placeholder="@lang('accounting::entries.details')">{{ $voucher->details }}</textarea>
                                </div>
                            @endslot
                            @slot('footer')
                                <button type="submit" class="btn btn-primary">@lang('accounting::global.submit')</button>
                            @endslot
                        @endcomponent
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col">
                    @component('components.widget')
                        @slot('noTitle', true)
                        @slot('sticky', true)
                        @slot('title')
                            <i class="fas fa-paperclip"></i>
                            <span>@lang('accounting::global.attachments')</span>
                        @endslot
                        @slot('body')
                            @component('accounting::components.attachments-viewer')
                            @slot('attachments', $voucher->attachments)
                            {{--  @slot('canAdd', true)  --}}
                            @slot('attachableType', 'Modules\Accounting\Models\Voucher')
                            @slot('attachableId', $voucher->id)
                            @endcomponent
                        @endslot
                    @endcomponent
                </div>
            </div>
        @endif
        @if (!request()->has('check'))
            <div class="row">
                <div class="col">
                    <form class="form" action="{{ route('vouchers.update', $voucher) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @component('components.widget')
                            @slot('title')
                                <i class="fas fa-list"></i>
                                <span>@lang('accounting::global.details')</span>
                            @endslot
                            @slot('body')
                                <div class="form-group row">
                                    @if (!$voucher->benefitIsModel())
                                    <div class="col">
                                        <label>@lang('accounting::global.benefit')</label>
                                        <input class="form-control" autocomplete="off" type="text" name="voucherable_type" value="{{ $voucher->voucherable_type }}" placeholder="@lang('accounting::global.benefit')" required />
                                    </div>
                                    @endif
                                    {{--  <div class="col">
                                        <label>@lang('accounting::global.number')</label>
                                        <input class="form-control" autocomplete="off" type="number" name="number" value="{{ $voucher->number ? $voucher->number : $voucher->id }}" placeholder="@lang('accounting::global.number')" />
                                    </div>  --}}
                                    <div class="col">
                                        <label>@lang('accounting::global.type')</label>
                                        <select class="form-control type" name="type" id="type" required>
                                            @foreach (\Modules\Accounting\Models\Voucher::TYPES as $type)
                                            <option value="{{ $type }}" {{ $type == $voucher->type ? 'selected' : '' }}>@lang('accounting::vouchers.types.' . $type)</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <label>@lang('accounting::global.amount')</label>
                                        <div class="input-group">
                                            <input type="number" id="amount" name="amount" class="form-control" value="{{ $voucher->amount }}" required>
                                            <select name="currency" id="currency" class="form-control" required>
                                                <option value="ريال" {{ $voucher->currency == 'ريال' ? 'selected' : ''}}>ريال</option>
                                                <option value="دولار" {{ $voucher->currency == 'دولار' ? 'selected' : ''}}>دولار</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label>@lang('accounting::global.date')</label>
                                        <input class="form-control" autocomplete="off" type="date" name="voucher_date" value="{{ $voucher->voucher_date }}" placeholder="@lang('accounting::global.date')" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>@lang('accounting::global.details')</label>
                                    <textarea class="form-control" name="details" placeholder="@lang('accounting::global.details')">{{ $voucher->details }}</textarea>
                                </div>
                            @endslot
                            @slot('footer')
                                <button type="submit" class="btn btn-primary">@lang('accounting::global.submit')</button>
                            @endslot
                        @endcomponent
                    </form>
                </div>
                <div class="col">
                    @component('components.widget')
                        @slot('noTitle', true)
                        @slot('sticky', true)
                        @slot('title')
                            <i class="fas fa-paperclip"></i>
                            <span>@lang('accounting::global.attachments')</span>
                        @endslot
                        @slot('body')
                            @component('accounting::components.attachments-viewer')
                            @slot('attachments', $voucher->attachments)
                            {{--  @slot('canAdd', true)  --}}
                            @slot('attachableType', 'Modules\Accounting\Models\Voucher')
                            @slot('attachableId', $voucher->id)
                            @endcomponent
                        @endslot
                    @endcomponent
                </div>
            </div>
        @endif
    </section>
@endpush