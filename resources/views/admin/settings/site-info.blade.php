@extends('admin.layout.admin-layout')

@section('content')
<div class="pc-container">
    <div class="pc-content">

        @include('admin.includes.page-header', [
            'button_text' => null,
            'url' => null,
            'title' => 'Site Informations',
            'links' => ['Settings','Site Settings'],
        ])

        <div class="row">
            <div class="col-sm-12">

                <div class="card">
                    <div class="card-header">
                        <h5>Site Informations</h5>
                    </div>

                    <form action="{{ route('admin.site-settings.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">Site Name</label>
                                <input
                                    type="text"
                                    name="company_name"
                                    class="form-control"
                                    value="{{ old('company_name', $siteinfo->company_name ?? '') }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Company Address</label>
                                <input
                                    type="text"
                                    name="company_address"
                                    class="form-control"
                                    value="{{ old('company_address', $siteinfo->company_address ?? '') }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Company Email</label>
                                <input
                                    type="email"
                                    name="company_email"
                                    class="form-control"
                                    value="{{ old('company_email', $siteinfo->company_email ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Company Phone Number</label>
                                <input
                                    type="text"
                                    name="company_phone_number"
                                    class="form-control"
                                    value="{{ old('company_phone_number', $siteinfo->company_phone_number ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Copyright</label>
                                <input
                                    type="text"
                                    name="copyright"
                                    class="form-control"
                                    value="{{ old('copyright', $siteinfo->copyright ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Footer Text</label>
                                <input
                                    type="text"
                                    name="footer_text"
                                    class="form-control"
                                    value="{{ old('footer_text', $siteinfo->footer_text ?? '') }}">
                            </div>

                            {{-- Logo Preview --}}
                            <div class="mb-3">
                                <label class="form-label d-block">Current Logo</label>

                                @if(isset($siteinfo) && !empty($siteinfo->logo_path))
                                    <img
                                        src="{{ asset('storage/' . $siteinfo->logo_path) }}"
                                        alt="Logo"
                                        width="80"
                                        height="80"
                                        class="img-thumbnail">
                                @else
                                    <p class="text-muted mb-0">No logo uploaded.</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Upload Logo</label>
                                <input
                                    type="file"
                                    name="site_logo"
                                    class="form-control">
                            </div>

                            {{-- Favicon Preview --}}
                            <div class="mb-3">
                                <label class="form-label d-block">Current Favicon</label>

                                @if(isset($siteinfo) && !empty($siteinfo->favicon_path))
                                    <img
                                        src="{{ asset('storage/' . $siteinfo->favicon_path) }}"
                                        alt="Favicon"
                                        width="50"
                                        height="50"
                                        class="img-thumbnail">
                                @else
                                    <p class="text-muted mb-0">No favicon uploaded.</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Upload Favicon</label>
                                <input
                                    type="file"
                                    name="favicon"
                                    class="form-control">
                            </div>

                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                Save
                            </button>
                            <a class="btn btn-secondary" href="{{ route('admin.site-settings.create') }}">Cancel</a>
                        </div>

                    </form>

                </div>

            </div>
        </div>

    </div>
</div>
@endsection