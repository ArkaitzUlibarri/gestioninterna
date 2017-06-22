@extends('layouts.app')

@section('content')
<div class="container" style="margin-bottom: 100px;">

    
    <div class="row form-inline">
        @include('validation.filter')
    </div>

    <hr>

    <div class="row">

        <div v-if="reports.length==0">
            <h4>No reports available...</h4>
        </div>
        <div v-else class="col-xs-12 col-md-4" v-for="report in filtered_reports">
            <div class="postcard">

                @if (Auth::user()->isAdmin() || Auth::user()->isPM())
                    <button class="btn btn-sm pull-right"
                            v-bind:class="filter.validated == 'true' ? 'btn-danger' : 'btn-success'"
                            v-on:click.prevent="validate(report.user_id, report.date_year, report.date_week)">

                        <span class="glyphicon"
                              v-bind:class="filter.validated == 'true' ? 'glyphicon-remove' : 'glyphicon-ok'"
                              aria-hidden="true">
                        </span> @{{ filter.validated == 'true' ? 'Invalidate' : 'Validate' }}
                    </button>
                @endif
                
                <div class="header">
                    <strong>@{{ report.user_name }}</strong> - W@{{ report.date_week }}
                </div>

                <hr>
                <div v-for="item in report.items">
                    <p>@{{ item.time_slot }} Hours | @{{ item.name }}</p>
                </div>
                <hr>

                <p><strong>Total Hours:</strong> @{{ report.total }}</p>
            </div>
        </div>
    </div>

</div>
@endsection


@push('script-bottom')
<style>
.postcard {
    border: 1px solid #ccc;
    min-height: 230px;
    padding: 1em;
    margin-bottom: 1em;
}

.postcard .header {
    margin-top: 1.5em;
}

.postcard hr {
    margin-bottom: 10px;
    margin-top: 10px;
}
</style>

<script src="{{ asset('js/weeklyValidation.js') }}"></script>

@endpush