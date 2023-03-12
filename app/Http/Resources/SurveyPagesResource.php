<?php
    
    namespace App\Http\Resources;
    
    use Illuminate\Http\Resources\Json\JsonResource;
    
    class SurveyPagesResource extends JsonResource {
        
        /**
         * The "data" wrapper that should be applied.
         *
         * @var string|null
         */
        public static $wrap = 'post';
        
        /**
         * Transform the resource into an array.
         *
         * @param \Illuminate\Http\Request $request
         *
         * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
         */
        public function toArray($request) {
            return [
                'id'    => $this->id,
                'pages' => $this->pages,
                'uv'    => $this->uv,
                'pv'    => $this->pv,
                'amt'   => $this->amt,
            ];
        }
    }
