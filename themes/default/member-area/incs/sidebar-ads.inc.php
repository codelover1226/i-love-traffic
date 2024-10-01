<?php ?>
<div class="col-xl-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="badge bg-danger">Text Ad</span><br>
                            <?= $textAdController->getTextAd() ?>
                        </div>
                        <div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                    <i data-feather="file-text" class="text-info"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="badge bg-danger">Text Ad</span><br>
                            <?= $textAdController->getTextAd() ?>
                        </div>
                        <div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                    <i data-feather="file-text" class="text-info"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="badge bg-dark" style="font-size: 14px">Admin Recommended Site</span><br><br>
                            <?= $adminAdsController->showAd() ?>
                        </div>
                        <div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                    <i data-feather="file-text" class="image"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="badge bg-dark" style="font-size: 14px">125x125 Banner Ad</span><br><br>
                            <?= $smallBannerAdController->getBannerAd() ?>
                            <?= $smallBannerAdController->getBannerAd() ?>
                        </div>
                        <div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                    <i data-feather="image" class="image"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="badge bg-dark" style="font-size: 14px">160x600 Banner Ad</span><br><br>
                            <?= $bannerAd160600Controller->getBannerAd() ?>
                            <!-- <?= $bannerAd160600Controller->getBannerAd() ?> -->
                        </div>
                        <div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                    <i data-feather="image" class="image"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>