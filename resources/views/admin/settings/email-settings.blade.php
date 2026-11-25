@extends('admin.layout.admin-layout')

@section('content')
    <div class="pc-container">
        <div class="pc-content">

            @include('admin.includes.page-header', [
                'button_text' => 'View Emal telplates',
                'url' => route('admin.email-template.index'),
                'title' => 'Email Settings',
                'links' => ['Settings','Email Settings'],
            ])

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">

                        <div class="card-header">
                            <h5>SMTP Configuration</h5>
                        </div>

                        <form action="{{ route('admin.email-settings.store') }}" method="POST">
                            @csrf

                            <div class="card-body">

                                <div class="mb-3">
                                    <label class="form-label">Mail Mailer</label>
                                    <select name="mail_mailer" class="form-control">
                                        <option value="smtp" selected>SMTP</option>
                                        <option value="sendmail">Sendmail</option>
                                        <option value="log">Log</option>
                                        <option value="ses">Amazon SES</option>
                                        <option value="mailgun">Mailgun</option>
                                      
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">SMTP Host</label>
                                    <input type="text" name="mail_host" class="form-control"
                                        value="{{ $emailSetting->mail_host ?? '' }}" placeholder="smtp.gmail.com">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">SMTP Port</label>
                                    <input type="number" name="mail_port" class="form-control"
                                        value="{{ $emailSetting->mail_port ?? env('MAIL_PORT') }}" placeholder="587">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">SMTP Username</label>
                                    <input type="text" name="mail_username" class="form-control"
                                        value="{{ $emailSetting->mail_username ?? env('MAIL_USERNAME') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">SMTP Password</label>
                                    <input type="password" name="mail_password" class="form-control"
                                        value="{{ $emailSetting->mail_password ?? env('MAIL_PASSWORD') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Encryption</label>
                                    <select name="mail_encryption" class="form-control">
                                        <option value="">None</option>
                                        <option value="tls" {{ env('MAIL_ENCRYPTION') == 'tls' ? 'selected' : '' }}>TLS
                                        </option>
                                        <option value="ssl" {{ env('MAIL_ENCRYPTION') == 'ssl' ? 'selected' : '' }}>SSL
                                        </option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">From Email Address</label>
                                    <input type="email" name="mail_from_address" class="form-control"
                                        value="{{ $emailSetting->mail_from_address ?? env('MAIL_FROM_ADDRESS') }}"
                                        placeholder="noreply@example.com">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">From Name</label>
                                    <input type="text" name="mail_from_name" class="form-control"
                                        value="{{ $emailSetting->mail_from_name ?? env('MAIL_FROM_NAME') }}"
                                        placeholder="My Website">
                                </div>

                            </div>

                            <div class="card-footer text-start">
                                <button type="submit" class="btn btn-primary">
                                    Save Settings
                                </button>
                            </div>

                        </form>

                        <div class="card-body">

                            {{-- Success Message --}}
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            {{-- Error Message --}}
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form action="{{ route('admin.test-email.test-email') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="test_email" class="form-label">Test Email</label>

                                    <input type="email" name="test_email" id="test_email"
                                        class="form-control @error('test_email') is-invalid @enderror"
                                        value="{{ old('test_email') }}" required>

                                    @error('test_email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary btn-sm">
                                    Test
                                </button>
                            </form>
                        </div>


                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
