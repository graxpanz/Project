<?php
class RoleController extends Controller
{
    private $roleModel;
    private $permissions;

    public function __construct()
    {
        parent::__construct();
        $this->roleModel = $this->model('Role');
        $this->permissions = [
            'dashboard' => 'หน้าหลัก/แดชบอร์ด',
            'user' => 'จัดการผู้ใช้',
            'role' => 'จัดการสิทธ์ผู้ใช้',
            'customer' => 'จัดการข้อมูลลูกค้า',
            'service_type' => 'จัดการประเภทของบริการ',
            'service' => 'จัดการข้อมูลการบริการ',
            'promotion' => 'จัดการโปรโมชั่น',
            'booking' => 'จัดการข้อมูลการจอง',
            'feedback' => 'จัดการข้อมูลความคิดเห็น',
            'estimate' => 'จัดการข้อมูลการประเมิน'
        ];
    }

    public function index()
    {
        $roles = $this->roleModel->getAllroles();
        $data = [
            'title' => 'จัดการสิทธ์ผู้ใช้ | Mira ศูนย์ความงามครบวงจร',
            'roles' => $roles,
            'permission' => $this->permissions
        ];
        $this->view('role/index', $data);
    }

    public function add()
    {
        $data = [
            'title' => 'เพิ่มข้อมูลสิทธ์ผู้ใช้ | Mira ศูนย์ความงามครบวงจร',
            'permissions' => $this->permissions
        ];
        $this->view('role/add', $data);
    }

    public function insert()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->roleModel->insertRole($_POST)) {
                redirect()->with('success', 'เพิ่มข้อมูลสิทธ์ผู้ใช้สำเร็จ')->to('/role');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล')->back();
            }
        }
    }

    public function edit($id)
    {
        $role = $this->roleModel->getRoleById($id);
        if (!$role) {
            redirect()->with('error', 'ไม่พบข้อมูลสิทธ์ผู้ใช้')->to('/role');
        }

        $data = [
            'title' => 'แก้ไขข้อมูลสิทธ์ผู้ใช้| Mira ศูนย์ความงามครบวงจร',
            'role' => $role,
            'permissions' => $this->permissions
        ];
        $this->view('role/edit', $data);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $validate = (!empty($_POST['permissions']));
            if ($validate && $this->roleModel->updateRole($_POST)) {
                redirect()->with('success', 'อัปเดตข้อมูลสิทธ์ผู้ใช้สำเร็จ')->to('/role');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล')->back();
            }
        }
    }

    public function delete($id)
    {
        if ($id == 1) {
            redirect()->with('error', 'ไม่สามารถลบสิทธ์ผู้ใช้ "ผู้ดูแลระบบ" ได้')->back();
        }

        if ($this->roleModel->deleteRole($id)) {
            redirect()->with('success', 'ลบข้อข้อมูลสิทธ์ผู้ใช้สำเร็จ')->to('/role');
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
