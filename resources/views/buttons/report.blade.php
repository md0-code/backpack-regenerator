<script>
	$('#{{ $name }}Modal').appendTo("body");
</script>

<style>
	@media (min-width: 768px) {
		.modal-xl {
			max-width: 1600px;
		}
	}
</style>

<div class="modal fade w-100" id="{{ $name }}Modal" tabindex="-1" role="dialog" aria-labelledby="{{ $name }}ModalTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="{{ $name }}ModalTitle">{{ $title }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				@if ($report)
				{!! $report !!}
				@else
				Nothing to display.
				@endif
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">{{ __('Close') }}</button>
			</div>
		</div>
	</div>
</div>

<a class="btn btn-sm btn-link" data-toggle="modal" data-target="#{{ $name }}Modal" href="" title="{{__('Generate the report')}}"><i class="la la-file-code"></i> HTML</a>