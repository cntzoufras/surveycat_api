<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Surveycat</title>
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">


        <meta name="description" content="We craft awesome Survey templates for free and share them with anyone in the world &amp; beyond. Register , use, share <icon-share>" />

        <link href="{{ URL::to('/') }}/img/mobiapp_logo_96dp.png" rel="apple-touch-icon" sizes="96x96">
        <link href="{{ URL::to('/') }}/img/mobiapp_logo_96dp.png" rel="icon" sizes="96x96" type="image/png">
        <link href="{{ URL::to('/') }}/img/mobiapp_logo_32dp.png" rel="icon" sizes="32x32" type="image/png">
        <meta content="{{ URL::to('/') }}/img/mobiapp_logo_96dp.png" name="msapplication-TileImage">
        
        <meta property="og:url" content="https://www.boostraptheme.com/demo/index.html" />
        <meta property="og:title" content="Mobiapp - Mobile App Free Bootstrap Template | BoostrapTheme" />
        <meta property="og:locale" content="en_IN" /> 
        <meta property="og:site_name" content="Boostraptheme" />
        <meta name="twitter:card" content="summary" />
        <meta name="twitter:site" content="BoostrapTheme" />
        <meta name="twitter:creator" content="BoostrapTheme" />

        <link rel='stylesheet' href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
        <link href="{{ asset('device-mockups/device-mockups.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/owl.carousel.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/owl.theme.default.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/animate.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script> $(window).on('load', function(){ $(".loader").fadeOut(2000); }); </script>
        <!-- Fonts -->
        {{-- @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap'); --}}

        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            body {
                font-family: 'Nunito';
            }
        </style>
    </head>
    <body class="loader">
        <!-- OUR PROCESS    
            ================-->
            <section id="our-process" class="our-process"> 
              <div class="container">   
                <div class="row mb-5 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="col-md-12 text-center">
                        <div class="heading">
                            <h1>Superb Feature</h1>
                            <div class="bord-bot"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                  <div class="col-md-4 wow fadeInLeft" data-wow-delay="0.3s">
                    <div class="col-md-12 col-sm-12">
                      <div class="service-desc-cont">
                        <i class="fa fa-bar-chart-o"></i>
                        <h5>Contrast Ratio</h5>
                        <p>Contrast ratio is the difference between the darkest black and the brightest white a TV can produce experts makes any decision.</p>
                      </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                      <div class="service-desc-cont">
                        <i class="fa fa-globe"></i>
                        <h5>Offline voice typing</h5>
                        <p>voice dictations in Jelly bean can be done in flight mode or without an internet connection. All the phrasing voice.</p>
                      </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                      <div class="service-desc-cont mb-5">
                        <i class="fa fa-battery-full"></i>
                        <h5>Triple buffering</h5>
                        <p> Android used to lag in the race with iPhone and windows phone in terms of smooth visual display and notifications.</p>
                      </div>
                    </div> 
                  </div>
                  <div class="col-md-4 wow bounceInUp" data-wow-delay="0.6s">
                    <div class="servie-img text-center">
                      <img src="{{ URL::to('/') }}/img/iphone-x.png" alt="" class="img-fluid">
                    </div>
                  </div>
                  <div class="col-md-4 wow fadeInRight" data-wow-delay="0.9s">
                    <div class="col-md-12 col-sm-12">
                      <div class="service-desc-cont">
                        <i class="fab fa-app-store"></i>
                        <h5>Automatic widget resizing</h5>
                        <p>Unlike conventional widget structure, Android 4.1 has introduced the automatic resizing of widget according to home.</p>
                      </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                      <div class="service-desc-cont">
                        <i class="fa fa-archive"></i>
                        <h5>Advanced notification panel</h5>
                        <p> A notification panel cannot be usual one if its about the new version of Android. This is the reason that Jelly bean.</p>
                      </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                      <div class="service-desc-cont">
                        <i class="fa fa-life-bouy"></i>
                        <h5>Predictive Keywords</h5>
                        <p>In terms of advanced algorithm for predicting text has been introduced. Better search results with voice searching.</p>
                      </div>
                    </div> 
                  </div>
                </div>
              </div>
            </section>    

        <!-- FAQ    
            ================-->
            <section id="faq" class="faq py-5">
              <div class="container">
                <div class="row">
                  <div class="col-md-6">
                    <div class="faq-img">
                      <img src="{{ URL::to('/') }}/img/doubel-mobile.png" alt="" class="img-fluid">
                    </div>
                  </div>
                  <div class="col-md-6 col-sm-12 wow fadeInUp mt-5 mx-auto" data-wow-delay="0.6s">
                    <div class="faq-cont">
                        <div id="accordion">
                          <div class="card">
                            <div class="card-header" id="headingOne">
                              <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                  <i class="fa fa-arrow-right"></i>
                                  Why Use Mobiapp? 
                                </button>
                              </h5>
                            </div>

                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                              <div class="card-body">
                                Because there is standard vibration and ringing functions are utilised to alert users to incoming calls messages and social networking notifications.  Along with the built in ringtones, there is also have the luxury of MP3 ringtone support, which means users can assign their favourite music tracks as ringtones. The task of taking them is made all the more simple thanks to Autofocus and face detection included as standard.  Geo-tagging is another feature of the camera which functions thanks to GPS and allows users to keep tabs of where they took their photos.  Users also have the luxury of being able to shoot video footage with the camera.
                              </div>
                            </div>
                          </div>
                          <div class="card">
                            <div class="card-header" id="headingTwo">
                              <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                  <i class="fa fa-arrow-right"></i>
                                  What Factors Mobiapp Use? 
                                </button>
                              </h5>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                              <div class="card-body">
                                Intense Research provides a range of marketing and business research solutions designed for our client’s specific needs based on our expert resources. The business scopes of Intense Research cover more than 30 industries includsing energy, new materials, transportation, daily consumer goods, chemicals, etc. We provide our clients with one-stop solution for all the research requirements.Techno Exponent is a pioneering Android application development company that offers custom Android app development services. Thus, we have been developing Android Smartphone and tablet applications dating back to the start of the decade. Hire Android developers at our disposition.
                              </div>
                            </div>
                          </div>
                          <div class="card">
                            <div class="card-header" id="headingThree">
                              <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                  <i class="fa fa-arrow-right"></i>
                                  Mobiapp Network Security?  
                                </button>
                              </h5>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                              <div class="card-body">
                                Smart Phones are no longer a simple hand through which we could call a person or could send out message massage therapies to somebody; instead it is an accumulation of hand collection, electronic daily record, bridge to net, amusement source and so on. For lots of purposes we have set up many applications in our cell phone that we could not also remember them up until we are in real demand of them. Several of those applications are so helpful and also private that we need real safety and security to shield them from unwanted entry and un-authorized un-installation. For this kind of inconvenience, first thing comes in my thoughts is AppLock.
                              </div>
                            </div>
                          </div>
                          <div class="card">
                            <div class="card-header" id="headingThree">
                              <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFourth" aria-expanded="false" aria-controls="collapseThree">
                                  <i class="fa fa-arrow-right"></i>
                                  How Mobiapp work?  
                                </button>
                              </h5>
                            </div>
                            <div id="collapseFourth" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                              <div class="card-body">
                                The various other functions of this application are; fast lock switcher on standing bar, re-lock, fast leave, never ever reveals the app lock symbol from the launcher, AppLock itself can not be uninstalled; activity fantastic could not get rid of AppLock etc. The costs version consists some even more attributes e.g. tailored background, time lock, place lock, random keyboard etc. The future benefits of this application will certainly be cloud backup, lock button (wifi, mobile data, 3G), fake lock, visitor log and so on.In one word we could state that this application is a dream application for a user who cares about the security as well as security of his/ her wise tool.
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>   
                </div>
              </div>
            </section>

        <!-- GALLERY    
            ================-->
            <section id="gallery" class="gallery">
              <div class="container">
                <div class="row mb-5 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="col-md-12 text-center">
                        <div class="heading">
                            <h1>Our Gallery</h1>
                            <div class="bord-bot"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div id="gallery-slider" class="owl-carousel owl-theme">
                      <div class="gallery-img">
                        <img src="{{ URL::to('/') }}/img/app/first.jpg" alt="" class="img-fluid">
                      </div> 
                      <div class="gallery-img">
                        <img src="{{ URL::to('/') }}/img/app/second.jpg" alt="" class="img-fluid">
                      </div> 
                      <div class="gallery-img">
                        <img src="{{ URL::to('/') }}/img/app/third.jpg" alt="" class="img-fluid">
                      </div> 
                      <div class="gallery-img">
                        <img src="{{ URL::to('/') }}/img/app/four.jpg" alt="" class="img-fluid">
                      </div> 
                      <div class="gallery-img">
                        <img src="{{ URL::to('/') }}/img/app/five.jpg" alt="" class="img-fluid">
                      </div>
                      <div class="gallery-img">
                        <img src="{{ URL::to('/') }}/img/app/six.jpg" alt="" class="img-fluid">
                      </div> 
                      <div class="gallery-img">
                        <img src="{{ URL::to('/') }}/img/app/seventh.jpg" alt="" class="img-fluid">
                      </div>  
                    </div>
                  </div>
                </div>
              </div>
            </section> 
        
        <!-- DOWNLOAD APP    
            ================-->
            <section id="download" class="download-app pb-0">
              <div class="container">
                <div class="row">
                  <div class="col-md-12 text-center mb-4">
                    <h1>New Way Express World</h1>
                    <p>Download our latest app with latest features</p>
                  </div>
                  <div class="col-md-6 apple"><a href="#"><img src="{{ URL::to('/') }}/img/app/apple.png" alt="" class="img-fluid"></a></div>
                  <div class="col-md-6 android"><a href="#"><img src="{{ URL::to('/') }}/img/app/android.png" alt="" class="img-fluid"></a></div>
                  <div class="col-md-12 text-center mt-5 mx-auto">
                    <div class="device-container">
                      <div class="device-mockup iphone6_plus portrait white wow bounceInUp"  data-wow-delay=".5s">
                        <div class="device">
                          <div class="screen">
                            <img src="{{ URL::to('/') }}/img/app/six.jpg" class="img-fluid" alt="">
                          </div> 
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section> 

        <!-- CONTACT    
            ================-->
            <section id="contact" class="contact">
              <div class="container"> 
                <div class="row mb-5 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="col-md-12 text-center">
                        <div class="heading">
                            <h1>Conect On Social</h1>
                            <div class="bord-bot"></div>
                            <p class="wow fadeInUp" data-wow-delay="0.4s">Connect with mobiapp on social networking sites</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                  <div class="col-md-3 col-sm-6 text-center">
                    <div class="contact-cont wow fadeInUp"  data-wow-delay="0.3s">
                      <a href="#">
                        <img src="{{ URL::to('/') }}/img/icon/icon-1.png" alt="" class="img-fluid">
                        <h5>10000+ Likes</h5>
                      </a>
                    </div>
                  </div>
                  <div class="col-md-3 col-sm-6 text-center">
                    <div class="contact-cont wow fadeInUp"  data-wow-delay="0.6s">
                      <a href="#">
                        <img src="{{ URL::to('/') }}/img/icon/icon-2.png" alt="" class="img-fluid">
                        <h5>8000+ Followers</h5>
                      </a>
                    </div>
                  </div>
                  <div class="col-md-3 col-sm-6 text-center">
                    <div class="contact-cont wow fadeInUp"  data-wow-delay="0.9s">
                      <a href="#">
                        <img src="{{ URL::to('/') }}/img/icon/icon-4.png" alt="" class="img-fluid">
                        <h5>5000+ Followers</h5>
                      </a>
                    </div>
                  </div>
                  <div class="col-md-3 col-sm-6 text-center">
                    <div class="contact-cont wow fadeInUp"  data-wow-delay="1.2s">
                      <a href="#">
                        <img src="{{ URL::to('/') }}/img/icon/icon-3.png" alt="" class="img-fluid">
                        <h5>53000+ View</h5>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </section> 

        <!-- FOOTER 
            ==============-->
            <footer id="footer" class="footer">
              <div class="container-fluid">
                <div class="row top-footer">
                  <div class="col-md-12 text-center">
                    <p>Copyrights &copy; 2018 Design by <a href="https://boostraptheme.com/">Boostraptheme</a></p>
                  </div> 
                </div> 
              </div>
            </footer>

        

        <script src="{{ asset('js/popper.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>        
        <script src="{{ asset('js/jquery.easing.min.js') }}"></script>
        <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('js/wow.min.js') }}"></script>
        <script src="{{ asset('js/app.js') }}"></script>

        <!--Start of Tawk.to Script-->
            {{-- <script type="text/javascript">
            var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
            (function(){
            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            s1.async=true;
            s1.src='https://embed.tawk.to/5a6d77fc4b401e45400c7419/default';
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
            })(); 
            </script> --}}
        <!--End of Tawk.to Script-->
    </body>
</html>