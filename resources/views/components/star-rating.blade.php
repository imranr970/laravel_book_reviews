<div title="{{ number_format($rating, 1) }}">

    @if ($rating)

        @for ($i = 1; $i <= 5; $i++)
            
            <span>{{  $i <= round($rating) ? '★' : '☆'  }}</span>

        @endfor
        
    @else
        No rating yet    
    @endif

</div>