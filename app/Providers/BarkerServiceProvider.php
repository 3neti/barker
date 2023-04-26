<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Classes\Barker;

class BarkerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configureChannels();

        $this->configureRoutes();

        $this->addInstructions();

        $this->addRiders();

        $this->addProfiles();
    }

    protected function configureChannels(): void
    {
        Barker::type('accounting', 'Accounting', [
            'email'
        ])->description('Attendance, Commissioning, Deployment, Enlistment, Enrollment, Mobilization, Recruitment, Registration, Reporting, Reservation');

        Barker::type('authentication', 'Authentication', [
            'email',
            'mobile',
        ])->description('Certification, Confirmation, Endorsement, Licensing, Purchase, Redemption, Verification, Witness');

        Barker::type('authorization', 'Authorization', [
            'email',
            'mobile',
            'webhook'
        ])->description('Approval, Accreditation, Permission, Sanction, Sign-up, Signature');
    }

    protected function configureRoutes()
    {
        //TODO: transfer all the routes here
    }

    protected function addInstructions()
    {
        Barker::instruction('Event Registration', 'Odio ut enim blandit volutpat maecenas volutpat blandit aliquam etiam.')
            ->timing('pre-checkin')
            ->type('accounting')
            ->description('Generate a guest book for assemblies, balls, concerts, conventions, delegation, exhibits, fairs, gatherings, reunions, summits, symposiums, & tournaments.');
        Barker::instruction('Contact Tracing', 'Mauris pharetra et ultrices neque ornare aenean euismod elementum. 7 ')
            ->timing('pre-checkin')
            ->type('accounting')
            ->description('Automate geo-tagging of citizens and personnel for asset management, field work, health declaration, orienteering activities, package tracking, passenger manifest, & workflow document tracking');
        Barker::instruction('Data Enumeration', 'Mauris pharetra et ultrices neque ornare aenean euismod elementum. ')
            ->timing('pre-checkin')
            ->type('accounting')
            ->description('Eradicate doubts in the source of truth in census, elections, examinations, investigations, market research, polls, & surveys.');
        Barker::instruction('Lead Qualification', 'Mauris pharetra et ultrices neque ornare aenean euismod elementum. 2 ')
            ->timing('pre-checkin')
            ->type('accounting')
            ->description('Instantiate the sales on-boarding process in the automotive, BPO, financial, gaming, health, housing, insurance & manpower industries.');
        Barker::instruction('Security Screening', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.')
            ->timing('pre-checkin')
            ->type('authentication')
            ->description('Establish a protocol for ingress and egress of personnel in firing ranges, government offices, hospitality facilities, national borders, military camps, private residences, & quarantine centers.');
        Barker::instruction('KYC Authentication', 'Mauris pharetra et ultrices neque ornare aenean euismod elementum. 3 ')
            ->timing('pre-checkin')
            ->type('authentication')
            ->description('Instantly identify individuals for any purpose i.e. adult entertainment, cash disbursement, liquor sales, parcel delivery & SIM registration.');
        Barker::instruction('Service Availment', 'Mauris pharetra et ultrices neque ornare aenean euismod elementum. 4 ')
            ->timing('pre-checkin')
            ->type('authentication')
            ->description('Delegate operational oversight and eliminate ghost beneficiaries in healthcare, housing, insurance, payroll, pension, & social welfare programs');
        Barker::instruction('Document Stamping', 'Mauris pharetra et ultrices neque ornare aenean euismod elementum. 6 ')
            ->timing('pre-checkin')
            ->type('authentication')
            ->description('Witness signing between real people on agreements, conferments, deed of transfers, living will and testament, & title certificates.');
        Barker::instruction('Voucher Redemption', 'Mauris pharetra et ultrices neque ornare aenean euismod elementum. 5 ')
            ->timing('pre-checkin')
            ->type('authorization')
            ->description('Honor commitments e.g. accommodation reservations, cash equivalents, discounted pricing, membership invitation, promotional goods and services & ticketing.');
        Barker::instruction('Fund Transfer', 'Mauris pharetra et ultrices neque ornare aenean euismod elementum. 8 ')
            ->timing('pre-checkin')
            ->type('authorization')
            ->description('Disburse with confidence on bank withdrawals, check encashments, grassroots operation fund releases, remittances & welfare fund releases.');
    }

    protected function addRiders()
    {
        Barker::rider('Thank you', 'Thank you for participating. Your privacy is protected by the Republic Act 10173 https://www.privacy.gov.ph/data-privacy-act/.')
            ->timing('post-checkin')
            ->description('Send a token of appreciation to your participants.');
        Barker::rider('Feedback', 'Please answer our feedback form https://forms.gle/GqAHvn4eENCDUBkS6.')
            ->timing('post-checkin')
            ->description('Ask for a feedback from your participants.');
        Barker::rider('Sign up', __('Please visit :url.', ['url' => 'https://kwyc-check.mo']))
            ->timing('post-checkin')
            ->description('Invite your participants to sign up.');
    }

    protected function addProfiles()
    {
        Barker::profile('Gender', ['Male', 'Female'])
            ->description('Gender description');
        Barker::profile('Age', ['Young', 'Middle-Aged', 'Old'])
            ->description('Age description');
        Barker::profile('Complexion', ['Dark', 'Fair', 'White'])
            ->description('Complexion description');
        Barker::profile('Height', ['Short', 'Average', 'Tall'])
            ->description('Height description');
    }
}
