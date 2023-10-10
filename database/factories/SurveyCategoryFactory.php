<?php
    
    namespace Database\Factories;
    
    use Illuminate\Database\Eloquent\Factories\Factory;
    
    /**
     * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SurveyCategory>
     */
    class SurveyCategoryFactory extends Factory {
        
        /**
         * Define the model's default state.
         *
         * @return array<string, mixed>
         */
        public function definition(): array {
            
            $titles = ['Art and Culture',
                'Business',
                'Cars and Automotive',
                'Cryptocurrency',
                'DIY and Home Improvement',
                'Economic',
                'Education',
                'Entertainment',
                'Entrepreneurship',
                'Environment Conservation',
                'Environmental',
                'Extreme Sports',
                'Fashion and Style Trends',
                'Fashion',
                'Film and Cinema',
                'Fitness and Wellness',
                'Food and Cuisine',
                'Futuristic Technology',
                'Gaming and Hobbies',
                'Gardening and Horticulture',
                'Health',
                'Healthcare Professionals',
                'History',
                'Home and Garden',
                'Lifestyle',
                'Literature and Books',
                'Mental Health',
                'Music',
                'Outdoor Activities',
                'Paranormal Investigations',
                'Parenting and Family',
                'Parenting',
                'Pets and Animals',
                'Philosophy and Ethics',
                'Politics',
                'Relationships',
                'Religion',
                'Science and Innovation',
                'Science',
                'Self-Help and Personal Development',
                'Social Issues',
                'Social Media and Networking',
                'Space Exploration',
                'Sports',
                'Sustainable Living',
                'Technology',
                'Travel Destinations',
                'Travel',
                'Urban Exploration',
                'Vintage Collectibles',
            ];
            $descriptions = [
                'Explore the rich tapestry of human creativity, from timeless masterpieces to contemporary expressions of art and culture.',
                'Dive into the world of commerce and entrepreneurship, where innovation, strategy, and ambition drive economic growth.',
                'Navigate the fascinating realm of automobiles, from classic cars to cutting-edge technology in the automotive industry.',
                'Delve into the world of digital currency and blockchain technology, reshaping the future of finance and transactions.',
                'Unleash your inner craftsman and embark on DIY projects to transform your living spaces.',
                'Examine the intricate web of economic systems, policies, and global markets shaping our financial landscape.',
                'Discover the transformative power of learning and education, from formal institutions to lifelong self-improvement.',
                'Immerse yourself in the world of entertainment, where creativity and storytelling captivate hearts and minds.',
                'Witness the journeys of daring entrepreneurs as they bring innovative ideas to life in the business world.',
                'Champion the cause of preserving our planet\'s natural beauty and biodiversity through conservation efforts.',
                'Explore the delicate balance between human activities and the environment, seeking sustainable solutions.',
                'Experience the adrenaline rush of extreme sports, pushing the boundaries of physical limits.',
                'Stay in vogue with the latest fashion trends and style inspirations from around the world.',
                'Uncover the artistry and expression woven into every fabric and garment in the ever-evolving world of fashion.',
                'Immerse yourself in the world of storytelling and cinematography through the lens of the silver screen.',
                'Embrace a healthier lifestyle by exploring fitness routines, wellness practices, and nutrition.',
                'Embark on a gastronomic journey to discover diverse culinary traditions and mouthwatering dishes.',
                'Peer into the future with cutting-edge technology innovations that promise to reshape our world.',
                'Unwind with gaming and hobbies that offer fun and relaxation in your free time.',
                'Cultivate green thumbs by exploring the art and science of gardening and horticulture.',
                'Dive into the world of dedicated healthcare heroes, saving lives and delivering compassionate care.',
                'Discover the art of interior design, landscaping, and sustainable living in your home.',
                'Explore captivating tales of the past, from ancient civilizations to modern revolutions.',
                'Discover the art of interior design, landscaping, and sustainable living in your home.',
                'Embrace a balanced and fulfilling way of life, from wellness tips to travel adventures.',
                'Immerse yourself in timeless tales, literary classics, and the magic of storytelling.',
                'Cultivate well-being, resilience, and emotional balance for a healthier mind.',
                'Journey through diverse melodies, genres, and the universal language of music.',
                'Embark on exciting adventures in the great outdoors, from hiking trails to thrilling sports.',
                'Delve into the mysterious and unexplained, exploring the realms of the supernatural.',
                'Nurturing love, laughter, and strong bonds within your family is a beautiful journey.',
                'Discover the joys and challenges of raising happy, healthy, and compassionate children.',
                'Celebrate the incredible world of animals, from pets to wildlife, and their unique stories.',
                'Explore timeless questions, moral dilemmas, and the foundations of ethical thought.',
                'Navigate the intricate world of government, policies, and the forces shaping our society.',
                'Uncover the complexities of human connections, from love and friendship to family dynamics.',
                'Journey through diverse faiths, beliefs, and spiritual practices that shape our world.',
                'Dive into the frontiers of scientific discovery and technological advancements.',
                'Unearth the wonders of the natural world, from the cosmos to microscopic life forms.',
                'Embark on a path of self-discovery and growth to unlock your full potential.',
                'Delve into critical matters impacting society, from inequality to environmental concerns.',
                'Navigate the digital landscape of connectivity, communication, and online communities.',
                'Embark on a cosmic journey, exploring the mysteries of the universe and beyond.',
                'Dive into the world of athletics, from iconic matches to the stories of remarkable athletes.',
                'Embrace eco-friendly practices and sustainable choices for a greener tomorrow.',
                'Discover the latest innovations, gadgets, and tech trends shaping the modern world.',
                'Embark on virtual adventures to captivating travel destinations worldwide.',
                'Plan your next adventure with insights into travel tips, destinations, and experiences.',
                'Uncover hidden gems and urban wonders in bustling cities around the globe.',
                'Explore the world of vintage treasures, from retro memorabilia to antique finds.',
            ];
            $index = $this->faker->numberBetween(0, count($titles) - 1);
            
            return [
                'title'       => $titles[$index],
                'description' => $descriptions[$index],
            ];
        }
    }
    
    