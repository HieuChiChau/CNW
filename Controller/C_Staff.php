<?php
include_once("Model/M_Staff.php");

class Ctrl_Staff
{
    public function invoke()
    {
        if (isset($_POST['login'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $modelStaff = new Model_Staff();
            // Kiểm tra đăng nhập
            if ($modelStaff->checkLogin($username, $password)) {
                header("Location: View/main.html");
                exit();
            } else {
                echo "Login failed!";
            }
        } else {
            include_once("View/login.html");
        }
        if (isset($_POST['delete'])) {
            $idnv = $_POST['idnv'];

            $modelStaff = new Model_Staff();
            $result = $modelStaff->deleteStaff($idnv);

            if ($result) {
                $resultMessage = "Xóa sinh viên thành công";
            } else {
                $resultMessage = "Có lỗi khi xóa sinh viên";
            }

            // Chuyển hướng về trang StudentList_Delete.html
            header("Location: ../Controller/C_Staff.php?mod3=1");
            exit();
        }

        if (isset($_GET['mod3'])) {
            if (isset($_GET['stid'])) {
                $modelStaff = new Model_Staff();
                $staff = $modelStaff->getStaffDetail($_GET['stid']);
                include_once("../View/StaffDelete.html");
            } else {
                $modelStaff = new Model_Staff();
                $staff = $modelStaff->getAllStaff();
                include_once("../View/StaffList_Delete.html");
                return;
            }
        }
    }
}
$C_Staff = new Ctrl_Staff();
$C_Staff->invoke();
