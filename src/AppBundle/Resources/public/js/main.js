/**
  * Header Sticky
  * Top Slider
  * Retina Logos
  * Responsive Texts
  * Project Isotope
  * Blog Masonry
  * Tesimonials
  * Orches Slider
  * Toggles
  * Google Map
  * Progress Bars
  * Mobile Navigation
  * Mailchimp Subscribe
  * Animation
  * Project Effect
  * Go Top Button
  * Lastest Tweets
  * Ajax Contact Form
  * Flickr Feed
  * Count Down
  * Parallax
  * Fullscreen Background Slideshow
  * Fullscreen Background Video
*/

;(function($) {

   'use strict'

   var init_header = function() {
      var largeScreen = matchMedia('only screen and (min-width: 992px)').matches;
      if ( largeScreen ) {
         if( $().sticky ){
            $('header.header-sticky').sticky();
         }
      }

      $(window).scroll( function() {
         if ( $( window).scrollTop() > 200 ) {
            $('header').addClass('float-header');
         } else {
            $('header').removeClass('float-header');
         }
      });

      $('.one-page .mainnav ul > li > a').on('click',function() {
         var anchor = $(this).attr('href').split('#')[1];

         if (anchor) {
            if ( $('#'+anchor).length > 0 ) {
               var headerHeight = 0;
               if ( $('.header-sticky').length > 0 && largeScreen ) {
                  headerHeight = $('#header').outerHeight();
               }
               var target = $('#'+anchor).offset().top - headerHeight;
               $('html,body').animate({scrollTop: target}, 1000, 'easeInOutExpo');
            }
         }
         return false;
      }); // click on one-page menu
      
      $('.one-page .mainnav > ul > li > a').on( 'click', function() {
         $( this ).addClass('active').parent().siblings().children().removeClass('active');
      });
   };

   var fullScreen = function() {
      (function() {
         function setupSlider() {
            var sliderOrches = $('#top-slider'),
            headerHeight = $('#header').height(),
            windowHeight = $(window).height(),
            sliderHeight = 650;

            if ( $('body').hasClass('one-page') ){
               sliderHeight = windowHeight;
            }

            sliderOrches.css({ height: sliderHeight+"px", });
            var sliderContent = $('#top-slider .content'),
            contentHeight = sliderContent.height(),
            contentMargin = (sliderHeight - contentHeight) / 2;

            sliderContent.css({ 
               "margin-bottom" : contentMargin + "px",
               "margin-top": contentMargin - headerHeight + "px"
            });
         }
         $(window).on("resize", setupSlider);
         $(document).on("ready", setupSlider);
      })(); // set fullscreen and vertical align for content

      (function() {
         function textScroll(){
            if ( $('#top-slider .content').hasClass('scroll') ) {
               var current = 1; 
               var height = $('.text-scroll').height(); 
               var numberDivs = $('.text-scroll').children().length; 
               var first = $('.text-scroll h1:nth-child(1)');

               setInterval(function() {
                  var number = current * -height;
                  first.css('margin-top', number + 'px');
                  if ( current === numberDivs ) {
                    first.css('margin-top', '0px');
                    current = 1;
                  } else current++;
               }, 2500);
            } // end if
         }
         $(document).on("ready", textScroll);
      })(); // end scroll texts

      (function() {
         $('.btn-top, .slide-button a').on('click',function() {
            var anchor = $(this).attr('href').split('#')[1];
            if ( anchor ) {
               if ( $('#'+anchor).length > 0 ) {
                  var headerHeight = 0;
                  if ( $('.header-sticky').length > 0 ) {
                     headerHeight = $('#header').outerHeight();
                  }
                  var target = $('#'+anchor).offset().top - headerHeight;

                  $('html,body').animate({scrollTop: target}, 1000, 'easeInOutExpo');
               }
            }
            return false;
         });
      })(); // scroll target
   };

   var retinaLogos = function() {
      var retina = window.devicePixelRatio > 1 ? true : false;

      if(retina) {
         $('.logo img').attr({src:'./images/logo@2x.png',width:'105',height:'23'});
      }
   };

   var responsiveTexts = function() {
      if ( $().fitText ) {
         $(".text-scroll h1").fitText(1.3, { minFontSize: '20px', maxFontSize: '80px' });
      }
   };

   var projectIsotope = function() {
      if ( $().isotope ) {
         var $container = $('.project-container');

         $container.imagesLoaded(function(){
            $container.isotope({
               itemSelector: '.project-item',
               transitionDuration: '0.6s'
            }); // isotope
         });

         $('.project-filter li').on('click',function() {
            $('.project-filter li').removeClass('active');
            $(this).addClass('active');
            var selector = $(this).find("a").attr('data-filter');
            $container.isotope({ filter: selector });
            return false;
         }); // filter
      };
   };

   var blogMasonry = function() {
      if ( $().isotope ) {
         var $container = $('.posts-container');

         $container.imagesLoaded(function(){
            $container.isotope({
               itemSelector: '.post-item',
               transitionDuration: '0.5s',
               layoutMode: 'masonry',
               masonry: { columnWidth: $container.width() / 12 }
            }); // isotope
         });

         $(window).resize(function() {
            $container.isotope({
               masonry: { columnWidth: $container.width() / 12 }
            });
         }); // relayout
         
         $('.post-filter li').on('click',function() {
            $('.post-filter li').removeClass('active');
            $(this).addClass('active');
            var selector = $(this).find("a").attr('data-filter');
            $container.isotope({ filter: selector });
            return false;
         }); // filter
      };
   };

   var testimonial = function () {
      $('.testimonial-text').bxSlider({
         mode: 'fade',
         touchEnabled: true,
         oneToOneTouch: true,
         pagerCustom: '#bx-pager',
         nextSelector: '#bx-next',
         prevSelector: '#bx-prev',
         nextText: '<i class="icons-angle-right"></i>',
         prevText: '<i class="icons-angle-left"></i>',
      });
   };

   var orSlider = function() {
      if ( $().flexslider ) {
         $('.or-slider').each(function() {
            var $this = $(this);
            var easing = ( $this.data('effect') == 'fade' ) ? 'linear' : 'easeInOutExpo';
            $this.find('.flexslider').flexslider({
               animation      :  $this.data('effect'),
               direction      :  $this.data('direction'), // vertical
               pauseOnHover   :  true,
               useCSS         :  false,
               easing         :  easing,
               animationSpeed :  500,
               slideshowSpeed :  5000,
               controlNav     :  false,
               directionNav   :  true,
               slideshow      :  $this.data('auto'),
               prevText    :  '<i class="icons-angle-left"></i>',
               nextText    :  '<i class="icons-angle-right"></i>',
               smoothHeight   :  true,
            }); // flexslider
         }); // or-slider each
      }
   };

   var toggles = function() {
      var args = {easing : 'easeOutExpo', duration: 600};
      $('.toggle .toggle-title.active').siblings('.toggle-content').show();

      $('.toggle.toggle-enable .toggle-title').on('click', function() {
         $(this).closest('.toggle').find('.toggle-content').slideToggle(args);
         $(this).toggleClass('active');
      }); // toggle 

      $('.accordion .toggle-title').on('click', function () {
         if( !$(this).is('.active') ) {
            $(this).closest('.accordion').find('.toggle-title.active').toggleClass('active').next().slideToggle(args);
            $(this).toggleClass('active');
            $(this).next().slideToggle(args);
         } else {
            $(this).toggleClass('active');
            $(this).next().slideToggle(args);
         }     
      }); // accordion
   };

   var gmapSetup = function() {
      if ( $().gmap3 ) {
         $("#map").gmap3({
            map:{
               options:{
                  zoom: 17,
                  mapTypeId: 'orches_style',
                  mapTypeControlOptions: {
                     mapTypeIds: ['orches_style', google.maps.MapTypeId.SATELLITE, google.maps.MapTypeId.HYBRID]
                  },
                  scrollwheel: false
               }
            },
            getlatlng:{
               address:  "3 London Rd London SE1 6JZ United Kingdom",
               callback: function(results) {
                  if ( !results ) return;
                     $(this).gmap3('get').setCenter(new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng()));
                     $(this).gmap3({
                     marker:{
                     latLng:results[0].geometry.location
                     }
                  });
               }
            },
            styledmaptype:{
               id: "orches_style",
               options:{
                  name: "Orches Map"
               },
            },
         });
      }
   };

   var progressBar = function() {
      $('.progress-bar').on('on-appear', function() {
         $(this).each(function() {
            var percent = $(this).data('percent');

            $(this).find('.progress-animate').animate({
               "width": percent + '%'
            },3000);

            $(this).parent('.progress-single').find('.perc').addClass('show').animate({
               "width": percent + '%'
            },3000);
         });
      });
   }

   var ResponsiveMenu = {

      menuType: 'desktop',

      initial: function(winWidth) {
         ResponsiveMenu.menuWidthDetect(winWidth);
         ResponsiveMenu.menuBtnClick();
         ResponsiveMenu.parentMenuClick();
      },

      menuWidthDetect: function(winWidth) {
         var currMenuType = 'desktop';

         if (matchMedia('only screen and (max-width: 978px)').matches) {
            currMenuType = 'mobile';
         } // change menu type

         if ( currMenuType !== ResponsiveMenu.menuType ) {
            ResponsiveMenu.menuType = currMenuType;

            if ( currMenuType === 'mobile' ) {
               var $mobileMenu = $('#mainnav').attr('id', 'mainnav-mobi').hide();
               $('#header').find('.header-wrap').after($mobileMenu);
               var hasChildMenu = $('#mainnav-mobi').find('li:has(ul)');
               hasChildMenu.children('ul').hide();
               hasChildMenu.children('a').after('<span class="btn-submenu"></span>');
               $('.btn-menu').removeClass('active');
             } else {
               var $desktopMenu = $('#mainnav-mobi').attr('id', 'mainnav').removeAttr('style');
               $desktopMenu.find('.sub-menu').removeAttr('style');
               $('#header').find('.span10').append($desktopMenu);
               $('.btn-submenu').remove();
             }
         } // clone and insert menu
      },

      menuBtnClick: function() {
         $('.btn-menu').on('click', function() {
            $('#mainnav-mobi').slideToggle(300);
            $(this).toggleClass('active');
         });
      }, // click on moblie button

      parentMenuClick: function() {
         $(document).on('click', '#mainnav-mobi li .btn-submenu', function(e) {
            if ( $(this).has('ul') ) {
               e.stopImmediatePropagation()
               $(this).next('ul').slideToggle(300);
               $(this).toggleClass('active');
            }
         });
      } // click on sub-menu button
   };

   var ajaxSubscribe = {
      obj: {
         subscribeEmail    : $('#subscribe-email'),
         subscribeButton   : $('#subscribe-button'),
         subscribeMsg      : $('#subscribe-msg'),
         subscribeContent  : $("#subscribe-content"),
         dataMailchimp     : $('#subscribe-form').attr('data-mailchimp'),
         success_message   : '<div class="notification_ok">Thank you for joining our mailing list. Please check your email for a confirmation link.</div>',
         failure_message   : '<div class="notification_error">There was a problem processing your submission.</div>',
         noticeError       : '<div class="notification_error">{msg}</div>',
         noticeInfo        : '<div class="notification_error">{msg}</div>',
         basicAction       : 'mail/subscribe.php',
         mailChimpAction   : 'mail/subscribe-mailchimp.php'
      },

      eventLoad: function() {
         var objUse = ajaxSubscribe.obj;

         $(objUse.subscribeButton).on('click', function() {
            if ( window.ajaxCalling ) return;
            var isMailchimp = objUse.dataMailchimp === 'true';

            if ( isMailchimp ) {
              ajaxSubscribe.ajaxCall(objUse.mailChimpAction);
            } else {
              ajaxSubscribe.ajaxCall(objUse.basicAction);
            }
         });
      },

      ajaxCall: function (action) {
         window.ajaxCalling = true;
         var objUse = ajaxSubscribe.obj;
         var messageDiv = objUse.subscribeMsg.html('').hide();
         $.ajax({
            url: action,
            type: 'POST',
            dataType: 'json',
            data: {
               subscribeEmail: objUse.subscribeEmail.val()
            },
            success: function (responseData, textStatus, jqXHR) {
               if ( responseData.status ) {
                  objUse.subscribeContent.fadeOut(500, function () {
                     messageDiv.html(objUse.success_message).fadeIn(500);
                  });
               } else {
                  switch (responseData.msg) {
                  case "email-required":
                     messageDiv.html(objUse.noticeError.replace('{msg}','Email is required.'));
                     break;
                  case "email-err":
                     messageDiv.html(objUse.noticeError.replace('{msg}','Email invalid.'));
                     break;
                  case "duplicate":
                     messageDiv.html(objUse.noticeError.replace('{msg}','Email is duplicate.'));
                     break;
                  case "filewrite":
                     messageDiv.html(objUse.noticeInfo.replace('{msg}','Mail list file is open.'));
                     break;
                  case "undefined":
                     messageDiv.html(objUse.noticeInfo.replace('{msg}','undefined error.'));
                     break;
                  case "api-error":
                     objUse.subscribeContent.fadeOut(500, function () {
                        messageDiv.html(objUse.failure_message);
                     });
                  }
                  messageDiv.fadeIn(500);
               }
            },
            error: function (jqXHR, textStatus, errorThrown) {
               alert('Connection error');
            },
            complete: function (data) {
               window.ajaxCalling = false;
            }
         });
      }
   };

   var orAnimation = function() {
      $('.orches-animation').each( function() {
      var orElement = $(this),
         orAnimationClass = orElement.data('animation'),
         orAnimationDelay = orElement.data('animation-delay'),
         orAnimationOffset = orElement.data('animation-offset');

         orElement.css({
            '-webkit-animation-delay':  orAnimationDelay,
            '-moz-animation-delay':     orAnimationDelay,
            'animation-delay':          orAnimationDelay
         });
        
         orElement.waypoint(function() {
            orElement.addClass('animated').addClass(orAnimationClass);
            },{
               triggerOnce: true,
               offset: orAnimationOffset
         });
      });
   };

   var effectProject = function() {
      var effect = $('.project-container').data('portfolio-effect');
      $('.project-item').children('.item-wrap').addClass('orches-animation');

      $('.project-container').waypoint(function(direction) {
         $('.project-item').children('.item-wrap').each(function(idx, ele) {
            setTimeout(function() {
               $(ele).addClass('animated ' + effect);
            }, idx * 100);
         });
      }, {
         offset: '75%'
      });
   };

   var goTop = function() {
      $(window).scroll(function() {
         if ( $(this).scrollTop() > 800 ) {
            $('.go-top').addClass('show');
         } else {
            $('.go-top').removeClass('show');
         }
      }); 
      
      $('.go-top').on('click', function() {
         $("html, body").animate({ scrollTop: 0 }, 1000 , 'easeInOutExpo');
         return false;
      });
   };

   var lastestTweets = function() {
      if ( $().tweet ) {
         $('.latest-tweets').each(function() {
            var $this = $(this);
            $this.tweet({
               username: $this.data('username'),
               join_text: "auto",
               avatar_size: null,
               count: $this.data('number'),
               template: "{text}",
               loading_text: "loading tweets...",
               modpath: $this.data('modpath'),        
            }); // tweet
         }); // lastest-tweets each
      }
   };

   var ajaxContactForm = function() {
      // http://www.bitrepository.com/a-simple-ajax-contact-form-with-php-validation.html
      $('.contact-form').each(function() {
         var $this = $(this); 
         $this.submit(function() {
            var str = $this.serialize();
            $.ajax({
               type: "POST",
               url:  $this.attr('action'),
               data: str,
               success: function(msg) {
                  // Message Sent? Show the 'Thank You' message and hide the form
                  var result;
                  if(msg == 'OK') {
                     result = '<div class="notification_ok">Your message has been sent. Thank you!</div>';
                  } else {
                     result = msg;
                  }
                  result = '<div class="result">' + result + '</div>';
                  $this.find('.note').html(result);
               }
            });
            return false;
         }); // submit

      }); // each contactform
   }; // contact

   var flickrFeed = function() {
      if ( $().jflickrfeed ) {
         $('.flickr-photos').each( function() {
            $(this).jflickrfeed({
               limit: 6,
               qstrings: {
                  id: '92231417@N05' // Your Flickr Id
               },
               itemTemplate: '<li><a href="{{link}}" title="{{title}}" target="_blank"><img src="{{image_s}}" alt="{{title}}" /></a></li>'
            });
         });
      }
   };

   var orCountdown = function() {
      var orches_style = function(data) {
         $(this.el).html("<div class='days'><div class='numb'><div class='bg'>" + this.leadingZeros(data.days, 3) + "</div></div><span class='text'>Days</span></div><div class='hours'><div class='numb'><div class='bg'>" + this.leadingZeros(data.hours, 2) + "</div></div><span class='text'>Hours</span></div><div class='mins'><div class='numb'><div class='bg'>" + this.leadingZeros(data.min, 2) + "</div></div><span class='text'>Minutes</span></div><div class='secs'><div class='numb'><div class='bg'>" + this.leadingZeros(data.sec, 2) + "</div></div><span class='text'>Seconds</span></div>");
      }

      $('.countdown').each(function() {
         $(this).countdown({
            date: $(this).attr('data-date'),
            render: orches_style
         });
      });
   };

   var parallax = function() {
      $('.parallax-bg1').parallax("50%", 0.5);
      $('.parallax-bg2').parallax("50%", 0.3);
      $('.parallax-bg3').parallax("50%", 0.3);
      $('.parallax-bg4').parallax("50%", 0.3);
      $('.parallax-bg5').parallax("50%", 0.3);
      $('.parallax-bg6').parallax("50%", 0.3);
      $('.parallax-bg7').parallax("50%", 0.3);
      $('.parallax-bg8').parallax("50%", 0.4);
   };

   var controlSlider = function() {
      if ( $().flexslider ) {
         $('.control-slider').each(function() {
            $(this).find('.flexslider').flexslider({
               animation: 'fade',
               animationLoop: true,
               slideshow: true,
               slideshowSpeed: 6000,
               animationSpeed: 800,
               pauseOnHover: true, 
               pauseOnAction:true,
               controlNav: false,
               directionNav: true,
               prevText: '<i class="icons-angle-left"></i>',
               nextText: '<i class="icons-angle-right"></i>',
               controlsContainer: '.flex-container',
               start: function(slider) {
                  var topSubtitle = $('.control-slider .flex-active-slide .sub-title').data('topsubtitle');
                  var topMaintitle = $('.control-slider .flex-active-slide .main-title').data('topmaintitle');
                  var topIdeas = $('.control-slider .flex-active-slide .ideas').data('topideas');
                  var topButton = $('.control-slider .flex-active-slide .slide-button').data('topbutton');

                  var widthSubtitle = $('.control-slider .flex-active-slide .sub-title').outerWidth();
                  var widthMaintitle = $('.control-slider .flex-active-slide .main-title').outerWidth();
                  var widthIdeas = $('.control-slider .flex-active-slide .ideas').outerWidth();
                  var widthButton = $('.control-slider .flex-active-slide .slide-button').outerWidth();

                  $('.control-slider .flex-active-slide').find('.sub-title').css({ left: '50%', marginLeft: - widthSubtitle / 2 });
                  $('.control-slider .flex-active-slide').find('.main-title').css({ left: '50%', marginLeft: - widthMaintitle / 2 });
                  $('.control-slider .flex-active-slide').find('.ideas').css({ left: '50%', marginLeft: - widthIdeas / 2 });
                  $('.control-slider .flex-active-slide').find('.slide-button').css({ left: '50%', marginLeft: - widthButton / 2 });

                  $('.control-slider .flex-active-slide').find('.sub-title').animate({ top: topSubtitle, opacity: '1'}, 1400);
                  $('.control-slider .flex-active-slide').find('.main-title').animate({ top: topMaintitle, opacity: '1'}, 1000);
                  $('.control-slider .flex-active-slide').find('.ideas').animate({ top: topIdeas, opacity: '1'}, 1000);
                  $('.control-slider .flex-active-slide').find('.slide-button').animate({ top: topButton, opacity: '1'}, 500);
               },
               before: function(slider) {
                  $('.control-slider .flex-active-slide').find('.sub-title').animate({ top: '-200px', opacity: '0'}, 1400);
                  $('.control-slider .flex-active-slide').find('.main-title').animate({ top: '-100px', opacity: '0'}, 1000);
                  $('.control-slider .flex-active-slide').find('.ideas').animate({ top: '0', opacity: '0'}, 1000);
                  $('.control-slider .flex-active-slide').find('.slide-button').animate({ top: '150px', opacity: '0'}, 500);
               },
               after: function(slider) {
                  var topSubtitle = $('.control-slider .flex-active-slide .sub-title').data('topsubtitle');
                  var topMaintitle = $('.control-slider .flex-active-slide .main-title').data('topmaintitle');
                  var topIdeas = $('.control-slider .flex-active-slide .ideas').data('topideas');
                  var topButton = $('.control-slider .flex-active-slide .slide-button').data('topbutton');

                  var widthSubtitle = $('.control-slider .flex-active-slide .sub-title').outerWidth();
                  var widthMaintitle = $('.control-slider .flex-active-slide .main-title').outerWidth();
                  var widthIdeas = $('.control-slider .flex-active-slide .ideas').outerWidth();
                  var widthButton = $('.control-slider .flex-active-slide .slide-button').outerWidth();

                  $('.control-slider .flex-active-slide').find('.sub-title').css({ left: '50%', marginLeft: - widthSubtitle / 2 });
                  $('.control-slider .flex-active-slide').find('.main-title').css({ left: '50%', marginLeft: - widthMaintitle / 2 });
                  $('.control-slider .flex-active-slide').find('.ideas').css({ left: '50%', marginLeft: - widthIdeas / 2 });
                  $('.control-slider .flex-active-slide').find('.slide-button').css({ left: '50%', marginLeft: - widthButton / 2 });

                  $('.control-slider .flex-active-slide').find('.sub-title').animate({ top: topSubtitle, opacity: '1'}, 1400);
                  $('.control-slider .flex-active-slide').find('.main-title').animate({ top: topMaintitle, opacity: '1'}, 1000);
                  $('.control-slider .flex-active-slide').find('.ideas').animate({ top: topIdeas, opacity: '1'}, 1000);
                  $('.control-slider .flex-active-slide').find('.slide-button').animate({ top: topButton, opacity: '1'}, 500);
               }
            }); // end flexslider
         }); // end each
      } // end if
   };

   var fullScreenImage = function() {
      if ( $('body').hasClass('image-bg-slider') ) {
         $("#fullscreen-slider .super").each(function() {
            var $this = $(this);
            var number = $this.data('number');
            number = parseInt(number);

            var i = 1;
            var slides = [];
            while ( i <= number ) {
               slides.push({image:$this.data('image-'+i)});
               i++;
            }

            $this.supersized({
               autoplay          : true,
               slide_interval    : 4000,
               transition        : $this.data('effect'),
               transition_speed  : 700,
               slides            : slides,
               fit_portrait      : false
            });
         });
      };
   };

   var fullScreenVideo = function() {
      if ( $('body').hasClass('video-bg-slider') ) {
         var fullVideo = new $.BigVideo({useFlashForFirefox:false});
         fullVideo.init();
         fullVideo.show('http://vjs.zencdn.net/v/oceans.mp4',{ambient:true});
         //fullVideo.show('video/oceans.mp4',{altSource:'video/oceans.ogv', ambient:true});  //For crossbrowser fallbacks
      };
   };

   // Dom Ready
   $(function() {
      init_header();
      fullScreen();
      controlSlider();
      fullScreenImage();
      fullScreenVideo();
      retinaLogos();
      responsiveTexts();
      projectIsotope();
      blogMasonry();
      testimonial();
      orSlider();
      toggles();
      gmapSetup();
      progressBar();
      orAnimation();
      effectProject();
      goTop()
      ajaxContactForm();
      flickrFeed();
      orCountdown();
      ajaxSubscribe.eventLoad();
      parallax();
      lastestTweets();
      
      // Initialize responsive menu
      ResponsiveMenu.initial($(window).width());
      $(window).resize(function() {
         ResponsiveMenu.menuWidthDetect($(this).width());
      });

      // Detect elements into viewport
      $('[data-waypoint-active="yes"]').waypoint(function() {
         $(this).trigger('on-appear');
      }, { offset: '90%' });

      $(window).on('load', function() {
         setTimeout(function() {
            $.waypoints('refresh');
         }, 100);
      });
   });

})(jQuery);