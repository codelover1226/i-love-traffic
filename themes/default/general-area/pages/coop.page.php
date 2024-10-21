<?php 

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . 
        "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; 
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage),basename(__FILE__)) == 0) { 
    http_response_code(404); 
    die(""); 
} 
require_once "themes/default/general-area/incs/header.coop.inc.php"; 
$coopUrls = $coopUrlsController->lastCoopUrlsList(15);
// echo json_encode($coopUrls);
?>
<div class="col-lg-12">
    <div class="card border border-primary">
        <table class="table table-striped">
            <thead>
                <td>Link</td>
                <td>Credits</td>
                <td>Views</td>
                <td>Clicks</td>
                <td>Status</td>
            </thead>
            <tbody>
                <?php if (!empty($coopUrls)) : ?>
                    <?php foreach ($coopUrls as $coopUrl) {?>
                        <tr>
                            <td><?php echo $coopUrl['ad_link'];?></td>
                            <td><?php echo $coopUrl['credits'];?></td>
                            <td><?php echo $coopUrl['total_views'];?></td>
                            <td><?php echo $coopUrl['total_clicks'];?></td>
                            <td><?php echo $coopUrl['status'];?></td>
                        </tr>
                    <?php }?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<!--============= Team Section Starts Here =============-->
<section style="display: flex; justify-content: center; margin: 30px 0;">

    <div
        style="display: flex; flex-direction: column; align-items: center; margin-right: 20px;">
        <div
            style="width: 200px; height: 200px; border: 2px solid blue; border-radius: 50%; text-align: center; padding: 20px;">
            <img
                src="themes/default/general-area/assets/images/team/Clare.jpg"
                alt="Image 1"
                style="width: 100%; height: auto; max-width: 100%; max-height: 100%; border-radius: 50%;">
        </div>
        <p style="color: blue; margin: 10px 0; text-align: center;">Clare Bowen</p>
        <p style="color: blue; margin: 10px 0; text-align: center;">Owner/Admin</p>
    </div>
    <div style="display: flex; flex-direction: column; align-items: center;">
        <div
            style="width: 200px; height: 200px; border: 2px solid blue; border-radius: 50%; text-align: center; padding: 20px;">
            <img
                src="themes/default/general-area/assets/images/team/8ff4947ffe5c8932cff8c4c61e96890b.png"
                alt="Image 2"
                style="width: 100%; height: auto; max-width: 100%; max-height: 100%; border-radius: 50%;">
        </div>
        <p style="color: blue; margin: 10px 0; text-align: center;">Brenton Senegal</p>
        <p style="color: blue; margin: 10px 0; text-align: center;">Owner/Admin</p>
    </div>
</section>
<!--============= Team Section Ends Here =============-->

<?php require_once "themes/default/general-area/incs/footer.inc.php"; ?>