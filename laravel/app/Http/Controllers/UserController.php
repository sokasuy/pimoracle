<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Registered;
use App\Models\User;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //
    public function index()
    {
        // $hasUpdateUsers = Permission::where('role', Auth::user()->role)
        //     ->where('view', 'users')
        //     ->where('update', true)
        //     ->exists();
        // $hasDeleteUsers = Permission::where('role', Auth::user()->role)
        //     ->where('view', 'users')
        //     ->where('delete', true)
        //     ->exists();
        $hasCreateNewUsers = Permission::checkPermission(Auth::user()->role,'authentication','users','users','create');
        $hasUpdateUsers = Permission::checkPermission(Auth::user()->role,'authentication','users','users','update');
        $hasDeleteUsers = Permission::checkPermission(Auth::user()->role,'authentication','users','users','delete');
        return view('auth.users', compact('hasCreateNewUsers','hasUpdateUsers','hasDeleteUsers'));
        // return view('auth.users', ['hasUpdateUsers' => $hasUpdateUsers], ['hasDeleteUsers' => $hasDeleteUsers]);
        // return view('auth.users');
    }

    public function loginOracle(Request $request)
    {
        // DB::select("exec UPDATE_SKKI_REV(:year, :unit)", array ('year' => 1997, 'unit' => 'yourunit'))
// dd($request->get('password'));
        $login = DB::connection('oracle')
                ->table('dual')
                ->selectRaw("fnd_web_sec.validate_login(?, ?) as hasil", array($request->get('email'), $request->get('password')))
                ->value('hasil');
        dd($login);
        // return view('home');
        // return route('dashboard.home');
        // return view('auth.adduser');
    }

    public function getUsersList(Request $request)
    {
        $data = User::getDataListUsers();
        // dd($data);
        return response()->json(
            array(
                'status' => 'ok',
                'data' => $data
            ),
            200
        );
    }

    public function addUser()
    {
        $datarole = Role::select('role_name')->get();
        return view('auth.adduser', compact('datarole'));
        // return view('auth.adduser');
    }

    public function actionRegister(Request $request)
    {
        $user = new User();
        $user->validator($request->all())->validate();

        event(new Registered($user = $user->create($request->all())));

        //TAMBAHAN BARU dari https://www.ayongoding.com/membuat-register-user-laravel/

        Session::flash('message', 'Penambahan user baru berhasil. User sudah aktif, silahkan login menggunakan email dan password.');
        return redirect(route('auth.users'));
    }

    public function changeUserPassword(Request $request)
    {
        //
        $id = $request->get('id');
        $data = User::find($id);

        return response()->json(
            array(
                'status' => 'ok',
                'msg' => view('auth.changeuserpwdform', compact('data'))->render() //untuk modal data dan view diambil dari sini
            ),
            200
        );
    }

    public function actionChangeUserPassword(Request $request)
    {
        try {
            $id = $request->get('id');

            // $user = new User();
            // $user->updateValidator($request->all())->validate();

            $user = User::find($id);
            $user->password = Hash::make($request->get('password'));
            $user->save();
            return response()->json(
                array(
                    'status' => 'ok',
                    'msg' => "<div class='fas fa-bell alert alert-success' style='margin-bottom:10px;'> User '" . $user->name . "' data updated</div>"
                ),
                200
            );
        } catch (\PDOException $e) {
            return response()->json(
                array(
                    'status' => 'error',
                    'msg' => $user->name . " gagal diupdate"
                ),
                200
            );
        }
    }
}
