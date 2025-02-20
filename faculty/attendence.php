<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

$attendanceQuery = "SELECT * 
                    FROM tbl_faculty_attendance  
                    WHERE attendance_faculty_id = ? 
                    AND DATE(attendance_date) = CURDATE()";


$stmt = $conn->prepare($attendanceQuery);
$attendance_faculty_id = $_SESSION["faculty_id"];
$stmt->bind_param("i", $attendance_faculty_id);
$stmt->execute();
$result = $stmt->get_result();
$attendanceRecord = $result->fetch_assoc();
?>

<div class="content-wrapper p-2">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header p-3">
                <h2 class="font-weight-bold text-center">Attendance with Live Image Capture</h2>
            </div>
            <div class="card-body">
                <?php if ($attendanceRecord): ?>
                    <div class="text-center mt-4">
                        <h3>Today's Attendance Record:</h3>

                        <img src="<?= $base_url . "assets/images/facultyattendence/" . $attendanceRecord['attendance_photo'] ?>" height="350" width="450">

                        <p>Coordinates: <?= $attendanceRecord['attendance_latitude'] ?>, <?= $attendanceRecord['attendance_longitude'] ?></p>
                        <p>Date & Time: <?= date("d/m/Y h:i:s A", strtotime($attendanceRecord['attendance_date'])) ?></p>

                    </div>
                <?php else: ?>
                    <div class="text-center mb-4">
                        <button class="btn btn-outline-success shadow" id="startCameraButton">Start Camera</button>
                    </div>
                    <div class="text-center d-flex justify-content-center mb-4">
                        <video id="video" width="320" height="240" autoplay style="display:none;"></video>
                    </div>
                    <div class="text-center mb-4">
                        <button id="captureButton" class="btn shadow btn-primary" style="display:none;">Capture Photo</button>
                    </div>
                    <div class="d-flex justify-content-center">
                        <canvas id="canvas" width="320" height="240" style="display:none;"></canvas>
                    </div>
                    <div class="text-center mb-4">
                        <img id="capturedImage" style="display:none; margin: auto;" width="320" height="240" class="img-fluid" />
                    </div>
                    <input type="hidden" name="photoData" id="photoData">
                    <div id="locationError" class="text-danger" style="display: none;">Please allow location access to mark attendance.</div>
                    <div id="todayDate" class="text-center mb-4 font-weight-bold"></div>
                    <div id="locationInfo" class="text-center mb-4"></div>
                    <div class="text-center">
                        <button id="submitAttendance" class="btn btn-success shadow" style="display:none;">Submit Attendance</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    var video = document.getElementById('video');
    var canvas = document.getElementById('canvas');
    var capturedImage = document.getElementById('capturedImage');
    var stream = null; // To store the camera stream object
    var latitude = "";
    var longitude = "";
    var placeName = "";

    document.getElementById('todayDate').innerText = "Today's Date: " + new Date().toLocaleDateString() + " " + new Date().toLocaleTimeString();

    function startCamera() {
        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then(function(localStream) {
                stream = localStream;
                video.srcObject = stream;
                video.style.display = 'block';
                document.getElementById('captureButton').style.display = 'inline';
                document.getElementById('startCameraButton').style.display = 'none';
            })
            .catch(function(error) {
                alert('Unable to access the camera: ' + error.message);
            });
    }

    function capturePhoto() {
        var photoData = document.getElementById('photoData');
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
        var dataURL = canvas.toDataURL('image/png');
        photoData.value = dataURL;
        capturedImage.src = dataURL;
        capturedImage.style.display = 'block';
        video.style.display = 'none';
        document.getElementById('captureButton').style.display = 'none';
        document.getElementById('submitAttendance').style.display = 'inline-block';
        stopCamera();
    }

    function stopCamera() {
        if (stream) {
            let tracks = stream.getTracks();
            tracks.forEach(track => track.stop());
            video.srcObject = null;
        }
    }

    function submitAttendance() {
        var photoData = document.getElementById('photoData').value;

        if (!photoData) {
            alert("Please capture your photo before submitting.");
            return;
        }

        var canvas = document.getElementById('canvas');
        canvas.toBlob(function(blob) {
            var formData = new FormData();
            formData.append("photoData", blob);
            formData.append("latitude", latitude);
            formData.append("longitude", longitude);

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "submit_attendance.php", true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert("Attendance submitted successfully!");
                    location.reload();
                } else {
                    alert("Error submitting attendance: " + xhr.statusText);
                }
            };

            xhr.send(formData);
        }, 'image/png');
    }

    document.getElementById('startCameraButton').addEventListener('click', function() {
        startCamera();
    });

    document.getElementById('captureButton').addEventListener('click', function() {
        capturePhoto();
    });

    document.getElementById('submitAttendance').addEventListener('click', function() {
        submitAttendance();
    });

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                latitude = position.coords.latitude;
                longitude = position.coords.longitude;
                document.getElementById('locationInfo').innerText = "Coordinates: " + latitude + ", " + longitude;
                getPlaceName(latitude, longitude);
            }, function(error) {
                document.getElementById('locationError').style.display = 'block';
                alert('Error fetching location: ' + error.message);
            });
        } else {
            document.getElementById('locationError').style.display = 'block';
            alert("Geolocation is not supported by this browser.");
        }
    }

    function getPlaceName(lat, lon) {
        fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${lat}&longitude=${lon}&localityLanguage=en`)
            .then(response => response.json())
            .then(data => {
                if (data.locality) {
                    placeName = data.locality;
                    document.getElementById('locationInfo').innerText += "\nLocation: " + placeName;
                } else {
                    document.getElementById('locationInfo').innerText += "\nLocation: Not available";
                }
            })
            .catch(error => console.error('Error fetching place name:', error));
    }

    window.onload = function() {
        getLocation();
    };
</script>

<?php
include "../component/footer.php";
?>