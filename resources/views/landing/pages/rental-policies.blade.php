@extends('landing.master')
@section('title','Rental Policies')

@push('style')
    <style>
        .rid-menubar ul li a {
            font-size: 15px !important;
            margin-right: 20px !important;
        }

        .terms-container {
            background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
            min-height: 100vh;
            padding: 60px 0;
        }

        .main-title {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 3rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

        .terms-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(243, 54, 79, 0.1);
            margin-bottom: 2rem;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: white;
        }

        .terms-card:hover {
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

        .section-number {
            background: rgba(255, 255, 255, 0.2);
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

        .list-lower-alpha {
            list-style-type: lower-alpha;
            padding-left: 1.5rem;
        }

        .list-lower-alpha li {
            margin-bottom: 0.8rem;
            line-height: 1.6;
            color: #444;
        }

        .refund-table {
            background: #fff5f6;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-left: 4px solid #F3364F;
        }

        .refund-table ul {
            margin: 0;
            padding-left: 1rem;
        }

        .refund-table li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #f8d7da;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .refund-table li:last-child {
            border-bottom: none;
        }

        .refund-table h5 {
            color: #F3364F;
            font-weight: 600;
        }

        .highlight-box {
            background: #fff5f6;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-left: 4px solid #F3364F;
        }

        .contact-info {
            background: #F3364F;
            color: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            text-align: center;
        }

        .contact-info h5 {
            color: white;
            margin-bottom: 0.5rem;
        }

        .insurance-explanation {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            margin: 2rem 0;
            border: 2px solid #F3364F;
        }

        .insurance-title {
            color: #F3364F;
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .prohibited-list {
            background: #fff5f6;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1rem 0;
            border-left: 4px solid #F3364F;
        }

        .prohibited-list ul {
            margin: 0;
            color: #d12a3f;
        }

        .prohibited-list h6 {
            color: #F3364F;
            font-weight: 600;
        }

        .charges-list {
            background: #fff5f6;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1rem 0;
            border-left: 4px solid #F3364F;
        }

        .charges-list h6 {
            color: #F3364F;
            font-weight: 600;
        }

        .important-note {
            background: #F3364F;
            color: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            text-align: center;
            font-weight: 600;
            border: 2px solid #d12a3f;
        }

        .company-footer {
            background: linear-gradient(135deg, #F3364F 0%, #d12a3f 100%);
            color: white;
            border-radius: 10px;
            padding: 2rem;
            margin-top: 2rem;
            text-align: center;
        }

        .company-footer a {
            color: white;
            text-decoration: none;
        }

        .company-footer a:hover {
            color: #f8f9fa;
            text-decoration: underline;
        }

        .badge.bg-success {
            background-color: #28a745 !important;
        }

        .badge.bg-warning {
            background-color: #ffc107 !important;
            color: #212529;
        }

        .badge.bg-orange {
            background-color: #fd7e14 !important;
        }

        .badge.bg-danger {
            background-color: #F3364F !important;
        }

        .text-primary {
            color: #F3364F !important;
        }

        .border-primary {
            border-color: #F3364F !important;
        }

        .btn-primary {
            background-color: #F3364F;
            border-color: #F3364F;
        }

        .btn-primary:hover {
            background-color: #d12a3f;
            border-color: #d12a3f;
        }

        @media (max-width: 768px) {
            .terms-container {
                padding: 30px 0;
            }

            .main-title {
                font-size: 2rem;
                margin-bottom: 2rem;
            }

            .card-body-custom {
                padding: 1.5rem;
            }

            .section-number {
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
    <div class="terms-container">
        <div class="container">
            <h2 class="main-title text-center">Rental Terms and Conditions</h2>

            <div class="row justify-content-center">
                <div class="col-12">
                    <?php
                    $cnt = 1;
                    ?>
                            <!-- Section 1: General -->
                    @if($policies->isNotEmpty())

                        @foreach($policies as $policy)
                            <div class="terms-card">
                                <div class="card-header-custom">
                                    <h4 class="section-title">
                                        <span class="section-number"><?= $cnt ?></span>
                                        {{$policy->key ?? '-'}}
                                    </h4>
                                </div>
                                <div class="card-body-custom">
                                    {!! $policy->value !!}
                                </div>
                            </div>
                                <?php $cnt++; ?>

                        @endforeach
                    @endif

{{--                    <!-- Section 2: Booking -->--}}
{{--                    <div class="terms-card">--}}
{{--                        <div class="card-header-custom">--}}
{{--                            <h4 class="section-title">--}}
{{--                                <span class="section-number">2</span>--}}
{{--                                Booking--}}
{{--                            </h4>--}}
{{--                        </div>--}}
{{--                        <div class="card-body-custom">--}}
{{--                            <ol class="list-lower-alpha">--}}
{{--                                <li>Bookings are made via our booking system on the website, by email or by telephone.</li>--}}
{{--                                <li><strong>Payment in full must be made via the payment system on the website to confirm a booking.</strong></li>--}}
{{--                                <li>Small changes in dates are permitted but the rental period is fixed. A reduction in days follows our cancellation policy.</li>--}}
{{--                                <li>Cancellations must be made in writing (email).</li>--}}
{{--                                <li>A standard refund is paid based on the total amount due including the 50% deposit if reservation is canceled. Please note, EZ Moto Kansai is not able to make any refunds if there is a cancellation 7 days or less prior to pick up. Refunds will not be given if a cancelation is made during the rental period.</li>--}}
{{--                            </ol>--}}

{{--                            <div class="refund-table">--}}
{{--                                <h5 class="mb-3">Refund Schedule</h5>--}}
{{--                                <p class="mb-3">Refunds will be paid if the reservation is canceled based on the following prior to the pick up time:</p>--}}
{{--                                <ul class="list-unstyled">--}}
{{--                                    <li><span>More than 30 days prior:</span> <span class="badge bg-success">75%</span></li>--}}
{{--                                    <li><span>30-21 days prior:</span> <span class="badge bg-warning">50%</span></li>--}}
{{--                                    <li><span>20-8 days prior:</span> <span class="badge bg-orange">25%</span></li>--}}
{{--                                    <li><span>7 or less days prior:</span> <span class="badge bg-danger">0%</span></li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}

{{--                            <ol class="list-lower-alpha" start="6">--}}
{{--                                <li>With all efforts EZ Moto Kansai will provide the vehicle originally requested in the booking, however due to unseen circumstances it may be necessary to provide a substitute vehicle of a similar class. Amends will be made to the cost if the substitute vehicle is of a lower class. For a higher class the cost will remain the same as when booked. EZ Moto Kansai will notify the renter as early as possible if this situation arises.</li>--}}
{{--                            </ol>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <!-- Section 3: Insurance -->--}}
{{--                    <div class="terms-card">--}}
{{--                        <div class="card-header-custom">--}}
{{--                            <h4 class="section-title">--}}
{{--                                <span class="section-number">3</span>--}}
{{--                                Insurance--}}
{{--                            </h4>--}}
{{--                        </div>--}}
{{--                        <div class="card-body-custom">--}}
{{--                            <ol class="list-lower-alpha">--}}
{{--                                <li>The rental motorcycle (251cc or more) is insured with compulsory third party insurance plus a rental insurance policy covering: (Third part injury: Unlimited, Third Party Property: Unlimited, Personal injury: 80,000,000 yen, Property: 2,000,000yen, passenger: 5,000,000 yen, an excess of 300,000 is payable on all claims). For mopeds or vehicles from 51cc-250cc, only personal unrestricted damages are covered. Damages that exceed the amount covered by the insurance policy is assumed by the renter. The vehicle insurance doesn't cover this and is unrestricted.</li>--}}
{{--                                <li><strong>Damage to the vehicle from a single vehicle accident are solely the responsibility of the renter</strong> who is liable for the full repair or replacements costs of the vehicle, unless however the excess reduction insurance (ERI) of 1000jpy/day is purchased whereby the excess is reduced to a maximum of 300,000jpy.</li>--}}
{{--                                <li>Accidents involving other vehicles will be decided by the police who give a percentage blame. This is contestable and EZ Moto Kansai will do our utmost to reduce this further if at all possible.</li>--}}
{{--                                <li>If any minor infringement notices are issued due to or connected with the accident including but not limited to speeding or driving on the other side of the road (intentionally or not) EZ Moto Kansai's ERI will be null and void. (Case example: renter collided with a car while they were speeding and received an infringement notice from the police investigation. The renter took out EZ Moto Kansai's Excess Waiver Reduction (ERI) however this was nulled and voided due to the speeding infringement notice. The renter was liable for 100% of the rental vehicle's losses.)</li>--}}
{{--                                <li>Accidents resulting in an infringement notice to the renter including but not limited to speeding, driving while intoxicated may cancel any insurance cover and any damages will be solely the responsibility of the renter and may/will be pursued in a court of law.</li>--}}
{{--                                <li>Any occurrence of theft or accident must be reported immediately to the police or the nearest police box and EZ Moto Kansai quoting the accident or crime number provided by the police. You must never under any circumstances accept responsibility or make or receive any out of court settlements.</li>--}}
{{--                            </ol>--}}

{{--                            <div class="contact-info">--}}
{{--                                <h5>Emergency Contact</h5>--}}
{{--                                <p class="mb-0"><strong>EZ Moto Kansai: 06 4864 2081</strong></p>--}}
{{--                            </div>--}}

{{--                            <div class="insurance-explanation">--}}
{{--                                <h5 class="insurance-title">Our insurance in Layman's terms</h5>--}}
{{--                                <p>Our rental insurance covers everything legally required by Japanese law as laid out in our terms and conditions. However, damage to the bike regardless of fault is not covered, this is far too expensive and not financially viable to have as a business expense plus with the nature of the rental bike business there are more claims than private ownership resulting in higher and higher premiums so unfortunately the customer has to cover this. This is commonplace in Japan and as far as we know there are no companies offering full insurance to cover bike damage. In the case of single vehicle accident, this excess is unlimited and could potentially be the amount to replace the whole bike. Some travel insurance policies cover this so we urge you to take out this kind of cover if available. We offer the excess reduction insurance of 1000/day which reduces the excess from unlimited to a maximum of 300,000 jpy. This is not available on some bikes so please do ask when booking. In the case of multiple vehicle accidents, a 300,000 jpy excess is automatically charged and will be adjusted depending on the police investigation where they award percentage blame and insurance is paid accordingly. In Japan, the larger vehicle has an automatic higher percentage blame, so a car has responsibility over a motorcycle, a motocycle over a bicycle etc. So riding a motorcycle is finacially percentage blame wise safer. However it is extremely rare to be given 0% blame, being on the road anyway you are considered to be partly to blame for any accident involving yourself so typically if you are hit while stationary at a traffic light then you are awarded 10% blame and your insurance is calculated on that. This percentage blame is contestable and we at EZ Moto will do our best to reduce this as best we can and then refund any monies owed. Camera footage always helps so if you do have your own GoPro etc then please do use it.</p>--}}
{{--                            </div>--}}

{{--                            <h6 class="insurance-title">Insurance restrictions and uses</h6>--}}
{{--                            <ol>--}}
{{--                                <li>If any of the terms and conditions are broken, voluntary insurance and our standard vehicle compensation become null and void.</li>--}}
{{--                                <li>If in the case of an accident, you settle privately, any later insurance claims are null and void and are not covered by the optional insurance. Any further costs are the burden of the renter. All processes at the accident site must be completed by the renter.</li>--}}
{{--                                <li>If the damages exceed the amount of compensation covered by insurance it becomes the burden of the renter to settle the damages.</li>--}}
{{--                                <li>Theft is not covered under the insurance. This becomes the burden of the renter and EZ Moto will demand compensation. The replacement costs will be calculated by EZ Moto Kansai and is based on the market value of the vehicle or parts.</li>--}}
{{--                                <li>Any unsettled costs which are unpaid by the customer including but not limited to parking tickets, speeding fines, damage excess etc for which the renter then skips the country will be pursued aggressively according to the law of Japan and that of the renter's native country, and may include being being detained at customs if a crime is reported against the renter.</li>--}}
{{--                            </ol>--}}

{{--                            <ol class="list-lower-alpha" start="7">--}}
{{--                                <li>The renter is liable for the following one off payment excess in the case of a vehicle being unable to be rented out due to any accident or occurrence. (It is not related to any time period and is a uniform charge).</li>--}}
{{--                            </ol>--}}

{{--                            <div class="highlight-box">--}}
{{--                                <p class="mb-0">--}}
{{--                                    <strong>50～125cc = ¥20,000</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--}}
{{--                                    <strong>126cc or more = ¥40,000</strong>--}}
{{--                                </p>--}}
{{--                            </div>--}}

{{--                            <p>We will estimate and claim the cost of any parts and repair costs caused by a fall or accident. However, accidents which occur with a third party are to be reported to the police who investigate and issue a percentage blame ratio. The insurance claim by the blame ratio is claimed to the opponent for the person in question. The renter is liable for an excess of 300,000jpy (see 3. Insurance clause c) for the immunity of responsibility of the person's fault though the optional insurance is paid to the other party. In the case of theft, It is necessary to make repairs or to replace the damaged vehicle. (note 4, <strong>Insurance restrictions and uses.)</strong></p>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <!-- Section 4: Prohibited Use -->--}}
{{--                    <div class="terms-card">--}}
{{--                        <div class="card-header-custom">--}}
{{--                            <h4 class="section-title">--}}
{{--                                <span class="section-number">4</span>--}}
{{--                                Prohibited Use and Breach of Contract--}}
{{--                            </h4>--}}
{{--                        </div>--}}
{{--                        <div class="card-body-custom">--}}
{{--                            <ol class="list-lower-alpha">--}}
{{--                                <li>The motorcycle must under no circumstances be used for or have any the following activities applied to it:</li>--}}
{{--                            </ol>--}}

{{--                            <div class="prohibited-list">--}}
{{--                                <h6>Strictly Prohibited</h6>--}}
{{--                                <ul>--}}
{{--                                    <li>Be transferred or sold to another person.</li>--}}
{{--                                    <li>Used for racing on or off road, or used on any closed circuit for track days etc.</li>--}}
{{--                                    <li>Have any modifications made to the vehicle.</li>--}}
{{--                                    <li>Be used for commercial purposes.</li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}

{{--                            <p>If the contract is broken, the customer is liable for the following charges or cancellation costs:</p>--}}

{{--                            <div class="charges-list">--}}
{{--                                <h6>Breach of Contract Charges</h6>--}}
{{--                                <ul>--}}
{{--                                    <li>Prohibitions, (Clause 4), Rental x 1.5 times the contracted rental period.</li>--}}
{{--                                    <li>Parking Violations, (Clause 4-c), if the vehicle is returned with outstanding violations the amount will be calculated.</li>--}}
{{--                                    <li>Repeated prohibited use, breach of contract or repeated violations and we consider it malicious we will cancel any future rental.</li>--}}
{{--                                    <li>Non return (Clause 4-d), we charge the extension rate based on the charge table for every one hour of unarranged rental and with no contact from the renter at the basic hourly rate x 1.5.</li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}

{{--                            <ol class="list-lower-alpha" start="2">--}}
{{--                                <li>EZ Moto solely deal in renting a vehicle to a customer and do not supply or introduce any labour of 3rd party drivers. Hired or paid drivers are forbidden to drive our vehicles.</li>--}}
{{--                                <li>Parking tickets/fines must be paid by the renter themselves when at all possible and depend on that specific infringement. The renter is liable for all associated costs for example when the vehicle is impounded. If the vehicle is returned with outstanding parking tickets/fines/infringements then the renter is liable for the extra unlimited costs of processing these notices. Please contact us for advice on how to pay locally.</li>--}}
{{--                                <li>Please contact us immediately if you have passed or are going to miss the return date. (we will charge the extension rate based on the charge table for every one hour of unarranged rental if there is no contact from renter. We also charge the rental fee for every one hour over the agreed hire time. ) For 1 week or less rental a rental day is calculated at a 24 hour block, e.g. 10am to 10am. For longer than 1 week rental more leeway is given but the renter must abide by the agreed return time, please confirm on sign up. Extension rate for arranged time is based on the length of the current contract. Extension rate for late notified returns is 2500ypy/hour. Extension rate for unarranged returns with no contact from the renter 3750jpy/hour.</li>--}}
{{--                            </ol>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <!-- Section 5: Rental -->--}}
{{--                    <div class="terms-card">--}}
{{--                        <div class="card-header-custom">--}}
{{--                            <h4 class="section-title">--}}
{{--                                <span class="section-number">5</span>--}}
{{--                                Rental--}}
{{--                            </h4>--}}
{{--                        </div>--}}
{{--                        <div class="card-body-custom">--}}
{{--                            <ol class="list-lower-alpha">--}}
{{--                                <li>EZ Moto Kansai reserve the right to cancel the rental contract in the case of any accident, theft or natural disaster for which EZ Moto Kansai has no control over, and are not liable to replace a vehicle or refund any rental cost if there is time remaining on the contract.</li>--}}
{{--                                <li>The renter must conduct necessary daily inspection and maintenance of the Rental Vehicle before use if rented for more than two days.</li>--}}
{{--                                <li>Please confirm and understand in store on pick up that we supply vehicles covered by two types of insurance, and that you agree and understand the conditions of both.</li>--}}
{{--                                <li>In the case of lost keys, the renter is liable for the actual replacement costs only, however if further costs are incurred the renter is liable for those.</li>--}}
{{--                                <li>If rental goods or extras are damaged, the customer is liable to be charged a fixed amount for each item. Please confirm it in the details when you return the bike. The replacement costs are based on the RRP of the specific parts.</li>--}}
{{--                                <li>Except in the case of a rental being canceled by us, we do not refund the full amount. If we ask for a return earlier than is contracted due to unforeseen circumstances the rate will be fully refunded, for example, for 1 week out of a monthly rental of ¥63000, ¥63,000-¥31,500 = ¥31500will be refunded.</li>--}}
{{--                            </ol>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <!-- Section 6: When in Use -->--}}
{{--                    <div class="terms-card">--}}
{{--                        <div class="card-header-custom">--}}
{{--                            <h4 class="section-title">--}}
{{--                                <span class="section-number">6</span>--}}
{{--                                When in Use.--}}
{{--                            </h4>--}}
{{--                        </div>--}}
{{--                        <div class="card-body-custom">--}}
{{--                            <ol class="list-lower-alpha">--}}
{{--                                <li>This agreement must be carried at all times during the rental period without exception and presented to any police officer, District Transport Bureau or Land Transport Office who demands it.</li>--}}
{{--                                <li>If not a resident of Japan, your passport must be carried at all times.</li>--}}
{{--                                <li>Your license or IDP must be carried at all times.</li>--}}
{{--                                <li>Copies of the vehicle's shaken and insurance are supplied with the vehicle.</li>--}}
{{--                                <li>Failure to carry the above three documents will result in heavy penalties by the police and more so in the case of any accidents or infringements.</li>--}}
{{--                                <li>The renter must conduct necessary daily inspection and maintenance of the Rental Vehicle before use if rented for more than two days. Any problems must be reported to EZ Moto Kansai, failure to undertake required maintenance resulting in damage and EZ Moto Kansai judge that it is due to the renter's negligence then any costs will be the renter's liability.</li>--}}
{{--                                <li>Punctures are the responsibility of the renter to repair or replace the tyre if necessary.</li>--}}
{{--                                <li>EZ Moto Kansai will not be held liable for any unforeseen mechanical breakdown rendering the vehicle unusable or for any time or travel costs involved in picking up a replacement vehicle. The renter is highly recommended to take out travel insurance to cover such costs.</li>--}}
{{--                                <li>EZ Moto Kansai will not be held liable for any losses due to natural disaster or similar unavoidable occurrences.</li>--}}
{{--                                <li>EZ Moto Kansai will not be held liable for any injuries, sickness or death of any renter, passenger, third party or anyone associated with the rental. The renter entirely indemnifies EZ Moto Kansai of any claims whatsoever.</li>--}}
{{--                                <li>Gasoline is full when rented; it must be full on return. A 3000jpy fee will be charged for any vehicles returned without being full.</li>--}}
{{--                                <li>If carrying a passenger, the passenger must also read these terms and conditions.</li>--}}
{{--                            </ol>--}}

{{--                            <div class="important-note">--}}
{{--                                <strong>NOTE: Japan has a 0 tolerance policy for drink driving.</strong>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <!-- Company Information Footer -->--}}
{{--                    <div class="company-footer">--}}
{{--                        <p class="mb-0">--}}
{{--                            <strong>EZ Moto Kansai</strong>, 4/10 Senrioka Shimo, Suita Shi, Osaka, Japan. 565-0813, 06-4864-2081--}}
{{--                            <a href="mailto:info@ezmotokansai.com">info@ezmotokansai.com</a>--}}
{{--                            <a href="http://www.ezmotokansai.com">www.ezmotokansai.com</a>--}}
{{--                        </p>--}}
{{--                    </div>--}}

                </div>
            </div>
        </div>
    </div>
@endsection