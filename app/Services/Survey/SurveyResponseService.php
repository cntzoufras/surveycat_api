<?php

namespace App\Services\Survey;

use App\Models\Survey\SurveyResponse;
use App\Repositories\RespondentRepository;
use App\Repositories\Survey\SurveyResponseRepository;
use App\Services\RespondentService;
use Carbon\Carbon;

class SurveyResponseService
{

    private SurveyResponseRepository $survey_response_repository;

    protected RespondentService $respondent_service;


    public function __construct(SurveyResponseRepository $survey_response_repository,
                                RespondentService        $respondent_service,
    )
    {
        $this->survey_response_repository = $survey_response_repository;
        $this->respondent_service = $respondent_service;
    }

    protected RespondentRepository $respondent_repository;

    /**
     * @throws \Exception
     */
    public function index(array $params)
    {
        return $this->survey_response_repository->index($params);
    }

    /**
     * @throws \Exception
     */
    public function store(array $params): SurveyResponse
    {
        $metadata = $this->handleSurveyResponseMetadata($params);
        return $this->survey_response_repository->store($metadata);
    }

    public function update(SurveyResponse $survey_response, array $params)
    {
        // Normalize potential datetime inputs
        if (isset($params['started_at'])) {
            $params['started_at'] = $this->normalizeToUtc($params['started_at']);
        }
        if (isset($params['completed_at'])) {
            $params['completed_at'] = $this->normalizeToUtc($params['completed_at']);
        }
        return $this->survey_response_repository->update($survey_response, $params);
    }

    public function show($params): mixed
    {
        return $this->survey_response_repository->getIfExist($params);
    }

    /**
     * @throws \Exception
     */
    protected function handleSurveyResponseMetadata($params): array
    {
        $device_info = $this->getDeviceInfo();
        session_start();
        $session_id = session_id();
        $respondent_id = $this->respondent_service->store($params);
        $ip_address = $params['respondent_ip'];
        // Normalize datetime fields to UTC (handles ISO with Z, timestamps, or tz-less strings)
        if (isset($params['started_at'])) {
            $params['started_at'] = $this->normalizeToUtc($params['started_at']);
        }
        if (isset($params['completed_at'])) {
            $params['completed_at'] = $this->normalizeToUtc($params['completed_at']);
        }

        return array_merge($params, ['device' => $device_info['user_agent'],
            'session_id' => $session_id,
            'respondent_id' => $respondent_id,
            'ip_address' => $ip_address,
        ]);
    }

    protected function getDeviceInfo(): array
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        return [
            'user_agent' => $user_agent,
        ];
    }

    /**
     * Normalize arbitrary date input to UTC.
     * Accepts ISO8601 (with or without Z), unix timestamps, or 'Y-m-d H:i:s'.
     * Returns a Carbon instance in UTC so Eloquent can persist consistently.
     */
    private function normalizeToUtc($value): Carbon
    {
        // If numeric, treat as Unix timestamp in UTC
        if (is_numeric($value)) {
            return Carbon::createFromTimestamp((int)$value, 'UTC')->utc();
        }

        // Try ISO 8601 or other parseable strings
        try {
            $dt = Carbon::parse($value);
            return $dt->utc();
        } catch (\Exception $e) {
            // Fallback: attempt as 'Y-m-d H:i:s' assuming UTC
            try {
                return Carbon::createFromFormat('Y-m-d H:i:s', (string)$value, 'UTC')->utc();
            } catch (\Exception $e2) {
                // As a last resort, return "now" in UTC to avoid invalid values
                return Carbon::now('UTC');
            }
        }
    }
}
