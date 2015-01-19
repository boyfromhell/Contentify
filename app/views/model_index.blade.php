@if ($buttons or $showSearchBox)
    <div class="form-helpers top">
        {{ $buttons }}

        @if ($showSearchBox)
            <div class="model-search-box">
                {{ Form::open(['url' => URL::current().'/search']) }}
                        {{ Form::text('search', $searchString, ['class' => 'search-string']) }}
                        {{ Form::submit(trans('app.search'), ['class' => 'btn btn-default']) }}
                {{ Form::close() }}
            </div>
        @endif
    </div>
@endif

@if ($infoText)
    <span class="info-text">{{ $infoText }}</span>
@endif
{{ $contentTable }}

<div class="form-helpers bottom">
    {{ $sortSwitcher }}
    {{ $recycleBin }}
    {{ $paginator }}
</div>