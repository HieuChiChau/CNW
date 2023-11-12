<?php
include_once("E_Staff.php");

class Model_Staff
{
    function __construct()
    {
    }
    public function checkLogin($username, $password)
    {
        $link = mysqli_connect("localhost", "root", "") or die("khong the ket noi csdl");
        mysqli_select_db($link, "quanly");
        $sql = "SELECT * FROM admin WHERE  username = ? AND password = ?";
        $stmt = mysqli_prepare($link, $sql);

        mysqli_stmt_bind_param($stmt, "ss", $username, $password);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            return true;
        } else {
            return false;
        }
        // Đóng kết nối và statement
        mysqli_stmt_close($stmt);
        mysqli_close($link);
    }
    public function getAllStaff()
    {
        $link = mysqli_connect("localhost", "root", "") or die("Không thể kết nối đến CSDL");
        mysqli_select_db($link, "quanly");

        $sql = "SELECT idnv, hoten FROM nhanvien";
        $rs = mysqli_query($link, $sql);

        $Staff = array();
        while ($row = mysqli_fetch_array($rs)) {
            $Staff[] = array(
                'idnv' => $row['idnv'],
                'hoten' => $row['hoten']
            );
        }
        return $Staff;
    }
    public function getStaffDetail($staff_id)
    {
        $link = mysqli_connect("localhost", "root", "") or die("Không thể kết nối đến CSDL");
        mysqli_select_db($link, "quanly");

        $sql = "SELECT * FROM nhanvien WHERE id = $staff_id";
        $rs = mysqli_query($link, $sql);

        $row = mysqli_fetch_assoc($rs);

        $staffDetail = array(
            'idnv' => $row['idnv'],
            'hoten' => $row['hoten'],
            'idpb' => $row['idpb'],
            'diachi' => $row['diachi']
        );
        return $staffDetail;
    }
    public function addStaff(Entity_Nhanvien $staff)
    {
        $link = mysqli_connect("localhost", "root", "") or die("Không thể kết nối đến CSDL");
        mysqli_select_db($link, "quanly");

        // Kiểm tra xem mã sinh viên đã tồn tại hay chưa
        if ($this->checkId($staff->idnv)) {
            return false; // Mã sinh viên đã tồn tại, không thêm được
        }

        $sql = "INSERT INTO nhanvien (idnv, hoten, idpb, diachi) VALUES (?, ?, ?, ?)";
        $rs = mysqli_prepare($link, $sql);

        // Bảo vệ trước SQL injection
        mysqli_stmt_bind_param($rs, "ssis", $staff->idnv, $staff->hoten, $staff->idpb, $staff->diachi);

        $result = mysqli_stmt_execute($rs);
        mysqli_stmt_close($rs);

        return $result;
    }
    public function checkId($staff_id)
    {
        $link = mysqli_connect("localhost", "root", "") or die("Không thể kết nối đến CSDL");
        mysqli_select_db($link, "quanly");

        $sql = "SELECT 1 FROM nhanvien WHERE idnv = ? LIMIT 1";
        $rs = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($rs, "s", $staff_id);
        mysqli_stmt_execute($rs);

        $result = mysqli_stmt_get_result($rs);
        $exists = (mysqli_num_rows($result) > 0);

        mysqli_stmt_close($rs);

        return $exists;
    }
    public function updateStaff(Entity_Nhanvien $staff)
    {
        $link = mysqli_connect("localhost", "root", "") or die("Khong the ket noi csdl");
        mysqli_select_db($link, "quanly");

        $sql = "update nhanvien set hoten = ?, idpb = ?, diachi = ? where id = ?";
        $rs = mysqli_prepare($link, $sql);

        mysqli_stmt_bind_param($rs, "siss", $staff->hoten, $staff->idpb, $staff->diachi, $staff->idnv);

        $result = mysqli_stmt_execute($rs);
        mysqli_stmt_close($rs);

        return $result;
    }
    public function deleteStaff($staff_id)
    {
        $link = mysqli_connect("localhost", "root", "") or die("Khong the ket noi csdl");
        mysqli_select_db($link, "quanly");

        $sql = "delete from nhanvien where idnv = ?";
        $rs = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($rs, "s", $staff_id);

        $result = mysqli_stmt_execute($rs);
        mysqli_stmt_close($rs);

        return $result;
    }
}
