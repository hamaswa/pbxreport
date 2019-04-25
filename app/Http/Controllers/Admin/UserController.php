<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SubUserDataTable;
use App\Http\Requests\CreateSubUsersRequest;
use App\Http\Requests\UpdateSubUsersRequest;
use App\Models\Extension;
use App\Repositories\SubUsersRepository;
use App\Http\Controllers\AppBaseController;
use App\Models\User;

use Flash;
use DB;
use Response;
use Auth;
use Hash;


class UserController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;
    private $temp_ext;

    public function __construct(SubUsersRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the Package.
     *
     * @param PackageDataTable $packageDataTable
     * @return Response
    */
    public function index(SubUserDataTable $subUserDataTable)
    {
        return $subUserDataTable->render('admin.users.index');
    }
    /*/
    public function index(){
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        //$data['selected'] = array();  // Auth::User()->Extension()->Pluck("extension_no")->ToArray();
        $data['data'] = $this->getExtension();
		return view('admin.users.create',array("data"=>$data));
    }

    public function getExtension($selected=array()){
        $where = "extension_no not in (\"". (count($selected)>0? implode('", "',$selected):0) ."\")";

        $ext = DB::connection('mysql')->table('extensions')->select(DB::raw('extension_no'))
                ->whereRaw($where)
                ->get()
                ->toArray();
        $ext = json_decode(json_encode($ext), true);

        $this->temp_ext = array();
        foreach ($ext as $item) {
            $this->temp_ext[]=$item['extension_no'];
        }
        $ext = implode(',',$this->temp_ext);

        $this->temp_ext = array();
        $where = "user not in ($ext)";
        //if($ext!=0)
        //$data = DB::connection('mysql4')->table('devices')->select(DB::raw('id,description'))->whereRaw($where)->get()->toArray();
        //else
        $data = DB::connection('mysql4')->table('devices')->select(DB::raw('id,description'))->get()->toArray();

        $data = json_decode(json_encode($data), true);

        foreach ($data as $item) {
            $this->temp_ext[$item['id']]=$item['description'];
        }
        return $this->temp_ext;
    }


    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function store(CreateSubUsersRequest $request)
    {

        $input = $request->all();
		$input['password'] = Hash::make($input['password']);
		$extension = new Extension();

		$user = new User();
        $user_id = $user->insertGetId([
            'name'=>$input['name'],'created_at'=>date('Y-m-d h:i:s'),'email'=>$input['email'],'mobile'=>$input['mobile'],'password'=>$input['password'],'did_no'=>$input['did_no'],'status'=>$input['status']]);

        foreach ($input['extension'] as $ext) {
            $extension->create(['user_id' => $user_id, 'extension_no' => $ext]);
        }
        Flash::success('User saved successfully.');

        return redirect(route('nusers.index'));
    }

    /**
     * Display the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('nusers.index'));
        }

        return view('admin.users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $user = $this->userRepository->findWithoutFail($id);
        $data['selected'] = $user->Extension()->Pluck("extension_no")->ToArray();
        $data['data'] = $this->getExtension($data['selected']);
        return view('admin.users.edit',['data'=>$data])->with('user', $user);
    }

    /**
     * Update the specified User in storage.
     *
     * @param  int              $id
     * @param UpdateSubUsersRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSubUsersRequest $request)
    {

        $user = $this->userRepository->findWithoutFail($id);
        $input = $request->all();

        if (empty($user)) {
            Flash::error('User not found');
            return redirect(route('nusers.index'));
        }

        $user->name = $input['name'];
        $user->did_no = $input['did_no'];
        $user ->mobile = $input['mobile'];
        $user->status = $input['status'];
        $user->Extension()->delete();
        foreach ($input['extension'] as $ext) {
            $user->Extension()->create(['extension_no'=> $ext]);
        }
        $user->update();
        return redirect(route('nusers.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');
            return redirect(route('nusers.index'));
        }
        $user->delete();

        Flash::success('User deleted successfully.');

        return redirect(route('nusers.index'));
    }
}
