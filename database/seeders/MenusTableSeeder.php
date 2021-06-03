<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class MenusTableSeeder extends Seeder
{
    private $menuId = null;
    private $dropdownId = array();
    private $dropdown = false;
    private $sequence = 1;
    private $joinData = array();
    private $masterRole = null;
    private $supervisorRole = null;
    private $operatorRole = null;
    private $subFolder = '';

    public function join($roles, $menusId){
        $roles = explode(',', $roles);
        foreach($roles as $role){
            array_push($this->joinData, array('role_name' => $role, 'menus_id' => $menusId));
        }
    }

    /*
        Function assigns menu elements to roles
        Must by use on end of this seeder
    */
    public function joinAllByTransaction(){
        DB::beginTransaction();
        foreach($this->joinData as $data){
            DB::table('menu_role')->insert([
                'role_name' => $data['role_name'],
                'menus_id' => $data['menus_id'],
            ]);
        }
        DB::commit();
    }

    public function insertLink($roles, $name, $href, $icon = null){
        $href = $this->subFolder . $href;
        if($this->dropdown === false){
            DB::table('menus')->insert([
                'slug' => 'link',
                'name' => $name,
                'icon' => $icon,
                'href' => $href,
                'menu_id' => $this->menuId,
                'sequence' => $this->sequence
            ]);
        }else{
            DB::table('menus')->insert([
                'slug' => 'link',
                'name' => $name,
                'icon' => $icon,
                'href' => $href,
                'menu_id' => $this->menuId,
                'parent_id' => $this->dropdownId[count($this->dropdownId) - 1],
                'sequence' => $this->sequence
            ]);
        }
        $this->sequence++;
        $lastId = DB::getPdo()->lastInsertId();
        $this->join($roles, $lastId);
        $permission = Permission::where('name', '=', $name)->get();
        if(empty($permission)){
            $permission = Permission::create(['name' => 'visit ' . $name]);
        }
        $roles = explode(',', $roles);
        if(in_array('master', $roles)){
            $this->masterRole->givePermissionTo($permission);
        }
        if(in_array('supervisor', $roles)){
            $this->supervisorRole->givePermissionTo($permission);
        }
        if(in_array('operator', $roles)){
            $this->operatorRole->givePermissionTo($permission);
        }
        return $lastId;
    }

    public function insertTitle($roles, $name){
        DB::table('menus')->insert([
            'slug' => 'title',
            'name' => $name,
            'menu_id' => $this->menuId,
            'sequence' => $this->sequence
        ]);
        $this->sequence++;
        $lastId = DB::getPdo()->lastInsertId();
        $this->join($roles, $lastId);
        return $lastId;
    }

    public function beginDropdown($roles, $name, $icon = ''){
        if(count($this->dropdownId)){
            $parentId = $this->dropdownId[count($this->dropdownId) - 1];
        }else{
            $parentId = null;
        }
        DB::table('menus')->insert([
            'slug' => 'dropdown',
            'name' => $name,
            'icon' => $icon,
            'menu_id' => $this->menuId,
            'sequence' => $this->sequence,
            'parent_id' => $parentId
        ]);
        $lastId = DB::getPdo()->lastInsertId();
        array_push($this->dropdownId, $lastId);
        $this->dropdown = true;
        $this->sequence++;
        $this->join($roles, $lastId);
        return $lastId;
    }

    public function endDropdown(){
        $this->dropdown = false;
        array_pop( $this->dropdownId );
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { 
        /* Get roles */
        $this->masterRole = Role::where('name' , '=' , 'master' )->first();
        $this->supervisorRole = Role::where('name', '=', 'supervisor' )->first();
        $this->operatorRole = Role::where('name', '=', 'operator' )->first();
        /* Create Sidebar menu */
        DB::table('menulist')->insert([
            'name' => 'sidebar menu'
        ]);
        $this->menuId = DB::getPdo()->lastInsertId();  //set menuId
        $this->insertLink('master,supervisor,operator', 'Dashboard', '/home', 'cil-speedometer');
        $this->beginDropdown('master', 'Settings', 'cil-calculator');
            $this->insertLink('master', 'Users',                   '/users');
            $this->insertLink('master', 'Edit menu',               '/menu/element');
            $this->insertLink('master', 'Edit roles',              '/roles');
        $this->endDropdown();
        $this->insertTitle('master,supervisor,operator', 'Menu');
        $this->insertLink('master,operator', 'Customers', '/customers', 'cil-people');
        $this->insertLink('master,operator', 'Clusters', '/clusters', 'cil-factory');
        $this->insertLink('master,operator', 'Units', '/units', 'cil-house');
        $this->beginDropdown('master,operator', 'Payments', 'cil-dollar');
            $this->insertLink('master,operator', 'Methods', '/payments');
            $this->insertLink('master,operator', 'Reports', '/payments');
        $this->endDropdown();
        $this->insertLink('master,supervisor,operator', 'Transactions', '/transactions', 'cil-cart');

        $this->joinAllByTransaction(); ///   <===== Must by use on end of this seeder
    }
}
