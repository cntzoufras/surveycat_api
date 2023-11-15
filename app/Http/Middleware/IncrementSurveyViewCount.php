<?php
    
    namespace App\Http\Middleware;
    
    use App\Models\Survey;
    use Closure;
    use Illuminate\Http\Request;
    use Symfony\Component\HttpFoundation\Response;
    
    class IncrementSurveyViewCount {
        
        /**
         * Handle an incoming request.
         *
         * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
         */
        public function handle(Request $request, Closure $next): Response {
            
            // Get the survey ID from the route parameters or request data
            $surveyId = $request->route('survey_id') ?? $request->input('survey_id');
            
            if ($surveyId) {
                // Find the survey by ID
                $survey = Survey::query()->find($surveyId);
                
                if ($survey) {
                    // Increment the view count
                    $survey->increment('views_count');
                }
            }
            
            return $next($request);
        }
    }
