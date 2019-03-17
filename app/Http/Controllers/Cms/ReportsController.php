<?php

namespace App\Http\Controllers\Cms;

use App\Repositories\ReportsRepository;
use App\Http\Controllers\AppBaseController;
use Flash;
use Auth;
use App\Models\Pbx_cdr;
use App\Models\Realtime;
use Illuminate\Http\Request;

class ReportsController extends AppBaseController
{
    /** @var  SubUsersRepository */
    private $reportRepository;
    private $temp_queue;

    public function __construct(ReportsRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }
	
	public function ioUserReport(Request $request)
	{
		$inputs =  $request->all();
		$ioReport = $this->reportRepository->ioUserReport($inputs);
		return view('cms.reports.iouserreport', compact('ioReport'));
	}
	
	public function ioCallReport(Request $request)
	{
		$inputs =  $request->all();
		$ioReport = $this->reportRepository->ioCallReport($inputs);
		return view('cms.reports.iocallreport', compact('ioReport'));
	}


	public function iUserReport(Request $request)
	{
		$inputs =  $request->all();
		$inputs['direction'] = 2;
		$iReportDetail = $this->reportRepository->iCallReport($inputs );
		$iReport = $this->reportRepository->iUserReport($inputs);
		return view('cms.reports.iuserreport', array('iReport' => $iReport, 'iReportDetail' => $iReportDetail));
	}
	
	public function oUserReport(Request $request)
	{
		$inputs =  $request->all();
		$inputs['direction'] = 1;
		$oReportDetail = $this->reportRepository->oCallReport($inputs );
		$oReport = $this->reportRepository->oUserReport($inputs);
		return view('cms.reports.ouserreport', array('oReport' => $oReport, 'oReportDetail' => $oReportDetail));
	}
	
	public function showRealTimeReport(Request $request)
	{
		return view('cms.reports.realtime');
	}
	
	public function realTimeReport(Request $request)
	{
		$userExtensions = Auth::User()->Extension()->Pluck("extension_no")->ToArray();
		$userExtensions[] = Auth::User()->did_no;
		print_r($userExtensions);
		$info_Realtime = Realtime::WhereIn('extension', $userExtensions)->Get();
		return response()->json($info_Realtime);
	}
	
	public function showQueueStatsReport(Request $request)
	{
		return view('cms.reports.queuestats');
	}
	
	public function queueStatsReport(Request $request)
	{
	    $ReportDetail = $this->reportRepository->QueueReport($request->all());
		return response()->json($ReportDetail);
	}

    public function showQueueReport(Request $request)
    {
        $hour=array();
        $hour =['00:00-00:30'=>'00:00-00:30','00:30-01:00'=>'00:30-01:00','01:00-01:30'=>'01:00-01:30','01:30-02:00'=>'01:30-02:00',
                '02:00-02:30'=>'02:00-02:30','02:30-03:00'=>'02:30-03:00','03:00-03:30'=>'03:00-03:30','03:30-04:00'=>'03:30-04:00',
                '04:00-04:30'=>'04:00-04:30','04:30-05:00'=>'04:30-05:00','05:00-05:30'=>'05:00-03:30','05:30-06:00'=>'05:30-06:00',
                '06:00-06:30'=>'06:00-06:30','06:30-07:00'=>'06:30-07:00','07:00-07:30'=>'07:00-07:30','07:30-08:00'=>'07:30-08:00',
                '08:00-08:30'=>'08:00-08:30','08:30-09:00'=>'08:30-09:00','09:00-09:30'=>'09:00-09:30','09:30-10:00'=>'09:30-10:00',
                '10:00-10:30'=>'10:00-10:30','10:30-11:00'=>'10:30-11:00','11:00-11:30'=>'11:00-11:30','11:30-12:00'=>'11:30-12:00',
                '12:00-12:30'=>'12:00-12:30','12:30-13:00'=>'12:30-13:00','13:00-13:30'=>'13:00-13:30','13:30-14:00'=>'13:30-14:00',
                '14:00-14:30'=>'14:00-14:30','14:30-15:00'=>'14:30-15:00','15:00-15:30'=>'15:00-15:30','15:30-16:00'=>'15:30-16:00',
                '16:00-16:30'=>'16:00-16:30','16:30-17:00'=>'16:30-17:00','17:00-17:30'=>'17:00-17:30','17:30-18:00'=>'17:30-18:00',
                '18:00-18:30'=>'18:00-18:30','18:30-19:00'=>'18:30-19:00','19:00-19:30'=>'19:00-19:30','19:30-20:00'=>'19:30-20:00',
                '20:00-20:30'=>'20:00-20:30','20:30-21:00'=>'20:30-21:00','21:00-21:30'=>'21:00-21:30','21:30-22:00'=>'21:30-22:00',
                '22:00-22:30'=>'22:00-22:30','22:30-23:00'=>'22:30-23:00','23:00-23:30'=>'23:00-23:30','23:30-24:00'=>'23:30-00:00'];

        $queue = array('options'=>$this->getQueue(),'selected'=>$request['queue']);
        return view('cms.reports.queuereport',array('hour'=>$hour,'year'=>array("2018"=>'2018',"2019"=>'2019',),'queue' => $queue));
    }


    public function queueReport(Request $request)
    {
        //return $this->reportRepository->QueueReportByStatus($request->all());
        $ReportDetail = $this->reportRepository->QueueReportByStatus($request->all());
        return response()->json($ReportDetail);
    }

    public function getQueue(){
        $queue = Auth::User()->queue()->select("queue","queue_description")->get()->toArray();
        foreach ($queue as $item) {
            $this->temp_queue[$item['queue']]=$item['queue_description'];
        }
        return $this->temp_queue;
    }


}
