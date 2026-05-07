<?php
session_start();
require "../config/koneksi.php";

if(!isset($_SESSION['user'])){
    header("Location: ../user/index.html");
    exit;
}

$user_id = $_SESSION['user_id'];

/* ================= AMBIL DATA EDIT ================= */

$edit = null;

if(isset($_GET['edit'])){

    $id_edit = $_GET['edit'];

    $ambil_edit = mysqli_query($conn,"
        SELECT * FROM muat
        WHERE id='$id_edit'
    ");

    $edit = mysqli_fetch_assoc($ambil_edit);
}

/* ================= UPDATE DATA ================= */

if(isset($_POST['update'])){

    $id                 = $_POST['id'];
    $plat               = $_POST['plat'];
    $supir              = $_POST['supir'];
    $type               = $_POST['type'];
    $lokasi_muat        = $_POST['lokasi_muat'];
    $tujuan_bongkar     = $_POST['tujuan_bongkar'];
    $tanggal            = $_POST['tanggal'];
    $jam_mulai_muat     = $_POST['jam_mulai_muat'];
    $jam_selesai_muat   = $_POST['jam_selesai_muat'];
    $jam_kedatangan     = $_POST['jam_kedatangan'];
    $jam_keberangkatan  = $_POST['jam_keberangkatan'];
    $keterangan         = $_POST['keterangan'];

    mysqli_query($conn,"
        UPDATE muat SET
            plat='$plat',
            supir='$supir',
            type_unit='$type',
            lokasi_muat='$lokasi_muat',
            tujuan_bongkar='$tujuan_bongkar',
            tanggal='$tanggal',
            jam_mulai_muat='$jam_mulai_muat',
            jam_selesai_muat='$jam_selesai_muat',
            jam_kedatangan='$jam_kedatangan',
            jam_keberangkatan='$jam_keberangkatan',
            keterangan='$keterangan'
        WHERE id='$id'
    ");

    header("Location: muat.php");
}

/* ================= SIMPAN DATA ================= */

if(isset($_POST['simpan'])){

    $plat               = $_POST['plat'];
    $supir              = $_POST['supir'];
    $type               = $_POST['type'];
    $lokasi_muat        = $_POST['lokasi_muat'];
    $tujuan_bongkar     = $_POST['tujuan_bongkar'];
    $tanggal            = $_POST['tanggal'];
    $jam_mulai_muat     = $_POST['jam_mulai_muat'];
    $jam_selesai_muat   = $_POST['jam_selesai_muat'];
    $jam_kedatangan     = $_POST['jam_kedatangan'];
    $jam_keberangkatan  = $_POST['jam_keberangkatan'];
    $keterangan         = $_POST['keterangan'];

    $query = "INSERT INTO muat
    (
        user_id,
        plat,
        supir,
        type_unit,
        lokasi_muat,
        tujuan_bongkar,
        tanggal,
        jam_mulai_muat,
        jam_selesai_muat,
        jam_kedatangan,
        jam_keberangkatan,
        keterangan
    )
    VALUES
    (
        '$user_id',
        '$plat',
        '$supir',
        '$type',
        '$lokasi_muat',
        '$tujuan_bongkar',
        '$tanggal',
        '$jam_mulai_muat',
        '$jam_selesai_muat',
        '$jam_kedatangan',
        '$jam_keberangkatan',
        '$keterangan'
    )";

    mysqli_query($conn, $query);
}

/* ================= AMBIL DATA ================= */

$data = mysqli_query($conn, "
    SELECT * FROM muat
    WHERE user_id='$user_id'
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Muat Barang</title>

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
    background:#0c1730;
    padding:30px 20px;
    border-right:1px solid rgba(255,255,255,0.08);
}

.logo{
    font-size:22px;
    font-weight:bold;
    margin-bottom:40px;
    letter-spacing:1px;
}

.sidebar a{
    display:block;
    color:#dbe7ff;
    text-decoration:none;
    padding:14px 0;
    font-size:17px;
    transition:0.3s;
}

.sidebar a:hover{
    color:#69a3ff;
    padding-left:8px;
}

.sidebar .active{
    color:#69a3ff;
}

/* CONTENT */
.content{
    flex:1;
    padding:20px;
}

/* CARD */
.card{
    background:#101d3a;
    border:1px solid rgba(255,255,255,0.08);
    border-radius:20px;
    padding:20px;
    box-shadow:0 0 25px rgba(0,0,0,0.4);
}

.title{
    font-size:22px;
    font-weight:700;
    margin-bottom:20px;
}

/* FORM */
.form-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:14px;
    margin-bottom:18px;
}

.input{
    background:#0b1730;
    border:1px solid rgba(255,255,255,0.08);
    color:white;
    padding:14px;
    border-radius:12px;
    outline:none;
    width:100%;
}

.input::placeholder{
    color:#7e8ba7;
}

.input:focus{
    border-color:#5f9cff;
}

.btn{
    background:#5f9cff;
    border:none;
    color:white;
    padding:12px 25px;
    border-radius:12px;
    cursor:pointer;
    font-weight:600;
    transition:0.3s;
}

.btn:hover{
    background:#77adff;
}

/* TABLE */
.table-wrapper{
    width:100%;
    overflow-x:auto;
    margin-top:10px;
}

table{
    width:100%;
    border-collapse:collapse;
    overflow:hidden;
    border-radius:15px;
}

thead{
    background:linear-gradient(90deg,#5f8fe6,#6ea6ff);
}

th{
    padding:16px;
    text-align:left;
    font-size:16px;
}

td{
    padding:14px 16px;
    background:#111d39;
    border-bottom:1px solid rgba(255,255,255,0.05);
    color:#dce7ff;
}

tr:hover td{
    background:#16264a;
}

/* MOBILE */
@media(max-width:768px){

    body{
        flex-direction:column;
    }

    .sidebar{
        width:100%;
        height:auto;
    }

    .content{
        padding:15px;
    }

    .title{
        font-size:20px;
    }

}
</style>

</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">

    <div class="logo">AGWI TRANS</div>

    <a href="../user/dashboard.php">Dashboard</a>
    <a href="../user/muat.php" class="active">Muat Barang</a>
    <a href="../user/bongkar.php">Bongkar Barang</a>
    <a href="../user/maintenance.php">Maintenance</a>
    <a href="../auth/logout.php">Logout</a>

</div>

<!-- CONTENT -->
<div class="content">

    <div class="card">

        <div class="title">Muat Barang</div>

        <!-- FORM -->
        <form method="POST">

            <div class="form-grid">

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
                    name="supir"
                    class="input"
                    placeholder="Nama Supir"
                    value="<?= $edit['supir'] ?? ''; ?>"
                    required
                >

                <input
                    type="text"
                    name="type"
                    class="input"
                    placeholder="Type Unit"
                    value="<?= $edit['type_unit'] ?? ''; ?>"
                    required
                >

                <input
                    type="text"
                    name="lokasi_muat"
                    class="input"
                    placeholder="Lokasi Muat"
                    value="<?= $edit['lokasi_muat'] ?? ''; ?>"
                    required
                >

                <input
                    type="text"
                    name="tujuan_bongkar"
                    class="input"
                    placeholder="Tujuan Bongkar"
                    value="<?= $edit['tujuan_bongkar'] ?? ''; ?>"
                    required
                >

                <input
                    type="date"
                    name="tanggal"
                    class="input"
                    value="<?= $edit['tanggal'] ?? ''; ?>"
                    required
                >

                <input
                    type="time"
                    name="jam_mulai_muat"
                    class="input"
                    value="<?= $edit['jam_mulai_muat'] ?? ''; ?>"
                    required
                >

                <input
                    type="time"
                    name="jam_selesai_muat"
                    class="input"
                    value="<?= $edit['jam_selesai_muat'] ?? ''; ?>"
                    required
                >

                <input
                    type="time"
                    name="jam_kedatangan"
                    class="input"
                    value="<?= $edit['jam_kedatangan'] ?? ''; ?>"
                    required
                >

                <input
                    type="time"
                    name="jam_keberangkatan"
                    class="input"
                    value="<?= $edit['jam_keberangkatan'] ?? ''; ?>"
                    required
                >

                <input
                    type="text"
                    name="keterangan"
                    class="input"
                    placeholder="Keterangan"
                    value="<?= $edit['keterangan'] ?? ''; ?>"
                    required
                >

            </div>

            <?php if($edit){ ?>

                <input
                    type="hidden"
                    name="id"
                    value="<?= $edit['id']; ?>"
                >

            <?php } ?>

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
                        <th>Tanggal</th>
                        <th>Plat</th>
                        <th>Supir</th>
                        <th>Type Unit</th>
                        <th>Lokasi Muat</th>
                        <th>Tujuan Bongkar</th>
                        <th>Jam Mulai Muat</th>
                        <th>Jam Selesai Muat</th>
                        <th>Jam Kedatangan</th>
                        <th>Jam Keberangkatan</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                <?php while($row = mysqli_fetch_assoc($data)){ ?>

                    <tr>

                        <td><?= $row['tanggal']; ?></td>
                        <td><?= $row['plat']; ?></td>
                        <td><?= $row['supir']; ?></td>
                        <td><?= $row['type_unit']; ?></td>
                        <td><?= $row['lokasi_muat']; ?></td>
                        <td><?= $row['tujuan_bongkar']; ?></td>
                        <td><?= $row['jam_mulai_muat']; ?></td>
                        <td><?= $row['jam_selesai_muat']; ?></td>
                        <td><?= $row['jam_kedatangan']; ?></td>
                        <td><?= $row['jam_keberangkatan']; ?></td>
                        <td><?= $row['keterangan']; ?></td>

                        <td>

                            <a
                                href="muat.php?edit=<?= $row['id']; ?>"
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
