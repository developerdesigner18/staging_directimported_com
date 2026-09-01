@extends('landing.master')
@section('title', "FAQ's")

@push('style')
    {{-- <style>
        /* FAQ Card */

        .faq-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            transition: all .3s ease;
        }

        .faq-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        /* Header */

        .faq-header {
            padding: 18px 22px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f7f9fb;
        }

        /* Body */

        .faq-body {
            padding: 20px;
            line-height: 1.7;
            color: #555;
        }

        /* Fix the ugly <pre> blocks */

        .faq-body pre {
            white-space: pre-wrap;
            word-break: break-word;
            font-family: inherit;
            background: transparent;
            border: none;
            padding: 0;
            margin: 0;
        }

        /* Icon */

        .faq-icon {
            font-size: 22px;
            font-weight: 700;
            transition: 0.3s;
            color: darkred;
        }

        .faq-icon i {
            transition: transform 0.3s ease;
        }
    </style> --}}
    <style>
        /* FAQ Container Variables - Direct Imported Branding */
        :root {
            --primary-color: #2c3e50;
            --accent-color: #3498db;
            --bg-color: #f9f9f9;
            --text-color: #333333;
            --border-color: #e0e0e0;
        }

        .faq-container {
            max-width: 960px;
            margin: 40px auto;
            padding: 0 20px;
            color: var(--text-color);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .faq-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .faq-header h1 {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 10px;
            font-weight: 700;
        }

        .faq-header p {
            font-size: 1.1rem;
            color: #666;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .faq-category-title {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-top: 50px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--accent-color);
            font-weight: 600;
        }

        .faq-item {
            background-color: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            margin-bottom: 12px;
            overflow: hidden;
            transition: all 0.2s ease-in-out;
        }

        .faq-item summary {
            padding: 18px 24px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            list-style: none;
            position: relative;
            background-color: var(--bg-color);
            color: var(--primary-color);
        }

        .faq-item summary::after {
            content: '+';
            position: absolute;
            right: 24px;
            font-size: 1.5rem;
            color: var(--accent-color);
            transition: transform 0.3s ease;
        }

        .faq-item[open] summary::after {
            content: '−';
        }

        .faq-item summary::-webkit-details-marker {
            display: none;
        }

        .faq-item summary:hover {
            background-color: #f1f1f1;
        }

        .faq-content {
            padding: 0 24px 24px 24px;
            line-height: 1.7;
        }

        .faq-content p {
            margin-top: 15px;
            margin-bottom: 10px;
        }

        .faq-content ul {
            margin-top: 10px;
            margin-bottom: 10px;
            padding-left: 20px;
        }

        .faq-content li {
            margin-bottom: 8px;
        }

        .faq-footer {
            margin-top: 60px;
            text-align: center;
            padding: 30px;
            background-color: var(--bg-color);
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .faq-footer a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: bold;
        }

        .faq-footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .faq-header h1 {
                font-size: 2rem;
            }

            .faq-item summary {
                font-size: 1rem;
                padding-right: 40px;
            }
        }
    </style>
@endpush

@section('main')
    <!-- FAQ Section Start -->
    <section class="mt-5">
        <div class="container">
            <!-- FAQ Title -->
            {{-- <h2 class="text-center mb-5">Frequently Asked Questions</h2> --}}
            <!-- FAQ  -->
            {{-- <div class="row" id="faqContainer">
                @foreach($faqs as $index => $faq)

                <div class="col-lg-6 mb-4">

                    <div class="faq-card">

                        <div class="faq-header" role="button" data-bs-toggle="collapse" data-bs-target="#faq{{$index}}">

                            {{ $faq->key }}

                            <span class="faq-icon">
                                <i class="bx bx-plus" style="color:#8f0000;"></i>
                            </span>

                        </div>

                        <div id="faq{{$index}}" class="collapse" data-bs-parent="#faqContainer">

                            <div class="faq-body">
                                {!! $faq->value !!}
                            </div>

                        </div>

                    </div>

                </div>

                @endforeach

            </div> --}}

            <div class="faq-container">

                <div class="faq-header">
                    <h1>Direct Imported Japan’s Guide</h1>
                    <p>Everything you need to know about sourcing, evaluating, bidding on, and shipping cars directly from
                        Japanese wholesale markets.</p>
                </div>

                <!-- Category 1: General Information & Sourcing -->
                <h3 class="faq-category-title">Category 1: General Information & Sourcing</h3>

                <details class="faq-item">
                    <summary>What is the step-by-step process to buy a car through your agency?</summary>
                    <div class="faq-content">
                        <p>Importing with us follows four straightforward steps:</p>
                        <ul>
                            <li><strong>System Registration:</strong> Create an account on our platform to browse incoming
                                inventory across more than 100 major Japanese wholesale venues running six days a week.</li>
                            <li><strong>Budget & Target Consultation:</strong> We assist you in reviewing historical sales
                                data to establish realistic expectations for your target model. If you have unrealistic
                                expectations, we will let you know if the budget is not feasible in order to best help you.
                            </li>
                            <li><strong>Retainer & Verification:</strong> Placing a bidding deposit unlocks full access,
                                including sheet translations, site inspections, and live bidding rights.</li>
                            <li><strong>Bidding & Export:</strong> We place live bids up to your specified cap. If
                                successful, we handle all export logistics, port transport, and shipping bookings.</li>
                        </ul>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>What are your office hours?</summary>
                    <div class="faq-content">
                        <p>Our Japan office operates Monday through Friday, 9:00 a.m. to 6:00 p.m. (Japan Standard Time). We
                            are closed on weekends, but we still run skeleton bidding and inspection services for Saturday
                            auctions. We also observe major Japanese public holidays, including New Year's, Golden Week
                            (late April/early May), and the Obon Summer Holiday (mid-August). We do our best to maintain
                            critical communication for live bids during these periods.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>How do wholesale auction prices compare to retail sites like Goo-Net or CarSensor?</summary>
                    <div class="faq-content">
                        <p>Auctions operate at true wholesale floor prices. Buying through auction bypasses retail dealer
                            margins, storage overhead, and dealer reconditioning fees. Sites like Goo-Net reflect fixed
                            consumer pricing, whereas auctions allow you to secure vehicles at true market value before
                            dealer markup is applied. However, buying directly from a dealer can sometimes land a bargain as
                            well, so don't be discouraged.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>What is the difference between preview access and full bidding clearance?</summary>
                    <div class="faq-content">
                        <p>Preview access allows guests to research market trends and view upcoming listings across general
                            auction houses. Full bidding clearance is activated once a deposit is received.</p>
                        <p>Requiring a deposit for full access is necessary because major auction networks (such as USS)
                            enforce strict dealer licensing restrictions. Furthermore, live auction lots move in seconds, so
                            having cleared bidding funds ready ensures we never miss a fast-listing vehicle on your behalf.
                        </p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>Why do Japanese domestic vehicles generally have remarkably low mileage?</summary>
                    <div class="faq-content">
                        <p>Japan’s exceptional train infrastructure means most vehicle owners commute without driving daily,
                            saving cars for occasional weekend trips. Additionally, strict government registration (Shaken)
                            every two years creates a permanent paper trail of mileage history.</p>
                        <p>To ensure total accuracy, our physical inspection process independently validates mileage claims
                            by checking pedal wear, steering wheel condition, brake disc lip depth, and instrument panel
                            tampering indicators.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>Can I ask you to change the vehicle’s odometer reading (mileage) to reduce import taxes?
                    </summary>
                    <div class="faq-content">
                        <p>No. Altering a vehicle’s odometer reading or falsifying documentation is illegal. Direct Imported
                            strictly adheres to all international trade laws and will not participate in any illegal
                            actions, regardless of the reason.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>Are spare keys and English owner manuals provided?</summary>
                    <div class="faq-content">
                        <p>Because we source true Japanese Domestic Market (JDM) vehicles, all included manuals and
                            infotainment systems will be in Japanese. Spare keys are only included if the previous owner
                            provided them to the auction house; if they are not in the glove compartment upon inspection,
                            they are not provided, and you will be responsible for cutting a spare upon arrival.</p>
                    </div>
                </details>


                <!-- Category 2: Vehicle Condition & Pre-Bid Inspections -->
                <h3 class="faq-category-title">Category 2: Vehicle Condition & Pre-Bid Inspections</h3>

                <details class="faq-item">
                    <summary>How do you verify a car's condition before I place a bid?</summary>
                    <div class="faq-content">
                        <p>We combine official documentation with physical verification if in attendance:</p>
                        <ul>
                            <li><strong>Translation Services:</strong> We translate the official auction sheet, decoding all
                                handwritten inspector notes and defect diagrams.</li>
                            <li><strong>On-Site Physical Evaluations:</strong> Whenever possible, our team or vetted local
                                inspection agents attend the venue to inspect the car firsthand and provide up to 30
                                detailed photos to help with your buying decision.</li>
                            <li><strong>3rd Party Inspections:</strong> We have a network of paid inspection services and
                                can sometimes arrange 3rd party inspections for auction or dealer purchases. However, these
                                are paid services; please ask us if this is possible, and we will quote you based on the
                                inspection area.</li>
                        </ul>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>What extra details do your physical inspections look for?</summary>
                    <div class="faq-content">
                        <p>Standard auction sheet evaluations only take a few minutes. Our detailed physical checks go
                            further by testing paint depth with electronic gauges to detect hidden body filler, checking
                            fluid reservoirs (oil/coolant), evaluating tire tread wear, testing power accessories
                            (convertible tops, air suspension), and assessing undercarriage corrosion.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>When do I need to submit my final inspection requests and bid limits?</summary>
                    <div class="faq-content">
                        <p>Because auction events start early in Japan, all inspection requests and maximum bid thresholds
                            must be finalized in our portal by 7:00 AM JST on the morning of the auction. Submitting early
                            gives our ground staff sufficient time to inspect the vehicle before bidding starts.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>Are pre-export inspections required for my country?</summary>
                    <div class="faq-content">
                        <p>Certain countries (such as Kenya, Zambia, and Australia) legally require a mandatory pre-export
                            inspection (e.g., JEVIC, EAA, or Intertek) prior to the vehicle leaving Japan. It is your
                            responsibility to research your local government regulations. If an inspection is mandatory, we
                            will gladly arrange it for you prior to vessel loading.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>Can I request independent Odometer Verification?</summary>
                    <div class="faq-content">
                        <p>Yes. Beyond checking official registration histories (Shaken) and auction databases, we can
                            arrange third-party digital odometer verification services prior to export upon request. The fee
                            is advised based on the type of inspection required, as there are various levels of odometer
                            inspections available.</p>
                    </div>
                </details>


                <!-- Category 3: Bidding Rules & Post-Purchase Services -->
                <h3 class="faq-category-title">Category 3: Bidding Rules & Post-Purchase Services</h3>

                <details class="faq-item">
                    <summary>How does live proxy bidding work?</summary>
                    <div class="faq-content">
                        <p>You authorize a maximum bid limit. During the live auction—which takes under 30 seconds per
                            car—our licensed agent bids incrementally on your behalf. We always attempt to win the lot for
                            as little as possible; if bidding stops below your cap, the savings go directly to you.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>What happens if a car passes without reaching the seller's minimum price?</summary>
                    <div class="faq-content">
                        <p>If a vehicle fails to meet its hidden reserve price, two options usually open up:</p>
                        <ul>
                            <li><strong>Post-Auction Negotiation:</strong> We can submit a formal counter-offer to the
                                seller during a short post-sale negotiation window (a standard negotiation fee applies if
                                successful, ranging from 15,000 JPY to 25,000 JPY on average).</li>
                            <li><strong>Fixed "Buy-It-Now":</strong> Sellers occasionally list unsold cars at a fixed price
                                for the remainder of the day on a first-come, first-served basis. Fees are similar to
                                negotiation costs, ranging from 15,000 JPY to 25,000 JPY on average.</li>
                        </ul>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>Can I retract a bid after winning an auction?</summary>
                    <div class="faq-content">
                        <p>No. In the Japanese wholesale auction system, all winning bids represent legally binding
                            commercial contracts. Once the hammer falls, sales are final and cannot be canceled or returned.
                        </p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>Can you buy cars directly from private Japanese dealerships?</summary>
                    <div class="faq-content">
                        <p>Yes. If you spot a vehicle on dealer platforms like Goo-Net or CarSensor, we can contact the
                            dealership directly, negotiate pricing, inspect the car where possible, and manage the export
                            logistics on your behalf.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>Can you arrange mechanical servicing or parts installation before export?</summary>
                    <div class="faq-content">
                        <p>Yes. After securing a vehicle, we can transport it to our trusted local partner workshops in
                            Japan for routine maintenance, audio system installations, or aftermarket alloy wheels prior to
                            loading onto the vessel. Please inform us of these requests before the final Proforma Invoice is
                            issued.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>Can you purchase aftermarket parts and ship them inside my vehicle?</summary>
                    <div class="faq-content">
                        <p>By international maritime law, we are strictly prohibited from placing loose parts or cargo
                            inside a vehicle if it is being shipped via RoRo (Roll-on/Roll-off) vessels. Loose items can
                            only be loaded if the destination country accepts loose cargo (usually original OEM parts for
                            the shipped vehicle). These items need to be categorized, valued, and added to the Bill of
                            Lading (B/L). We generally try to avoid this to limit the risk of stolen parts. Alternatively,
                            loose parts are allowed if you have booked a dedicated container shipment.</p>
                    </div>
                </details>


                <!-- Category 4: Pricing, Deposits & Payments -->
                <h3 class="faq-category-title">Category 4: Pricing, Deposits & Payments</h3>

                <details class="faq-item">
                    <summary>How much deposit is required, and what currencies are accepted?</summary>
                    <div class="faq-content">
                        <p>We require an initial bidding retainer of 25% of your maximum target bid over 1,000,000 JPY
                            (minimum baseline of ¥200,000 JPY). We accept payments in Japanese Yen (JPY). All payments must
                            be made via secure Telegraphic Transfer (T/T) or Wise Business (Wise is preferred for deposits
                            as it is simple to reverse payments). We do not accept cash under any circumstances.</p>
                        <p>Your deposit is fully refundable at any point prior to a successful auction win, minus any direct
                            costs incurred for completed single-vehicle inspection tasks, banking fees, and exchange rate
                            fluctuations.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>Can I pay my deposit or invoice using Wise (Wise Business)?</summary>
                    <div class="faq-content">
                        <p>Yes! We fully support payments made through <strong>Wise Business</strong> (formerly
                            TransferWise) in addition to traditional international bank wire transfers (T/T). Using Wise
                            Business is often the fastest and most economical option for our global clients, as it offers
                            real mid-market exchange rates and significantly lower conversion fees compared to traditional
                            retail banks.</p>
                        <p>When sending funds via Wise, you can transfer directly in Japanese Yen (JPY) to our designated
                            business receiving account provided on your invoice.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>What is a TT Copy and do I receive a receipt?</summary>
                    <div class="faq-content">
                        <p>A TT Copy (Telegraphic Transfer Copy) or Wise transfer confirmation receipt is the official
                            confirmation provided by your financial institution once you execute a transfer. Because
                            international wire transfers act as an official legal record between buyer and seller, we do not
                            issue separate paper receipts. Your transfer confirmation serves as your official proof of
                            payment.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>Who pays the bank transfer fees and what is your IBAN?</summary>
                    <div class="faq-content">
                        <p>The buyer is responsible for all remittance and exchange fees. Please instruct your bank or
                            payment provider to cover all origin and intermediary bank fees so that the exact invoice amount
                            arrives in our account. Please note: Japanese banks do not use IBAN codes; you will instruct
                            your bank using our Swift Code.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>How quickly must the balance be paid once a car is won?</summary>
                    <div class="faq-content">
                        <p>Japanese auctions require rapid settlement; we pay this the next day from our own accounts. The
                            remaining balance issued to you must be remitted within 7 days of a winning bid. Please be aware
                            that traditional international bank transfers can take up to 3 business days to clear into our
                            Japanese account (Wise transfers are often faster), so initiating payment immediately is crucial
                            to avoid late penalties.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>What costs are included in FOB vs. CIF quotes?</summary>
                    <div class="faq-content">
                        <ul>
                            <li><strong>FOB (Free On Board):</strong> Covers the hammer price, auction buying fees, Japanese
                                inland transport, customs clearance, and port loading, plus any requested extras.</li>
                            <li><strong>CIF (Cost, Insurance, Freight):</strong> Includes everything in FOB plus ocean
                                vessel freight and marine transit insurance to your destination port. (Note: Destination
                                port duties, local taxes, and clearance fees are paid separately by the buyer at arrival.)
                            </li>
                        </ul>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>Are Japanese consumption taxes charged on exported cars?</summary>
                    <div class="faq-content">
                        <p>No. Vehicles purchased strictly for overseas export are exempt from Japanese domestic consumption
                            tax (10%) and local recycling fees. Our export invoices reflect pure wholesale pricing.</p>
                    </div>
                </details>


                <!-- Category 5: Shipping, Documentation & Customs -->
                <h3 class="faq-category-title">Category 5: Shipping, Documentation & Customs</h3>

                <details class="faq-item">
                    <summary>What shipping options do you offer?</summary>
                    <div class="faq-content">
                        <p>We offer two primary transport methods:</p>
                        <ul>
                            <li><strong>RoRo (Roll-on/Roll-off):</strong> The vehicle is driven onto specialized vehicle
                                carrier ships. This is the most popular and economical choice for drivable cars
                                (Recommended).</li>
                            <li><strong>Containerized Freight:</strong> Vehicles are loaded inside 20ft or 40ft ocean
                                containers. Best suited for high-value exotics, non-drivable project builds, or
                                multi-vehicle orders.</li>
                        </ul>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>How long will it take for my car to arrive?</summary>
                    <div class="faq-content">
                        <p>While shipping company schedules change frequently, average transit times from Japan are roughly:
                        </p>
                        <ul>
                            <li><strong>Pacific Region (Australasia):</strong> 2–4 weeks</li>
                            <li><strong>Africa & Caribbean:</strong> 3–5 weeks</li>
                            <li><strong>South America & Europe:</strong> 4–8 weeks</li>
                        </ul>
                        <p>We will provide your B/L so you can access online resources to track your shipment. Please note
                            that shipping lines control vessel schedules, and we cannot intervene if they decide to delay or
                            transship your cargo.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>Why must I provide my exact name and address for shipping?</summary>
                    <div class="faq-content">
                        <p>The Consignee (owner-to-be) information is used to generate the Bill of Lading (B/L), which is an
                            official, legal document proving ownership of the cargo. Your name and address must perfectly
                            match your Passport or National ID. Even the smallest spelling mistake will require a formal B/L
                            amendment, causing severe delays and additional fees imposed by the shipping line.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>Can I alter the vehicle details or value on the final documents?</summary>
                    <div class="faq-content">
                        <p>Absolutely not. Altering the commercial value, year, or chassis information on the Bill of Lading
                            or Commercial Invoice is a criminal offense. We supply accurate, legal documentation only.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>My customs office requires a "Packing List." Do you provide one?</summary>
                    <div class="faq-content">
                        <p>For Roll-on/Roll-off vehicle shipments, we do not issue a separate packing list. Our official
                            Commercial Invoice includes all relevant weights, dimensions, and descriptions required, and
                            serves as a universally accepted substitute during customs clearance.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>How and when will I receive my official export documents?</summary>
                    <div class="faq-content">
                        <p>Once your vessel departs, we compile your original Bill of Lading, Japanese Export Certificate,
                            English translations, and Invoice. These are dispatched to your designated address via DHL
                            express courier. We will provide a DHL tracking number. (Please allow 24 hours for the DHL
                            system to update once the package is picked up).</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>How does marine transit insurance protect my vehicle?</summary>
                    <div class="faq-content">
                        <p>All CIF shipments are backed by Institute Cargo Clauses (A) "All Risks" Marine Insurance. This
                            includes a pre-loading clause that protects your car while stored at the Japanese port terminal
                            prior to vessel departure, as well as full coverage against total loss or damage while at sea.
                        </p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>Who handles customs clearance in my country?</summary>
                    <div class="faq-content">
                        <p>Customs entry, compliance testing, and local tax payments in your destination country are managed
                            by the buyer. You must ensure your desired car meets local import criteria (such as the US
                            25-year rule or Canadian 15-year rule). We recommend hiring a local Customs Broker at your
                            destination port prior to ship arrival.</p>
                    </div>
                </details>


                <!-- Category 6: Country-Specific Rules (USA & Australia) -->
                <h3 class="faq-category-title">Category 6: Country-Specific Rules: USA & Australia</h3>

                <details class="faq-item">
                    <summary>USA: What is the 25-Year Rule for importing vehicles?</summary>
                    <div class="faq-content">
                        <p>To legally import a non-conforming Japanese Domestic Market (JDM) vehicle into the United States,
                            it must be at least 25 years old down to the exact month of manufacture. Once a vehicle reaches
                            this age, it is legally exempt from the National Highway Traffic Safety Administration (NHTSA)
                            Federal Motor Vehicle Safety Standards (FMVSS) and Environmental Protection Agency (EPA)
                            emissions standards. We verify the exact manufacture month using the chassis number before
                            allowing you to bid on any vehicle destined for the USA to ensure full compliance.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>USA: What is the import procedure for American buyers?</summary>
                    <div class="faq-content">
                        <p>We manage all Japanese-side logistics, deregistration, and ocean freight to your chosen US port.
                            On your end, you (or your Customs Broker) must file the mandatory ISF (Importer Security Filing)
                            with US Customs and Border Protection at least 24 hours before the ship departs Japan. Upon
                            arrival at the US port, you will submit EPA Form 3520-1, DOT Form HS-7, and CBP Form 7501 to
                            clear customs. We strongly advise hiring a licensed US Customs Broker to handle these filings to
                            avoid severe penalties or port delays.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>Australia: What is ROVER and do I need an import approval?</summary>
                    <div class="faq-content">
                        <p>Yes. ROVER (Road Vehicle Regulator) is the Australian Government's online portal for managing
                            vehicle imports. Before your vehicle can legally leave Japan, you must apply for and be granted
                            a Vehicle Import Approval through the ROVER system. If a vehicle is shipped without this
                            approval in place, the Australian Border Force will intercept the vehicle upon arrival,
                            resulting in it being seized, crushed, or exported back at your expense.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>Australia: What is the 25-Year Rule for Australian imports?</summary>

                    <div class="faq-content">
                        <p>Under Australia's Road Vehicle Standards Act (RVSA), vehicles that are 25 years or older from the
                            exact date of manufacture qualify for the "Older Vehicle" import concession. This allows these
                            classic vehicles to be imported for road use without needing to meet modern Australian Design
                            Rules (ADRs) or go through a Registered Automotive Workshop (RAW).</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>Australia: Are there specific inspection or cleaning requirements?</summary>
                    <div class="faq-content">
                        <p>Yes. Australia enforces some of the strictest import regulations in the world:</p>
                        <ul>
                            <li><strong>25-Year or Older Cars (Asbestos Testing):</strong> The Australian Border Force (ABF)
                                has a zero-tolerance policy for asbestos (commonly found in older Japanese brake pads and
                                gaskets). We can arrange mandatory, certified asbestos testing and removal in Japan prior to
                                export to ensure compliance.</li>
                            <li><strong>Biosecurity (DAFF):</strong> Vehicles must be impeccably clean. They will be
                                strictly inspected for soil, seeds, and plant matter upon arrival. We arrange thorough
                                cleaning and steam washing prior to shipment.</li>
                        </ul>
                    </div>
                </details>


                <!-- Category 7: Glossary of Import Terms -->
                <h3 class="faq-category-title">Category 7: Glossary of Import Terms</h3>

                <div class="faq-content">
                    <p>Understanding standard shipping terms will help streamline your import process:</p>
                    <ul>
                        <li><strong>B/L (Bill of Lading):</strong> The official contract of carriage and proof of ownership
                            issued by the shipping line. Must be presented at your destination port to claim your vehicle.
                        </li>
                        <li><strong>FOB (Free On Board):</strong> The price of the vehicle including all Japanese-side costs
                            (auction fees, inland transport, customs). It does not include ocean freight or insurance.</li>
                        <li><strong>CIF (Cost, Insurance, and Freight):</strong> The FOB price plus the cost of ocean
                            freight and marine transit insurance to your destination port.</li>
                        <li><strong>Consignee:</strong> The person listed on the B/L who will legally own the car after it
                            is registered in the destination country.</li>
                        <li><strong>Notify Party:</strong> The person or clearing agent the shipping line will contact when
                            the vessel arrives at the port.</li>
                        <li><strong>Export Certificate:</strong> Issued by the Japanese government proving the vehicle’s
                            previous domestic registration has been legally canceled for export.</li>
                        <li><strong>Proforma Invoice:</strong> The final formal estimate based on your chosen vehicle and
                            shipping conditions, detailing the total amount due before we arrange shipping.</li>
                        <li><strong>Transshipment:</strong> When a shipping line transfers your vehicle from one vessel to
                            another at an intermediate port before reaching its final destination.</li>
                    </ul>
                </div>

                <div class="faq-footer">
                    <p><strong>Have a question not listed here?</strong></p>
                    <p>Our team is ready to assist you. <a href="{{ url('/contact') }}">Contact Direct Imported today.</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- FAQ Section End -->
@endsection
@section('script')
    <script>

        document.querySelectorAll('.collapse').forEach(function (el) {

            el.addEventListener('show.bs.collapse', function () {

                let icon = this.previousElementSibling.querySelector('.faq-icon i');

                icon.classList.remove('bx-plus');
                icon.classList.add('bx-minus');

            });

            el.addEventListener('hide.bs.collapse', function () {

                let icon = this.previousElementSibling.querySelector('.faq-icon i');

                icon.classList.remove('bx-minus');
                icon.classList.add('bx-plus');

            });

        });

    </script>
@endsection