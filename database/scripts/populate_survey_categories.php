<?php
    
    $apiUrl = 'http://surveycat.test/api/survey-categories';
    
    if (($handle = fopen("database/seeds/survey_categories_system.csv", "r")) !== false) {
        // Skip the header line
        fgetcsv($handle);
        
        while (($data = fgetcsv($handle, 10000, ",")) !== false) {
            
            $title = isset($data[0]) ? trim($data[0]) : '';
            $description = isset($data[1]) ? trim($data[1]) : '';
            
            $postData = [
                'title'       => $title,
                'description' => $description,
            ];
            
            $jsonPayload = json_encode($postData);
            
            $ch = curl_init($apiUrl);
            
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($jsonPayload),
                ]
            );
            
            $result = curl_exec($ch);
            $info = curl_getinfo($ch);
            
            if (curl_error($ch)) {
                throw new Exception(curl_error($ch));
            } else {
                $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                
                if ($info['http_code'] == 200 || $info['http_code'] == 201) {
                    echo "Stored object: " . $result . "\n";
                } else {
                    if ($httpStatusCode == 429) {
                        sleep(60);
                    } else {
                        if ($info['http_code'] == 301 || $info['http_code'] == 302) {
                            echo "Redirected to: " . $info['redirect_url'] . "\n";
                        } else {
                            echo "HTTP status code: " . $info['http_code'] . "\n";
                        }
                    }
                }
            }
            
            curl_close($ch);
            
            echo "Posted: " . $jsonPayload . "\n";
            echo "Result: " . $result . "\n";
            
            sleep(1); // Sleep for 1 second before making the next request
        }
        fclose($handle);
    }

?>