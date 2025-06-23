@extends('layout')

@section('title')
    {{ get_label('embed_code', 'Embed Code') }}
@endsection

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb and Actions -->
    <div class="d-flex justify-content-between mb-2 mt-4">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{ url('home') }}">{{ get_label('home', 'Home') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span>{{ get_label('leads_management', 'Leads Management') }}</span>
                    </li>
                    <li class="breadcrumb-item">
                        {{ get_label('lead_forms', 'Lead Forms') }}
                    </li>
                    <li class="breadcrumb-item active">
                        {{ get_label('embed_code', 'Embed code') }}
                    </li>
                </ol>
            </nav>

        <div>
            <!-- You can add action buttons here later if needed -->
        </div>
        <div>
            <button class="btn btn-sm btn-primary d-flex align-items-center gap-1" onclick="copyEmbedCode()" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="{{ get_label('copy_embed_code', 'Copy Embed Code') }}">
                <i class="bx bx-copy fs-5"></i>
                <span class="d-none d-md-inline">{{ get_label('copy', 'Copy') }}</span>
            </button>
        </div>
    </div>

    <!-- Card -->
    <div class="card border-0 shadow rounded-4">
        <div class="card-body p-4">
            <!-- Header -->
            <h4 class="fw-semibold mb-4">{{ get_label('embed_your_form', 'Embed Your Form') }}</h4>

            <!-- Embed Code -->
            <div class="mb-4">
                <label for="embedCode" class="form-label fw-semibold">
                    {{ get_label('copy_embed_code', 'Copy the Embed Code') }}
                </label>
                <textarea class="form-control bg-light border rounded-3" id="embedCode" rows="5" readonly>{!! $leadForm->embed_code !!}</textarea>
            </div>

            <hr class="my-4">

            <!-- Preview -->
            <div>
                <label class="form-label fw-semibold mb-2">
                    {{ get_label('preview', 'Preview') }}
                </label>
                <div class="border rounded-3 bg-white overflow-hidden" style="height: 60vh;">
                    <div class="w-100 h-100">
                        {!! str_replace('<iframe', '<iframe style="width:100%; height:100%; border:0;"', $leadForm->embed_code) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/pages/lead-form.js') }}"></script>
@endsection
