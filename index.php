<?php

    $connect = new PDO('mysql:host=localhost;dbname=triplec', 'root', '');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD</title>
    <style>
        img{
            width: 30px;
            height: 40px;
        }
    </style>
</head>
<body>
    <h3>Create</h3>
    <form action="index.php" method="POST" enctype="multipart/form-data">
        nama : <input type="text" name="nama"><br>
        NIA : <input type="text" name="nia"><br>
        Foto : <input type="file" name="foto"><br>
        <input type="submit" name="insertData">
    </form>

    <?php
        if (isset($_POST['insertData'])) {

            $foto = $_FILES['foto']['name'];
            $tmp = $_FILES['foto']['tmp_name'];
            $destinasi = "foto/".$foto;

            move_uploaded_file($tmp, $destinasi);

            $insert = $connect->prepare("INSERT INTO anggota (nia, nama, foto) VALUES (:nia, :nama, :foto)");
            $insert->bindValue(':nia', $_POST['nia']);
            $insert->bindValue(':nama', $_POST['nama']);
            $insert->bindValue(':foto', $foto);
            $insert->execute();

        }
    ?>

    <h3>Read</h3>
    <?php
        $data = $connect->query("SELECT * FROM anggota");
        foreach ($data as $key) {
            echo "<div> nama : {$key['nama']} </div>";
            echo "<div> NIA : {$key['nia']} </div>";
            echo "<div> foto : <img src=\"foto/{$key['foto']}\" alt=\"foto orang\"> </div>";
        }
    ?>
    
    <h3>Update</h1>
    <form action="index.php" method="POST" enctype="multipart/form-data">
        pilih data yang ingin di edit:
        <select name="nia">
        <?php
        $data = $connect->query("SELECT * FROM anggota");
        foreach ($data as $key) {?>
            <option value="<?php echo "{$key['nia']}" ?>"> <?php echo "{$key['nia']}" ?> </option>
            <?php
        }
        ?>
        </select><br>
        <input type="submit" name="editData" value="edit?">
    </form>

    <?php
        if (isset($_POST['editData'])) {
            $getNama = $connect->query("SELECT nama FROM anggota WHERE nia = {$_POST['nia']}");
            foreach ($getNama as $key) {
            ?>
                <form action="index.php" method="POST" enctype="multipart/form-data">
                    nama : <input type="text" name="nama" value="<?php echo "{$key['nama']}" ?>"><br>
                    nia : <input type="text" name="niaBaru" value="<?php echo "{$_POST['nia']}" ?>"><br>
                    <input type="hidden" name="niaLama" value="<?php echo "{$_POST['nia']}" ?>">
                    foto : <input type="file" name="foto"><br>
                    <input type="submit" name="finalEdit">
                </form>
            <?php
            }
        }

        if (isset($_POST['finalEdit'])) {
            
            $foto = $_FILES['foto']['name'];
            $tmp = $_FILES['foto']['tmp_name'];
            $destinasi = "foto/".$foto;

            move_uploaded_file($tmp, $destinasi);

            $update = $connect->prepare("UPDATE anggota SET nia = :nia, nama = :nama, foto = :foto WHERE nia = {$_POST['niaLama']}");
            $update->bindValue(":nia", $_POST['niaBaru']);
            $update->bindValue(":nama", $_POST['nama']);
            $update->bindValue(":foto", $foto);
            $update->execute();
        }
    ?>
    
    <h3>Delete</h3>
    <form action="index.php" method="POST">
        pilih data yang ingin di hapus: 
        <select name="nia">
        <?php
        $data = $connect->query("SELECT * FROM anggota");
        foreach ($data as $key) {?>
            <option value="<?php echo "{$key['nia']}" ?>"> <?php echo "{$key['nia']}" ?> </option>
            <?php
        }
        ?>
        </select><br>
        <input type="submit" value="hapus" name="hapusData">
    </form>

    <?php 
        if (isset($_POST['hapusData'])) {
            $delete = $connect->query("DELETE FROM anggota WHERE anggota.nia = {$_POST['nia']}");
            echo "data berhasil dihapus";
        }
    ?>
    
</body>
</html>