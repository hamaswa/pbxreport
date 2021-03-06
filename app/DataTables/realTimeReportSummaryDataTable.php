<?php
namespace App\DataTables;
use App\Agentlogin;
use Yajra\Datatables\Services\DataTable;
use Auth;
class realTimeReportSummaryDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @return \Yajra\Datatables\Engines\BaseEngine
     */
    public function dataTable()
    {

        return $this->datatables
            ->eloquent($this->query())
            /*->addColumn('action', function($row) {
                return view('cms.reports.realtimereport_action',['row'=>$row])->render();
            })*/
            ->escapeColumns([]);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return mixed
     */
    public function query()
    {

        $userExtensions = Auth::User()->Extension()->Pluck("extension_no")->ToArray();
        $userExtensions[] = Auth::User()->did_no;
        $where = "interface='" . $this->interface . "'";
        $sql = "id,membername as agent,login_time,logout_time,timediff(logout_time,login_time) as session,interface";
        $where .= " and event = 'queuememberadded'
                    and logout_time is not Null";

        $query = Agentlogin::query()->selectRaw($sql)
            ->whereRaw($where);
        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->addAction(['width' => '80px'])
            ->parameters([
                'dom' => 'Bfrtip',
                'order' => [[0, 'desc']],
                'buttons' => [
                    'export',
                    'print',
                    'reset',
                    'reload',
                ],
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $arr = [
            'id' => ['visible' => false],
            'agent' => ['title'=>'Agent','name' => 'membername'],
            'interface'=>['title'=>'Extension'],
            'login_time' => ['title'=>'Login Time'],
            'logout_time',
            'session' => [
                'title'=>'Total Time',
                'orderable' => false,
                'searchable' => false,
            ],
        ];


        return $arr;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'users_' . time();
    }
}