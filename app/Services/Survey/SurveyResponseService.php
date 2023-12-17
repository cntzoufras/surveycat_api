<?php

namespace App\Services\Survey;

use App\Models\Survey\SurveyResponse;
use App\Repositories\RespondentRepository;
use App\Repositories\Survey\SurveyResponseRepository;
use App\Services\RespondentService;

class SurveyResponseService {

    private SurveyResponseRepository $survey_response_repository;

    protected RespondentService $respondent_service;


    public function __construct(SurveyResponseRepository $survey_response_repository,
                                RespondentService        $respondent_service,
    ) {
        $this->survey_response_repository = $survey_response_repository;
        $this->respondent_service = $respondent_service;
    }

    protected RespondentRepository $respondent_repository;

    /**
     * @throws \Exception
     */
    public function index(array $params) {
        return $this->survey_response_repository->index($params);
    }

    /**
     * @throws \Exception
     */
    public function store(array $params): SurveyResponse {
        $metadata = $this->handleSurveyResponseMetadata($params);
        return $this->survey_response_repository->store($metadata);
    }

    public function update(SurveyResponse $survey_response, array $params) {
        $survey_response = $this->survey_response_repository->resolveModel($survey_response);
        return $this->survey_response_repository->update($survey_response, $params);
    }

    public function show($params): mixed {
        return $this->survey_response_repository->getIfExist($params);
    }

    /**
     * @throws \Exception
     */
    protected function handleSurveyResponseMetadata($params): array {
        $device_info = $this->getDeviceInfo();
        session_start();
        $session_id = session_id();
        $respondent_id = $this->respondent_service->store($params);
        $ip_address = $params['respondent_ip'];
        return array_merge($params, ['device'        => $device_info['user_agent'],
                                     'session_id'    => $session_id,
                                     'respondent_id' => $respondent_id,
                                     'ip_address'    => $ip_address,
        ]);
    }

    protected function getDeviceInfo(): array {
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        return [
            'user_agent' => $user_agent,
        ];
    }
}