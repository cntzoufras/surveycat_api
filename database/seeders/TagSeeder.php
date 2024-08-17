<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder {

    public function run(): void {
        $tags = [
            'Customer Satisfaction', 'Product Feedback', 'User Experience', 'Market Research',
            'Employee Engagement', 'Service Quality', 'Feature Requests', 'Usability',
            'Performance', 'Reliability', 'Security', 'Survey Design', 'Accessibility',
            'Support', 'Pricing', 'Delivery', 'Payment Options', 'Return Policy',
            'Product Variety', 'Website Design', 'App Usability', 'Brand Loyalty',
            'Social Media', 'User Interface', 'Customer Service', 'Innovation',
            'Competitor Analysis', 'Sales Strategy', 'Advertising', 'Survey Length',
            'Question Quality', 'Survey Timing', 'Demographics', 'Geographic Distribution',
            'Education Level', 'Income Level', 'Occupation', 'Hobbies',
            'Interests', 'Brand Perception', 'Trust', 'Customer Retention',
            'Customer Acquisition', 'Product Launch', 'Market Penetration',
            'Customer Journey', 'Shopping Experience', 'Product Satisfaction', 'Brand Image',
        ];

        foreach ($tags as $tagContent) {
            Tag::create([
                'content' => $tagContent,
                'user_id' => User::query()->first()->id,
            ]);
        }
    }
}
