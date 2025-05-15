 <!-- start footer-->
 <div id="layoutDefault_footer">
     <footer class="footer pt-10 pb-5 mt-auto bg-light footer-light">
         <div class="container">
             <div class="row">
                 <div class="col-lg-3">
                     <div class="footer-brand">SMS Handover</div>
                     <div class="mb-3">Premium Messaging</div>
                     <div class="icon-list-social mb-5">
                         <a class="icon-list-social-link" href="https://www.linkedin.com/company/smshandover"><i class="fab fa-linkedin"></i></a>
						 
                         <a class="icon-list-social-link" href="https://www.instagram.com/smshandover/"><i class="fab fa-instagram"></i></a><a class="icon-list-social-link" href="https://www.facebook.com/profile.php?id=100088700426059&mibextid=ZbWKwL"><i class="fab fa-facebook"></i></a><a class="icon-list-social-link" href="https://twitter.com/SMSHandover"><i class="fab fa-twitter"></i></a>
						 
                     </div>
                 </div>
                 <div class="col-lg-9">
                     <div class="row">
                         <div class="col-lg-4 col-md-6 mb-5 mb-lg-0">
                             <div class="text-uppercase-expanded text-xs mb-4">
                                 Solutions
                             </div>
                             <ul class="list-unstyled mb-0">
                                 <li class="mb-2">
                                     <a href="/bulk-sms">Bulk SMS</a>
                                 </li>
                                 <li class="mb-2">
                                     <a href="/sms-api">HTTP/XML API</a>
                                 </li>
                                 <li class="mb-2">
                                     <a href="/click-tracking">Click Tracking</a>
                                 </li>
                                 <li><a href="/smpp-api">SMPP Server</a></li>
                             </ul>
                         </div>
                         <div class="col-lg-4 col-md-6 mb-5 mb-lg-0">
                             <div class="text-uppercase-expanded text-xs mb-4">
                                 Technical
                             </div>
                             <ul class="list-unstyled mb-0">
                                 <li class="mb-2">
                                     <a href="/developer-docs">Documentation</a>
                                 </li>
                                 <li class="mb-2">
                                     <a href="/api-status">API Status</a>
                                 </li>
                             </ul>
                         </div>

                         <div class="col-lg-4 col-md-6">
                             <div class="text-uppercase-expanded text-xs mb-4">
                                 Company
                             </div>
                             <ul class="list-unstyled mb-0">
                                 <li class="mb-2">
                                     <a href="/about">About</a>
                                 </li>
								 <li class="mb-2">
                                     <a href="/partners">Our Partners</a>
                                 </li>
                                 <li class="mb-2">
                                     <a href="/industries">Industries</a>
                                 </li>
                                 <li class="mb-2">
                                     <a href="/privacy">Privacy Policy</a>
                                 </li>
                                 <li class="mb-2">
                                     <a href="/terms">Terms &amp; Conditions</a>
                                 </li>
                             </ul>
                         </div>
                     </div>
                 </div>
             </div>
             <hr class="my-5" />
             <div class="row align-items-center">
                 <div class="col-md-6 small">
                     Copyright &copy; SMS Handover 2024
                 </div>
                 <div class="col-md-6 text-md-right small">
                     <a href="/privacy">Privacy Policy</a>
                     &middot;
                     <a href="/terms">Terms &amp; Conditions</a>
                 </div>
             </div>
         </div>
     </footer>
 </div>
 <!-- end footer-->
 </div>
 <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
 <script src="<?php echo get_template_directory_uri(); ?>/js/scripts.js"></script>
 <script src="<?php echo get_template_directory_uri(); ?>/js/prism.js"></script>
 <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
 <script>
     AOS.init({
         disable: "mobile",
         duration: 600,
         once: true,
     });
     Prism.highlightAll();
 </script>
 </body>
 <style>
     .label-info {
         background-color: #35b8e0;
     }

     .label {
         display: inline;
         padding: 0.3em 0.6em 0.3em;
         font-size: 75%;
         color: #fff;
         border-radius: 0.25em;
     }

     .badge,
     .label {
         font-weight: 700;
         line-height: 1;
         white-space: nowrap;
         text-align: center;
     }

     .label,
     sub,
     sup {
         vertical-align: baseline;
     }

     .label-success {
         background-color: #10c469;
     }

     code {
         white-space: pre-wrap !important;
         /* Since CSS 2.1 */
         white-space: -moz-pre-wrap;
         /* Mozilla, since 1999 */
         white-space: -pre-wrap;
         /* Opera 4-6 */
         white-space: -o-pre-wrap;
         /* Opera 7 */
         display: block !important;
         max-width: 800px !important;
         word-wrap: break-word !important;
         /* Internet Explorer 5.5+ */
     }

     .label-danger {
         background-color: #e81500;
     }
 </style>

 </html>