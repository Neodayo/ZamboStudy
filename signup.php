<?php
// Include database connection  
include 'db_connect.php';

$message = ""; // To display success or error messages

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $Name = $_POST['Name'];
    $Email = $_POST['Email'];
    $Password = md5($_POST['Password']); // Hash the password
    $Course = $_POST['Course'];
    $Barangay = $_POST['Barangay'];


    // Check if the email already exists
    $check_email = "SELECT * FROM user WHERE Email = '$Email'";
    $result = $conn->query($check_email);
    
    if ($result->num_rows > 0) {
        $message = "This email is already registered.";
    } else {
        // Insert new user into the database
        $sql = "INSERT INTO user (Name, Email, Password, Course, Barangay ) VALUES ('$Name', '$Email', '$Password', '$Course', '$Barangay')";
        
        if ($conn->query($sql) === TRUE) {
            header("Location: login.php");
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}
?> 
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/signup.css">
    <title>Zambo Study - Sign Up</title>


</head>

<body>
    <div class="signup-container">
        <div class="logo">
        <img src="images/zambostudylogocropped.png" alt="ZamboStudy Logo" class="logo">
        </div>
        
        <form action="signup.php" method="POST">

        <label for="name">Name</label>
        <div class="input-group">
            <input type="text" name="Name" placeholder="Name " id="Name" required>   
        </div>

        <label for="email">Email</label>
        <div class="input-group">
            <input type="email" name="Email" placeholder="Email" id="Email" required> 
        </div>

        <label for="password">Password</label>
        <div class="input-group">
            <input type="password" name="Password" placeholder="Password" id="Password" class="Password" required>
        </div>
<!-- 
        <label for="course">What subject/s are you best in?</label>
        <div class="input-group">
            <input type="text" name="Course" placeholder="Course" id="Course" required>
        </div> -->

        <label for="barangay">Barangay</label>
        <div class="input-group">
            <select id="Barangay" name="Barangay">
                <option value="arena_blanco">Arena Blanco</option>
                <option value="ayala">Ayala</option>
                <option value="baliwasan">Baliwasan</option>
                <option value="banguingui">Banguingui</option>
                <option value="bangkal">Bangkal</option>
                <option value="baragay">Baragay</option>
                <option value="bato">Bato</option>
                <option value="boalan">Boalan</option>
                <option value="bolong">Bolong</option>
                <option value="buenavista">Buenavista</option>
                <option value="cabaluay">Cabaluay</option>
                <option value="cabañangan">Cabañangan</option>
                <option value="cabatangan">Cabatangan</option>
                <option value="cacao">Cacao</option>
                <option value="calabasa">Calabasa</option>
                <option value="calarain">Calarian</option>
                <option value="camino_nuevo">Camino Nuevo</option>
                <option value="campo_islam">Campo Islam</option>
                <option value="canelar">Canelar</option>
                <option value="capisan">Capisan</option>
                <option value="divisoria">Divisoria</option>
                <option value="dulian_upper">Dulian (Upper)</option>
                <option value="dulian_lower">Dulian (Lower)</option>
                <option value="fatima">Fatima</option>
                <option value="guisao">Guisao</option>
                <option value="la_paz">La Paz</option>
                <option value="lunzuran">Lunzuran</option>
                <option value="manicahan">Manicahan</option>
                <option value="mercedes">Mercedes</option>
                <option value="muti">Muti</option>
                <option value="pamucutan">Pamucutan</option>
                <option value="pasonanca">Pasonanca</option>
                <option value="putik">Putik</option>
                <option value="san_jose_gusu">San Jose Gusu</option>
                <option value="san_roque">San Roque</option>
                <option value="santa_barbara">Santa Barbara</option>
                <option value="santa_maria">Santa Maria</option>
                <option value="santo_nino">Santo Niño</option>
                <option value="sinunuc">Sinunuc</option>
                <option value="sumagdang">Sumagdang</option>
                <option value = "talon_talon" > Talon-Talon </option >
                <option value = "taluksangay" > Taluksangay </option >
                <option value = "tetuan" > Tetuan </option >
                <option value = "tumaga" > Tumaga </option >
                <option value = "vitali" > Vitali </option >
                <option value = "zambowood" > Zambowood </option >
                <option value = "bungiao" > Bunguiao </option >
                <option value = "campo_islam_upper" > Campo Islam (Upper) </option >
                <option value = "campo_islam_lower" > Campo Islam (Lower) </option >
                <option value = "don_pablo_lorenzo_village" > Don Pablo Lorenzo Village </ option >
                <option value = "la_purisima" > La Purisima </option >
                <option value = "lumbangan" > Lumbangan </option >
                <option value = "mampang" > Mampang </option >
                <option value = "muti_upper" > Muti (Upper) </option >
                <option value = "putik_lower" > Putik (Lower) </option >
                <option value = "san_jose_upper" > San Jose (Upper) </option >
                <option value = "san_jose_lower" > San Jose (Lower) </option >
                <option value = "san_juan" > San Juan </option >
                <option value = "san_pedro" > San Pedro </option >
                <option value = "san_roque" > San Roque </option >
                <option value = "santa_catalina" > Santa Catalina </option >
                <option value = "santa_cruz" > Santa Cruz </option >
                <option value = "santa_maria_upper" > Santa Maria (Upper) </option >
                <option value = "santa_maria_lower" > Santa Maria (Lower) </option >
                <option value = "santo_nino_upper" > Santo Niño (Upper) </option >
                <option value = "santo_nino_lower" > Santo Niño (Lower) </option >
                <option value = "sinunuc_upper" > Sinunuc (Upper) </option >
                <option value = "sinunuc_lower" > Sinunuc (Lower) </option >
                <option value = "tictapul" > Tictapul </option >
                <option value = "tigbalabag" > Tigbalabag </option >
                <option value = "tugbungan" > Tugbungan </option >
                <option value = "tumitus" > Tumitus </option >
                <option value = "victoria" > Victoria </options >
          </select >
        </div>

        


        <p style="font-size: 12px;">Already have an account? <a href="login.php" style="font-size: 12px;">Login here!</a>.</p>
        <div id="submit">
            <input type="submit" class="submit-button">
        </div>
</div>
</body>

</html>
