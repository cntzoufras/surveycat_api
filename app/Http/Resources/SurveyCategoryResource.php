<?php
    
    namespace App\Http\Resources;
    
    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\JsonResource;
    
    class SurveyCategoryResource extends JsonResource {
        
        /**
         * The "data" wrapper that should be applied.
         *
         * @var string|null
         */
        public static $wrap = 'survey_categories';
        
        /**
         * Transform the resource into an array.
         *
         * @param \Illuminate\Http\Request $request *
         *
         * @return array<string, mixed>
         */
        public function toArray(Request $request): array {
            return [
                'id'          => $this->resource->id,
                'title'       => $this->resource->title,
                'description' => $this->resource->description,
                'created_at'  => $this->resource->created_at,
                'updated_at'  => $this->resource->updated_at,
            ];
        }
    }
