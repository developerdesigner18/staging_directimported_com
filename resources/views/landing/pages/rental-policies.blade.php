@extends('landing.master')
@section('title', 'Rental Policies')

@push('style')
    <style>
     /* 
     * Custom CSS to exactly match the provided image references.
     * This isolates the styling so it won't conflict with your site's current theme.
     */
    .terms-page-wrapper {
        background-color: #f8f9fb; /* Light off-white background from image */
        padding: 50px 20px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        color: #333333;
    }
    /* .terms-container {
        max-width: 1000px;
        margin: 0 auto;
    } */
    
    /* Main Page Title */
    .terms-page-title {
        text-align: center;
        font-size: 2.75rem;
        font-weight: 700;
        color: #2b3b4e; /* Dark slate blue */
        margin-bottom: 50px;
        position: relative;
    }
    .terms-page-title::after {
        content: "";
        display: block;
        width: 80px;
        height: 4px;
        background-color: #172a53; /* Dark navy underline matching the image */
        margin: 15px auto 0;
        border-radius: 2px;
    }

    /* Card Styling */
    .term-card {
        background-color: #ffffff;
        border-radius: 8px;
        margin-bottom: 40px;
        /* Soft shadow with a very faint pink/red tint matching the reference images */
        box-shadow: 0 12px 35px rgba(220, 20, 60, 0.04), 0 4px 10px rgba(0,0,0,0.03);
        overflow: hidden;
    }

    /* Card Header */
    .term-header {
        background-color: #121933; /* Deep Navy Blue from image */
        padding: 0px 24px;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .term-badge {
        background-color: #3460a8; /* Lighter blue circle */
        color: #ffffff;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: 700;
        font-size: 15px;
        flex-shrink: 0;
    }
    .term-header h2 {
        color: #ffffff;
        margin: 0;
        font-size: 1.15rem;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* Card Body */
    .term-body {
        padding: 30px 40px;
        line-height: 1.7;
        font-size: 0.95rem;
        color: #475569;
        /* Force word wrapping to prevent horizontal scrolling */
        word-wrap: break-word;
        overflow-wrap: break-word;
    }
    .term-body p {
        margin-top: 0;
        margin-bottom: 20px;
    }
    .term-body ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }
    .term-body li {
        margin-bottom: 24px;
        padding-left: 0;
    }
    .term-body li:last-child {
        margin-bottom: 0;
    }
    .term-body strong {
        color: #1e293b;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 4px;
    }

    /* Table Styling for Insurace & Glossary */
    .table-wrapper {
        overflow-x: auto;
        margin-top: 15px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
    }
    .terms-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        min-width: 600px; /* Ensures tables don't squish too much on mobile */
    }
    .terms-table th, .terms-table td {
        padding: 14px 20px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: top;
    }
    .terms-table th {
        background-color: #f8fafc;
        color: #0f172a;
        font-weight: 600;
    }
    .terms-table tr:last-child td {
        border-bottom: none;
    }

    /* Mobile Responsive Adjustments */
    @media (max-width: 768px) {
        .terms-page-title { font-size: 2rem; }
        .term-body { padding: 20px; }
        .term-header { padding: 14px 18px; }
    }
    </style>
@endpush

@section('main')
    <div class="container">
        
        <h1 class="terms-page-title">Terms and Conditions</h1>

        <!-- Section 1 -->
        <div class="term-card">
            <div class="term-header">
                <span class="term-badge">1</span>
                <h2>Account, Bidding, and Financial Terms</h2>
            </div>
            <div class="term-body">
                <ul>
                    <li><strong>Platform Access and Deposits:</strong> A bidding retainer (25% of your maximum target bid over ¥1,000,000 JPY, with a ¥200,000 minimum baseline) is required to activate live bidding rights. Deposits are fully refundable prior to a successful auction win, minus any bank processing fees, exchange rate fluctuations, or direct costs incurred for requested physical inspections.</li>
                    <li><strong>Payment and Settlement:</strong> The remaining balance must be settled via Telegraphic Transfer (SWIFT) or Wise Business within 7 days of a winning bid. Cash is not accepted.</li>
                    <li><strong>Bank Fees and Transaction Charges:</strong> The registered account holder is strictly responsible for all cross-border bank fees, transaction charges, and intermediary fees, including those incurred via Telegraphic Transfer (TT) or Wise. When initiating a transfer, you must explicitly select the option to cover all originator and intermediary bank fees (e.g., the "OUR" instruction for wire transfers) to ensure the exact invoice amount arrives in our account. Failure to cover these fees will result in a payment shortage, which will delay the shipping and documentation process until the remaining balance is cleared.</li>
                    <li><strong>Exchange Rates and Market Fluctuations:</strong> Direct Imported is not responsible for any financial loss or profit resulting from foreign exchange rate fluctuations. We highly recommend settling your invoice immediately within the required 7-day window to mitigate exposure to currency volatility and ensure exchange rate shifts do not negatively impact the profitability of your purchases.</li>
                    <li><strong>Binding Contracts (No Cancellations):</strong> Under the Japanese wholesale auction system, all winning bids are legally binding commercial contracts. Once the hammer falls, sales are final and cannot be canceled, retracted, or returned under any circumstances.</li>
                    <li><strong>Post-Auction Negotiations:</strong> If a vehicle fails to meet its reserve price, Direct Imported may submit a post-auction counter-offer or secure a "Buy-It-Now" fixed price. A standard negotiation fee (averaging ¥15,000 to ¥25,000) applies to successful post-sale negotiations.</li>
                </ul>
            </div>
        </div>

        <!-- Section 2 -->
        <div class="term-card">
            <div class="term-header">
                <span class="term-badge">2</span>
                <h2>Duty of Care, Vehicle Condition, and Auction Inaccuracies</h2>
            </div>
            <div class="term-body">
                <ul>
                    <li><strong>Standard Duty of Care:</strong> Direct Imported exercises all reasonable skill, diligence, and industry-standard care in translating auction documents, performing requested on-site inspections, and managing your export logistics. While every reasonable precaution is taken to ensure a transparent and smooth process, we act strictly as an intermediary purchasing agent. Our services are provided on a "best-effort" basis and do not constitute a warranty or guarantee of the vehicle's mechanical integrity or future performance.</li>
                    <li><strong>Auction Sheet Limitations:</strong> Standard auction house evaluations take only a few minutes. While our team translates inspector notes and defect diagrams, these sheets serve as baseline guides and do not constitute a perfect guarantee of condition.</li>
                    <li><strong>Liability for Inaccuracies:</strong> Because our platform grants access to fast-paced, true wholesale floor pricing, Direct Imported is not liable for hidden mechanical issues or undisclosed cosmetic defects missed by the auction house networks. We mitigate risks through detailed, on-site physical evaluations when requested, but all vehicles are ultimately purchased strictly "as-is."</li>
                    <li><strong>Odometer and Value Falsification:</strong> Direct Imported strictly complies with international trade laws. We will outright refuse any requests to roll back odometer readings, alter a vehicle’s manufacturing year, or falsify commercial invoice values to reduce your import taxes.</li>
                    <li><strong>JDM Specifications:</strong> All vehicles are true Japanese Domestic Market (JDM) models. Infotainment systems and owner manuals will be in Japanese. Spare keys are not guaranteed unless physically provided by the seller to the auction house.</li>
                </ul>
            </div>
        </div>

        <!-- Section 3 -->
        <div class="term-card">
            <div class="term-header">
                <span class="term-badge">3</span>
                <h2>Shipping, Logistics, and Customs</h2>
            </div>
            <div class="term-body">
                <ul>
                    <li><strong>Shipping Timelines and Force Majeure:</strong> We facilitate transport via RoRo (Roll-on/Roll-off) or Containerized freight. Ocean transit schedules are dictated entirely by the shipping lines. Direct Imported shall not be held liable for vessel delays, schedule changes, transshipments, or cancellations caused by unforeseen circumstances (Force Majeure). This includes, but is not limited to, severe weather events, natural disasters, geopolitical conflicts, acts of war, port strikes, or sudden operational cancellations by the shipping lines.</li>
                    <li><strong>Freight Rate Fluctuations:</strong> Global shipping rates are subject to market volatility. In the event that a shipment must be rebooked due to a carrier cancellation or an unforeseen delay, Direct Imported is not liable for any resulting fluctuations in ocean freight costs. Any subsequent increases in shipping rates applied by the carriers during the rebooking process will be amended on your final invoice and remain the responsibility of the buyer.</li>
                    <li><strong>Port Storage Fees and Vessel Allocation:</strong> Direct Imported provides up to 30 days of complimentary yard storage while a vehicle awaits vessel allocation. Because we do not control shipping line schedules, vessel space availability, or cargo roll-overs, we cannot guarantee departure within this window. Should a vehicle remain at the port or holding yard beyond the 30-day period, standard daily storage fees will apply. Any accrued storage fees will be calculated and billed to the account holder after the vessel officially departs Japan.</li>
                    <li><strong>Non-Running Vehicles and Loading Cancellations:</strong> RoRo vessels require vehicles to be fully drivable under their own power. Vehicles that fail to start, run, or operate normally on the day of loading (e.g., due to dead batteries, stale fuel, or mechanical failure while sitting) are subject to immediate cancellation by the shipping line. Classic and older vehicles inherently carry a high risk of falling into this "non-runner" category. Direct Imported is not liable for shipping cancellations caused by a vehicle's inability to run. The registered account holder remains wholly responsible for any resulting port storage fees, towing costs, or mechanical repair bills required to return the vehicle to a running state for the next available vessel.</li>
                    <li><strong>Loose Cargo Prohibition:</strong> In strict accordance with international maritime law, placing loose parts, aftermarket upgrades, or personal cargo inside a vehicle shipped via RoRo is strictly prohibited.</li>
                    <li><strong>Consignee Documentation Accuracy:</strong> The Consignee details provided for the Bill of Lading (B/L) must perfectly match your Passport or National ID. Typographical errors require formal B/L amendments, which will incur severe port delays and financial penalties at the buyer's expense.</li>
                </ul>
            </div>
        </div>

        <!-- Section 4 -->
        <div class="term-card">
            <div class="term-header">
                <span class="term-badge">4</span>
                <h2>Import Regulations and Compliance Indemnity</h2>
            </div>
            <div class="term-body">
                <ul>
                    <li><strong>Role as an Export Agent:</strong> Direct Imported operates strictly as a Japanese export agency acting on your behalf to facilitate the purchase, domestic handling, and outbound shipping of vehicles. We are not a guaranteed import company, destination customs broker, or compliance certifier.</li>
                    <li><strong>Account Holder Responsibility:</strong> It is the absolute and sole responsibility of the registered account holder (and/or their respective end-user) to thoroughly research and verify all local import laws, vehicle eligibility restrictions, age rules (e.g., the USA 25-Year Rule), emissions regulations, and required pre-export inspections before placing a bid.</li>
                    <li><strong>Indemnity Against Claims:</strong> By utilizing our services, the account holder agrees to fully indemnify and hold Direct Imported harmless against any claims, financial losses, port storage fees, legal actions, or damages resulting from a failure to comply with destination import laws.</li>
                    <li><strong>Failed Imports:</strong> Direct Imported accepts zero liability if a purchased vehicle is denied entry, seized, mandated for destruction (crushed), or forced to be re-exported by destination customs authorities (such as the Australian Border Force or US Customs and Border Protection) due to the account holder's failure to secure necessary approvals (e.g., ROVER) or check local regulations prior to purchase.</li>
                </ul>
            </div>
        </div>

        <!-- Section 5 -->
        <div class="term-card">
            <div class="term-header">
                <span class="term-badge">5</span>
                <h2>Service Fees and Inclusions</h2>
            </div>
            <div class="term-body">
                <ul>
                    <li><strong>Agency Fee Structure:</strong> Our standard import agency service fee covers your wholesale platform access, official auction sheet translations, ground staff physical inspections (including photos/videos), live proxy bidding, and the coordination of export and shipping logistics.</li>
                    <li><strong>Non-Refundable Services:</strong> Once our ground team has physically inspected a vehicle, or a winning bid has been secured on your behalf, the service fee becomes strictly non-refundable.</li>
                    <li><strong>Additional Third-Party Fees:</strong> Any requested services outside our standard agency scope—such as third-party digital odometer verifications, asbestos testing, post-auction negotiations, or domestic mechanical servicing—incur separate, non-refundable fees that will be quoted and added to your final Proforma Invoice.</li>
                </ul>
            </div>
        </div>

        <!-- Section 6 -->
        <div class="term-card">
            <div class="term-header">
                <span class="term-badge">6</span>
                <h2>Intellectual Property, Auction Data, and Media Restrictions</h2>
            </div>
            <div class="term-body">
                <ul>
                    <li><strong>Proprietary Nature of Data:</strong> All auction data, translated auction sheets, historical sales metrics, on-site physical inspection reports, digital photographs, and video footage provided to you by Direct Imported are strictly proprietary. They are provided solely for your private use to make an informed purchasing decision.</li>
                    <li><strong>Prohibition on Public Display and Social Media:</strong> You are expressly prohibited from publishing, broadcasting, reposting, or displaying any media or inspection data provided by Direct Imported on any public platform. This includes, but is not limited to, social media networks, public forums, personal blogs, or commercial websites.</li>
                    <li><strong>Commercial Exploitation:</strong> Direct Imported’s auction videos, translated documents, and inspection photos may not be used to advertise a vehicle for resale, generate social media engagement, or be monetized in any way prior to the vehicle’s legal arrival and physical transfer of ownership.</li>
                    <li><strong>Breach of Terms:</strong> Unauthorized public distribution or commercial use of our proprietary data and inspection media constitutes a direct breach of these Terms and Conditions. Direct Imported reserves the right to immediately terminate your account access, forfeit your bidding deposit, and pursue legal remedies for intellectual property infringement.</li>
                </ul>
            </div>
        </div>

        <!-- Section 7 -->
        <div class="term-card">
            <div class="term-header">
                <span class="term-badge">7</span>
                <h2>Late Payments and Default</h2>
            </div>
            <div class="term-body">
                <ul>
                    <li><strong>Late Payment Penalty:</strong> As Japanese auction houses require rapid settlement, your remaining balance must be remitted within 7 days of a winning bid. If funds are not received within this 7-14 day window, a late payment fee equal to 1% of the total invoice amount will be automatically applied to your balance.</li>
                    <li><strong>Extended Delays and Repeated Breaches:</strong> Prompt payment is critical to our operations. If a payment is delayed beyond 14 days, or if your account demonstrates a history of repeated late payment breaches, Direct Imported reserves the right to consider the transaction defaulted. In the event of a default, your bidding deposit may be forfeited to cover auction cancellation penalties for people who remain 30 days in default.</li>
                </ul>
            </div>
        </div>

        <!-- Section 8 -->
        <div class="term-card">
            <div class="term-header">
                <span class="term-badge">8</span>
                <h2>Re-Auction Vehicles and Cancellations</h2>
            </div>
            <div class="term-body">
                <ul>
                    <li><strong>Transport and Yard Liability:</strong> In the event a purchased vehicle needs to be re-auctioned, Direct Imported is not liable for domestic transport logistics, the recovery of non-running vehicles, or any repairs necessitated due to deterioration or damage occurring while the vehicle was stored at port yards.</li>
                    <li><strong>Account Holder Responsibility:</strong> All costs associated with every step of the re-auction process are the sole responsibility of the registered account holder. This applies strictly regardless of whether the account holder is acting as a broker, agent, or middleman for a third party.</li>
                    <li><strong>Financial Losses:</strong> Any financial deficit or loss generated at auction during the resale of the vehicle will be borne entirely by the registered account holder, regardless of the end user's involvement or failure to pay.</li>
                    <li><strong>Document Conversion Fees:</strong> Re-auctioning requires converting export documentation (such as reverting Export Certificates back to deregistered certificates for domestic sale). This administrative process will be billed at an hourly rate, plus any associated government or agency fees required to prepare the paperwork for domestic re-auction.</li>
                    <li><strong>Consumption Tax Application:</strong> Because re-auctioning transitions the vehicle from a tax-exempt export to a Japanese domestic transaction, the standard Japanese Consumption Tax (10%) becomes fully applicable. This tax will be applied to both the original purchase costs and the final sale costs, and billed directly to the account holder's ledger.</li>
                    <li><strong>End-User Acknowledgement:</strong> Account holders acting on behalf of clients or end users are strictly required to ensure their customers have read, understood, and accepted these Terms and Conditions regarding cancellations and re-auctions. The account holder remains the sole responsible party to Direct Imported in all circumstances.</li>
                </ul>
            </div>
        </div>

        <!-- Section 9 -->
        <div class="term-card">
            <div class="term-header">
                <span class="term-badge">9</span>
                <h2>Marine Transit Insurance</h2>
            </div>
            <div class="term-body">
                <p><strong>Optional Pre-Export and Transit Coverage:</strong> Marine transit insurance is available upon request (and is typically included under CIF shipping terms once the Bill of Lading (B/L) is produced, and not prior, unless explicitly notified or requested as an add-on). When this pre-export add-on is secured, coverage includes pre-export yard inclusions, protecting your vehicle against unforeseen environmental perils such as flood damage or severe storms while it is stored at the Japanese port awaiting vessel departure.</p>
                <p><strong>Standardized Institute Cargo Clauses:</strong> Policies are typically underwritten based on standard international shipping practices—Institute Cargo Clauses (A) or (C), depending on the shipping method and destination port regulations. The insured value is based on the final commercial invoice amount.</p>
                <p><strong>Claims Procedure and Port Surveys:</strong> In the rare event of damage, the Consignee (buyer) is strictly responsible for filing the insurance claim directly with the local insurance agent at the destination port. Damage must be photographed and officially documented by a certified port surveyor before the vehicle is removed from the customs or port authority yard. Moving the vehicle prior to an official inspection will immediately void the policy.</p>
                <p><strong>Coverage Limitations:</strong> Used vehicle marine insurance is designed to protect against major catastrophes. It does not act as a bumper-to-bumper warranty.</p>
                
                <div class="table-wrapper">
                    <table class="terms-table">
                        <thead>
                            <tr>
                                <th style="width: 50%;">Standard Inclusions (Covered)</th>
                                <th>Standard Exclusions (Not Covered)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Pre-export port yard damage from floods or storms (if add-on is secured prior to sailing).</td>
                                <td>Pre-existing damage noted on auction sheets or minor cosmetic wear occurring during port handling.</td>
                            </tr>
                            <tr>
                                <td>Total loss of the carrying vessel, sinking, or stranding.</td>
                                <td>Mechanical failures, engine issues, dead batteries, or "inherent vice" that manifest during the voyage.</td>
                            </tr>
                            <tr>
                                <td>Major catastrophes, including fire, explosion, or vessel collision.</td>
                                <td>Theft or loss of loose items, aftermarket parts, or spare keys left inside the vehicle.</td>
                            </tr>
                            <tr>
                                <td>Complete failure of port loading equipment dropping the vehicle.</td>
                                <td>Losses caused by shipping delays, acts of war, port strikes, or riots (unless a War & Strikes premium is purchased).</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Section 10 -->
        <div class="term-card">
            <div class="term-header">
                <span class="term-badge">10</span>
                <h2>General Legal Provisions & Compliance</h2>
            </div>
            <div class="term-body">
                <ul>
                    <li><strong>Export Controls and International Sanctions:</strong> Direct Imported complies strictly with Japanese (METI) and international export control laws. By utilizing our services, you warrant that you are not listed on any international sanctions or restricted party lists. Furthermore, you guarantee that any vehicle purchased through our platform will not be exported to embargoed nations or utilized for illicit, military, or terrorist activities.</li>
                    <li><strong>Limitation of Liability:</strong> In the event that Direct Imported is found liable for any claim, loss, or damage arising out of these Terms and Conditions or the provision of our services, our total aggregate liability shall under no circumstances exceed the total amount of the Agency Service Fee paid by the account holder for the specific transaction in dispute. We are not liable for the underlying cost of the vehicle, lost profits, or indirect consequential damages.</li>
                    <li><strong>Right to Refuse Service & Account Termination:</strong> Direct Imported reserves the right to refuse service, suspend bidding privileges, or permanently terminate any user account at our sole discretion, without prior notice. Grounds for termination include, but are not limited to, suspected fraudulent activity, abusive behavior toward our staff, repeated failure to remit timely payments, or submission of continuous unviable bids.</li>
                    <li><strong>Data Privacy and Document Handling:</strong> To generate official export and shipping documents, we require sensitive personal information (such as Passport or National ID copies). Direct Imported commits to utilizing this data strictly for customs clearance, shipping bookings, and legal compliance. We will not sell, rent, or distribute your personal data to unauthorized third parties.</li>
                    <li><strong>Severability:</strong> If any provision or clause of these Terms and Conditions is determined to be invalid, illegal, or unenforceable by a court of competent jurisdiction, such invalidity shall not affect the enforceability of any other provision within this document. The remaining terms shall remain in full force and effect.</li>
                    <li><strong>Right to Amend Terms:</strong> Direct Imported reserves the right to update, modify, or replace these Terms and Conditions at any time to reflect changing laws, shipping policies, or business operations. Continued use of our platform and services following any changes constitutes your explicit acceptance of the revised Terms.</li>
                </ul>
            </div>
        </div>

        <!-- Section 11 -->
        <div class="term-card">
            <div class="term-header">
                <span class="term-badge">11</span>
                <h2>Governing Law and Jurisdiction</h2>
            </div>
            <div class="term-body">
                <ul>
                    <li><strong>Applicable Law:</strong> These Terms and Conditions, as well as any separate agreements, invoices, or services provided by Direct Imported, shall be governed by, construed, and enforced strictly in accordance with the laws of Japan, without regard to its conflict of law principles.</li>
                    <li><strong>Exclusive Jurisdiction:</strong> Any disputes, controversies, claims, or legal proceedings arising out of or in connection with these Terms and Conditions, your account, the bidding process, or the export of any vehicle shall be subject to the exclusive jurisdiction of the district courts of Japan. By utilizing our services, you expressly consent to the personal and exclusive jurisdiction of these courts.</li>
                </ul>
            </div>
        </div>

        <!-- Section 12 -->
        <div class="term-card">
            <div class="term-header">
                <span class="term-badge">12</span>
                <h2>Glossary of Standard Import Terms</h2>
            </div>
            <div class="term-body">
                <div class="table-wrapper">
                    <table class="terms-table">
                        <thead>
                            <tr>
                                <th style="width: 25%;">Term</th>
                                <th>Definition</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>B/L (Bill of Lading)</strong></td>
                                <td>The official contract of carriage and legal proof of cargo ownership required to claim your vehicle at the destination port.</td>
                            </tr>
                            <tr>
                                <td><strong>FOB (Free On Board)</strong></td>
                                <td>The vehicle cost including all Japanese-side expenses (auction buying fees, inland transport, and export customs). Excludes ocean freight.</td>
                            </tr>
                            <tr>
                                <td><strong>CIF (Cost, Insurance, Freight)</strong></td>
                                <td>The complete FOB price plus the cost of ocean freight and "All Risks" marine transit insurance to your destination.</td>
                            </tr>
                            <tr>
                                <td><strong>Consignee</strong></td>
                                <td>The exact individual or business listed on the B/L who will legally own the vehicle upon arrival.</td>
                            </tr>
                            <tr>
                                <td><strong>Export Certificate</strong></td>
                                <td>An official document issued by the Japanese government proving the vehicle’s domestic registration has been legally canceled for international export.</td>
                            </tr>
                            <tr>
                                <td><strong>Proforma Invoice</strong></td>
                                <td>The final, formal estimate detailing the total amount due before shipping arrangements are booked.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection