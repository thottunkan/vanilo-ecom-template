@extends('admin.layout.admin-layout')

@section('content')

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->

            @include('admin.includes.page-header', [
                'button_text' => null,
                'url' => null,
                'title' => 'Choose Font',
                'links' => ['Design', 'Font', 'Create font'],
            ])
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Choose Font</h5>
                        </div>
                        <div class="card-body">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- content --}}
                            <form action="{{ route('admin.font.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <p>Header Fonts</p>
                                    <select name="header_font" id="header_fonts" class="form-control">
                                        <option value="">Select Header Font</option>
                                    </select>
                                    <div id="HeaderFontPreview"
                                        style="margin-top:15px;padding:15px;border:1px solid #ddd;font-size:22px;font-weight:500;">
                                        The quick brown fox jumps over the lazy dog
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <p>Body Fonts</p>
                                    <select name="body_font" id="body_fonts" class="form-control">
                                        <option value="">Select Body Font</option>
                                    </select>

                                    <div id="BodyFontPreview"
                                        style="margin-top:15px;padding:15px;border:1px solid #ddd;font-size:22px;font-weight:500;">
                                        The quick brown fox jumps over the lazy dog
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">submit</button>
                            </form>

                        </div>
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>

    <script>
        $(document).ready(function() {

            const apiKey = "AIzaSyBsgyHRRY4BSlleKtCytzak260dSmyuEQ8";
            const apiUrl = "https://www.googleapis.com/webfonts/v1/webfonts?key=" + apiKey;

            const selectedHeaderFont = "{{ $settings['header_font'] ?? '' }}";
            const selectedBodyFont = "{{ $settings['body_font'] ?? '' }}";

            function loadFont(fontUrl) {
                if (!fontUrl) return;

                if (!$(`link[href="${fontUrl}"]`).length) {
                    $("<link>", {
                        rel: "stylesheet",
                        href: fontUrl
                    }).appendTo("head");
                }
            }

            function populateFonts(selectId, selectedFont) {
                $.each(window.googleFonts, function(index, font) {

                    const fontUrl =
                        "https://fonts.googleapis.com/css?family=" +
                        font.family.replace(/ /g, "+");

                    const isSelected = fontUrl === selectedFont ? "selected" : "";

                    $(selectId).append(`
                <option value="${fontUrl}"
                    data-family="${font.family}"
                    style="font-family:'${font.family}'; font-size:15pt;"
                    ${isSelected}>
                    ${font.family}
                </option>
            `);
                });

                if (selectedFont) {
                    loadFont(selectedFont);
                    $(selectId).trigger("change");
                }
            }

            $.ajax({
                url: apiUrl,
                type: "GET",
                dataType: "json",
                success: function(response) {

                    window.googleFonts = response.items;

                    populateFonts("#header_fonts", selectedHeaderFont);
                    populateFonts("#body_fonts", selectedBodyFont);
                },
                error: function() {
                    console.error("Failed to load Google Fonts");
                }
            });

            // Header preview
            $("#header_fonts").on("change", function() {
                const fontUrl = $(this).val();
                const fontFamily = $(this).find(":selected").data("family");

                loadFont(fontUrl);
                $("#HeaderFontPreview").css("font-family", fontFamily);
            });

            // Body preview
            $("#body_fonts").on("change", function() {
                const fontUrl = $(this).val();
                const fontFamily = $(this).find(":selected").data("family");

                loadFont(fontUrl);
                $("#BodyFontPreview").css("font-family", fontFamily);
            });

        });
    </script>

@endsection
