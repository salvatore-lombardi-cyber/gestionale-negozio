<style>
    /* ===== STILI OROLOGIO COMPONENTE ===== */
    .clock-card {
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
    
    .clock-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        border-radius: 20px 20px 0 0;
    }
    
    .clock-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }
    
    .clock-container {
        position: relative;
        margin-bottom: 1rem;
    }
    
    .clock-face {
        width: 120px;
        height: 120px;
        border: 4px solid #029D7E;
        border-radius: 50%;
        position: relative;
        background: white;
        box-shadow: 0 0 20px rgba(2, 157, 126, 0.3);
    }
    
    .clock-number {
        position: absolute;
        width: 20px;
        height: 20px;
        text-align: center;
        line-height: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #029D7E;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(calc(var(--i) * 30deg)) translateY(-40px) rotate(calc(var(--i) * -30deg));
    }
    
    .clock-center {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 8px;
        height: 8px;
        background: #029D7E;
        border-radius: 50%;
        transform: translate(-50%, -50%);
        z-index: 10;
    }
    
    .clock-hand {
        position: absolute;
        bottom: 50%;
        left: 50%;
        transform-origin: bottom center;
        border-radius: 4px;
    }
    
    .hour-hand {
        width: 3px;
        height: 25px;
        background: #029D7E;
        margin-left: -1.5px;
        z-index: 3;
    }
    
    .minute-hand {
        width: 2px;
        height: 35px;
        background: #4DC9A5;
        margin-left: -1px;
        z-index: 2;
    }
    
    .second-hand {
        width: 1px;
        height: 40px;
        background: #ff8500;
        margin-left: -0.5px;
        z-index: 1;
        transition: transform 0.1s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .digital-time {
        font-size: 1.2rem;
        font-weight: 700;
        color: #029D7E;
        font-family: 'Courier New', monospace;
        text-align: center;
        background: rgba(2, 157, 126, 0.1);
        padding: 0.5rem 1rem;
        border-radius: 10px;
        border: 1px solid rgba(2, 157, 126, 0.3);
    }
    
    .clock-label {
        font-size: 0.9rem;
        color: #718096;
        margin-top: 0.5rem;
    }
</style>

<div class="clock-card">
    <div class="clock-container">
        <div class="clock-face">
            <div class="clock-hand hour-hand" id="hourHand-{{ $id ?? 'default' }}"></div>
            <div class="clock-hand minute-hand" id="minuteHand-{{ $id ?? 'default' }}"></div>
            <div class="clock-hand second-hand" id="secondHand-{{ $id ?? 'default' }}"></div>
            <div class="clock-center"></div>
            <!-- Numeri dell'orologio -->
            <div class="clock-number" style="--i: 1;">1</div>
            <div class="clock-number" style="--i: 2;">2</div>
            <div class="clock-number" style="--i: 3;">3</div>
            <div class="clock-number" style="--i: 4;">4</div>
            <div class="clock-number" style="--i: 5;">5</div>
            <div class="clock-number" style="--i: 6;">6</div>
            <div class="clock-number" style="--i: 7;">7</div>
            <div class="clock-number" style="--i: 8;">8</div>
            <div class="clock-number" style="--i: 9;">9</div>
            <div class="clock-number" style="--i: 10;">10</div>
            <div class="clock-number" style="--i: 11;">11</div>
            <div class="clock-number" style="--i: 12;">12</div>
        </div>
    </div>
    <div class="digital-time" id="digitalTime-{{ $id ?? 'default' }}"></div>
    @if($label ?? false)
        <div class="clock-label">{{ $label }}</div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const clockId = '{{ $id ?? "default" }}';
    
    function updateClock() {
        const now = new Date();
        const hours = now.getHours();
        const minutes = now.getMinutes();
        const seconds = now.getSeconds();
        
        // Calcola gli angoli per le lancette
        const hourAngle = (hours % 12) * 30 + (minutes / 60) * 30;
        const minuteAngle = minutes * 6;
        const secondAngle = seconds * 6;
        
        // Applica le rotazioni
        const hourHand = document.getElementById(`hourHand-${clockId}`);
        const minuteHand = document.getElementById(`minuteHand-${clockId}`);
        const secondHand = document.getElementById(`secondHand-${clockId}`);
        const digitalTime = document.getElementById(`digitalTime-${clockId}`);
        
        if (hourHand) hourHand.style.transform = `rotate(${hourAngle}deg)`;
        if (minuteHand) minuteHand.style.transform = `rotate(${minuteAngle}deg)`;
        if (secondHand) secondHand.style.transform = `rotate(${secondAngle}deg)`;
        
        // Aggiorna l'orario digitale
        if (digitalTime) {
            const timeString = now.toLocaleTimeString('it-IT', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            digitalTime.textContent = timeString;
        }
    }
    
    // Avvia l'orologio
    updateClock();
    setInterval(updateClock, 1000);
});
</script>