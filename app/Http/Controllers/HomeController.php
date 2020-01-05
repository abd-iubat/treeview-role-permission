<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $user = new User();
            return $user->DataTableLoader($request);
        }
        else {
            return view('home');
        }
    }


    /**
     * Edit user.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Request $request, $id)
    {
        $permissions = Permission::orderBy('order')->get()->toArray();
        $roles = Role::orderBy('created_at')->get();

        //prepare tree with HTML
        $html_tree = $this->buildTree($permissions);
        $user = User::findOrFail($id);
        $user_roles = [];
        $assigned_roles = $user->roles;
        foreach($assigned_roles as $assigned_role){
            $user_roles[] = $assigned_role->id;
        }

        $user_permissions = $user->getAllPermissions();

        return view('user_edit',compact('html_tree','user', 'user_permissions', 'roles', 'user_roles'));
    }

    /**
     * Update user.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update(['name' => $request->name]);
        $permissions = array_map('intval', explode(':', $request->permissions));
        $user->syncPermissions($permissions);
        $user->roles()->sync($request->roles);
        return redirect()->route('home');
    }



    /**
     * Prepare tree data for tree view permission
     *
     * @return HTML
     */
    protected function buildTree(array $elements, $level = 1, $parentId = 0) {

        $branch = NULL;

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $level+1, $element['id']);

                if ($children) {
                    $element['ul'] = '<ul>'.$children.'</ul>';
                }

                $branch .= '<li data-id='.$element['id'].' data-level='.$level.'><span>'.$element['description'].'</span>'.(isset($element['ul'])?$element['ul']:"").'</li>';

            }
        }

        return $branch;
    }
}
