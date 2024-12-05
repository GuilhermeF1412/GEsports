@props(['selectedDate', 'formattedDate'])

<style>
    /* Style for calendar picker arrows */
    ::-webkit-calendar-picker-indicator {
        filter: invert(48%) sepia(79%) saturate(2476%) hue-rotate(86deg) brightness(90%) contrast(95%);
        cursor: pointer;
    }

    /* Style for calendar navigation buttons */
    input[type="date"]::-webkit-datetime-edit-year-field,
    input[type="date"]::-webkit-datetime-edit-month-field {
        color: #198754;  /* Bootstrap's success color */
    }

    /* Style for year selector arrows - Chrome/Safari */
    .arrowUp,
    .arrowDown,
    .yearselect .yearselect-up,
    .yearselect .yearselect-down,
    ::-webkit-inner-spin-button {
        border-color: #198754 !important;
        color: #198754 !important;
        filter: invert(48%) sepia(79%) saturate(2476%) hue-rotate(86deg) brightness(90%) contrast(95%);
    }

    /* Firefox specific styles */
    @-moz-document url-prefix() {
        .arrowUp,
        .arrowDown {
            border-color: #198754 !important;
            color: #198754 !important;
        }
    }

    /* For Edge */
    @supports (-ms-ime-align: auto) {
        .arrowUp,
        .arrowDown {
            border-color: #198754 !important;
            color: #198754 !important;
        }
    }

    /* General spinner styles */
    input[type="date"]::-webkit-inner-spin-button,
    input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(48%) sepia(79%) saturate(2476%) hue-rotate(86deg) brightness(90%) contrast(95%);
    }
</style>

<div class="date-navigation">
    <div class="date-nav-container">
        <button type="button" class="date-nav-btn" onclick="changeDate(-1)">
            <i class="bi bi-chevron-left"></i>
        </button>
        
        <form action="{{ route('lolhome') }}" method="GET" class="date-selector">
            <input type="date" 
                   name="date" 
                   id="dateSelect" 
                   value="{{ $selectedDate }}" 
                   style="display: none;">
            <div class="current-date" id="currentDate">
                {{ $formattedDate }}
            </div>
        </form>
        
        <button type="button" class="date-nav-btn" onclick="changeDate(1)">
            <i class="bi bi-chevron-right"></i>
        </button>
    </div>
</div> 