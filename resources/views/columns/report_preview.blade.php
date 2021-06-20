@php
use MD0\ReGenerator\Report;
$report = new Report;
$report->setReport($entry['name']);
$data['name'] = $entry['name'];
$data['title'] = $report->title;
$data['report'] = $report->getHtml();
echo view('md0.backpack-regenerator::buttons.report', $data)->render();
@endphp

<a class="btn btn-sm btn-link" target="_blank" href="{{backpack_url()}}/{{config('md0.backpack-regenerator.route_name')}}/{{$entry['id']}}/pdf" title="{{__('Export to PDF')}}"><i class="la la-file-pdf"></i> PDF</a>
<a class="btn btn-sm btn-link" href="{{backpack_url()}}/{{config('md0.backpack-regenerator.route_name')}}/{{$entry['id']}}/csv" title="{{__('Export to CSV')}}"><i class="la la-file-csv"></i> CSV</a>