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
                        <h4 class="cate">Traffic is the heart-beat of any online business. Get it here for free!</h4>
                        <h4 class="title">Get powerful email traffic and real viral banner advertising with our simple but sophisticated marketing system.</h4>
                        <p>
                            Why wait? You can set up a new account in a heart-beat and within minutes, you can email our responsive list with your offer. HINT: Use the coupon code: I Love Traffic for 1000 bonus mailing credits.
                        </p>
                    </div>
                    <div class="row mb--20">
                        <div class="col-sm-6">
                           
                        </div>
                        
                        <div class="col-sm-6">
                          
                        </div>
                        <div class="col-sm-6">
                            
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-xl-6">
    <div class="feature-1-thumb" style="margin-top: -20px;">
        <div class="main-thumb">
            <img src="themes/default/general-area/assets/images/feature/landing1.png" alt="feature">
        </div>       
    </div>
</div>
            </div>
        </div>
    </section>
    <!--============= Exclusive Section Ends Here =============-->


   

     <!--============= Pricing Section Starts Here =============-->
   <section class="pricing-section padding-top oh padding-bottom pb-lg-half bg_img pos-rel" style="background-color: #428dff;" id="pricing">
        
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
   <section style="display: flex; justify-content: center; margin: 30px 0;">
       
  <div style="display: flex; flex-direction: column; align-items: center; margin-right: 20px;">
    <div style="width: 200px; height: 200px; border: 2px solid blue; border-radius: 50%; text-align: center; padding: 20px;">
      <img src="themes/default/general-area/assets/images/team/Clare.jpg" alt="Image 1" style="width: 100%; height: auto; max-width: 100%; max-height: 100%; border-radius: 50%;">
    </div>
    <p style="color: blue; margin: 10px 0; text-align: center;">Clare Bowen</p>
    <p style="color: blue; margin: 10px 0; text-align: center;">Owner/Admin</p>
  </div>
  <div style="display: flex; flex-direction: column; align-items: center;">
    <div style="width: 200px; height: 200px; border: 2px solid blue; border-radius: 50%; text-align: center; padding: 20px;">
      <img src="themes/default/general-area/assets/images/team/8ff4947ffe5c8932cff8c4c61e96890b.png" alt="Image 2" style="width: 100%; height: auto; max-width: 100%; max-height: 100%; border-radius: 50%;">
    </div>
    <p style="color: blue; margin: 10px 0; text-align: center;">Brenton Senegal</p>
    <p style="color: blue; margin: 10px 0; text-align: center;">Owner/Admin</p>
  </div>
</section>
    <!--============= Team Section Ends Here =============-->

    
<?php require_once "themes/default/general-area/incs/footer.inc.php"; ?>