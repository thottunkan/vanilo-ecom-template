<div class="page-header">
    <div class="page-block card mb-0">
        <div class="card-body">
            <div class="row align-items-center">
                
                <div class="col-md-12">
                    <div class="page-header-title border-bottom pb-2 mb-2 d-flex justify-content-between">
                        <h4 class="mb-0">{{ $title ?? 'Default title' }}</h4>
                        <div>
                            @if ($button_text)
                                <a href="{{ $url ?? '#' }}">
                                    <button class="btn btn-sm btn-primary">{{ $button_text ?? ''}}</button>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ url('admin/dashboard') }}">
                                <i class="ph ph-house"></i>
                            </a>
                        </li>
                        @foreach ($links as $key => $link)
                            @if ($key == count($links) - 1)
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ $link }}
                                </li>
                            @else
                                <li class="breadcrumb-item">
                                    <a href="javascript:void(0)">
                                        {{ $link }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>