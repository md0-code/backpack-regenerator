<?php

namespace MD0\BackpackReGenerator\Http\Controllers;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use MD0\ReGenerator\Report;
use MD0\BackpackReGenerator\Http\Requests\ReportsCreateRequest;
use MD0\BackpackReGenerator\Http\Requests\ReportsUpdateRequest;

/**
 * Class ReportsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ReportsCrudController extends CrudController
{
	use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
	use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
	use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
	use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
	use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
	use \Backpack\CRUD\app\Http\Controllers\Operations\CloneOperation;


	private $model;
	private $filterTag;

	function __construct()
	{
		parent::__construct();
		$this->model = \MD0\BackpackReGenerator\Models\Reports::class;
		$this->filterTag = config('md0.backpack-regenerator.filter_by_tag');
	}

	/**
	 * Configure the CrudPanel object. Apply settings to all operations.
	 * 
	 * @return void
	 */
	public function setup()
	{
		$singularEnt = __('report');
		$pluralEnt = __('Reports');
		CRUD::setModel($this->model);
		CRUD::setRoute(backpack_url(config('md0.backpack-regenerator.route_name')));
		CRUD::setEntityNameStrings($singularEnt, $pluralEnt);
		CRUD::enableResponsiveTable();
		CRUD::enableDetailsRow();

		if (config('md0.backpack-regenerator.allow_create') == false) CRUD::denyAccess('create');
		if (config('md0.backpack-regenerator.allow_update') == false) CRUD::denyAccess('update');
		if (config('md0.backpack-regenerator.allow_delete') == false) CRUD::denyAccess('delete');
		if (config('md0.backpack-regenerator.allow_show') == false) CRUD::denyAccess('show');
		if (config('md0.backpack-regenerator.allow_clone') == false) CRUD::denyAccess('clone');

		if ($this->filterTag) CRUD::addClause('tag', $this->filterTag);
	}

	/**
	 * Define what happens when the List operation is loaded.
	 * 
	 * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
	 * @return void
	 */
	protected function setupListOperation()
	{
		if (config('md0.backpack-regenerator.display_name_column')) {
			CRUD::addColumn([
				'name' => 'name',
				'label' => __('Name')
			]);
		}
		CRUD::addColumn([
			'name' => 'title',
			'label' => __('Title')
		]);
		if (config('md0.backpack-regenerator.display_tag_column')) {
			CRUD::addColumn([
				'name' => 'tag',
				'label' => __('Tag / Group')
			]);
		}
		CRUD::addColumn([
			'name' => 'generate',
			'label' => __('Report preview'),
			'type' => 'view',
			'view' => 'md0.backpack-regenerator::columns.report_preview',
		]);

		if (config('md0.backpack-regenerator.display_tag_column')) {
			CRUD::addFilter([
				'name'  => 'tag',
				'type'  => 'select2',
				'label' => __('Tag / Group')
			], function () {
				return $this->model::get('tag')->pluck('tag', 'tag')->toArray();
			}, function ($value) {
				CRUD::addClause('where', 'tag', $value);
			});
		}
		if (config('md0.backpack-regenerator.display_name_column')) {
			CRUD::addFilter([
				'name'  => 'db_name',
				'type'  => 'select2',
				'label' => __('Database')
			], function () {
				return $this->model::get('db_name')->pluck('db_name', 'db_name')->toArray();
			}, function ($value) {
				CRUD::addClause('where', 'db_name', $value);
			});
		}
	}

	/**
	 * Define what happens when the Create operation is loaded.
	 * 
	 * @see https://backpackforlaravel.com/docs/crud-operation-create
	 * @return void
	 */
	protected function setupCreateOperation()
	{
		CRUD::setValidation(ReportsCreateRequest::class);

		CRUD::addField(
			[
				'name' => 'name',
				'label' => __('Name'),
				'hint' => __('Unique name for this raport. Only alphanumeric characters, dashes or underscores.'),
				'tab' => __('Report'),
			]
		);
		CRUD::addField(
			[
				'name' => 'title',
				'label' => __('Title'),
				'hint' => __('A descriptive title.'),
				'tab' => __('Report'),
			]
		);
		CRUD::addField(
			[
				'name' => 'report_type',
				'label' => __('Report type'),
				'hint' => __('Defaults to vertical (classic), use horizontal for pivoted reports (columns on rows), or slices for individual records.'),
				'tab' => __('Report'),
				'type' => 'select_from_array',
				'options' => [
					'vertical' => 'vertical',
					'horizontal' => 'horizontal',
					'slices' => 'slices'
				],
				'allows_null' => false,
				'default'     => 'vertical'
			]
		);
		CRUD::addField(
			[
				'name' => 'tag',
				'label' => __('Tag / Group name'),
				'hint' => __('Tag for grouping reports into collections. Leave empty if not needed.'),
				'tab' => __('Report'),
			]
		);
		CRUD::addField(
			[
				'name' => 'db_name',
				'label' => __('Database name'),
				'hint' => __('Database name as defined in Laravel. Leave empty for default. Make sure to use the Laravel defined name and not the actual database name.'),
				'tab' => __('Report'),
			]
		);
		CRUD::addField(
			[
				'name' => 'sql_query',
				'label' => __('SQL Query'),
				'hint' => __('SQL query used to generate the report.'),
				'tab' => __('Report'),
				'attributes' => [
					'placeholder' => __('SQL Query'),
					'rows' => 5
				],
			]
		);
		CRUD::addField(
			[
				'name' => 'numeric',
				'label' => __('Numeric columns'),
				'hint' => __('Comma separated list of column numbers that contain numeric values.'),
				'tab' => __('Number formatting'),
				'attributes' => [
					'placeholder' => '1, 2, 3',
				],
				'fake' => true,
				'store_in' => 'formatting'
			]
		);
		CRUD::addField(
			[
				'name' => 'thousands',
				'label' => __('Thousands separator'),
				'hint' => __('Character used as thousands separator. Defaults to dot.'),
				'tab' => __('Number formatting'),
				'type' => 'select_from_array',
				'options' => [
					'.' => __('dot'),
					',' => __('comma')
				],
				'allows_null' => false,
				'default' => '.',
				'fake' => true,
				'store_in' => 'formatting'
			]
		);
		CRUD::addField(
			[
				'name' => 'decimals',
				'label' => __('Decimals separator'),
				'hint' => __('Character used as decimals separator. Defaults to comma.'),
				'tab' => __('Number formatting'),
				'type' => 'select_from_array',
				'options' => [
					',' => __('comma'),
					'.' => __('dot')
				],
				'allows_null' => false,
				'default' => ',',
				'fake' => true,
				'store_in' => 'formatting'
			]
		);
		CRUD::addField(
			[
				'name' => 'count',
				'label' => __('Count columns'),
				'hint' => __('Comma separated list of column numbers that will feature a count total.'),
				'tab' => __('Aggregate functions'),
				'attributes' => [
					'placeholder' => '1, 2, 5',
				],
				'fake' => true,
				'store_in' => 'aggregates'
			]
		);
		CRUD::addField(
			[
				'name' => 'sum',
				'label' => __('Sum columns'),
				'hint' => __('Comma separated list of column numbers that will feature a sum aggregate.'),
				'tab' => __('Aggregate functions'),
				'attributes' => [
					'placeholder' => '2, 4, 6',
				],
				'fake' => true,
				'store_in' => 'aggregates'
			]
		);
		CRUD::addField(
			[
				'name' => 'average',
				'label' => __('Average columns'),
				'hint' => __('Comma separated list of column numbers that will feature a average aggregate.'),
				'tab' => __('Aggregate functions'),
				'attributes' => [
					'placeholder' => '3, 7',
				],
				'fake' => true,
				'store_in' => 'aggregates'
			]
		);
		CRUD::addField(
			[
				'name' => 'page_size',
				'label' => __('Page size'),
				'hint' => __('As used by DomPdf, defaults to A4.'),
				'tab' => __('PDF'),
				'attributes' => [
					'placeholder' => 'A4',
				],
				'fake' => true,
				'store_in' => 'pdf'
			]
		);
		CRUD::addField(
			[
				'name' => 'page_orientation',
				'label' => __('Page orientation'),
				'hint' => __('Use landscape to accomodate reports with many columns.'),
				'tab' => __('PDF'),
				'type' => 'select_from_array',
				'options' => [
					'portrait' => __('portrait'),
					'landscape' => __('landscape')
				],
				'allows_null' => false,
				'default' => 'P',
				'fake' => true,
				'store_in' => 'pdf'
			]
		);
		CRUD::addField(
			[
				'name' => 'font_size',
				'label' => __('Font size'),
				'hint' => __('In points, defaults to 10.'),
				'tab' => __('PDF'),
				'attributes' => [
					'placeholder' => '10',
				],
				'fake' => true,
				'store_in' => 'pdf'
			]
		);
		CRUD::addField(
			[
				'name' => 'pdf_template',
				'label' => __('Template'),
				'hint' => __('Custom view to be used for wrapping the PDF report. Leave empty to use the default.'),
				'tab' => __('PDF'),
				'attributes' => [
					'placeholder' => 'pdf.report',
				],
				'fake' => true,
				'store_in' => 'pdf'
			]
		);
		CRUD::addField(
			[
				'name' => 'delimiter',
				'label' => __('Delimiter'),
				'hint' => __('CSV field delimiter. Usually comma or semicolon, defaults to comma.'),
				'tab' => __('CSV'),
				'attributes' => [
					'placeholder' => ',',
				],
				'fake' => true,
				'store_in' => 'csv'
			]
		);
		CRUD::addField(
			[
				'name' => 'quotes',
				'label' => __('Quotes'),
				'hint' => __('Type of quotes for enclosing fields. Defaults to none.'),
				'tab' => __('CSV'),
				'type' => 'select_from_array',
				'options' => [
					'\'' => __('single'),
					'"' => __('double'),
				],
				'allows_null' => true,
				'default'     => '',
				'fake' => true,
				'store_in' => 'csv'
			]
		);
		CRUD::addField(
			[
				'name' => 'type',
				'label' => __('Chart type'),
				'hint' => __('The type of chart that will be generated.'),
				'tab' => __('Chart'),
				'type' => 'select_from_array',
				'options' => [
					'line' => __('lines'),
					'bar' => __('bars'),
					'pie' => __('pie')
				],
				'allows_null' => false,
				'default' => 'line',
				'fake' => true,
				'store_in' => 'chart'
			]
		);
		CRUD::addField(
			[
				'name' => 'series',
				'label' => __('Series'),
				'hint' => __('Comma separated list of columns that hold the chart series.'),
				'tab' => __('Chart'),
				'attributes' => [
					'placeholder' => '1, 3',
				],
				'fake' => true,
				'store_in' => 'chart'
			]
		);
		CRUD::addField(
			[
				'name' => 'labels',
				'label' => __('Labels'),
				'hint' => __('Column number that holds the chart labels.'),
				'tab' => __('Chart'),
				'attributes' => [
					'placeholder' => '2',
				],
				'fake' => true,
				'store_in' => 'chart'
			]
		);
		CRUD::addField(
			[
				'name' => 'colors',
				'label' => __('Colors'),
				'hint' => __('Comma separated list of hex color codes to be used in chart.'),
				'tab' => __('Chart'),
				'attributes' => [
					'placeholder' => '#ff0000, #00ff00, #0000ff',
				],
				'fake' => true,
				'store_in' => 'chart'
			]
		);
		CRUD::addField(
			[
				'name' => 'chart_template',
				'label' => __('Template'),
				'hint' => __('Custom view to be used for the generated chart. Use if you want to tweak Chart.js\'s settings. Leave empty to use the default.'),
				'tab' => __('Chart'),
				'attributes' => [
					'placeholder' => 'chart.report',
				],
				'fake' => true,
				'store_in' => 'chart'
			]
		);
		CRUD::addField(
			[
				'name' => 'parameters',
				'label' => __('Parameters'),
				'hint' => __('Strings to be substituted in SQL queries.'),
				'tab' => __('Parameters'),
				'type' => 'repeatable',
				'fields' => [
					[
						'name'    => 'name',
						'type'    => 'text',
						'label'   => __('Name'),
						'wrapper' => ['class' => 'form-group col-md-4'],
					],
					[
						'name'    => 'value',
						'type'    => 'text',
						'label'   => __('Value'),
						'wrapper' => ['class' => 'form-group col-md-8'],
					],
				],
				'allows_null' => false,
				'new_item_label'  => 'Add parameter',
				'init_rows' => 1,
			]
		);
	}

	/**
	 * Define what happens when the Update operation is loaded.
	 * 
	 * @see https://backpackforlaravel.com/docs/crud-operation-update
	 * @return void
	 */
	protected function setupUpdateOperation()
	{
		$this->setupCreateOperation();
		CRUD::setValidation(ReportsUpdateRequest::class);
	}

	protected function setupShowOperation()
	{
		CRUD::set('show.setFromDb', false);

		if (config('md0.backpack-regenerator.display_name_column')) {
			CRUD::addColumn([
				'name' => 'name',
				'label' => __('Name')
			]);
		}
		CRUD::addColumn([
			'name' => 'title',
			'label' => __('Title')
		]);
		CRUD::addColumn([
			'name' => 'report_type',
			'label' => __('Report type')
		]);
		if (config('md0.backpack-regenerator.display_tag_column')) {
			CRUD::addColumn([
				'name' => 'tag',
				'label' => __('Tag / Group')
			]);
		}
		CRUD::addColumn([
			'name' => 'db_name',
			'label' => __('Database')
		]);
		CRUD::addColumn([
			'name' => 'sql_query',
			'label' => __('SQL Query')
		]);
		CRUD::addColumn([
			'name' => 'formatting',
			'label' => __('Number formatting'),
			'type' => 'view',
			'view' => 'md0.backpack-regenerator::columns.array_with_keys'
		]);
		CRUD::addColumn([
			'name' => 'aggregates',
			'label' => __('Aggregate functions'),
			'type' => 'view',
			'view' => 'md0.backpack-regenerator::columns.array_with_keys'
		]);
		CRUD::addColumn([
			'name' => 'pdf',
			'label' => __('PDF'),
			'type' => 'view',
			'view' => 'md0.backpack-regenerator::columns.array_with_keys'
		]);
		CRUD::addColumn([
			'name' => 'csv',
			'label' => __('CSV'),
			'type' => 'view',
			'view' => 'md0.backpack-regenerator::columns.array_with_keys'
		]);
		CRUD::addColumn([
			'name' => 'chart',
			'label' => __('Chart'),
			'type' => 'view',
			'view' => 'md0.backpack-regenerator::columns.array_with_keys'
		]);
		CRUD::addColumn([
			'name' => 'parameters',
			'label' => __('Parameters'),
			'type' => 'table',
			'columns' => [
				'name' => 'Name',
				'value' => 'Value',
			]
		]);
	}

	public function getPdfReport($id)
	{
		$entry = $this->crud->getEntry($id);
		$filename = date('Ymd') . '-' . strtolower(str_replace(' ', '_', $entry->title)) . '.pdf';
		$report = new Report;
		$pdfReport = $report->setReport($entry->name)->getPDF();
		return response()->make($pdfReport, 200, [
			'Content-Type' => 'application/pdf',
			'Content-Disposition' => 'inline; filename="' . $filename . '"',
		]);
	}

	public function getCsvReport($id)
	{
		$entry = $this->crud->getEntry($id);
		$filename = date('Ymd') . '-' . strtolower(str_replace(' ', '_', $entry->title)) . '.csv';
		$report = new Report;
		$csvReport = $report->setReport($entry->name)->getCSV($entry->name);
		return response()->make($csvReport, 200, [
			'Content-Type' => 'application/csv',
			'Content-Disposition' => 'attachment; filename="' . $filename . '"',
		]);
	}

	public function showDetailsRow($id)
	{
		$entry = $this->crud->getEntry($id);
		$report = new Report();

		echo $report->setReport($entry->name)->getChart() ? '<div class="d-flex justify-content-center"><div class="text-wrap w-50">' . $report->getChart() . '</div></div>' : __('Cannot display chart data.');
	}
}
