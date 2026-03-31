@extends('landing.master')
@section('title','License Requirements')

@push('style')
    <style>
        .rid-menubar ul li a {
            font-size: 15px !important;
            margin-right: 20px !important;
        }
        .license-container {
            background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
            min-height: 100vh;
            padding: 60px 0;
        }

        .main-title {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 3rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
        }

        .main-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, #F3364F, #d12a3f);
            border-radius: 2px;
        }

        .license-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(243, 54, 79, 0.1);
            margin-bottom: 2rem;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: white;
        }

        .license-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(243, 54, 79, 0.15);
        }

        .card-header-custom {
            background: linear-gradient(135deg, #F3364F 0%, #d12a3f 100%);
            color: white;
            padding: 1rem 1.5rem;
            border-bottom: none;
        }

        .section-title {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .group-number {
            background: rgba(255,255,255,0.2);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .card-body-custom {
            padding: 2rem;
            background: white;
        }

        .congratulations-box {
            background: #fff5f6;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid #F3364F;
            font-weight: 600;
            color: #2c3e50;
        }

        .documentation-note {
            background: #F3364F;
            color: white;
            border-radius: 10px;
            padding: 1rem 1.5rem;
            margin: 1.5rem 0;
            text-align: center;
            font-weight: 600;
        }

        .flags-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            margin: 2rem 0;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .flag {
            width: 80px;
            height: 60px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .flag:hover {
            transform: scale(1.1);
        }

        .flag img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .requirements-box {
            background: #fff5f6;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-left: 4px solid #F3364F;
            text-align: center;
            font-weight: 600;
            color: #2c3e50;
        }

        .license-image {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            margin: 1rem 0;
        }

        .important-notes {
            background: #fff5f6;
            border-radius: 10px;
            padding: 2rem;
            margin: 2rem 0;
            border: 2px solid #F3364F;
        }

        .special-notes {
            background: #F3364F;
            color: white;
            border-radius: 10px;
            padding: 2rem;
            margin: 2rem 0;
        }

        .special-notes h6 {
            color: white;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .special-notes img {
            width: 40px;
            height: 30px;
            margin: 0 5px;
            border-radius: 4px;
        }

        .translation-service {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            margin: 2rem 0;
            border: 2px solid #F3364F;
        }

        .translation-service h5 {
            color: #F3364F;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .jaf-logos {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 1rem 0;
            flex-wrap: wrap;
        }

        .jaf-logos img {
            max-height: 50px;
            width: auto;
        }

        .note-list {
            background: #fff5f6;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1rem 0;
            border-left: 4px solid #F3364F;
        }

        .note-list ol {
            margin: 0;
            padding-left: 1.5rem;
        }

        .note-list li {
            margin-bottom: 0.8rem;
            line-height: 1.6;
            color: #444;
        }

        @media (max-width: 768px) {
            .license-container {
                padding: 30px 0;
            }

            .main-title {
                font-size: 2rem;
                margin-bottom: 2rem;
            }

            .card-body-custom {
                padding: 1.5rem;
            }

            .flags-container {
                gap: 10px;
                padding: 1rem;
            }

            .flag {
                width: 60px;
                height: 45px;
            }

            .group-number {
                width: 28px;
                height: 28px;
                font-size: 0.8rem;
            }

            .section-title {
                font-size: 1rem;
            }
        }
    </style>
@endpush

@section('main')
    <div class="license-container">
        <div class="container">
            <h2 class="main-title text-center">License Requirements</h2>

            <div class="row justify-content-center">
                <div class="col-12">

                    <!-- Introduction -->
                    <div class="congratulations-box">
                        <strong>Congratulations you can drive or ride in Japan if you have the above license issued to you from that country. Also please check notes regarding foreign driving licenses at the end.</strong><br><br>
                        1. Monaco, France and Belgium: Please see especial note at the bottom of this page.<br>
                        2. Italy, Poland and Russia: Please see especial note at the bottom of this page.<br>
                        3. United States: Please see especial note at the bottom of this page.
                    </div>

                    <div class="documentation-note">
                        The documentation needed depends on the country:
                    </div>

                    <!-- Group 1 -->
                    <div class="license-card">
                        <div class="card-header-custom">
                            <h4 class="section-title">
                                <span class="group-number">1</span>
                                GROUP 1
                            </h4>
                        </div>
                        <div class="card-body-custom">
                            <div class="flags-container">
                                <div class="flag"><img src="{{asset('assets/license/Australia.png')}}" alt="Australia"></div>
                                <div class="flag"><img src="{{asset('assets/license/New-Zealand.png')}}" alt="New Zealand"></div>
                                <div class="flag"><img src="{{asset('assets/license/Canada.png')}}" alt="Canada"></div>
                                <div class="flag"><img src="{{asset('assets/license/Denmark.png')}}" alt="Denmark"></div>
                                <div class="flag"><img src="{{asset('assets/license/Hong-Kong.png')}}" alt="Hong Kong"></div>
                                <div class="flag"><img src="{{asset('assets/license/India.png')}}" alt="India"></div>
                                <div class="flag"><img src="{{asset('assets/license/Ireland.png')}}" alt="Ireland"></div>
                                <div class="flag"><img src="{{asset('assets/license/Israel.png')}}" alt="Israel"></div>
                                <div class="flag"><img src="{{asset('assets/license/United-States.png')}}" alt="United States"></div>
                                <div class="flag"><img src="{{asset('assets/license/United-Kingdom.png')}}" alt="United Kingdom"></div>
                                <div class="flag"><img src="{{asset('assets/license/Spain.png')}}" alt="Spain"></div>
                                <div class="flag"><img src="{{asset('assets/license/South-Korea.png')}}" alt="South Korea"></div>
                                <div class="flag"><img src="{{asset('assets/license/Russia.png')}}" alt="Russia"></div>
                                <div class="flag"><img src="{{asset('assets/license/Norway.png')}}" alt="Norway"></div>
                                <div class="flag"><img src="{{asset('assets/license/Sweden.png')}}" alt="Sweden"></div>
                            </div>

                            <div class="requirements-box">
                                Home Country Driving License + International Driving Permit<br>
                                (based on the Geneva International Road Traffic Convention of 19th September 1949)
                            </div>

                            <div class="text-center">
                                <img src="{{asset('assets/license/licence.jpg')}}" alt="licence" class="license-image">
                            </div>

                            <div class="requirements-box">
                                ※If in the International Driving Permit has<br>
                                "1968" written in the front cover, it is NOT valid in Japan:<br><br>
                                <strong>CANNOT Drive in Japan</strong><br>
                                ↓<br>
                                It also has to be stamped on "A" for motorcycles, and the 1 year validity has to be in force:
                            </div>

                            <div class="text-center">
                                <img src="{{asset('assets/license/idp-edited.jpg')}}" alt="IDP" class="license-image">
                            </div>

                            <div class="important-notes">
                                <p>If your country signed several International Road Traffic conventions (Paris, Geneva, Vienna), please make sure that you ask the traffic authorities to issue the International Driving Permit based on the 19th September 1949 Geneva Convention because this is the only one valid in Japan.</p>

                                <p>Full list of countries Listed at the top of this page.<br>
                                    Important notes regarding the International Driving Permit (IDP): If your driving license was issued in a country not listed above, you cannot drive in Japan. Also please check notes regarding foreign driving licenses at the end.</p>

                                <div class="note-list">
                                    <ol>
                                        <li>It has to be issued in the same country than the driver license.</li>
                                        <li>It has to be based on the 1949 Geneva Convention. Only these are recognized by Japan. It has to say "1949 Geneva Convention" on the front cover.</li>
                                        <li>Check that the IDP is not expired.</li>
                                        <li>Check that the IDP is correctly stamped for motorcycle use.</li>
                                        <li>Check that the IDP is issued by the correct licensing authority.</li>
                                        <li>The IDP has no validity on its own. It has to be accompanied at all times by a valid driving license.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Group 2 -->
                    <div class="license-card">
                        <div class="card-header-custom">
                            <h4 class="section-title">
                                <span class="group-number">2</span>
                                GROUP 2
                            </h4>
                        </div>
                        <div class="card-body-custom">
                            <div class="flags-container">
                                <div class="flag"><img src="{{asset('assets/license/Belgium.png')}}" alt="Belgium"></div>
                                <div class="flag"><img src="{{asset('assets/license/France.png')}}" alt="France"></div>
                                <div class="flag"><img src="{{asset('assets/license/Germany.png')}}" alt="Germany"></div>
                                <div class="flag"><img src="{{asset('assets/license/Switzerland.png')}}" alt="Switzerland"></div>
                                <div class="flag"><img src="{{asset('assets/license/Indonesia.png')}}" alt="Indonesia"></div>
                                <div class="flag"><img src="{{asset('assets/license/Slovenia.png')}}" alt="Slovenia"></div>
                                <div class="flag"><img src="{{asset('assets/license/Taiwan.png')}}" alt="Taiwan"></div>
                                <div class="flag"><img src="{{asset('assets/license/estonia.jpg')}}" alt="Estonia"></div>
                            </div>

                            <div class="requirements-box">
                                Home Country Driving License + Official Japanese translation of the Driving license
                            </div>

                            <div class="text-center">
                                <img src="{{asset('assets/license/licence2.jpg')}}" alt="licence" class="license-image">
                            </div>

                            <div class="important-notes">
                                <p>If your license is issued in Belgium, France, Germany, Switzerland, Monaco, Slovenia, Taiwan or Estonia you will need to obtain an official Japanese translation of your home country driving license. This can be obtained at the <a href="http://www.jaf.or.jp/inter/translation/specific_e.htm" target="_blank" style="color: #F3364F; font-weight: 600;">Japanese Automobile Federation</a> or official authorities like Embassies and consulates.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Group 3 -->
                    <div class="license-card">
                        <div class="card-header-custom">
                            <h4 class="section-title">
                                <span class="group-number">3</span>
                                GROUP 3
                            </h4>
                        </div>
                        <div class="card-body-custom">
                            <div class="requirements-box">
                                Japanese Driving License
                            </div>

                            <div class="text-center">
                                <img src="{{asset('assets/license/licence-jpn.jpg')}}" alt="Japanese licence" class="license-image">
                            </div>

                            <div class="important-notes">
                                <p>If you have a Japanese driving license, you will not need any additional document.</p>

                                <h6 style="color: #F3364F; font-weight: 600; margin-top: 2rem;">Notes regarding foreign driving licenses:</h6>
                                <div class="note-list">
                                    <ol>
                                        <li>Please check the expiration date, make sure it is still valid.</li>
                                        <li>Does your motorcycle license have any restrictions like engine displacement, transmission type, etc.? If so, the same restrictions will apply in Japan.</li>
                                        <li>You must have spent 3 months in the country where your motorcycle license was issued after obtaining the license. This is a requirement by Japanese law.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Special Notes -->
                    <div class="special-notes">
                        <h5 style="color: white; font-weight: 600; margin-bottom: 2rem;">Special Notes:</h5>

                        <div style="margin-bottom: 2rem;">
                            <img src="{{asset('assets/license/Belgium.png')}}" alt="Belgium">
                            <img src="{{asset('assets/license/France.png')}}" alt="France">
                            <img src="{{asset('assets/license/Monaco.png')}}" alt="Monaco">
                            <br><br>
                            <strong>License holders from Belgium, France and Monaco have two options:</strong><br>
                            • Obtain a 1949 Geneva Convention based IDP, as the "Group 1" countries mentioned above.<br>
                            • Obtain an official Japanese translation of their driving license, as the "Group 2" countries mentioned above.
                        </div>

                        <div style="margin-bottom: 2rem;">
                            <img src="{{asset('assets/license/Italy.png')}}" alt="Italy">
                            <img src="{{asset('assets/license/Poland.png')}}" alt="Poland">
                            <img src="{{asset('assets/license/Russia.png')}}" alt="Russia">
                            <br><br>
                            <strong>Italy, Poland and Russia signed both the:</strong><br>
                            – 1949 Geneva Convention on road traffic.<br>
                            – 1968 Vienna Convention on road traffic.<br><br>
                            Therefore authorities of these countries have rights to issue IDPs based on both conventions, but they usually issue IDPs based on the 1968 Vienna Convention. However Japan only recognizes IDPs issued based on the 1949 Convention. If you can obtain an IDP based on the 1949 Geneva Convention ("1949 Convention" is written on the front cover) then you can drive in Japan. But with an IDP based on the 1968 Vienna Convention you cannot drive in Japan.
                        </div>

                        <div style="margin-bottom: 2rem;">
                            <img src="{{asset('assets/license/United-States.png')}}" alt="United States">
                            <br><br>
                            In the USA, only two organizations are allowed to sell real, legal IDPs: the <a href="www.aaa.com/PPInternational/IDP_IADP.html" target="_blank" style="color: white; text-decoration: underline;">American Automobile Association,</a> and the American Automobile Touring Alliance, which offers IDPs through the <a href="http://www.nacroadservice.com/nonmember_services.htm" target="_blank" style="color: white; text-decoration: underline;">National Automobile Club.</a>

                            <div class="jaf-logos">
                                <img src="{{asset('assets/license/aaa.png')}}" alt="AAA">
                                <img src="{{asset('assets/license/logo_large.gif')}}" alt="National Automobile Club">
                            </div>
                        </div>
                    </div>

                    <!-- License Translation Service -->
                    <div class="translation-service">
                        <h5>License Translation Service</h5>

                        <p>Some countries require a translation by JAF (Japanese Automobile Federation) of the home license. Group 2 of our license requirement page: <a href="https://bikerentaljapan.com/license" style="color: #F3364F;">https://bikerentaljapan.com/license</a> (Belgium, France, Germany, Switzerland, Monaco, Slovenia, Taiwan or Estonia) and any license that is not in ALPHABET including but not limited to licenses written in Arabic or Russian, or licenses issued in the Republic of Korea, Kingdom of Thailand, Lao People's Democratic Republic, and the Republic of the Union of Myanmar.</p>

                        <p>More information can be seen here: <a href="https://english.jaf.or.jp/driving-in-japan/drive-in-japan/foreign-nationals-license" style="color: #F3364F;">https://english.jaf.or.jp/driving-in-japan/drive-in-japan/foreign-nationals-license</a></p>

                        <p>JAF offers this service in person at one of their offices, the nearest to Bike Rental Japan located here:<br>
                            <a href="https://goo.gl/maps/5c9vHjHN5tkSTqUZ7" style="color: #F3364F;">https://goo.gl/maps/5c9vHjHN5tkSTqUZ7</a></p>

                        <div class="requirements-box">
                            <strong>Service Details:</strong><br>
                            • Next working day service (Friday applications ready on Monday)<br>
                            • Translation cost: ¥4,000<br>
                            • Our service charge: ¥1,000<br>
                            • Total: ¥5,000
                        </div>

                        <p>JAF also provides an online application however this can only be applied for from within Japan and takes 2 weeks so we at Bike Rental Japan offer this service for you so your translation will be ready for you on pick up of your bike.</p>

                        <p><strong>Please select the "License Translation Service 5000 yen" on the "accessories" section of the booking form.</strong></p>

                        <p>For this we require a photo or scan of your full driver's license, front and back, all sections CLEARLY visible.</p>

                        <div class="note-list">
                            <p><strong>Some problems may occur and JAF may not be able to translate the following types of driver's licenses:</strong></p>
                            <ol>
                                <li>Licenses with handwritten sections that are illegible (e.g. the upper left part of the back side of a German driver's license).</li>
                                <li>Paper driver's licenses in particular, with illegible stamps or stamps overlapping the folds of the license, making them illegible (e.g. French driver's license).</li>
                            </ol>
                        </div>

                        <p>In this case you will be required to obtain a new license to be able to proceed with the translation service so please do allow enough time including the 2 week processing time for the JAF translation.</p>

                        <p>Full information for JAF conditions can be seen here, please be sure to read and understand all parts:<br>
                            <a href="https://english.jaf.or.jp/driving-in-japan/drive-in-japan/about-dltas" style="color: #F3364F;">https://english.jaf.or.jp/driving-in-japan/drive-in-japan/about-dltas</a></p>

                        <p><strong>Please note, Bike Rental Japan's cancellation policy will be applied in the case of any delayed license translation processing regardless of fault.</strong></p>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection