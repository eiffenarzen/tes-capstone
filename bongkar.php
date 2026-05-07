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
        SELECT * FROM bongkar
        WHERE id='$id_edit'
    ");

    $edit = mysqli_fetch_assoc($ambil_edit);
}

/* ================= UPDATE DATA ================= */

if(isset($_POST['update'])){

    $id                     = $_POST['id'];
    $tanggal                = $_POST['tanggal'];
    $jam_mulai_bongkar      = $_POST['jam_mulai_bongkar'];
    $jam_selesai_bongkar    = $_POST['jam_selesai_bongkar'];
    $total                  = $_POST['total'];

    // upload file
    $file = $_POST['file_lama'];

    if(isset($_FILES['file']['name'])){

        $nama_file = $_FILES['file']['name'];
        $tmp       = $_FILES['file']['tmp_name'];

        if($nama_file != ""){

            move_uploaded_file($tmp, "../upload/".$nama_file);

            $file = $nama_file;
        }
    }

    mysqli_query($conn,"
        UPDATE bongkar SET
            tanggal='$tanggal',
            jam_mulai_bongkar='$jam_mulai_bongkar',
            jam_selesai_bongkar='$jam_selesai_bongkar',
            total='$total',
            file='$file'
        WHERE id='$id'
    ");

    header("Location: bongkar.php");
}

/* ================= SIMPAN DATA ================= */

if(isset($_POST['simpan'])){

    $muat_id = $_POST['muat_id'];
    $tanggal = $_POST['tanggal'];
    $total   = $_POST['total'];

    // JAM BONGKAR
    $jam_mulai_bongkar   = $_POST['jam_mulai_bongkar'];
    $jam_selesai_bongkar = $_POST['jam_selesai_bongkar'];

    // upload file
    $file = "";

    if(isset($_FILES['file']['name'])){

        $file = $_FILES['file']['name'];
        $tmp  = $_FILES['file']['tmp_name'];

        if($file != ""){
            move_uploaded_file($tmp, "../upload/".$file);
        }
    }

    // ambil data muat
    $ambil = mysqli_query($conn, "
        SELECT * FROM muat
        WHERE id='$muat_id'
    ");

    $m = mysqli_fetch_assoc($ambil);

    $plat               = $m['plat'];
    $supir              = $m['supir'];
    $type_unit          = $m['type_unit'];

    $lokasi_muat        = $m['lokasi_muat'];
    $tujuan_bongkar     = $m['tujuan_bongkar'];

    $jam_mulai_muat     = $m['jam_mulai_muat'];
    $jam_selesai_muat   = $m['jam_selesai_muat'];

    $jam_kedatangan     = $m['jam_kedatangan'];
    $jam_keberangkatan  = $m['jam_keberangkatan'];

    $keterangan         = $m['keterangan'];

    mysqli_query($conn, "
        INSERT INTO bongkar (
            user_id,
            muat_id,
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
            jam_mulai_bongkar,
            jam_selesai_bongkar,
            keterangan,
            total,
            file
        ) VALUES (
            '$user_id',
            '$muat_id',
            '$plat',
            '$supir',
            '$type_unit',
            '$lokasi_muat',
            '$tujuan_bongkar',
            '$tanggal',
            '$jam_mulai_muat',
            '$jam_selesai_muat',
            '$jam_kedatangan',
            '$jam_keberangkatan',
            '$jam_mulai_bongkar',
            '$jam_selesai_bongkar',
            '$keterangan',
            '$total',
            '$file'
        )
    ");
}

/* ================= DATA MUAT ================= */

$muat = mysqli_query($conn, "
    SELECT * FROM muat
    WHERE user_id='$user_id'
    ORDER BY id DESC
");

/* ================= DATA BONGKAR ================= */

$data = mysqli_query($conn, "
    SELECT * FROM bongkar
    WHERE user_id='$user_id'
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bongkar Barang</title>

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

        .content{
            flex:1;
            padding:25px;
            overflow-x:auto;
        }

        .card{
            width:100%;
            overflow:hidden;
        }

        .title{
            font-size:24px;
            font-weight:700;
            margin-bottom:20px;
        }

        .form-grid{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
            gap:14px;
            margin-bottom:18px;
        }

        .input{
            width:100%;
            padding:14px;
            border:none;
            outline:none;
            border-radius:12px;
            background:#0b1730;
            color:white;
            border:1px solid rgba(255,255,255,0.08);
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

        .table-wrapper{
            width:100%;
            overflow-x:auto;
            margin-top:20px;
            border-radius:15px;
        }

        table{
            width:100%;
            border-collapse:collapse;
            min-width:1300px;
        }

        thead{
            background:linear-gradient(90deg,#5f8fe6,#6ea6ff);
        }

        th{
            padding:14px;
            text-align:left;
            font-size:14px;
            white-space:nowrap;
        }

        td{
            padding:14px;
            background:#111d39;
            border-bottom:1px solid rgba(255,255,255,0.05);
            color:#dce7ff;
            font-size:14px;
        }

        tr:hover td{
            background:#16264a;
        }

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

<div class="sidebar">

    <div class="logo">AGWI TRANS</div>

    <a href="../user/dashboard.php">Dashboard</a>

    <a href="../user/muat.php">
        Muat Barang
    </a>

    <a href="../user/bongkar.php" class="active">
        Bongkar Barang
    </a>

    <a href="../user/maintenance.php">
        Maintenance
    </a>

    <a href="../auth/logout.php">
        Logout
    </a>

</div>

<div class="content">

    <div class="card">

        <div class="title">
            Bongkar Barang
        </div>

        <form method="POST" enctype="multipart/form-data">

        <div class="form-grid">

            <?php if(!$edit){ ?>

            <select name="muat_id" class="input" required>

                <option value="">Pilih Data Muat</option>

                <?php while($m = mysqli_fetch_assoc($muat)){ ?>

                    <option value="<?= $m['id']; ?>">

                        <?= $m['plat']; ?> - <?= $m['supir']; ?>

                    </option>

                <?php } ?>

            </select>

            <?php } ?>

            <input
                type="date"
                name="tanggal"
                class="input"
                value="<?= $edit['tanggal'] ?? ''; ?>"
                required
            >

            <input
                type="time"
                name="jam_mulai_bongkar"
                class="input"
                value="<?= $edit['jam_mulai_bongkar'] ?? ''; ?>"
                required
            >

            <input
                type="time"
                name="jam_selesai_bongkar"
                class="input"
                value="<?= $edit['jam_selesai_bongkar'] ?? ''; ?>"
                required
            >

            <input
                type="text"
                name="total"
                class="input"
                placeholder="Total Harga"
                value="<?= $edit['total'] ?? ''; ?>"
                required
            >

            <input
                type="file"
                name="file"
                class="input"
            >

        </div>

        <?php if($edit){ ?>

            <input
                type="hidden"
                name="id"
                value="<?= $edit['id']; ?>"
            >

            <input
                type="hidden"
                name="file_lama"
                value="<?= $edit['file']; ?>"
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

        <div class="table-wrapper">

            <table>

                <thead>
                <tr>
                    <th>Plat</th>
                    <th>Supir</th>
                    <th>Type Unit</th>
                    <th>Lokasi Muat</th>
                    <th>Tujuan Bongkar</th>
                    <th>Tanggal</th>
                    <th>Jam Mulai Bongkar</th>
                    <th>Jam Selesai Bongkar</th>
                    <th>Total</th>
                    <th>File</th>
                    <th>Aksi</th>
                </tr>
                </thead>

                <tbody>

                <?php while($row = mysqli_fetch_assoc($data)){ ?>

                <tr>

                    <td><?= $row['plat']; ?></td>
                    <td><?= $row['supir']; ?></td>
                    <td><?= $row['type_unit']; ?></td>
                    <td><?= $row['lokasi_muat']; ?></td>
                    <td><?= $row['tujuan_bongkar']; ?></td>
                    <td><?= $row['tanggal']; ?></td>

                    <td>
                        <?= $row['jam_mulai_bongkar']; ?>
                    </td>

                    <td>
                        <?= $row['jam_selesai_bongkar']; ?>
                    </td>

                    <td><?= $row['total']; ?></td>

                    <td>

                        <?php if($row['file'] != ""){ ?>

                            <a
                                href="../upload/<?= $row['file']; ?>"
                                target="_blank"
                                style="color:#69a3ff;"
                            >
                                Lihat
                            </a>

                        <?php } else { ?>

                            -

                        <?php } ?>

                    </td>

                    <td>

                        <a
                            href="bongkar.php?edit=<?= $row['id']; ?>"
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
