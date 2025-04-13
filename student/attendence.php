<?php
date_default_timezone_set('Asia/Kolkata'); // Set your appropriate timezone

include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

// Get current day and time in proper format
$currentDay = date('l'); // Full day name (Monday-Sunday)
$currentTime = date('H:i:s'); // 24-hour format with seconds (00:00:00)
$currentDateTime = date('Y-m-d H:i:s');

// Verify student session
if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit();
}

// Get student info with prepared statement
$studentQuery = "SELECT student_course, student_first_name, student_last_name 
                FROM tbl_students 
                WHERE student_id = ?";
$stmt = $conn->prepare($studentQuery);
$stmt->bind_param("i", $_SESSION["student_id"]);
$stmt->execute();
$studentResult = $stmt->get_result();

if ($studentResult->num_rows === 0) {
    die("Student record not found");
}

$studentData = $studentResult->fetch_assoc();
$course_id = $studentData['student_course'];
$studentName = htmlspecialchars($studentData['student_first_name'] . ' ' . $studentData['student_last_name']);

// Get current period with 5 minute buffer
$timeBuffer = 300; // 5 minutes in seconds
$currentTimeWithBuffer = date('H:i:s', strtotime($currentTime) + $timeBuffer);
$currentTimeMinusBuffer = date('H:i:s', strtotime($currentTime) - $timeBuffer);

$timetableQuery = "SELECT r.*, s.subject_name, s.subject_code, f.faculty_name 
                  FROM tbl_regular_time_table r
                  JOIN tbl_subjects s ON r.subject_id = s.subject_id
                  JOIN tbl_faculty f ON r.faculty_id = f.faculty_id
                  WHERE r.course_id = ?
                  AND r.period_day = ?
                  AND r.period_start_time <= ?
                  AND r.period_end_time >= ?";
$stmt = $conn->prepare($timetableQuery);
$stmt->bind_param("isss", $course_id, $currentDay, $currentTimeWithBuffer, $currentTimeMinusBuffer);
$stmt->execute();
$timetableResult = $stmt->get_result();
$currentPeriod = $timetableResult->fetch_assoc();

// Check attendance for current period
$attendanceRecord = null;
if ($currentPeriod) {
    $attendanceQuery = "SELECT * FROM tbl_attendance 
                       WHERE attendance_student_id = ? 
                       AND DATE(attendance_date) = CURDATE()
                       AND period_id = ?";
    $stmt = $conn->prepare($attendanceQuery);
    $stmt->bind_param("ii", $_SESSION["student_id"], $currentPeriod['regular_time_table_id']);
    $stmt->execute();
    $attendanceRecord = $stmt->get_result()->fetch_assoc();
}
?>

<div class="content-wrapper p-2">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header p-3 bg-primary text-white">
                <h2 class="font-weight-bold text-center">Subject-wise Attendance</h2>
                <h5 class="text-center">Welcome, <?= $studentName ?></h5>
                <div class="text-center">
                    <span class="badge badge-light">
                        <i class="fas fa-clock"></i> Current Time: <?= date("h:i:A") ?>
                    </span>
                </div>
            </div>

            <div class="card-body">
                <!-- In your PHP file (after the "No class scheduled" check) -->
                <?php if ($currentPeriod && !$attendanceRecord): ?>
                    <div id="attendanceForm">
                        <!-- Camera Activation -->
                        <div class="text-center mb-4">
                            <button class="btn btn-lg btn-success shadow" id="startCameraButton">
                                <i class="fas fa-camera"></i> Start Camera
                            </button>
                        </div>

                        <!-- Camera Preview -->
                        <div class="row justify-content-center mb-4">
                            <div class="col-md-6 text-center">
                                <video id="video" width="100%" height="auto" autoplay playsinline style="display:none; max-width: 500px; background: #000;"></video>
                            </div>
                        </div>

                        <!-- Capture Button -->
                        <div class="text-center mb-4">
                            <button id="captureButton" class="btn btn-lg btn-primary shadow" style="display:none;">
                                <i class="fas fa-camera-retro"></i> Capture Photo
                            </button>
                        </div>

                        <!-- Preview Canvas (hidden) -->
                        <canvas id="canvas" width="640" height="480" style="display:none;"></canvas>

                        <!-- Captured Image Display -->
                        <div class="row justify-content-center mb-4">
                            <div class="col-md-6 text-center">
                                <img id="capturedImage" style="display:none; max-width: 100%;" class="img-thumbnail" />
                            </div>
                        </div>

                        <!-- Hidden Fields -->
                        <input type="hidden" name="photoData" id="photoData">
                        <input type="hidden" name="period_id" id="period_id" value="<?= $currentPeriod['regular_time_table_id'] ?>">

                        <!-- Location Verification -->
                        <div id="locationVerification" class="alert alert-info" style="display:none;">
                            <h5><i class="fas fa-map-marker-alt"></i> Location Verified</h5>
                            <div id="locationDetails"></div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center">
                            <button id="submitAttendance" class="btn btn-lg btn-success shadow" style="display:none;">
                                <i class="fas fa-check"></i> Submit Attendance
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($currentPeriod): ?>
                    <!-- Current Class Panel -->
                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Subject:</strong> <?= htmlspecialchars($currentPeriod['subject_name']) ?></p>
                                <p><strong>Code:</strong> <?= htmlspecialchars($currentPeriod['subject_code']) ?></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Faculty:</strong> <?= htmlspecialchars($currentPeriod['faculty_name']) ?></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Time:</strong> <?= $currentPeriod['period_start_time'] ?> - <?= $currentPeriod['period_end_time'] ?></p>
                                <p><strong>Duration:</strong>
                                    <?php
                                    $start = strtotime($currentPeriod['period_start_time']);
                                    $end = strtotime($currentPeriod['period_end_time']);
                                    echo gmdate("H:i:s", $end - $start);
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <?php if ($attendanceRecord): ?>
                        <!-- Already Marked Attendance -->
                        <div class="alert alert-success">
                            <div class="row">
                                <div class="col-md-6 text-center">
                                    <img src="<?= $base_url ?>assets/images/studentattendence/<?= htmlspecialchars($attendanceRecord['attendance_photo']) ?>"
                                        class="img-thumbnail" style="max-height: 300px;">
                                </div>
                                <div class="col-md-6">
                                    <h4><i class="fas fa-check-circle"></i> Attendance Recorded</h4>
                                    <p><strong>Time:</strong> <?= date("H:i:s", strtotime($attendanceRecord['attendance_date'])) ?></p>
                                    <p><strong>Location:</strong>
                                        <?= $attendanceRecord['attendance_latitude'] ?>, <?= $attendanceRecord['attendance_longitude'] ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Attendance Form -->
                        <div id="attendanceForm">
                            <!-- Camera and capture interface remains the same -->
                            <!-- ... -->
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <!-- No Class Scheduled -->
                    <div class="alert alert-warning">
                        <h4><i class="fas fa-calendar-times"></i> No Class Scheduled</h4>
                        <p>There are no classes scheduled at the current time (<?= date("H:i:s") ?>).</p>

                        <?php
                        // Show next class if available
                        $nextClassQuery = "SELECT r.*, s.subject_name 
                                         FROM tbl_regular_time_table r
                                         JOIN tbl_subjects s ON r.subject_id = s.subject_id
                                         WHERE r.course_id = ?
                                         AND r.period_day = ?
                                         AND r.period_start_time > ?
                                         ORDER BY r.period_start_time ASC
                                         LIMIT 1";
                        $stmt = $conn->prepare($nextClassQuery);
                        $stmt->bind_param("iss", $course_id, $currentDay, $currentTime);
                        $stmt->execute();
                        $nextClass = $stmt->get_result()->fetch_assoc();

                        if ($nextClass): ?>
                            <div class="mt-3">
                                <p><strong>Next Class:</strong> <?= $nextClass['subject_name'] ?> at <?= $nextClass['period_start_time'] ?></p>
                                <?php
                                $nextClassTime = strtotime($nextClass['period_start_time']);
                                $currentTime = strtotime(date('H:i:s'));
                                $timeDiff = $nextClassTime - $currentTime;
                                ?>
                                <p><strong>Time Remaining:</strong> <?= gmdate("H:i:s", $timeDiff) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Today's Timetable -->
        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                <h3 class="card-title"><i class="fas fa-calendar-day"></i> Today's Schedule (<?= $currentDay ?>)</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>Time</th>
                                <th>Duration</th>
                                <th>Subject</th>
                                <th>Code</th>
                                <th>Faculty</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $timetableQuery = "SELECT r.*, s.subject_name, s.subject_code, f.faculty_name 
                                             FROM tbl_regular_time_table r
                                             JOIN tbl_subjects s ON r.subject_id = s.subject_id
                                             JOIN tbl_faculty f ON r.faculty_id = f.faculty_id
                                             WHERE r.course_id = ?
                                             AND r.period_day = ?
                                             ORDER BY r.period_start_time";
                            $stmt = $conn->prepare($timetableQuery);
                            $stmt->bind_param("is", $course_id, $currentDay);
                            $stmt->execute();
                            $timetableResult = $stmt->get_result();

                            if ($timetableResult->num_rows > 0):
                                while ($period = $timetableResult->fetch_assoc()):
                                    $startTime = strtotime($period['period_start_time']);
                                    $endTime = strtotime($period['period_end_time']);
                                    $duration = $endTime - $startTime;

                                    // Check attendance status
                                    $attendanceCheck = "SELECT 1 FROM tbl_attendance 
                                                      WHERE attendance_student_id = ? 
                                                      AND DATE(attendance_date) = CURDATE()
                                                      AND period_id = ?";
                                    $stmt = $conn->prepare($attendanceCheck);
                                    $stmt->bind_param("ii", $_SESSION["student_id"], $period['regular_time_table_id']);
                                    $stmt->execute();
                                    $hasAttendance = $stmt->get_result()->num_rows > 0;

                                    $isCurrent = ($currentTime >= $period['period_start_time'] && $currentTime <= $period['period_end_time']);
                                    $isPast = ($currentTime > $period['period_end_time']);
                            ?>
                                    <tr class="<?= $isCurrent ? 'table-primary' : '' ?>">
                                        <td>
                                            <?= date("h:i A", strtotime($period['period_start_time'])) ?> -
                                            <?= date("h:i A", strtotime($period['period_end_time'])) ?>
                                        </td>
                                        <td>

                                            <?php
                                            $hours = floor($duration / 3600);
                                            $minutes = floor(($duration % 3600) / 60);

                                            if ($hours > 0) {
                                                echo $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ' . $minutes . ' minute' . ($minutes != 1 ? 's' : '');
                                            } else {
                                                echo $minutes . ' minute' . ($minutes != 1 ? 's' : '');
                                            }
                                            ?>
                                        </td>
                                        <td><?= htmlspecialchars($period['subject_name']) ?></td>
                                        <td><?= htmlspecialchars($period['subject_code']) ?></td>
                                        <td><?= htmlspecialchars($period['faculty_name']) ?></td>
                                        <td>
                                            <?php if ($hasAttendance): ?>
                                                <span class="badge badge-success">Present</span>
                                            <?php elseif ($isPast): ?>
                                                <span class="badge badge-danger">Absent</span>
                                            <?php else: ?>
                                                <span class="badge badge-warning">Upcoming</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php
                                endwhile;
                            else:
                                ?>
                                <tr>
                                    <td colspan="6" class="text-center">No classes scheduled for today</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Enhanced JavaScript with better error handling and user feedback
    document.addEventListener('DOMContentLoaded', function() {
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const capturedImage = document.getElementById('capturedImage');
        const startBtn = document.getElementById('startCameraButton');
        const captureBtn = document.getElementById('captureButton');
        const submitBtn = document.getElementById('submitAttendance');
        const locationInfo = document.getElementById('locationInfo');
        const locationDetails = document.getElementById('locationDetails');
        const locationStatus = document.getElementById('locationStatus');
        const cameraStatus = document.getElementById('cameraStatus');

        let stream = null;
        let latitude = "";
        let longitude = "";
        let placeName = "";
        let photoData = "";

        // Update time display every second
        function updateClock() {
            const now = new Date();
            document.getElementById('todayDate').innerText = "Current Time: " + now.toLocaleTimeString();
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Initialize camera
        async function startCamera() {
            try {
                cameraStatus.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Initializing camera...';
                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        width: {
                            ideal: 1280
                        },
                        height: {
                            ideal: 720
                        },
                        facingMode: 'user'
                    },
                    audio: false
                });

                video.srcObject = stream;
                video.style.display = 'block';
                startBtn.style.display = 'none';
                captureBtn.style.display = 'inline-block';
                cameraStatus.innerHTML = '<i class="fas fa-check-circle text-success"></i> Camera ready';

                // Auto-focus after 1 second
                setTimeout(() => {
                    video.focus();
                }, 1000);
            } catch (error) {
                console.error("Camera error:", error);
                cameraStatus.innerHTML = `<i class="fas fa-times-circle text-danger"></i> Camera error: ${error.message}`;
                alert(`Camera access failed: ${error.message}`);
            }
        }

        // Capture photo
        function capturePhoto() {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

            photoData = canvas.toDataURL('image/jpeg', 0.8);
            document.getElementById('photoData').value = photoData;
            capturedImage.src = photoData;
            capturedImage.style.display = 'block';
            video.style.display = 'none';
            captureBtn.style.display = 'none';

            // Stop camera
            stopCamera();

            // Show submit button if location is available
            if (latitude && longitude) {
                submitBtn.style.display = 'inline-block';
            }
        }

        // Stop camera
        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                video.srcObject = null;
            }
        }

        // Get location with detailed status
        function getLocation() {
            if (!navigator.geolocation) {
                locationStatus.innerHTML = '<i class="fas fa-times-circle text-danger"></i> Geolocation not supported';
                document.getElementById('locationError').style.display = 'block';
                return;
            }

            locationStatus.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Getting location...';

            navigator.geolocation.getCurrentPosition(
                position => {
                    latitude = position.coords.latitude.toFixed(6);
                    longitude = position.coords.longitude.toFixed(6);

                    locationStatus.innerHTML = `<i class="fas fa-check-circle text-success"></i> Location acquired (${latitude}, ${longitude})`;
                    document.getElementById('locationError').style.display = 'none';

                    // Show location info
                    locationInfo.style.display = 'block';
                    locationDetails.innerHTML = `
                    <p><strong>Coordinates:</strong> ${latitude}, ${longitude}</p>
                    <p><strong>Accuracy:</strong> ${position.coords.accuracy} meters</p>
                `;

                    // Get place name
                    getPlaceName(latitude, longitude);

                    // Enable submit if photo is captured
                    if (photoData) {
                        submitBtn.style.display = 'inline-block';
                    }
                },
                error => {
                    console.error("Geolocation error:", error);
                    let errorMsg = "Location access denied";
                    if (error.code === error.TIMEOUT) errorMsg = "Location request timed out";
                    if (error.code === error.POSITION_UNAVAILABLE) errorMsg = "Location unavailable";

                    locationStatus.innerHTML = `<i class="fas fa-times-circle text-danger"></i> ${errorMsg}`;
                    document.getElementById('locationError').style.display = 'block';
                    document.getElementById('locationError').innerHTML = `
                    <i class="fas fa-exclamation-triangle"></i> ${errorMsg}. Please enable location services.
                `;
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }

        // Get place name from coordinates
        async function getPlaceName(lat, lon) {
            try {
                const response = await fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${lat}&longitude=${lon}&localityLanguage=en`);
                const data = await response.json();

                placeName = data.locality || data.principalSubdivision || "Unknown location";
                locationDetails.innerHTML += `<p><strong>Location:</strong> ${placeName}</p>`;
            } catch (error) {
                console.error("Geocode error:", error);
                locationDetails.innerHTML += `<p><strong>Location:</strong> Could not determine location name</p>`;
            }
        }

        // Submit attendance
        async function submitAttendance() {
            if (!photoData) {
                alert("Please capture your photo first");
                return;
            }

            if (!latitude || !longitude) {
                alert("Location data is missing");
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

            try {
                const period_id = document.getElementById('period_id').value;
                const subject_id = document.getElementById('subject_id').value;

                // Convert data URL to blob
                const blob = await fetch(photoData).then(res => res.blob());

                const formData = new FormData();
                formData.append("photoData", blob, "attendance.jpg");
                formData.append("latitude", latitude);
                formData.append("longitude", longitude);
                formData.append("period_id", period_id);
                formData.append("subject_id", subject_id);

                const response = await fetch("submit_attendance.php", {
                    method: "POST",
                    body: formData
                });

                const result = await response.json();

                if (result.status === "success") {
                    alert("Attendance submitted successfully!");
                    location.reload();
                } else {
                    throw new Error(result.message || "Submission failed");
                }
            } catch (error) {
                console.error("Submission error:", error);
                alert(`Error: ${error.message}`);
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check"></i> Submit Attendance';
            }
        }

        // Event listeners
        startBtn.addEventListener('click', startCamera);
        captureBtn.addEventListener('click', capturePhoto);
        submitBtn.addEventListener('click', submitAttendance);

        // Initialize location check
        getLocation();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const capturedImage = document.getElementById('capturedImage');
        const startBtn = document.getElementById('startCameraButton');
        const captureBtn = document.getElementById('captureButton');
        const submitBtn = document.getElementById('submitAttendance');
        const photoData = document.getElementById('photoData');
        const locationDetails = document.getElementById('locationDetails');

        // State variables
        let stream = null;
        let latitude = null;
        let longitude = null;

        // 1. Start Camera
        startBtn.addEventListener('click', async function() {
            try {
                // Request camera access
                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        width: {
                            ideal: 1280
                        },
                        height: {
                            ideal: 720
                        },
                        facingMode: 'user' // Front camera
                    },
                    audio: false
                });

                // Display camera stream
                video.srcObject = stream;
                video.style.display = 'block';
                startBtn.style.display = 'none';
                captureBtn.style.display = 'inline-block';

                // Get location simultaneously
                getLocation();

            } catch (error) {
                console.error("Camera error:", error);
                alert(`Camera access failed: ${error.message}`);
            }
        });

        // 2. Capture Photo
        captureBtn.addEventListener('click', function() {
            // Set canvas dimensions to match video
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            // Draw video frame to canvas
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Convert to data URL
            const imageData = canvas.toDataURL('image/jpeg', 0.85);
            photoData.value = imageData;

            // Display captured image
            capturedImage.src = imageData;
            capturedImage.style.display = 'block';

            // Hide video and capture button
            video.style.display = 'none';
            captureBtn.style.display = 'none';

            // Stop camera stream
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }

            // Show submit button if location is available
            if (latitude && longitude) {
                submitBtn.style.display = 'inline-block';
            }
        });

        // 3. Get Location
        function getLocation() {
            if (!navigator.geolocation) {
                alert("Geolocation is not supported by your browser");
                return;
            }

            navigator.geolocation.getCurrentPosition(
                position => {
                    latitude = position.coords.latitude;
                    longitude = position.coords.longitude;

                    // Display location verification
                    document.getElementById('locationVerification').style.display = 'block';
                    locationDetails.innerHTML = `
                    <p><strong>Coordinates:</strong> 
                        <a href="https://maps.google.com/?q=${latitude},${longitude}" target="_blank">
                            ${latitude.toFixed(6)}, ${longitude.toFixed(6)}
                        </a>
                    </p>
                    <p><strong>Accuracy:</strong> ${position.coords.accuracy} meters</p>
                `;

                    // Enable submit if photo is captured
                    if (photoData.value) {
                        submitBtn.style.display = 'inline-block';
                    }
                },
                error => {
                    console.error("Geolocation error:", error);
                    alert(`Location access is required for attendance. Error: ${error.message}`);
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }

        // 4. Submit Attendance
        submitBtn.addEventListener('click', async function() {
            if (!photoData.value) {
                alert("Please capture your photo first");
                return;
            }

            if (!latitude || !longitude) {
                alert("Location data is missing");
                return;
            }

            // Disable button during submission
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

            try {
                // Convert data URL to blob
                const blob = await fetch(photoData.value).then(res => res.blob());

                // Create form data
                const formData = new FormData();
                formData.append("photo", blob, "attendance.jpg");
                formData.append("latitude", latitude);
                formData.append("longitude", longitude);
                formData.append("period_id", document.getElementById('period_id').value);

                // Submit to server
                const response = await fetch("submit_attendance.php", {
                    method: "POST",
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    alert("Attendance submitted successfully!");
                    location.reload();
                } else {
                    throw new Error(result.message || "Attendance submission failed");
                }
            } catch (error) {
                console.error("Submission error:", error);
                alert(`Error: ${error.message}`);
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check"></i> Submit Attendance';
            }
        });
    });
</script>
<?php
include "../component/footer.php";
?>