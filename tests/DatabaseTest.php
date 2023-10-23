<?php
    
    use Illuminate\Foundation\Testing\DatabaseTransactions;
    use Illuminate\Support\Facades\DB;
    use Tests\TestCase;
    
    class DatabaseTest extends TestCase {
        
        use DatabaseTransactions;
        
        public function testStoreData() {
            // Generate 100 requests
            for ($i = 0; $i < 100; $i++) {
                $response = $this->post('/store-data', [
                    'data' => 'Test Data ' . $i,
                ]);
                
                $response->assertStatus(200); // Optional: Check the response status code
            }
            
            // Assert that the data has been stored in the database
            $this->assertEquals(100, DgB::table('your_table')->count());
        }
    }
