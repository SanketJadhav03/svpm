<?php
$title = "Department Dashboard";
include "../config/connection.php";
include("../component/header.php");
include("../component/sidebar.php");
?>
<style>
    .notices-marquee {
        height: 330px;
        overflow: hidden;
        position: relative;
        background-color: #f1f1f1;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 10px;
    }

    .notice-item {
        margin-bottom: 20px;
        display: block;
        padding: 10px;
        border-bottom: 1px solid #ddd;
        background-color: #ffffff;
        border-radius: 4px;
        transition: transform 0.2s ease;
        font-size: 14px;
        /* Initial font size */
    }

    .notice-item:hover {
        transform: scale(1.1);
        /* Slightly enlarge on hover */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        /* Darker shadow on hover */
        font-size: 16px;
        /* Increase font size on hover */
    }

    /* New styles for the sliding animation */
    @keyframes scroll-vertical {
        0% {
            transform: translateY(100%);
        }

        100% {
            transform: translateY(-100%);
        }
    }

    .notices-content {
        display: flex;
        flex-direction: column;
        animation: scroll-vertical 15s linear infinite;
        animation-play-state: running;
        /* Default state is running */
    }

    .notices-content:hover {
        animation-play-state: paused;
        padding: 10px;
        /* Pause animation on hover */
    }
</style>
<div class="content-wrapper ">
    <div class="p-2 container-fluid">
        <div class="card ">
            <div class="card-body">
                <h2 class="font-weight-bold">Department Dashboard</h2>
            </div>
        </div>
        <?php
        $notices = [];
        $query = "SELECT * FROM `tbl_notices` WHERE `notice_status` = 1 ORDER BY `notice_id` DESC LIMIT 9"; // Only fetch active notices
        $noticesresult = mysqli_query($conn, $query);

        if ($noticesresult) {
            while ($row = mysqli_fetch_assoc($noticesresult)) {
                $notices[] = $row; // Store each notice in the array
            }
        }
        ?>
        <div class="card ">
            <div class="card-header">
                <h3 class="card-title">Notices</h3>
                <div class="card-tools">

                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>

                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="notices-marquee">
                    <div class="notices-content">
                        <div class="row">
                            <?php foreach ($notices as $notice): ?>
                                <div class="col-md-4">
                                    <span class="notice-item">
                                        <i class="fas fa-thumbtack"></i>&nbsp;
                                        <b><?= htmlspecialchars($notice['notice_title']) ?></b>
                                        <div class="notice-date"> - <?= date('F j, Y g:i A', strtotime($notice['notice_date'])) ?></div>
                                        <div> - <?= htmlspecialchars($notice['notice_description']) ?></div>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- /.card-body -->

        </div>
        
    </div>
</div>
<script>
    window.onload = function() {
        const marquee = document.querySelector('.notices-content');
        const speed = 30000; // Speed of the animation (higher value = slower)
        marquee.style.animationDuration = `${speed / 1000}s`;
    };
</script>
<?php
include "../component/footer.php";
?>