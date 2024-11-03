<?php
class RoleController extends Controller
{
    private $roleModel;
    private $permissions;

    public function __construct()
    {
        parent::__construct();
        $this->roleModel = $this->model('role');
        $this->permissions = [
            'dashboard' => 'หน้าหลัก/แดชบอร์ด',
            'manager' => 'ผู้ดูแลระบบ',
            'role' => 'จัดการสิทธ์การใช้งาน',
            'employee' => 'จัดการข้อมูลพนักงาน',
            'employee-schedule' => 'ตารางงานของพนักงาน',
            'customer' => 'จัดการข้อมูลลูกค้า',
            'stock' => 'จัดการข้อมูลสินค้าและผลิตภัณฑ์ภายในร้าน',
            'service' => 'จัดการข้อมูลการบริการ',
            'estimate' => 'จัดการข้อมูลการประเมินใบหน้า',
            'promotion' => 'จัดการโปรโมชั่น',
            'comment' => 'จัดการความคิดเห็น',
            'finance' => 'ข้อมูลทางการเงิน'
        ];
    }

    public function index()
    {
        $roles = $this->roleModel->getAllroles();
        $data = [
            'title' => 'จัดการโปรโมชั่น | Mira ศูนย์ความงามครบวงจร',
            'roles' => $roles,
            'permission' => $this->permissions
        ];
        $this->view('role/index', $data);
    }

    public function add()
    {
        $data = [
            'title' => 'เพิ่มข้อมูลโปรโมชั่น | Mira ศูนย์ความงามครบวงจร',
            'permissions' => $this->permissions
        ];
        $this->view('role/add', $data);
    }

    public function insert()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->roleModel->insertRole($_POST)) {
                redirect()->with('success', 'เพิ่มข้อมูลโปรโมชั่นสำเร็จ')->to('/role');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล')->back();
            }
        }
    }

    public function edit($id)
    {
        $role = $this->roleModel->getRoleById($id);
        if (!$role) {
            redirect()->with('error', 'ไม่พบข้อมูลโปรโมชั่น')->to('/role');
        }

        $data = [
            'title' => 'แก้ไขข้อมูลโปรโมชั่น | Mira ศูนย์ความงามครบวงจร',
            'role' => $role,
            'permissions' => $this->permissions
        ];
        $this->view('role/edit', $data);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->roleModel->updateRole($_POST)) {
                redirect()->with('success', 'อัปเดตข้อมูลโปรโมชั่นสำเร็จ')->to('/role');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล')->back();
            }
        }
    }

    public function delete($id)
    {
        if ($this->roleModel->deleteRole($id)) {
            redirect()->with('success', 'ลบข้อมูลโปรโมชั่นสำเร็จ')->to('/role');
        } else {
            redirect()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูล')->back();
        }
    }

    // user for view page
    public function showPermission($permissions) {
        $array = explode(", " , $permissions);
        foreach ($array as $index => $a) {
            if ($index == 6) {
                echo "<br>";
            }
            echo '<span class="badge badge-dark mr-2 mb-2">' . $this->permissions[$a] . "</span>";
        }
    }
}
