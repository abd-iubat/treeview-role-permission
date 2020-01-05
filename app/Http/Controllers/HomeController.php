<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

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
        $permission_tree = [];
        $permissions = Permission::orderBy('order')->get()->toArray();

//        foreach($permissions as $row) {
//            $pid  = ($row->menu_id == NULL || $row->menu_id == "")?0:$row->menu_id;
//            $id   = $row->id;
//            $name = $row->description;
//
//            // Create or add child information to the parent node
//            if (isset($permission_tree[$pid]))
//                // a node for the parent exists
//                // add another child id to this parent
//                $permission_tree[$pid]["children"][] = $id;
//            else
//                // create the first child to this parent
//                $permission_tree[$pid] = array("children"=>array($id));
//
//            // Create or add name information for current node
//            if (isset($permission_tree[$id]))
//                // a node for the id exists:
//                // set the name of current node
//                $permission_tree[$id]["name"] = $name;
//            else
//                // create the current node and give it a name
//                $permission_tree[$id] = array( "name"=>$name );
//        }
//dd($permissions);

        $permission_tree = $this->buildTree($permissions);


        if ($request->wantsJson()) {
            $user = new User();
            return $user->DataTableLoader($request);
        }
        else {
            //ksort($permission_tree);
            //dd($permission_tree);
            return view('home', compact('permission_tree'));
        }
    }

//    public function buildTree(array $elements, $parentId = 0) {
//
//        $branch = array();
//
//        foreach ($elements as $element) {
//            if ($element['parent_id'] == $parentId) {
//                $children = $this->buildTree($elements, $element['id']);
//                if ($children) {
//                    dd($children);
//                    $element['children'] = $children;
//                }
//                $branch[] = $element;
//            }
//        }
//
//        return $branch;
//    }

    public function buildTree(array $elements, $level = 1, $parentId = 0) {

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
