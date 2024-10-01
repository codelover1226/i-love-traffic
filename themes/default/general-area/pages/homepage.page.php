<?php



$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
  http_response_code(404);
  die("");
}
require_once "themes/default/general-area/incs/header.inc.php";
?>
<section class="exclusive-section padding-bottom-2 padding-top oh" id="feature">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-xl-6">
                    <div class="section-header left-style">
                        <h5 class="cate">Traffic is the heart-beat of any online business. Get it here for free!</h5>
                        <h2 class="title">Get powerful email traffic and real viral banner advertising with our simple but sophisticated marketing system.</h2>
                        <p>
                            Why wait? You can set up a new account in a heart-beat and within minutes, you can email our responsive list with your offer. HINT: Use the coupon code: I Love Traffic for 1000 bonus mailing credits.
                        </p>
                    </div>
                    <div class="row mb--20">
                        <div class="col-sm-6">
                            <div class="exclusive-item">
                                <div class="exclusive-thumb">
                                    <img src="themes/default/general-area/assets/images/feature/01.png" alt="feature">
                                </div>
                                <div class="exclusive-content">
                                    <h6 class="title">Total Members : <?= $membersController->totalMembers() ?></h6>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-6">
                            <div class="exclusive-item">
                                <div class="exclusive-thumb">
                                    <img src="themes/default/general-area/assets/images/feature/03.png" alt="feature">
                                </div>
                                <div class="exclusive-content">
                                    <h6 class="title">New Members : <?= $membersController->newMemberToday() ?></h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-xl-6">
                    <div class="feature-1-thumb">
                        <div class="main-thumb">
                            <img src="themes/default/general-area/assets/images/feature/feature01.png" alt="feature">
                        </div>
                        <div class="layer">
                            <img src="themes/default/general-area/assets/images/feature/feature1-layer.png" alt="feature">
                        </div>
                        <div class="layer">
                            <img src="themes/default/general-area/assets/images/feature/feature1-layer.png" alt="feature">
                        </div>
                        <div class="layer">
                            <img src="themes/default/general-area/assets/images/feature/feature1-layer.png" alt="feature">
                        </div>
                        <div class="layer">
                            <img src="themes/default/general-area/assets/images/feature/feature1-layer.png" alt="feature">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============= Exclusive Section Ends Here =============-->


    <!--============= Colaboration Section Starts Here =============-->
    <section class="colaboration-section padding-top-2 padding-bottom-2 oh" id="screenshot">
        <div class="container">
            <div class="row align-items-center flex-wrap-reverse">
                <div class="col-lg-6 col-xl-7 rtl">
                    <div class="collaboration-anime-area">
                        <div class="main-thumb">
                            <img src="themes/default/general-area/assets/images/collaboration/main.png" alt="colaboration">
                        </div>
                        <div class="mobile wow slideInUp" data-wow-delay="1s">
                            <div class="show-up">
                                <img src="themes/default/general-area/assets/images/collaboration/mobile.png" alt="colaboration">
                            </div>
                            <div class="mobile-slider owl-theme owl-carousel ltr">
                                <div class="mobile-item bg_img" data-background="themes/default/general-area/assets/images/collaboration/screen1.png"></div>
                                <div class="mobile-item bg_img" data-background="themes/default/general-area/assets/images/collaboration/screen2.png"></div>
                                <div class="mobile-item bg_img" data-background="themes/default/general-area/assets/images/collaboration/screen3.png"></div>
                                
                            </div>
                        </div>
                        <div class="girl wow slideInLeft">
                            <img src="themes/default/general-area/assets/images/collaboration/girl.png" alt="colaboration">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-5">
                    <div class="section-header left-style">
                        <h5 class="cate">It all comes together</h5>
                        <h2 class="title">Easy and perfect solution</h2>
                        <p>
                            Our features make it possible to both reach our members directly with your email offer, and to reach a broader spectrum of prospects with your banner advertising.
                        </p>
                    </div>
                    <div class="colaboration-wrapper">
                        <div class="colaboration-slider owl-carousel owl-theme">
                            <div class="colaboration-item">
                                <div class="colaboration-thumb">
                                    <div class="icon">
                                        <i class="flaticon-data-management"></i>
                                    </div>
                                </div>
                                <div class="colaboration-content">
                                    <h4 class="title">Easy to compose email ad copy</h4>
                                    <p>
                                        Use our inbuilt editor to compose your directly in your mailer area. Create beautiful emails on the fly!
                                    </p>
                                </div>
                            </div>
                            <div class="colaboration-item">
                                <div class="colaboration-thumb">
                                    <div class="icon">
                                        <i class="flaticon-data-management"></i>
                                    </div>
                                </div>
                                <div class="colaboration-content">
                                    <h4 class="title">AI at your service!</h4>
                                    <p>
                                        Upgraded members have access to our AI email generator. How helpful is that?!
                                    </p>
                                </div>
                            </div>
                            <div class="colaboration-item">
                                <div class="colaboration-thumb">
                                    <div class="icon">
                                        <i class="flaticon-data-management"></i>
                                    </div>
                                </div>
                                <div class="colaboration-content">
                                    <h4 class="title">Your ads in places you wouldn't reach on your own</h4>
                                    <p>
                                        Our powerful viral banner feature lets you add no less than 4 different banner sizes, to be displayed in unlimited websites across the internet.
                                    </p>
                                </div>
                            </div>
                            
                        </div>
                        <div class="cola-nav">
                            <a href="#0" class="cola-prev mr-4">
                                <img src="themes/default/general-area/assets/images/collaboration/left.png" alt="colaboration">
                            </a>
                            <a href="#0" class="cola-next">
                                <img src="themes/default/general-area/assets/images/collaboration/right.png" alt="colaboration">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============= Colaboration Section Ends Here =============-->
    
    
     <!--============= Feature Section Starts Here =============-->
    <section class="feature-section padding-top padding-bottom oh pos-rel">
        <div class="feature-shapes d-none d-lg-block">
            <img src="themes/default/general-area/assets/images/feature/feature-shape.png" alt="feature">
        </div>
        <div class="container">
            <div class="section-header mw-725">
                <h5 class="cate">Because time is money, and quality should never be compromised</h5>
                <h2 class="title">Fantastic Features that will boost your productivity </h2>
                <p>
                    We know that time and resource management is an essential component in online marketing. We're here to help!
                </p>
            </div>
            <div class="row">
                <div class="col-lg-5 rtl">
                    <div class="feature--thumb pr-xl-4 ltr">
                        <div class="feat-slider owl-carousel owl-theme" data-slider-id="1">
                            <div class="main-thumb">
                                <img src="themes/default/general-area/assets/images/feature/pro-main2.png" alt="feature">
                            </div>
                            <div class="main-thumb">
                                <img src="themes/default/general-area/assets/images/feature/pro-main2.png" alt="feature">
                            </div>
                            <div class="main-thumb">
                                <img src="themes/default/general-area/assets/images/feature/pro-main2.png" alt="feature">
                            </div>
                            <div class="main-thumb">
                                <img src="themes/default/general-area/assets/images/feature/pro-main2.png" alt="feature">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="feature-wrapper mb-30-none owl-thumbs" data-slider-id="1">
                        <div class="feature-item">
                            <div class="feature-thumb">
                                <div class="thumb">
                                    <img src="themes/default/general-area/assets/images/feature/pro1.png" alt="feature">
                                </div>
                            </div>
                            <div class="feature-content">
                                <h4 class="title">Easy to use interface</h4>
                                <p>We eliminate frustration by keeping navigation super simple.</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-thumb">
                                <div class="thumb">
                                    <img src="themes/default/general-area/assets/images/feature/pro2.png" alt="feature">
                                </div>
                            </div>
                            <div class="feature-content">
                                <h4 class="title">Flexible membership</h4>
                                <p>From our FREE membership where you can earn credits toward your marketing campaigns, to our LEADER upgrade which requires the bare minimum for intensive marketing, and two memberships inbetween. Big or small, we have you covered.</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-thumb">
                                <div class="thumb">
                                    <img src="themes/default/general-area/assets/images/feature/pro3.png" alt="feature">
                                </div>
                            </div>
                            <div class="feature-content">
                                <h4 class="title">Schedule your emails</h4>
                                <p>Set up your email campaign in your own time and we'll send it out at a time designated by you. Another way that we put the power in your hands.</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-thumb">
                                <div class="thumb">
                                    <img src="themes/default/general-area/assets/images/feature/pro4.png" alt="feature">
                                </div>
                            </div>
                            <div class="feature-content">
                                <h4 class="title">Designed for all devices</h4>
                                <p>Access and use your account, even if you are "out of office".</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-thumb">
                                <div class="thumb">
                                    <img src="themes/default/general-area/assets/images/feature/pro5.png" alt="feature">
                                </div>
                            </div>
                            <div class="feature-content">
                                <h4 class="title">Fresh prospects</h4>
                                <p>More of a benefit than a feature, but well worth the mention. We work hard to bring you fresh prospects so that you don't have to search for them, saving you time and money and we make sure your banner advertising reaches far and wide.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============= Feature Section Ends Here =============-->


     <!--============= Pricing Section Starts Here =============-->
    <section class="pricing-section padding-top oh padding-bottom pb-lg-half bg_img pos-rel" data-background="themes/default/general-area/assets/images/bg/pricing-bg.jpg" id="pricing">
        <div class="top-shape d-none d-md-block">
            <img src="themes/default/general-area/assets/css/img/top-shape.png" alt="css">
        </div>
        <div class="bottom-shape d-none d-md-block mw-0">
            <img src="themes/default/general-area/assets/css/img/bottom-shape.png" alt="css">
        </div>
        <div class="ball-2" data-paroller-factor="-0.30" data-paroller-factor-lg="0.60"
        data-paroller-type="foreground" data-paroller-direction="horizontal">
            <img src="themes/default/general-area/assets/images/balls/1.png" alt="balls">
        </div>
        <div class="ball-3" data-paroller-factor="0.30" data-paroller-factor-lg="-0.30"
        data-paroller-type="foreground" data-paroller-direction="horizontal">
            <img src="themes/default/general-area/assets/images/balls/2.png" alt="balls">
        </div>
        <div class="ball-4" data-paroller-factor="0.30" data-paroller-factor-lg="-0.30"
        data-paroller-type="foreground" data-paroller-direction="horizontal">
            <img src="themes/default/general-area/assets/images/balls/3.png" alt="balls">
        </div>
        <div class="ball-5" data-paroller-factor="0.30" data-paroller-factor-lg="-0.30"
        data-paroller-type="foreground" data-paroller-direction="vertical">
            <img src="themes/default/general-area/assets/images/balls/4.png" alt="balls">
        </div>
        <div class="ball-6" data-paroller-factor="-0.30" data-paroller-factor-lg="0.60"
        data-paroller-type="foreground" data-paroller-direction="horizontal">
            <img src="themes/default/general-area/assets/images/balls/5.png" alt="balls">
        </div>
        <div class="ball-7" data-paroller-factor="-0.30" data-paroller-factor-lg="0.60"
        data-paroller-type="foreground" data-paroller-direction="vertical">
            <img src="themes/default/general-area/assets/images/balls/6.png" alt="balls">
        </div>
        <div class="container">
            <div class="section-header cl-white">
                <h5 class="cate">Choose a plan that's right for you</h5>
                <h2 class="title">Simple Pricing Plans</h2>
                <p>
                    I-Love Traffic has plans, from free to paid, that scale with your needs. Subscribe to a plan that fits the size of your business.
                </p>
            </div>
            <div class="tab-up">
                <ul class="tab-menu pricing-menu">
                    <li class="active">Monthly</li>
                    <li>Yearly</li>
                </ul>
                <div class="tab-area">
                    <div class="tab-item active">
                        <div class="pricing-slider-wrapper">
                            <div class="pricing-slider owl-theme owl-carousel">
                                <div class="pricing-item-2">
                                    <h5 class="cate">Leader</h5>
                                    <div class="thumb">
                                        <img src="themes/default/general-area/assets/images/pricing/pricing1.png" alt="pricing">
                                    </div>
                                    <h2 class="title"><sup>$</sup>89</h2>
                                    <span class="info">Per Year</span>
                                    <ul class="pricing-content-3">
                                        <li>Mail 5 times per day </li>
                                        <li>Mail all members </li>
                                        <li>500,000 YEARLY bonus credits </li>
                                        <li>Earn 100 credits per email click</li>
                                        <li>6 Second Timer</li>
                                        <li>40% Affiliate Commission</li>
                                        <li>10 monthly uses of AI email generator</li>
                                        
                                    </ul>
                                
                                </div>
                                <div class="pricing-item-2">
                                    <h5 class="cate">Free</h5>
                                    <div class="thumb">
                                        <img src="themes/default/general-area/assets/images/pricing/pricing2.png" alt="pricing">
                                    </div>
                                    <h2 class="title"><sup>$</sup>0</h2>
                                    <span class="info">Per Month</span>
                                    <ul class="pricing-content-3">
                                        <li>Mail once per day </li>
                                        <li>Mail all members </li>
                                        <li>Zero monthly bonus credits </li>
                                        <li>Earn 15 credits per email click</li>
                                        <li>10 Second Timer</li>
                                        <li>10% Affiliate Commission</li>
                                        <li>No uses of AI email generator</li>
                                    </ul>
                                    
                                </div>
                                <div class="pricing-item-2">
                                    <h5 class="cate">Gold</h5>
                                    <div class="thumb">
                                        <img src="themes/default/general-area/assets/images/pricing/pricing3.png" alt="pricing">
                                    </div>
                                    <h2 class="title"><sup>$</sup>5</h2>
                                    <span class="info">Per Month</span>
                                    <ul class="pricing-content-3">
                                        <li>Mail once per day</li>
                                        <li>Mail all members</li>
                                        <li>1,000 monthly bonus credits</li>
                                        <li>Earn 20 Credits per email click</li>
                                        <li>6 Second Timer</li>
                                        <li>20% Affiliate Commission</li>
                                        <li>No uses of AI email generator</li>
                                        
                                    </ul>
                                   
                                </div>
                                <div class="pricing-item-2">
                                    <h5 class="cate">Platinum</h5>
                                    <div class="thumb">
                                        <img src="themes/default/general-area/assets/images/pricing/pricing4.png" alt="pricing">
                                    </div>
                                    <h2 class="title"><sup>$</sup>9</h2>
                                    <span class="info">Per Month</span>
                                    <ul class="pricing-content-3">
                                        <li>Mail twice per day</li>
                                        <li>Mail all members</li>
                                        <li>10,000 monthly bonus credits</li>
                                        <li>Earn 35 Credits per email click</li>
                                        <li>6 Second Timer</li>
                                        <li>30% Affiliate Commission</li>
                                        <li>No uses of AI email generator</li>
                                        
                                    </ul>
                                  
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-item">
                        <div class="pricing-slider-wrapper">
                            <div class="pricing-slider owl-theme owl-carousel">
                                <div class="pricing-item-2">
                                    <h5 class="cate">Free</h5>
                                    <div class="thumb">
                                        <img src="themes/default/general-area/assets/images/pricing/pricing1.png" alt="pricing">
                                    </div>
                                    <h2 class="title"><sup>$</sup>0</h2>
                                    <span class="info">Per Year</span>
                                    <ul class="pricing-content-3">
                                          <li>Mail once per day </li>
                                        <li>Mail all members </li>
                                        <li>Zero bonus credits </li>
                                        <li>Earn 15 credits per email click</li>
                                        <li>10 Second Timer</li>
                                        <li>10% Affiliate Commission</li>
                                        <li>No uses of AI email generator</li>
                                    </ul>
                                    
                                </div>
                                <div class="pricing-item-2">
                                    <h5 class="cate">Gold</h5>
                                    <div class="thumb">
                                        <img src="themes/default/general-area/assets/images/pricing/pricing2.png" alt="pricing">
                                    </div>
                                    <h2 class="title"><sup>$</sup>24</h2>
                                    <span class="info">Per Year</span>
                                    <ul class="pricing-content-3">
                                          <li>Mail once per day</li>
                                        <li>Mail all members</li>
                                        <li>6,000 YEARLY bonus credits</li>
                                        <li>Earn 20 Credits per email click</li>
                                        <li>6 Second Timer</li>
                                        <li>20% Affiliate Commission</li>
                                        <li>No uses of AI email generator</li>
                                        
                                    </ul>
                                    
                                </div>
                                <div class="pricing-item-2">
                                    <h5 class="cate">Platinum</h5>
                                    <div class="thumb">
                                        <img src="themes/default/general-area/assets/images/pricing/pricing3.png" alt="pricing">
                                    </div>
                                    <h2 class="title"><sup>$</sup>49</h2>
                                    <span class="info">Per Year</span>
                                    <ul class="pricing-content-3">
                                         <li>Mail twice per day</li>
                                        <li>Mail all members</li>
                                        <li>120,000 YEARLY bonus credits</li>
                                        <li>Earn 35 Credits per email click</li>
                                        <li>6 Second Timer</li>
                                        <li>30% Affiliate Commission</li>
                                        <li>No uses of AI email generator</li>
                                     
                                    </ul>
                                   
                                </div>
                                <div class="pricing-item-2">
                                    <h5 class="cate">Leader</h5>
                                    <div class="thumb">
                                        <img src="themes/default/general-area/assets/images/pricing/pricing4.png" alt="pricing">
                                    </div>
                                    <h2 class="title"><sup>$</sup>89</h2>
                                    <span class="info">Per Year</span>
                                    <ul class="pricing-content-3">
                                       <li>Mail 5 times per day </li>
                                        <li>Mail all members </li>
                                        <li>500,000 YEARLY bonus credits </li>
                                        <li>Earn 100 credits per email click</li>
                                        <li>6 Second Timer</li>
                                        <li>40% Affiliate Commission</li>
                                        <li>10 monthly uses of AI email generator</li>
                                        
                                    </ul>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============= Pricing Section Ends Here =============-->





   <!--============= Team Section Starts Here =============-->
    <section class="team-section padding-top padding-bottom oh">
        <div class="container">
            <div class="section-header">
                <h5 class="cate"</h5>
                <h2 class="title">Expert Team Members</h2>
                <p>
                    This site is owned and operated by people who love great traffic.
                </p>
            </div>
            <div class="row justify-content-center mb-40-none">
                <div class="col-lg-4 col-md-6">
                    <div class="team-item">
                        <div class="team-thumb">
                            <img src="themes/default/general-area/assets/images/team/Clare.jpg" alt="team">
                        </div>
                        <div class="team-content">
                            <h4 class="title">
                                <a href="#0">Clare Bowen</a>
                            </h4>
                            <span class="info">Owner/Admin</span>
                            
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="team-item">
                        <div class="team-thumb">
                            <img src="themes/default/general-area/assets/images/team/8ff4947ffe5c8932cff8c4c61e96890b.png" alt="team">
                        </div>
                        <div class="team-content">
                            <h4 class="title">
                                <a href="#0">Brenton Senegal</a>
                            </h4>
                            <span class="info">Owner/Admin</span>
                            
                        </div>
                    </div>
                </div>
               
            </div>
        </div>
    </section>
    <!--============= Team Section Ends Here =============-->

    
<?php require_once "themes/default/general-area/incs/footer.inc.php"; ?>