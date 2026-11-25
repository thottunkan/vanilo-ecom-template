
@include('admin.widgets.dashboard.buttons.export-to-pdf', [
    'route' =>  $exportToPdfRoute ,
])

@include('admin.widgets.dashboard.buttons.export-to-excel', [
    'route' =>  $exportToExcelRoute,
    'btntext' => 'Export Excel',
])

@include('admin.widgets.dashboard.buttons.upload', [
    'route' =>  $importExcelRoute,
    'btntext' => 'import Excel',
    'uploadLabel' => 'choose excel file',
])

@include('admin.widgets.dashboard.buttons.upload', [
    'route' =>  $importCSVRoute,
    'btntext' => 'import CSV',
    'uploadLabel' => 'Choose CSV file',
])

@include('admin.widgets.dashboard.buttons.download-sample-btn', [
    'route' =>  $downloadSampleRoute ,
    'btntext' => 'Download Template',
    'btnclass' => 'btn-warning',
])
