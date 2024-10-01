<?php



$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
?>

<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <script>
                    document.write(new Date().getFullYear())
                </script> © <?= $siteSettingsData["site_title"] ?>.
            </div>
            <div class="col-sm-6">
                <div class="text-sm-end d-none d-sm-block">
                    
                </div>
            </div>
        </div>
    </div>
</footer>
</div>
</div>



<button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
    <i class="ri-arrow-up-line"></i>
</button>

<div id="preloader">
    <div id="status">
        <div class="spinner-border text-primary avatar-sm" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>


<div class="offcanvas offcanvas-end border-0" tabindex="-1" id="theme-settings-offcanvas">
    <div class="offcanvas-body p-0">
        <div data-simplebar class="h-100">
            <div class="colorscheme-cardradio">
                <div class="row">
                    <div class="col-4">
                        <div class="form-check card-radio">
                            <input class="form-check-input" type="radio" name="data-bs-theme" id="layout-mode-light" value="light">
                            <label class="form-check-label p-0 avatar-md w-100" for="layout-mode-light">
                                <span class="d-flex gap-1 h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                            <span class="d-block p-1 px-2 bg-primary-subtle rounded mb-2"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                            <span class="bg-light d-block p-1 mt-auto"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Light</h5>
                    </div>

                    <div class="col-4">
                        <div class="form-check card-radio dark">
                            <input class="form-check-input" type="radio" name="data-bs-theme" id="layout-mode-dark" value="dark">
                            <label class="form-check-label p-0 avatar-md w-100 bg-dark" for="layout-mode-dark">
                                <span class="d-flex gap-1 h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-white bg-opacity-10 d-flex h-100 flex-column gap-1 p-1">
                                            <span class="d-block p-1 px-2 bg-white bg-opacity-10 rounded mb-2"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-white bg-opacity-10"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-white bg-opacity-10"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-white bg-opacity-10"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-white bg-opacity-10 d-block p-1"></span>
                                            <span class="bg-white bg-opacity-10 d-block p-1 mt-auto"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Dark</h5>
                    </div>
                </div>
            </div>

            <div id="sidebar-visibility">
                <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Sidebar Visibility</h6>
                <p class="text-muted">Choose show or Hidden sidebar.</p>

                <div class="row">
                    <div class="col-4">
                        <div class="form-check card-radio">
                            <input class="form-check-input" type="radio" name="data-sidebar-visibility" id="sidebar-visibility-show" value="show">
                            <label class="form-check-label p-0 avatar-md w-100" for="sidebar-visibility-show">
                                <span class="d-flex gap-1 h-100">
                                    <span class="flex-shrink-0 p-1">
                                        <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                            <span class="d-block p-1 px-2 bg-primary-subtle rounded mb-2"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column pt-1 pe-2">
                                            <span class="bg-light d-block p-1"></span>
                                            <span class="bg-light d-block p-1 mt-auto"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Show</h5>
                    </div>
                    <div class="col-4">
                        <div class="form-check card-radio">
                            <input class="form-check-input" type="radio" name="data-sidebar-visibility" id="sidebar-visibility-hidden" value="hidden">
                            <label class="form-check-label p-0 avatar-md w-100 px-2" for="sidebar-visibility-hidden">
                                <span class="d-flex gap-1 h-100">
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column pt-1 px-2">
                                            <span class="bg-light d-block p-1"></span>
                                            <span class="bg-light d-block p-1 mt-auto"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Hidden</h5>
                    </div>
                </div>
            </div>

            <div id="layout-width">
                <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Layout Width</h6>
                <p class="text-muted">Choose Fluid or Boxed layout.</p>

                <div class="row">
                    <div class="col-4">
                        <div class="form-check card-radio">
                            <input class="form-check-input" type="radio" name="data-layout-width" id="layout-width-fluid" value="fluid">
                            <label class="form-check-label p-0 avatar-md w-100" for="layout-width-fluid">
                                <span class="d-flex gap-1 h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                            <span class="d-block p-1 px-2 bg-primary-subtle rounded mb-2"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                            <span class="bg-light d-block p-1 mt-auto"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Fluid</h5>
                    </div>
                    <div class="col-4">
                        <div class="form-check card-radio">
                            <input class="form-check-input" type="radio" name="data-layout-width" id="layout-width-boxed" value="boxed">
                            <label class="form-check-label p-0 avatar-md w-100 px-2" for="layout-width-boxed">
                                <span class="d-flex gap-1 h-100 border-start border-end">
                                    <span class="flex-shrink-0">
                                        <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                            <span class="d-block p-1 px-2 bg-primary-subtle rounded mb-2"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                            <span class="bg-light d-block p-1 mt-auto"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Boxed</h5>
                    </div>
                </div>
            </div>

            <div id="layout-position">
                <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Layout Position</h6>
                <p class="text-muted">Choose Fixed or Scrollable Layout Position.</p>

                <div class="btn-group radio" role="group">
                    <input type="radio" class="btn-check" name="data-layout-position" id="layout-position-fixed" value="fixed">
                    <label class="btn btn-light w-sm" for="layout-position-fixed">Fixed</label>

                    <input type="radio" class="btn-check" name="data-layout-position" id="layout-position-scrollable" value="scrollable">
                    <label class="btn btn-light w-sm ms-0" for="layout-position-scrollable">Scrollable</label>
                </div>
            </div>
            <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Topbar Color</h6>
            <p class="text-muted">Choose Light or Dark Topbar Color.</p>

            <div class="row">
                <div class="col-4">
                    <div class="form-check card-radio">
                        <input class="form-check-input" type="radio" name="data-topbar" id="topbar-color-light" value="light">
                        <label class="form-check-label p-0 avatar-md w-100" for="topbar-color-light">
                            <span class="d-flex gap-1 h-100">
                                <span class="flex-shrink-0">
                                    <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                        <span class="d-block p-1 px-2 bg-primary-subtle rounded mb-2"></span>
                                        <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                        <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                        <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                    </span>
                                </span>
                                <span class="flex-grow-1">
                                    <span class="d-flex h-100 flex-column">
                                        <span class="bg-light d-block p-1"></span>
                                        <span class="bg-light d-block p-1 mt-auto"></span>
                                    </span>
                                </span>
                            </span>
                        </label>
                    </div>
                    <h5 class="fs-13 text-center mt-2">Light</h5>
                </div>
                <div class="col-4">
                    <div class="form-check card-radio">
                        <input class="form-check-input" type="radio" name="data-topbar" id="topbar-color-dark" value="dark">
                        <label class="form-check-label p-0 avatar-md w-100" for="topbar-color-dark">
                            <span class="d-flex gap-1 h-100">
                                <span class="flex-shrink-0">
                                    <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                        <span class="d-block p-1 px-2 bg-primary-subtle rounded mb-2"></span>
                                        <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                        <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                        <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                    </span>
                                </span>
                                <span class="flex-grow-1">
                                    <span class="d-flex h-100 flex-column">
                                        <span class="bg-primary d-block p-1"></span>
                                        <span class="bg-light d-block p-1 mt-auto"></span>
                                    </span>
                                </span>
                            </span>
                        </label>
                    </div>
                    <h5 class="fs-13 text-center mt-2">Dark</h5>
                </div>
            </div>

            <div id="sidebar-size">
                <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Sidebar Size</h6>
                <p class="text-muted">Choose a size of Sidebar.</p>

                <div class="row">
                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-sidebar-size" id="sidebar-size-default" value="lg">
                            <label class="form-check-label p-0 avatar-md w-100" for="sidebar-size-default">
                                <span class="d-flex gap-1 h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                            <span class="d-block p-1 px-2 bg-primary-subtle rounded mb-2"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                            <span class="bg-light d-block p-1 mt-auto"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Default</h5>
                    </div>

                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-sidebar-size" id="sidebar-size-compact" value="md">
                            <label class="form-check-label p-0 avatar-md w-100" for="sidebar-size-compact">
                                <span class="d-flex gap-1 h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                            <span class="d-block p-1 bg-primary-subtle rounded mb-2"></span>
                                            <span class="d-block p-1 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 pb-0 bg-primary-subtle"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                            <span class="bg-light d-block p-1 mt-auto"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Compact</h5>
                    </div>

                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-sidebar-size" id="sidebar-size-small" value="sm">
                            <label class="form-check-label p-0 avatar-md w-100" for="sidebar-size-small">
                                <span class="d-flex gap-1 h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-light d-flex h-100 flex-column gap-1">
                                            <span class="d-block p-1 bg-primary-subtle mb-2"></span>
                                            <span class="d-block p-1 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 pb-0 bg-primary-subtle"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                            <span class="bg-light d-block p-1 mt-auto"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Small (Icon View)</h5>
                    </div>

                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-sidebar-size" id="sidebar-size-small-hover" value="sm-hover">
                            <label class="form-check-label p-0 avatar-md w-100" for="sidebar-size-small-hover">
                                <span class="d-flex gap-1 h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-light d-flex h-100 flex-column gap-1">
                                            <span class="d-block p-1 bg-primary-subtle mb-2"></span>
                                            <span class="d-block p-1 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 pb-0 bg-primary-subtle"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                            <span class="bg-light d-block p-1 mt-auto"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Small Hover View</h5>
                    </div>
                </div>
            </div>

            <div id="sidebar-view">
                <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Sidebar View</h6>
                <p class="text-muted">Choose Default or Detached Sidebar view.</p>

                <div class="row">
                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-layout-style" id="sidebar-view-default" value="default">
                            <label class="form-check-label p-0 avatar-md w-100" for="sidebar-view-default">
                                <span class="d-flex gap-1 h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                            <span class="d-block p-1 px-2 bg-primary-subtle rounded mb-2"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                            <span class="bg-light d-block p-1 mt-auto"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Default</h5>
                    </div>
                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-layout-style" id="sidebar-view-detached" value="detached">
                            <label class="form-check-label p-0 avatar-md w-100" for="sidebar-view-detached">
                                <span class="d-flex h-100 flex-column">
                                    <span class="bg-light d-flex p-1 gap-1 align-items-center px-2">
                                        <span class="d-block p-1 bg-primary-subtle rounded me-1"></span>
                                        <span class="d-block p-1 pb-0 px-2 bg-primary-subtle ms-auto"></span>
                                        <span class="d-block p-1 pb-0 px-2 bg-primary-subtle"></span>
                                    </span>
                                    <span class="d-flex gap-1 h-100 p-1 px-2">
                                        <span class="flex-shrink-0">
                                            <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                            </span>
                                        </span>
                                    </span>
                                    <span class="bg-light d-block p-1 mt-auto px-2"></span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Detached</h5>
                    </div>
                </div>
            </div>
            <div id="sidebar-color">
                <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Sidebar Color</h6>
                <p class="text-muted">Choose a color of Sidebar.</p>

                <div class="row">
                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio" data-bs-toggle="collapse" data-bs-target="#collapseBgGradient.show">
                            <input class="form-check-input" type="radio" name="data-sidebar" id="sidebar-color-light" value="light">
                            <label class="form-check-label p-0 avatar-md w-100" for="sidebar-color-light">
                                <span class="d-flex gap-1 h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-white border-end d-flex h-100 flex-column gap-1 p-1">
                                            <span class="d-block p-1 px-2 bg-primary-subtle rounded mb-2"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                            <span class="bg-light d-block p-1 mt-auto"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Light</h5>
                    </div>
                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio" data-bs-toggle="collapse" data-bs-target="#collapseBgGradient.show">
                            <input class="form-check-input" type="radio" name="data-sidebar" id="sidebar-color-dark" value="dark">
                            <label class="form-check-label p-0 avatar-md w-100" for="sidebar-color-dark">
                                <span class="d-flex gap-1 h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-primary d-flex h-100 flex-column gap-1 p-1">
                                            <span class="d-block p-1 px-2 bg-white bg-opacity-10 rounded mb-2"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-white bg-opacity-10"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-white bg-opacity-10"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-white bg-opacity-10"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                            <span class="bg-light d-block p-1 mt-auto"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Dark</h5>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

</div>
</div>

<script src="themes/default/member-area/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="themes/default/member-area/assets/libs/simplebar/simplebar.min.js"></script>
<script src="themes/default/member-area/assets/libs/node-waves/waves.min.js"></script>
<script src="themes/default/member-area/assets/libs/feather-icons/feather.min.js"></script>
<script src="themes/default/member-area/assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
<script src="themes/default/member-area/assets/js/plugins.js"></script>
<script src="themes/default/member-area/assets/libs/apexcharts/apexcharts.min.js"></script>
<script src="themes/default/member-area/assets/libs/jsvectormap/js/jsvectormap.min.js"></script>
<script src="themes/default/member-area/assets/libs/jsvectormap/maps/world-merc.js"></script>
<script src="themes/default/member-area/assets/js/pages/dashboard-analytics.init.js"></script>
<script src="themes/default/member-area/assets/js/app.js"></script>
<script src="vendor/ckeditor5/build/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#email_body'), {

            toolbar: {
                items: [
                    'heading',
                    '|',
                    'underline',
                    'bold',
                    'italic',
                    'link',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'horizontalLine',
                    'specialCharacters',
                    'superscript',
                    'subscript',
                    '|',
                    'removeFormat',
                    'sourceEditing',
                    'undo',
                    'redo'
                ]
            },
            language: 'en',
            licenseKey: '',

        })
        .then(editor => {
            window.editor = editor;




        })
        .catch(error => {

        });
</script>
</body>

</html>