<?php
session_start();
require "../config/koneksi.php";

if(!isset($_SESSION['user'])){
    header("Location: ../user/index.html");
    exit;
}

$user_id = $_SESSION['user_id'];

/* ================= EDIT DATA ================= */

$edit = null;

if(isset($_GET['edit'])){

    $id_edit = $_GET['edit'];

    $ambil_edit = mysqli_query($conn,"
        SELECT * FROM maintenance
        WHERE id='$id_edit'
    ");

    $edit = mysqli_fetch_assoc($ambil_edit);
}

/* ================= UPDATE DATA ================= */

if(isset($_POST['update'])){

    $id             = $_POST['id'];
    $supir          = $_POST['supir'];
    $plat           = $_POST['plat'];
    $kendaraan      = $_POST['kendaraan'];
    $tanggal        = $_POST['tanggal'];
    $keterangan     = $_POST['keterangan'];

    $checklist = [];

    if(isset($_POST['kondisi'])){

        foreach($_POST['kondisi'] as $nama => $kondisi){

            if($kondisi != "Pilih"){

                $checklist[] = $nama . " (" . $kondisi . ")";

            }

        }

    }

    $checklist = implode(", ", $checklist);

    mysqli_query($conn,"
        UPDATE maintenance SET
            supir='$supir',
            plat='$plat',
            kendaraan='$kendaraan',
            tanggal='$tanggal',
            checklist='$checklist',
            keterangan='$keterangan'
        WHERE id='$id'
    ");

    header("Location: maintenance.php");
}

/* ================= SIMPAN DATA ================= */

if(isset($_POST['simpan'])){

    $supir          = $_POST['supir'];
    $plat           = $_POST['plat'];
    $kendaraan      = $_POST['kendaraan'];
    $tanggal        = $_POST['tanggal'];
    $keterangan     = $_POST['keterangan'];

    $checklist = [];

    if(isset($_POST['kondisi'])){

        foreach($_POST['kondisi'] as $nama => $kondisi){

            if($kondisi != "Pilih"){

                $checklist[] = $nama . " (" . $kondisi . ")";

            }

        }

    }

    $checklist = implode(", ", $checklist);

    mysqli_query($conn,"
        INSERT INTO maintenance
        (
            user_id,
            supir,
            plat,
            kendaraan,
            tanggal,
            checklist,
            keterangan
        )
        VALUES
        (
            '$user_id',
            '$supir',
            '$plat',
            '$kendaraan',
            '$tanggal',
            '$checklist',
            '$keterangan'
        )
    ");
}

/* ================= AMBIL DATA ================= */

$data = mysqli_query($conn,"
    SELECT * FROM maintenance
    WHERE user_id='$user_id'
    ORDER BY id DESC
");

/* ================= CHECKLIST ================= */

$items = [

    "Oli Mesin",
    "Oli Hidrolik Wingbox & Kabin",
    "Oli Power Steering",
    "Air Radiator",
    "Minyak Rem",
    "Fisik Ban",
    "Tekanan Angin Ban",
    "Lampu",
    "Kebersihan",


    "Track-Belt",
    "Terpal",
    "Gembok",

];
$aksesoris_items = [
    "Track-Belt",
    "Terpal",
    "Gembok",

];
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Maintenance</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    background:linear-gradient(135deg,#071226,#091833,#0b1d40);
    color:white;
    display:flex;
    min-height:100vh;
}

/* SIDEBAR */

.sidebar{
    width:250px;
    background:#08152c;
    padding:30px 20px;
    border-right:1px solid rgba(255,255,255,0.05);
}

.logo{
    font-size:22px;
    font-weight:bold;
    margin-bottom:40px;
}

.sidebar a{
    display:block;
    color:#dce7ff;
    text-decoration:none;
    padding:14px 10px;
    border-radius:10px;
    transition:0.3s;
    margin-bottom:6px;
}

.sidebar a:hover{
    background:#132449;
}

.sidebar .active{
    background:#132449;
}

/* CONTENT */

.content{
    flex:1;
    padding:15px;
}

/* CARD */

.card{
    background:#0d1c3a;
    border-radius:20px;
    padding:20px;
    border:1px solid rgba(255,255,255,0.05);
}

.title{
    font-size:22px;
    font-weight:bold;
    margin-bottom:20px;
}

/* FORM */

.form-top{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:14px;
    margin-bottom:15px;
}

.input{
    width:100%;
    background:#09152c;
    border:1px solid rgba(255,255,255,0.08);
    color:white;
    padding:13px;
    border-radius:12px;
    outline:none;
}

.input::placeholder{
    color:#7d8ba5;
}

.section-title{
    font-size:18px;
    font-weight:bold;
    margin:20px 0 15px;
}

/* GRID */

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
    gap:14px;
}

.box{
    background:#0b1730;
    border:1px solid rgba(255,255,255,0.06);
    border-radius:15px;
    padding:14px;
}

.box label{
    display:flex;
    align-items:center;
    gap:10px;
    margin-bottom:12px;
    font-size:15px;
}

.select{
    width:100%;
    padding:10px;
    border:none;
    border-radius:8px;
    background:#8b8f97;
    color:white;
    outline:none;
}

/* BUTTON */

.btn{
    margin-top:15px;
    background:#5f9cff;
    border:none;
    color:white;
    padding:12px 24px;
    border-radius:12px;
    cursor:pointer;
    font-weight:bold;
    transition:0.3s;
}

.btn:hover{
    background:#76adff;
}

/* TABLE */

.table-wrapper{
    margin-top:20px;
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
    overflow:hidden;
    border-radius:16px;
}

thead{
    background:linear-gradient(90deg,#5f8fe6,#6ea6ff);
}

th{
    padding:15px;
    text-align:left;
}

td{
    padding:14px;
    background:#111d39;
    border-bottom:1px solid rgba(255,255,255,0.04);
}

tr:hover td{
    background:#16264a;
}

.keterangan-box{
    margin-top:20px;
    background:#0b1730;
    border:1px solid rgba(255,255,255,0.06);
    border-radius:16px;
    padding:18px;
}

/* MOBILE */

@media(max-width:768px){

    body{
        flex-direction:column;
    }

    .sidebar{
        width:100%;
    }

}

</style>

</head>
<body>

<!-- SIDEBAR -->

<div class="sidebar">

    <div class="logo">AGWI TRANS</div>

    <a href="../user/dashboard.php">Dashboard</a>
    <a href="../user/muat.php">Muat Barang</a>
    <a href="../user/bongkar.php">Bongkar Barang</a>
    <a href="../user/maintenance.php" class="active">Maintenance</a>
    <a href="../auth/logout.php">Logout</a>

</div>

<!-- CONTENT -->

<div class="content">

    <div class="card">

        <div class="title">Maintenance</div>

        <form method="POST">

            <div class="form-top">

                <input
                    type="text"
                    name="supir"
                    class="input"
                    placeholder="Nama Supir"
                    value="<?= $edit['supir'] ?? ''; ?>"
                    required
                >

                <input
                    type="text"
                    name="plat"
                    class="input"
                    placeholder="Plat Nomor"
                    value="<?= $edit['plat'] ?? ''; ?>"
                    required
                >

                <input
                    type="text"
                    name="kendaraan"
                    class="input"
                    placeholder="Kendaraan"
                    value="<?= $edit['kendaraan'] ?? ''; ?>"
                    required
                >

                <input
                    type="date"
                    name="tanggal"
                    class="input"
                    value="<?= $edit['tanggal'] ?? ''; ?>"
                    required
                >

            </div>

            <div class="section-title">Cek Kondisi</div>

            <div class="grid">

                <?php foreach($items as $item){ ?>

                <div class="box">

                    <label>

                        <input
                            type="checkbox"
                            name="checklist[]"
                            value="<?= $item; ?>"
                        >

                    <?= $item; ?>

                    </label>

                    <select
                        name="kondisi[<?= $item; ?>]"
                        class="select"
                    >

                        <option>Pilih</option>
                        <option value="Baik">Baik</option>
                        <option value="Perlu Ganti/Tambah">
                            Perlu Ganti/Tambah
                        </option>

                    </select>

                </div>

                <?php } ?>

            </div>

            <?php if($edit){ ?>

                <input
                    type="hidden"
                    name="id"
                    value="<?= $edit['id']; ?>"
                >

            <?php } ?>

            <div class="section-title">Cek Aksesoris</div>

            <div class="grid">

                <?php foreach($aksesoris_items as $aksesoris_items){ ?>

                <div class="box">

                    <label>

                        <input
                            type="checkbox"
                            name="checklist[]"
                            value="<?= $aksesoris_items; ?>"
                        >

                        <?= $aksesoris_items; ?>

                    </label>

                    <select
                        name="kondisi[<?= $aksesoris_items; ?>]"
                        class="select"
                    >

                        <option>Pilih</option>
                        <option value="Baik">Baik</option>
                        <option value="Perlu Ganti/Tambah">
                            Perlu Ganti/Tambah
                        </option>

                    </select>

                </div>

                <?php } ?>

            </div>

            <?php if($edit){ ?>

                <input
                    type="hidden"
                    name="id"
                    value="<?= $edit['id']; ?>"
                >

            <?php } ?>

            <!-- KETERANGAN -->

            <div class="keterangan-box">

                <div class="section-title">
                    Keterangan Barang Kurang (diisi jika ada barang lain yang perlu diganti)
                </div>

                <input
                    type="text"
                    name="keterangan"
                    class="input"
                    placeholder="Contoh : Ring 8, Dll"
                    value="<?= $edit['keterangan'] ?? ''; ?>"
                >

            </div>

            <?php if($edit){ ?>

                <button type="submit" name="update" class="btn">
                    Update
                </button>

            <?php } else { ?>

                <button type="submit" name="simpan" class="btn">
                    Simpan
                </button>

            <?php } ?>

        </form>

        <!-- TABLE -->

        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th>Supir</th>
                        <th>Plat</th>
                        <th>Kendaraan</th>
                        <th>Tanggal</th>
                        <th>Checklist</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                <?php while($row = mysqli_fetch_assoc($data)){ ?>

                    <tr>

                        <td><?= $row['supir']; ?></td>
                        <td><?= $row['plat']; ?></td>
                        <td><?= $row['kendaraan']; ?></td>
                        <td><?= $row['tanggal']; ?></td>
                        <td><?= $row['checklist']; ?></td>
                        <td><?= $row['keterangan']; ?></td>

                        <td>

                            <a
                                href="maintenance.php?edit=<?= $row['id']; ?>"
                                style="
                                    color:white;
                                    background:#5f9cff;
                                    padding:8px 14px;
                                    border-radius:8px;
                                    text-decoration:none;
                                    font-size:13px;
                                "
                            >
                                Edit
                            </a>

                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>
