<?php $conn = new mysqli("localhost", "root", "", "vorsha_ngo");
if ($conn->connect_error) {
    showPopup("Database connection failed.");
}
