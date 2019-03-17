<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Repositories\ReportsRepository;
use Auth;


class DistributionController extends AppBaseController
{
    public function __construct()
    {
        $this->repo = new ReportsRepository();
    }

    public function index(Request $request){
        $data['queue']=  Auth::User()->queue()->Pluck("queue","queue")->ToArray();
        $data['queue_sel']= Auth::User()->queue()->Pluck("queue")->ToArray();
        $data['extension'] = $this->repo->extensions(implode(',',Auth::User()->Extension()->Pluck("extension_no")->ToArray()));

        $data['extension_sel']=  Auth::User()->Extension()->Pluck("extension_no")->ToArray();
        return view('cms.reports.distributionform',$data);
    }

    public function distribution(Request $request){
        $req = $request->all();
        $this->data = $this->repo->distribution($req);
        return view('cms.reports.distribution',$this->data);

    }

    public function distributionSubData(Request $request){
       $this->data = $this->repo->distributionSubData($request->all());
        return view('cms.reports.distributionsubdata',$this->data);
    }




}
