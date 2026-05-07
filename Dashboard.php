<?php
session_start();

if(!isset($_SESSION['user'])){
    echo "Akses ditolak!";
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard AGWI TRANS</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    min-height:100vh;
    background:
    linear-gradient(
        135deg,
        #071226,
        #091833,
        #0b1d40
    );
    color:white;
}

/* HEADER */

.header{
    width:100%;
    padding:22px 35px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    background:rgba(13,28,58,0.9);
    border-bottom:1px solid rgba(255,255,255,0.08);
    backdrop-filter:blur(10px);
}

.logo h1{
    font-size:28px;
    letter-spacing:2px;
    margin-bottom:4px;
}

.logo p{
    color:#8fa8d6;
    font-size:14px;
}

.user-box{
    display:flex;
    align-items:center;
    gap:15px;
}

.username{
    background:#09152c;
    padding:10px 16px;
    border-radius:12px;
    color:#dce7ff;
    font-size:14px;
}

.logout{
    text-decoration:none;
    background:
    linear-gradient(
        90deg,
        #5f8fe6,
        #6ea6ff
    );
    color:white;
    padding:10px 18px;
    border-radius:12px;
    font-weight:600;
    transition:0.3s;
}

.logout:hover{
    transform:translateY(-2px);
    box-shadow:0 6px 16px rgba(95,156,255,0.25);
}

/* CONTENT */

.container{
    padding:40px;
}

/* WELCOME */

.welcome{
    margin-bottom:35px;
}

.welcome h2{
    font-size:34px;
    margin-bottom:10px;
}

.welcome p{
    color:#8fa8d6;
    line-height:1.7;
    font-size:15px;
}

/* MENU */

.dashboard-menu{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:22px;
}

/* CARD */

.menu-card{
    background:#0d1c3a;
    border:1px solid rgba(255,255,255,0.08);
    border-radius:24px;
    padding:28px;
    text-decoration:none;
    color:white;
    transition:0.3s;
    position:relative;
    overflow:hidden;
}

.menu-card::before{
    content:"";
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:4px;
    background:
    linear-gradient(
        90deg,
        #5f8fe6,
        #6ea6ff
    );
}

.menu-card:hover{
    transform:translateY(-6px);
    border-color:#5f9cff;
    box-shadow:0 12px 28px rgba(0,0,0,0.35);
}

.icon{
    width:72px;
    height:72px;
    border-radius:20px;
    background:#09152c;
    display:flex;
    justify-content:center;
    align-items:center;
    font-size:34px;
    margin-bottom:22px;
}

.menu-card h3{
    font-size:22px;
    margin-bottom:10px;
}

.menu-card p{
    color:#8fa8d6;
    line-height:1.6;
    font-size:14px;
}

/* ================= LOGO ================= */

.logo-wrap{
    display:flex;
    align-items:center;
    gap:18px;
}

.logo-img{
    width:120px;          /* Lebar persegi panjang */
    height:60px;          /* Tinggi */
    object-fit:contain;   /* Biar gambar ga ke crop */
    background:white;
    padding:2px 4px;

    border-radius:18px;  /* Ujung melengkung */

    box-shadow:
    0 6px 18px rgba(0,0,0,0.35);

    transition:0.3s;
}

.logo-img:hover{
    transform:scale(1.03);
}

.logo h1{
    font-size:30px;
    letter-spacing:2px;
    color:white;
    margin-bottom:4px;
    font-weight:700;
}

.logo p{
    color:#8fa8d6;
    font-size:14px;
    letter-spacing:0.5px;
}

/* ================= MOBILE ================= */

@media(max-width:768px){

    .logo-wrap{
        justify-content:center;
        flex-direction:column;
    }

    .logo-img{
        width:100px;
        height:60px;
    }

    .logo h1{
        font-size:24px;
        text-align:center;
    }

    .logo p{
        text-align:center;
    }

}
/* RESPONSIVE */

@media(max-width:768px){

    .header{
        flex-direction:column;
        gap:15px;
        text-align:center;
        padding:20px;
    }

    .container{
        padding:20px;
    }

    .welcome h2{
        font-size:28px;
    }

    .dashboard-menu{
        grid-template-columns:1fr;
    }

}

</style>

</head>

<body>

<!-- HEADER -->

<div class="header">

    <div class="logo">

        <div class="logo-wrap">
            <img
                src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEisyaJDkkmnBrh0ELgT9cc3cQw70PEiMWusc086ztgoIVEdx-vvQU-WR8XWjAlEYX4YZB_6ArF7TVBjbNREumGxVZKXz5Tl_-Dzk4VJE_ocfgfwDgf6ChOtjNOC0UZzbMrsNHcWF6W50mHVXHAfAq2xPezGpDIvchFKMqTMdrMFLUON4vBb8inPSTvro3HG/s320/WhatsApp%20Image%202026-04-06%20at%2012.07.51%20(1).png"
                alt="Logo AGWI"
                class="logo-img"
            >

            <div>

                <h1>AGWI TRANS</h1>

                <p>
                    Sistem Monitoring Transportasi
                </p>

            </div>

        </div>

    </div>

    <div class="user-box">

        <div class="username">
            👤 <?= $_SESSION['user'] ?>
        </div>

        <a href="../auth/Logout.php" class="logout">
            Logout
        </a>

    </div>

</div>

<!-- CONTENT -->

<div class="container">

    <!-- WELCOME -->

    <div class="welcome">

        <h2>Dashboard</h2>

        <p>
            Selamat datang di sistem monitoring transportasi AGWI TRANS.
            Pilih menu di bawah untuk mengelola data muat barang,
            bongkar barang, dan maintenance kendaraan.
        </p>

    </div>

    <!-- MENU -->

    <div class="dashboard-menu">

        <!-- MUAT -->

        <a href="../user/muat.php" class="menu-card">

            <div class="icon">
                📦
            </div>

            <h3>Muat Barang</h3>

            <p>
                Kelola data muatan kendaraan dan aktivitas pengiriman barang.
            </p>

        </a>

        <!-- BONGKAR -->

        <a href="../user/bongkar.php" class="menu-card">

            <div class="icon">
                🚚
            </div>

            <h3>Bongkar Barang</h3>

            <p>
                Monitoring proses bongkar barang dan data perjalanan kendaraan.
            </p>

        </a>

        <!-- MAINTENANCE -->

        <a href="../user/maintenance.php" class="menu-card">

            <div class="icon">
                🛠
            </div>

            <h3>Maintenance</h3>

            <p>
                Monitoring kondisi kendaraan dan jadwal maintenance armada.
            </p>

        </a>

    </div>

</div>

</body>
</html>
