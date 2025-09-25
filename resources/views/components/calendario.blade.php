<style>
    /* ===== STILI CALENDARIO COMPONENTE ===== */
    .calendar-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 200px;
    }
    
    .calendar-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #9333ea, #c084fc);
        border-radius: 20px 20px 0 0;
    }
    
    .calendar-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }
    
    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        margin-bottom: 0.5rem;
        margin-top: 0.5rem;
    }
    
    .calendar-nav-btn {
        background: rgba(147, 51, 234, 0.1);
        border: 1px solid rgba(147, 51, 234, 0.3);
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #9333ea;
        font-size: 0.9rem;
    }
    
    .calendar-nav-btn:hover {
        background: rgba(147, 51, 234, 0.2);
        transform: scale(1.1);
    }
    
    .calendar-month-year {
        font-size: 1.1rem;
        font-weight: 600;
        color: #9333ea;
        text-align: center;
    }
    
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 2px;
        width: 100%;
        background: #f8fafc;
        border-radius: 10px;
        padding: 0.5rem;
        border: 1px solid rgba(147, 51, 234, 0.2);
    }
    
    .calendar-weekday {
        font-size: 0.7rem;
        font-weight: 600;
        color: #9333ea;
        text-align: center;
        padding: 0.3rem 0;
        background: rgba(147, 51, 234, 0.1);
        border-radius: 4px;
    }
    
    .calendar-day {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
        color: #374151;
        background: white;
    }
    
    .calendar-day:hover {
        background: rgba(147, 51, 234, 0.1);
        color: #9333ea;
    }
    
    .calendar-day.today {
        margin-top: 10px;
        background: linear-gradient(135deg, #9333ea, #c084fc);
        color: white;
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(147, 51, 234, 0.3);
    }
    
    .calendar-day.other-month {
        color: #d1d5db;
        background: #f9fafb;
    }
    
    .calendar-day.selected {
        background: rgba(147, 51, 234, 0.2);
        color: #9333ea;
        font-weight: 600;
        border: 2px solid #9333ea;
    }
    
    
    .calendar-date-display {
        font-size: 1rem;
        font-weight: 600;
        color: #9333ea;
        margin-bottom: 0.3rem;
    }
    
    .calendar-weekday-display {
        font-size: 0.85rem;
        color: #718096;
        font-weight: 500;
    }
    
    .calendar-settings {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(147, 51, 234, 0.1);
        border: 1px solid rgba(147, 51, 234, 0.3);
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #9333ea;
    }
    
    .calendar-settings:hover {
        background: rgba(147, 51, 234, 0.2);
        transform: rotate(90deg);
    }
</style>

<div class="calendar-card">
    <!-- Pulsante Impostazioni -->
    <div class="calendar-settings" id="calendarSettings-{{ $id ?? 'default' }}" title="Vai a oggi">
        <i class="bi bi-house"></i>
    </div>
    
    <div class="calendar-header">
        <button class="calendar-nav-btn" id="prevMonth-{{ $id ?? 'default' }}" title="Mese precedente">
            <i class="bi bi-chevron-left"></i>
        </button>
        <div class="calendar-month-year" id="monthYear-{{ $id ?? 'default' }}"></div>
        <button class="calendar-nav-btn" id="nextMonth-{{ $id ?? 'default' }}" title="Mese successivo">
            <i class="bi bi-chevron-right"></i>
        </button>
    </div>
    
    <div class="calendar-grid" id="calendarGrid-{{ $id ?? 'default' }}">
        <!-- Giorni della settimana -->
        <div class="calendar-weekday">D</div>
        <div class="calendar-weekday">L</div>
        <div class="calendar-weekday">M</div>
        <div class="calendar-weekday">M</div>
        <div class="calendar-weekday">G</div>
        <div class="calendar-weekday">V</div>
        <div class="calendar-weekday">S</div>
    </div>
    
    <div class="calendar-today-info">
        <div class="calendar-date-display" id="todayDate-{{ $id ?? 'default' }}"></div>
        <div class="calendar-weekday-display" id="todayWeekday-{{ $id ?? 'default' }}"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarId = '{{ $id ?? "default" }}';
    let currentDate = new Date();
    let selectedDate = new Date();
    
    // Elementi
    const monthYearEl = document.getElementById(`monthYear-${calendarId}`);
    const prevBtn = document.getElementById(`prevMonth-${calendarId}`);
    const nextBtn = document.getElementById(`nextMonth-${calendarId}`);
    const settingsBtn = document.getElementById(`calendarSettings-${calendarId}`);
    const calendarGrid = document.getElementById(`calendarGrid-${calendarId}`);
    const todayDateEl = document.getElementById(`todayDate-${calendarId}`);
    const todayWeekdayEl = document.getElementById(`todayWeekday-${calendarId}`);
    
    // Nomi mesi e giorni in italiano
    const monthNames = [
        'Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno',
        'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'
    ];
    
    const weekdayNames = [
        'Domenica', 'Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato'
    ];
    
    // Event listeners
    prevBtn?.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });
    
    nextBtn?.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });
    
    settingsBtn?.addEventListener('click', () => {
        currentDate = new Date();
        selectedDate = new Date();
        renderCalendar();
        updateTodayInfo();
    });
    
    function renderCalendar() {
        // Aggiorna header mese/anno
        if (monthYearEl) {
            monthYearEl.textContent = `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
        }
        
        // Pulisci griglia precedente (mantenendo header giorni settimana)
        const dayElements = calendarGrid.querySelectorAll('.calendar-day');
        dayElements.forEach(el => el.remove());
        
        // Calcola primo giorno del mese e numero di giorni
        const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
        const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
        const startDate = new Date(firstDay);
        startDate.setDate(startDate.getDate() - firstDay.getDay()); // Inizia dalla domenica
        
        // Genera 35 giorni (5 settimane)
        for (let i = 0; i < 35; i++) {
            const date = new Date(startDate);
            date.setDate(startDate.getDate() + i);
            
            const dayEl = document.createElement('div');
            dayEl.className = 'calendar-day';
            dayEl.textContent = date.getDate();
            
            // Classi CSS
            const today = new Date();
            if (date.toDateString() === today.toDateString()) {
                dayEl.classList.add('today');
            }
            
            if (date.toDateString() === selectedDate.toDateString()) {
                dayEl.classList.add('selected');
            }
            
            if (date.getMonth() !== currentDate.getMonth()) {
                dayEl.classList.add('other-month');
            }
            
            // Click handler
            dayEl.addEventListener('click', () => {
                // Rimuovi selezione precedente
                calendarGrid.querySelectorAll('.calendar-day.selected').forEach(el => {
                    el.classList.remove('selected');
                });
                
                // Aggiungi selezione
                dayEl.classList.add('selected');
                selectedDate = new Date(date);
                updateTodayInfo();
            });
            
            calendarGrid.appendChild(dayEl);
        }
    }
    
    function updateTodayInfo() {
        const today = new Date();
        
        if (todayDateEl) {
            todayDateEl.textContent = selectedDate.toLocaleDateString('it-IT', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }
        
        if (todayWeekdayEl) {
            const weekday = weekdayNames[selectedDate.getDay()];
            const isToday = selectedDate.toDateString() === today.toDateString();
            todayWeekdayEl.textContent = isToday ? `${weekday} - Oggi` : weekday;
        }
    }
    
    function updateTodayInfo() {
        const today = new Date();
        
        if (todayDateEl) {
            todayDateEl.textContent = selectedDate.toLocaleDateString('it-IT', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }
        
        if (todayWeekdayEl) {
            const weekday = weekdayNames[selectedDate.getDay()];
            const isToday = selectedDate.toDateString() === today.toDateString();
            todayWeekdayEl.textContent = isToday ? `${weekday} - Oggi` : weekday;
        }
    }
    
    // Inizializzazione
    renderCalendar();
    updateTodayInfo();
});
</script>